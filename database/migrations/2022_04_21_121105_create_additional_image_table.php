<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionalImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_images', function (Blueprint $table) {
            $table->id();
            $table->integer('left');
            $table->integer('top');
            $table->integer('width');
            $table->integer('height');
            $table->integer('rotation');
            $table->string('type');
            $table->integer('custom_image_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('additional_image');
    }
}
