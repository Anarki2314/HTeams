<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailVerifyController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
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

Route::apiResource('/teams', TeamController::class)->only(['index', 'show']);
Route::apiResource('/teams', TeamController::class)->only(['store'])->middleware(['auth:sanctum', 'ability:Пользователь', 'verified']);



// Event Organizer/Admin routes

Route::get('/events/{id}/full', [EventController::class, 'getFullEvent'])->middleware(['auth:sanctum', 'ability:Организатор,Админ', 'verified'])->where(['id' => '[0-9]+']);

Route::get('/events/moderation', [EventController::class, 'getModerationEvents'])->middleware(['auth:sanctum', 'ability:Организатор,Админ', 'verified']);

Route::get('/events/upcoming', [EventController::class, 'getUpcomingEvents'])->middleware(['auth:sanctum', 'ability:Пользователь,Организатор', 'verified']);
Route::get('/events/finished', [EventController::class, 'getFinishedEvents'])->middleware(['auth:sanctum', 'ability:Пользователь,Организатор', 'verified']);

Route::post('/events/{id}/join', [EventController::class, 'joinEvent'])->middleware(['auth:sanctum', 'ability:Пользователь', 'verified', 'verified'])->where(['id' => '[0-9]+']);
Route::delete('/events/{id}/leave', [EventController::class, 'leaveEvent'])->middleware(['auth:sanctum', 'ability:Пользователь', 'verified'])->where(['id' => '[0-9]+']);


Route::get('/events/{id}/answers', [EventController::class, 'getEventAnswers'])->middleware(['auth:sanctum', 'ability:Организатор', 'verified'])->where(['id' => '[0-9]+']);
Route::get('/events/{id}/teams', [EventController::class, 'getEventTeams'])->middleware(['auth:sanctum', 'ability:Организатор', 'verified'])->where(['id' => '[0-9]+']);

Route::post('/events/{id}/winners', [EventController::class, 'setEventWinners'])->middleware(['auth:sanctum', 'ability:Организатор', 'verified'])->where(['id' => '[0-9]+']);
Route::post('/events/{id}/answer', [EventController::class, 'answerEvent'])->middleware(['auth:sanctum', 'ability:Пользователь', 'verified'])->where(['id' => '[0-9]+']);

Route::post('/events/{id}/cancel', [EventController::class, 'cancelEvent'])->middleware(['auth:sanctum', 'ability:Организатор,Админ', 'verified'])->where(['id' => '[0-9]+']);
Route::put('/events/{id}/approve', [EventController::class, 'approveEvent'])->middleware(['auth:sanctum', 'ability:Админ', 'verified'])->where(['id' => '[0-9]+']);

Route::put('/events/{id}', [EventController::class, 'updateEvent'])->middleware(['auth:sanctum', 'ability:Организатор', 'verified'])->where(['id' => '[0-9]+']);


// Event routes

Route::post('/events/', [EventController::class, 'createEvent'])->middleware(['auth:sanctum', 'ability:Организатор', 'verified']);

Route::apiResource('/events', EventController::class)->only(['index', 'show'])->where(['id' => '[0-9]+']);


// Profile routes (only for logged users)

Route::prefix('/profile')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [ProfileController::class, 'getProfile'])->middleware(['verified']);

    Route::delete('/', [ProfileController::class, 'deleteProfile', 'verified']);
    Route::put('/', [ProfileController::class, 'updateProfile', 'verified']);

    // Profile Team routes

    Route::post('/create-team', [ProfileController::class, 'createTeam'])->middleware('ability:Пользователь')->middleware(['verified']);

    Route::post('/join-team', [ProfileController::class, 'inviteToTeam'])->middleware('ability:Пользователь')->middleware(['verified']);

    Route::get('/team', [ProfileController::class, 'getTeam'])->middleware('ability:Пользователь')->middleware(['verified']);

    Route::post('/team/invite', [ProfileController::class, 'inviteFromTeam'])->middleware('ability:Пользователь')->middleware(['verified']);

    Route::post('/team/invite/team-choice', [ProfileController::class, 'teamChoiceInvite'])->middleware('ability:Пользователь');

    Route::post('/team/invite/user-choice', [ProfileController::class, 'userChoiceInvite'])->middleware('ability:Пользователь');

    Route::get('/team/invites', [ProfileController::class, 'getTeamInvites'])->middleware('ability:Пользователь')->middleware(['verified']);

    Route::post('/generate-avatar', [ProfileController::class, 'generateNewAvatar'])->middleware(['verified']);

    Route::post('/change-password', [ProfileController::class, 'changePassword'])->middleware(['verified']);

    Route::delete('/team/leave', [ProfileController::class, 'leaveTeam'])->middleware('ability:Пользователь')->middleware(['verified']);

    Route::delete('/team', [ProfileController::class, 'deleteTeam'])->middleware('ability:Пользователь')->middleware(['verified']);
    Route::delete('/team/{id}/kick', [TeamController::class, 'kickMember'])->middleware('ability:Пользователь')->middleware(['verified'])->where(['id' => '[0-9]+']);
});


Route::get('/notifications', [NotificationsController::class, 'getNotifications'])->middleware('auth:sanctum');
Route::delete('/notifications/{id}', [NotificationsController::class, 'deleteNotification'])->middleware('auth:sanctum')->where(['id' => '[0-9]+']);




Route::get('/tags', [TagController::class, 'index']);

Route::get('/statuses', [StatusController::class, 'index']);

Route::get('/stats', [NotificationsController::class, 'getStatsInfo']);

Route::get('/near', [EventController::class, 'getNearEvents']);
// Admin routes

Route::prefix('/admin')->middleware(['auth:sanctum', 'ability:Админ', 'verified'])->group(function () {

    Route::apiResource('/tags', TagController::class, ['only' => ['index', 'store', 'update', 'destroy']])->where(['id' => '[0-9]+']);

    Route::apiResource('/users', UserController::class, ['only' => ['index', 'show']])->where(['id' => '[0-9]+']);

    Route::post('/users/{id}/ban', [UserController::class, 'banUser'])->where(['id' => '[0-9]+']);

    Route::post('/users/{id}/unban', [UserController::class, 'unbanUser'])->where(['id' => '[0-9]+']);

    Route::post('/users/{id}/ban-by-team', [UserController::class, 'banUsersByTeam'])->where(['id' => '[0-9]+']);

    Route::post('/users/{id}/unban-by-team', [UserController::class, 'unbanUsersByTeam'])->where(['id' => '[0-9]+']);
});

Route::post('/email/verify/resend', [EmailVerifyController::class, 'send'])->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->middleware('guest')->name('password.email');

Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->middleware('guest')->name('password.update');
