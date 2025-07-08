<?php

use Illuminate\Support\Facades\Route;
use FaceAuth\Http\Controllers\FaceAuthController;

Route::get('/faceauth/faces', [FaceAuthController::class, 'faces']);
Route::get('/faceauth/user-image/{id}/{filename}', [FaceAuthController::class, 'userImage']);
