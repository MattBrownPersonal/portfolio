<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSiteTable extends Migration
{
    public function up()
    {
        Schema::create('user_site', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('site_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_site');
    }
}
