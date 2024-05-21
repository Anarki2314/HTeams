<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Ban;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = Role::where('title', $request->get('role'))->where('title', '!=', 'Админ')->first();
        if (!$role) {
            return response()->json(['message' => 'Роль не найдена'], 404);
        }
        $users = QueryBuilder::for(User::class)
            ->select('id', 'email', 'created_at', 'updated_at', 'role_id', 'avatar_id')
            ->with('avatar', function ($query) {
                $query->select('id', 'name', 'path');
            })
            ->where(['role_id' => $role->id])
            ->with('role', function ($query) {
                $query->select('id', 'title');
            })
            ->allowedSorts(['email', 'created_at'])
            ->allowedFilters(['email'])
            ->paginate($request->get('per_page', 10));

        return response()->json($users);
    }

    public function show(Request $request, $id)
    {
        $user = User::where('role_id', '!=', Role::getIdRoleByTitle('Админ'))->findOrFail($id);

        return response()->json([
            'data' => new UserResource($user),
        ]);
    }

    public function banUser(Request $request, $id)
    {
        $validated = $request->validate(
            [
                'banTime' => 'required|integer|min:1|max:365',
                'reason' => 'required|string|max:32|regex:/^[а-яА-ЯёЁa-zA-Z0-9\s]+$/u',
            ],
            [
                'banTime.required' => 'Необходимо указать время бана',
                'banTime.integer' => 'Время бана должно быть целым числом',
                'banTime.min' => 'Минимальное время бана 1 день',
                'banTime.max' => 'Максимальное время бана 365 дней',
                'reason.required' => 'Необходимо указать причину бана',
                'reason.string' => 'Причина бана должна быть строкой',
                'reason.max' => 'Максимальная длина причины бана 32 символа',
                'reason.regex' => 'Причина бана может содержать только буквы, цифры и пробелы',
            ]
        );


        $user = User::where('role_id', '!=', Role::getIdRoleByTitle('Админ'))->findOrFail($id);

        $expiresAt = now()->addDays($validated['banTime']);

        Ban::banUser($user, $validated['reason'], $expiresAt);

        return response()->json(['message' => 'Пользователь заблокирован']);
    }

    public function unbanUser(Request $request, $id)
    {
        $user = User::where('role_id', '!=', Role::getIdRoleByTitle('Админ'))->findOrFail($id);
        Ban::unbanUser($user);

        return response()->json(['message' => 'Пользователь разблокирован']);
    }

    public function banUsersByTeam(Request $request, $id)
    {
        $validated = $request->validate(
            [
                'banTime' => 'required|integer|min:1|max:365',
                'reason' => 'required|string|max:32|regex:/^[а-яА-ЯёЁa-zA-Z0-9\s]+$/u',
            ],
            [
                'banTime.required' => 'Необходимо указать время бана',
                'banTime.integer' => 'Время бана должно быть целым числом',
                'banTime.min' => 'Минимальное время бана 1 день',
                'banTime.max' => 'Максимальное время бана 365 дней',
                'reason.required' => 'Необходимо указать причину бана',
                'reason.string' => 'Причина бана должна быть строкой',
                'reason.max' => 'Максимальная длина причины бана 32 символа',
                'reason.regex' => 'Причина бана может содержать только буквы, цифры и пробелы',
            ]
        );

        $team = Team::findOrFail($id);

        $expiresAt = now()->addDays($validated['banTime']);
        Ban::banUsersByTeam($team, $request->get('reason'), $expiresAt);

        return response()->json(['message' => 'Пользователи заблокированы']);
    }

    public function unbanUsersByTeam(Request $request, $id)
    {
        $team = Team::findOrFail($id);
        Ban::unbanUsersByTeam($team);

        return response()->json(['message' => 'Пользователи разблокированы']);
    }
}
