<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
   public function up()
   {
      if (Schema::hasTable('users') && !Schema::hasColumn('users', 'face_image')) {
         Schema::table('users', function (Blueprint $table) {
            $table->text('face_image')->nullable()->after('remember_token');
         });
      }
   }

   public function down()
   {
      if (Schema::hasTable('users') && Schema::hasColumn('users', 'face_image')) {
         Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('face_image');
         });
      }
   }
};
