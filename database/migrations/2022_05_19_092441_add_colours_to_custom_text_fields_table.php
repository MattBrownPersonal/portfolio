<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColoursToCustomTextFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_text_fields', function (Blueprint $table) {
            $table->string('highlight')->nullable();
            $table->string('shadow')->nullable();
            $table->string('fill')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_text_fields', function (Blueprint $table) {
            //
        });
    }
}
