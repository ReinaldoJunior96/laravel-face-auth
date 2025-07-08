<?php

use Illuminate\Support\Facades\Route;
use FaceAuth\Http\Controllers\FaceAuthController;
use Illuminate\Support\Facades\Config;

$prefix = Config::get('faceauth.route_prefix', 'faceauth');

Route::prefix($prefix)
   ->middleware(['web', 'throttle:10,1'])
   ->group(function () {
      Route::get('/faces', [FaceAuthController::class, 'faces']);
      Route::get('/user-image/{id}/{filename}', [FaceAuthController::class, 'userImage']);
      Route::post('/login-by-id', [FaceAuthController::class, 'loginById'])->name('faceauth.loginById');
   });
