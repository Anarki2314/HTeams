<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Auth routes
Route::post('/auth/sign-up', [AuthController::class, 'signUp']);
Route::post('/auth/sign-in', [AuthController::class, 'signIn']);
Route::post('/auth/sign-out', [AuthController::class, 'signOut'])->middleware('auth:sanctum');
Route::post('/auth/refresh', [AuthController::class, 'refresh'])->middleware('auth:sanctum');

// Team routes 

Route::apiResource('/teams', TeamController::class);

// Profile routes (only for logged users)

Route::prefix('/profile')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [ProfileController::class, 'getProfile']);
    Route::post('/create-team', [ProfileController::class, 'createTeam'])->middleware('ability:Пользователь');
    Route::post('/join-team', [ProfileController::class, 'inviteToTeam'])->middleware('ability:Пользователь');
    Route::get('/team', [ProfileController::class, 'getTeam'])->middleware('ability:Пользователь');
    Route::post('/team/invite', [ProfileController::class, 'inviteFromTeam'])->middleware('ability:Пользователь');
    Route::post('/team/invite/team-choice', [ProfileController::class, 'teamChoiceInvite'])->middleware('ability:Пользователь');
    Route::post('/team/invite/user-choice', [ProfileController::class, 'userChoiceInvite'])->middleware('ability:Пользователь');
    Route::post('/team/leave', [ProfileController::class, 'leaveTeam'])->middleware('ability:Пользователь');

    Route::post('/team/join', [ProfileController::class, 'inviteToTeam'])->middleware('ability:Пользователь');
    Route::get('/team/invites', [ProfileController::class, 'getTeamInvites'])->middleware('ability:Пользователь');

    Route::delete('/team/leave', [ProfileController::class, 'leaveTeam'])->middleware('ability:Пользователь');

    Route::delete('/team', [ProfileController::class, 'deleteTeam'])->middleware('ability:Пользователь');

    Route::delete('/', [ProfileController::class, 'deleteProfile']);
});
Route::get('/notifications', [NotificationsController::class, 'getNotifications'])->middleware('auth:sanctum');
Route::get('/user', function (Request $request) {
    return new UserResource($request->user());
})->middleware('auth:sanctum');
