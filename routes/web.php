<?php

use Illuminate\Support\Facades\Route;
use FaceAuth\Http\Controllers\FaceAuthController;

Route::middleware(['web'])->group(function () {
   Route::get('/faceauth/faces', [FaceAuthController::class, 'faces']);
   Route::get('/faceauth/user-image/{id}/{filename}', [FaceAuthController::class, 'userImage']);
   Route::post('/faceauth/login-by-id', [FaceAuthController::class, 'loginById'])->name('faceauth.loginById');
});
