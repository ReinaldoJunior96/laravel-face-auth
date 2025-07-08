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
      }
      // Registra namespace para as views
      $this->loadViewsFrom(__DIR__ . '/../resources/views', 'faceauth');
      // Registra as rotas do package
      $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
   }

   public function register()
   {
      // Futuras dependências e bindings
   }
}
