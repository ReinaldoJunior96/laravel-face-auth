<?php

namespace FaceAuth\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FaceAuthController extends Controller
{
   public function faces()
   {
      $basePath = \FaceAuth\FaceAuthServiceProvider::getUsersImagePath();
      $usersDir = base_path($basePath . '/users');
      $faces = [];

      if (is_dir($usersDir)) {
         foreach (glob($usersDir . '/*') as $userDir) {
            $userId = basename($userDir);
            foreach (glob($userDir . '/*.jpg') as $img) {
               $faces[] = [
                  'user_id' => $userId,
                  'name' => $this->getUserName($userId), // customize conforme seu projeto
                  'url' => url('/faceauth/user-image/' . $userId . '/' . basename($img)),
               ];
            }
         }
      }
      return response()->json($faces);
   }

   public function userImage($id, $filename)
   {
      $basePath = \FaceAuth\FaceAuthServiceProvider::getUsersImagePath();
      $path = base_path($basePath . "/users/{$id}/{$filename}");
      if (!file_exists($path)) abort(404);
      return response()->file($path);
   }

   protected function getUserName($userId)
   {
      // Aqui você pode buscar do banco, ou usar o próprio id como nome
      return 'Usuário ' . $userId;
   }

   public function loginById(\Illuminate\Http\Request $request)
   {
      $request->validate([
         'user_id' => 'required|exists:users,id',
      ]);

      $userModel = config('auth.providers.users.model', \App\Models\User::class);
      $user = $userModel::find($request->user_id);

      \Illuminate\Support\Facades\Auth::login($user);
      $request->session()->regenerate();

      return response()->json(['redirect' => route('dashboard')]);
   }
}
