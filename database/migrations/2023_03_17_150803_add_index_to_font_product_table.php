<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToFontProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('font_product', function (Blueprint $table) {
            $table->index('font_id');
            $table->index('product_id');
            $table->unique(['product_id', 'font_id'], 'composite_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('font_product', function (Blueprint $table) {
            //
        });
    }
}
