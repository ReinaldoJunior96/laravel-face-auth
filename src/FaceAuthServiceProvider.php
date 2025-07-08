<?php

namespace FaceAuth;

use Illuminate\Support\ServiceProvider;

class FaceAuthServiceProvider extends ServiceProvider
{
   public function boot()
   {
      // Publica a migration ao rodar vendor:publish
      if ($this->app->runningInConsole()) {
         $this->publishes([
            __DIR__ . '/../database/migrations/2025_07_08_000001_create_faceauth_faces_table.php' => database_path('migrations/2025_07_08_000001_create_faceauth_faces_table.php'),
         ], 'faceauth-migrations');
         $this->publishes([
            __DIR__ . '/../resources/js/face-api.min.js' => public_path('vendor/faceauth/face-api.min.js'),
         ], 'faceauth-assets');
         $this->publishes([
            __DIR__ . '/../resources/models/face-api' => public_path('vendor/faceauth/models'),
         ], 'faceauth-models');
         // Publica todos os assets de uma vez
         $this->publishes([
            __DIR__ . '/../resources/js/face-api.min.js' => public_path('vendor/faceauth/face-api.min.js'),
            __DIR__ . '/../resources/models/face-api' => public_path('vendor/faceauth/models'),
         ], 'faceauth-assets');
         // Publica o config do package
         $this->publishes([
            __DIR__ . '/../config/faceauth.php' => config_path('faceauth.php'),
         ], 'faceauth-config');
      }
      // Registra namespace para as views
      $this->loadViewsFrom(__DIR__ . '/../resources/views', 'faceauth');
      // Registra as rotas do package com prefixo customizável
      $prefix = config('faceauth.route_prefix', 'faceauth');
      $this->loadRoutesFrom(function () use ($prefix) {
         \Illuminate\Support\Facades\Route::prefix($prefix)
            ->middleware(['web', 'throttle:10,1'])
            ->group(__DIR__ . '/../routes/web.php');
      });
   }

   public function register()
   {
      // Futuras dependências e bindings
   }
   // Retorna o path das imagens dos usuários, configurável via .env
   public static function getUsersImagePath()
   {
      return env('FACEAUTH_USERS_IMAGE_PATH', 'storage/app/private');
   }
}
