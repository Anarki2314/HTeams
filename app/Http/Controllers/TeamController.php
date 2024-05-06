<?php

namespace App\Http\Controllers;

use App\Http\Resources\TeamMembersCollection;
use App\Http\Resources\TeamResource;
use App\Http\Resources\UserResource;
use App\Models\Team;
use App\Models\TeamInvites;
use App\Models\TeamMembers;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $teams = QueryBuilder::for(Team::class)
            ->defaultSort('-id')
            ->select('id', 'title')
            ->withCount('members')
            ->allowedFilters(['title', AllowedFilter::custom('isFull', new \App\Filters\IsFullFilter())->nullable()])


            ->paginate($request->get('perPage', 10));
        return response()->json($teams);
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
    public function show(Request $request, $id)
    {
        if ((int)$id == 0) {
            return throw new NotFoundHttpException('Команда не найдена');
        }
        $team = Team::findOrFail($id);
        return response()->json(['data' => new TeamResource($team)]);
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

    public function joinTeam(Request $request)
    {
        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id',

        ], [

            'team_id.required' => 'Поле `Id команды` обязательно.',
            'team_id.exists' => 'Команда не найдена.',
        ]);

        $user = $request->user();
        if ($user->team) {
            return response()->json(['message' => 'Вы уже состоите в команде'], 409);
        }

        $team = Team::where('id', $validated['team_id'])->first();
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
}
