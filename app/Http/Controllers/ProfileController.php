<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Team;
use App\Models\TeamsMembers;
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
        $teamMembers = TeamsMembers::create([
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
}
