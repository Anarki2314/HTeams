<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Role;
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
}
