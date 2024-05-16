<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
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

Route::post('/teams/join', [TeamController::class, 'joinTeam'])->middleware(['auth:sanctum', 'ability:Пользователь']);

Route::apiResource('/teams', TeamController::class)->only(['index', 'store', 'show']);



// Event Organizer/Admin routes

Route::get('/events/{id}/full', [EventController::class, 'getFullEvent'])->middleware(['auth:sanctum', 'ability:Организатор, Админ'])->where(['id' => '[0-9]+']);

Route::get('/events/moderation', [EventController::class, 'getModerationEvents'])->middleware(['auth:sanctum', 'ability:Организатор, Админ']);

Route::delete('/events/{id}/cancel', [EventController::class, 'cancelEvent'])->middleware(['auth:sanctum', 'ability:Организатор, Админ'])->where(['id' => '[0-9]+']);

Route::put('/events/{id}', [EventController::class, 'updateEvent'])->middleware(['auth:sanctum', 'ability:Организатор']);


// Event routes

Route::post('/events/', [EventController::class, 'createEvent'])->middleware(['auth:sanctum', 'ability:Организатор']);

Route::apiResource('/events', EventController::class)->only(['index', 'show'])->where(['id' => '[0-9]+']);


// Profile routes (only for logged users)

Route::prefix('/profile')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [ProfileController::class, 'getProfile']);

    Route::delete('/', [ProfileController::class, 'deleteProfile']);


    // Profile Team routes

    Route::post('/create-team', [ProfileController::class, 'createTeam'])->middleware('ability:Пользователь');

    Route::post('/join-team', [ProfileController::class, 'inviteToTeam'])->middleware('ability:Пользователь');

    Route::get('/team', [ProfileController::class, 'getTeam'])->middleware('ability:Пользователь');

    Route::post('/team/invite', [ProfileController::class, 'inviteFromTeam'])->middleware('ability:Пользователь');

    Route::post('/team/invite/team-choice', [ProfileController::class, 'teamChoiceInvite'])->middleware('ability:Пользователь');

    Route::post('/team/invite/user-choice', [ProfileController::class, 'userChoiceInvite'])->middleware('ability:Пользователь');

    Route::post('/team/join', [ProfileController::class, 'inviteToTeam'])->middleware('ability:Пользователь');

    Route::get('/team/invites', [ProfileController::class, 'getTeamInvites'])->middleware('ability:Пользователь');

    Route::delete('/team/leave', [ProfileController::class, 'leaveTeam'])->middleware('ability:Пользователь');

    Route::delete('/team', [ProfileController::class, 'deleteTeam'])->middleware('ability:Пользователь');
});


Route::get('/notifications', [NotificationsController::class, 'getNotifications'])->middleware('auth:sanctum');




Route::get('/tags', [TagController::class, 'index']);


// Admin routes

Route::prefix('/admin')->middleware(['auth:sanctum', 'ability:Админ'])->group(function () {

    Route::apiResource('/tags', TagController::class, ['only' => ['index', 'store', 'update', 'destroy']])->where(['id' => '[0-9]+']);
});
