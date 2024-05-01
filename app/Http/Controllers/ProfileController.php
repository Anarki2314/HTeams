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
        ]);
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
        ]);
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
        ]);
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
        if ($team->invites->where('email', $validated['email'])->count() > 0) {
            return response()->json(['message' => 'Пользователь уже приглашен в команду'], 409);
        }
        if ($team->members->where('email', $validated['email'])->count() > 0) {
            return response()->json(['message' => 'Пользователь уже состоит в команде'], 409);
        }

        $toUser = User::where('email', $validated['email'])->first();
        $teamInvites = TeamInvites::create([
            'team_id' => $team->id,
            'from_user' => $fromUser->id,
            'to_user' => $toUser->id,
        ]);

        return response()->json(['message' => 'Приглашение отправлено']);
    }

    public function getTeamInvites(Request $request)
    {
        $user = $request->user();
        if (!$user->team) {
            return response()->json(['message' => 'Вы не состоите в команде'], 409);
        }

        return response()->json([
            'data' => ['invites' => TeamInvitesResource::collection($user->team->invites)],
        ]);
    }
}
