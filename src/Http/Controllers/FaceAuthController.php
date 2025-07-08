<?php

namespace FaceAuth\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FaceAuthController extends Controller
{
   public function faces()
   {
      $faces = DB::table('faceauth_faces')->get();
      return response()->json($faces->map(function ($face) {
         return [
            'user_id' => $face->user_id,
            'image_url' => Storage::url($face->face_image),
         ];
      }));
   }
}
