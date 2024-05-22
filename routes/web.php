<?php

use App\Http\Controllers\EmailVerifyController;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/email/verify', function () {
    return view('welcome');
})->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [EmailVerifyController::class, 'verify'])->middleware(['signed'])->name('verification.verify')->where('id', '[0-9]+');

Route::get('/forgot-password', function () {
    return view('welcome');
})->name('password.request');

Route::get('/reset-password/{token}', function (string $token) {
    return view('welcome');
})->middleware('guest')->name('password.reset');
Route::get('/{vue_capture?}', function () {
    return view('welcome');
})->where('vue_capture', '[\/\w\.-]*')->name('welcome');
