<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteStylesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_styles', function (Blueprint $table) {
            $table->id();
            $table->integer('site_id');
            $table->text('primary_colour');
            $table->text('secondary_colour');
            $table->text('primary_font_colour');
            $table->text('secondary_font_colour');
            $table->integer('image_id')->nullable();
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
        Schema::dropIfExists('site_styles');
    }
}
