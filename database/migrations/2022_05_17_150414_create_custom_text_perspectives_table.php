<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomTextPerspectivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_text_perspectives', function (Blueprint $table) {
            $table->id();
            $table->integer('custom_image_id');
            $table->integer('ax');
            $table->integer('ay');
            $table->integer('bx');
            $table->integer('by');
            $table->integer('cx');
            $table->integer('cy');
            $table->integer('dx');
            $table->integer('dy');
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
        Schema::dropIfExists('custom_text_perspectives');
    }
}
