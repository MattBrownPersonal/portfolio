<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLandingCodeToDeceasedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Making the column nullable allows the unique constraint to be applied when data already exists
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deceaseds', function (Blueprint $table) {
            $table->string('landing_code')->nullable()->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deceaseds', function (Blueprint $table) {
            //
        });
    }
}
