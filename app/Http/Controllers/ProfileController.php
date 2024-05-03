<?php

namespace App\Http\Controllers;

use App\Http\Resources\TeamInvitesResource;
use App\Http\Resources\TeamResource;
use App\Http\Resources\UserResource;
use App\Models\Team;
use App\Models\TeamInvites;
use App\Models\TeamMembers;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function getProfile(Request $request)
    {
        return response()->json([
            'data' => ['user' => new UserResource($request->user())],
        ], 200);
    }

    public function createTeam(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|unique:teams,title|min:2|regex:/^(?!.*\s{2})[а-яА-ЯёЁA-Za-z\s]+$/u',
        ], [
            'title.regex' => 'Поле `Название команды` должно содержать только кириллицу, латиницу и пробелы.',
            'title.required' => 'Поле `Название команды` обязательно.',
        ]);

        $user = $request->user();
        if ($user->team) {
            return response()->json(['message' => 'Вы уже состоите в команде'], 409);
        }

        $team = Team::create($validated);
        $user->team = $team;
        $teamMembers = TeamMembers::create([
            'team_id' => $team->id,
            'user_id' => $user->id
        ]);

        return response()->json([
            'message' => 'Команда успешно создана',
            'data' => [
                'user' => new UserResource($user),
            ],
        ], 201);
    }

    public function getTeam(Request $request)
    {
        if (!$request->user()->team) {
            return response()->json([
                'message' => 'Вы не состоите в команде',
            ], 404);
        }

        return response()->json([
            'data' => ['team' => new TeamResource($request->user()->team)],
        ], 200);
    }

    public function inviteFromTeam(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|exists:users,email',

        ], [

            'email.required' => 'Поле `Email` обязательно.',
            'email.exists' => 'Пользователь с таким email не найден.',

        ]);

        $fromUser = $request->user();
        $team = $fromUser->team;
        if (!$team) {
            return response()->json(['message' => 'Вы не состоите в команде'], 409);
        }
        if ($team->members->count() >= 5) {
            return response()->json(['message' => 'Команда переполнена'], 409);
        }
        if ($team->members->where('email', $validated['email'])->count() > 0) {
            return response()->json(['message' => 'Пользователь уже состоит в команде'], 409);
        }

        $toUser = User::where('email', $validated['email'])->first();
        if ($toUser->team) {
            return response()->json(['message' => 'Пользователь уже состоит в другой команде'], 409);
        }

        if ($toUser->role->title != 'Пользователь') {
            return response()->json(['message' => 'Пользователь не может быть приглашен'], 409);
        }
        if ($toUser->invites->where('id', $team->id)->count() > 0) {
            return response()->json(['message' => 'Пользователь уже приглашен в команду'], 409);
        }
        $teamInvites = TeamInvites::create([
            'team_id' => $team->id,
            'from_user' => $fromUser->id,
            'to_user' => $toUser->id,
        ]);

        return response()->json(['message' => 'Приглашение отправлено'], 201);
    }

    public function inviteToTeam(Request $request)
    {
        $validated = $request->validate([
            'invite_code' => 'required|exists:teams,invite_code',

        ], [

            'invite_code.required' => 'Поле `Код приглашения` обязательно.',
            'invite_code.exists' => 'Код приглашения не найден.',
        ]);

        $user = $request->user();
        if ($user->team) {
            return response()->json(['message' => 'Вы уже состоите в команде'], 409);
        }

        $team = Team::where('invite_code', $validated['invite_code'])->first();
        if ($team->members->count() >= 5) {
            return response()->json(['message' => 'Команда переполнена'], 409);
        }

        $teamInvites = TeamInvites::create([
            'team_id' => $team->id,
            'from_user' => $user->id,
            'to_user' => $team->leader_id,
        ]);

        return response()->json(['message' => 'Приглашение отправлено'], 201);
    }

    public function userChoiceInvite(Request $request)
    {
        $validated = $request->validate([
            'choice' => 'required|boolean',
            'team_id' => 'required|exists:teams,id',
        ]);

        $user = $request->user();
        if ($user->team) {
            return response()->json(['message' => 'Вы уже состоите в команде'], 409);
        }
        $invite = TeamInvites::where('team_id', $validated['team_id'])->where('to_user', $user->id)->first();
        if (!$invite) {
            return response()->json(['message' => 'Приглашение не найдено'], 409);
        }
        $invite->delete();
        $team = Team::where('id', $validated['team_id'])->first();
        $responseData = [];
        if ($validated['choice']) {
            if ($team->members->count() >= 5) {
                $responseData['message'] = 'Команда переполнена';
                return response()->json($responseData, 409);
            }
            TeamMembers::create([
                'team_id' => $team->id,
                'user_id' => $user->id,
            ]);

            $responseData['message'] = 'Приглашение одобрено';
        } else {

            $responseData['message'] = 'Приглашение отклонено';
        }

        return response()->json($responseData, 200);
    }

    public function teamChoiceInvite(Request $request)
    {
        $validated = $request->validate([
            'choice' => 'required|boolean',
            'from_user' => 'required|exists:users,id',
        ]);

        $user = $request->user();
        if (!$user->team) {
            return response()->json(['message' => 'Вы не состоите в команде'], 409);
        }
        $fromUser = User::where('id', $validated['from_user'])->first();
        $invite = TeamInvites::where('from_user', $validated['from_user'])->where('to_user', $user->team->leader_id)->first();
        if (!$invite) {
            return response()->json(['message' => 'Запрос не найден'], 409);
        }
        $invite->delete();
        if ($fromUser->team) {
            return response()->json(['message' => 'Пользователь уже состоит в другой команде'], 409);
        }
        $responseData = [];
        if ($validated['choice']) {
            if ($user->team->members->count() >= 5) {
                $responseData['message'] = 'Команда переполнена';
                return response()->json($responseData, 409);
            }

            TeamMembers::create([
                'team_id' => $user->team->id,
                'user_id' => $validated['from_user'],
            ]);

            $responseData['message'] = 'Запрос одобрен';
        } else {

            $responseData['message'] = 'Запрос отклонен';
        }

        return response()->json($responseData, 200);
    }
    public function getTeamInvites(Request $request)
    {
        $user = $request->user();
        if (!$user->team) {
            return response()->json(['message' => 'Вы не состоите в команде'], 409);
        }

        return response()->json([
            'data' => ['invites' => TeamInvitesResource::collection($user->team->invites)],
        ], 200);
    }

    public function getUserInvites(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'data' => ['invites' => TeamInvitesResource::collection($user->invites)],
        ], 200);
    }
}
