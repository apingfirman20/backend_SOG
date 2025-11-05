<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class MediafileController extends Controller
{
   public function up()
{
    Schema::table('news', function (Blueprint $table) {
        $table->foreignId('user_id')->nullable()->change();
        $table->string('news_id')->nullable();
        $table->string('media_path')->nullable();
    });
}


}
