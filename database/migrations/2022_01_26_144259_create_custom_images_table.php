<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_images', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('image_id');
            $table->integer('material_id')->nullable();
            $table->string('custom_image_url')->nullable();
            $table->string('layout')->nullable();
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
        Schema::dropIfExists('custom_images');
    }
}
