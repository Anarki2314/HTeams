<?php

namespace App\Http\Controllers;

use App\Http\Resources\TeamMembersCollection;
use App\Http\Resources\TeamResource;
use App\Http\Resources\UserResource;
use App\Models\Team;
use App\Models\TeamMembers;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'data' => TeamResource::collection(Team::all()),
            'count' => Team::count()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
        $teamMembers = TeamMembers::create([
            'team_id' => $team->id,
            'user_id' => $user->id
        ]);

        return response()->json([
            'message' => 'Команда успешно создана',
            'data' => [
                'team' => new TeamResource($team),
            ],
        ]);

        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
