<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomTextFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_text_fields', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('custom_image_id')->nullable();
            $table->string('custom_product_text_id')->nullable();
            $table->string('lineType')->nullable();
            $table->integer('left')->nullable();
            $table->integer('top')->nullable();
            $table->integer('fontSize')->nullable();
            $table->integer('angle')->nullable();
            $table->integer('radius')->nullable();
            $table->integer('centerRotation')->nullable();
            $table->integer('arc')->nullable();
            $table->integer('rectangleX')->nullable();
            $table->integer('rectangleY')->nullable();
            $table->integer('rectangleWidth')->nullable();
            $table->integer('rectangleHeight')->nullable();
            $table->integer('rectangleTextScale')->nullable();
            $table->integer('letterCount')->nullable();
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
        Schema::dropIfExists('custom_text_fields');
    }
}
