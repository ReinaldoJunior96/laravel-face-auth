<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
   public function up()
   {
      Schema::create('faceauth_faces', function (Blueprint $table) {
         $table->id();
         $table->unsignedBigInteger('user_id');
         $table->text('face_image'); // base64, hash, ou caminho da imagem
         $table->timestamps();
         $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      });
   }

   public function down()
   {
      Schema::dropIfExists('faceauth_faces');
   }
};
