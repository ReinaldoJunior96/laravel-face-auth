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
            __DIR__ . '/../database/migrations/2025_07_08_000000_add_face_column_to_users_table.php' => database_path('migrations/2025_07_08_000000_add_face_column_to_users_table.php'),
         ], 'faceauth-migrations');
      }
   }

   public function register()
   {
      // Futuras dependências e bindings
   }
}
