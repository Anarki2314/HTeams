<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\FileResource;
use App\Http\Resources\TeamInvitesResource;
use App\Http\Resources\TeamResource;
use App\Http\Resources\UserResource;
use App\Models\EventTeams;
use App\Models\Team;
use App\Models\TeamInvites;
use App\Models\TeamMembers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        if (!$fromUser->isLeader()) {
            return response()->json(['message' => 'Пригласить может только лидер'], 409);
        }
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
            if ($user->team->members()->count() >= 5) {
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

    public function leaveTeam(Request $request)
    {
        $user = $request->user();
        if (!$user->team) {
            return response()->json(['message' => 'Вы не состоите в команде'], 409);
        }
        TeamMembers::where('team_id', $user->team->id)->where('user_id', $user->id)->delete();
        EventTeams::where('user_id', $user->id)->delete();
        return response()->json(['message' => 'Вы покинули команду'], 200);
    }

    public function deleteTeam(Request $request)
    {
        $user = $request->user();
        if (!$user->isLeader()) {
            return response()->json(['message' => 'Вы не являетесь лидером команды'], 409);
        }
        $user->team->delete();
        return response()->json(['message' => 'Команда удалена'], 200);
    }

    public function deleteProfile(Request $request)
    {
        $user = $request->user();
        $user->delete();
        return response()->json(['message' => 'Аккаунт удален'], 200);
    }

    public function generateNewAvatar(Request $request)
    {
        $user = $request->user();
        $file = $user->generateAvatar();
        return response()->json(['message' => 'Аватар обновлен', 'data' => new FileResource($file)], 200);
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();
        $validated = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/',

        ], [

            'old_password.required' => 'Поле `Текущий пароль` обязательно.',
            'new_password.required' => 'Поле `Новый пароль` обязательно.',
            'new_password.regex' => 'Пароль должен содержать цифры, строчные и заглавные буквы.',
            'new_password.confirmed' => 'Поле `Повтор пароля` должно совпадать с полем `Новый пароль`.',
            'new_password.min' => 'Поле `Новый пароль` должно содержать не менее 8 символов.',
        ]);
        if (Hash::check($validated['new_password'], $user->password)) {
            return response()->json(['message' => 'Новый пароль должен отличаться от текущего'], 409);
        }
        if ($user->changePassword($validated['old_password'], $validated['new_password'])) {

            return response()->json(['message' => 'Пароль изменен'], 200);
        } else {

            return response()->json(['message' => 'Неверный пароль'], 409);
        }
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $request->user();
        if ($user->isAdmin()) {
            return response()->json(['message' => 'Администратор не может изменить профиль'], 409);
        }
        $validated = $request->validated();
        $user->update($validated);
        return response()->json(['message' => 'Профиль обновлен', 'data' => new UserResource($user)], 200);
    }
}
