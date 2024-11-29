<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToItemOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_orders', function (Blueprint $table) {
            $table->dropColumn(['product_id', 'text_colour']);
            $table->string('item_type')->nullable();
            $table->string('attribute_type')->nullable();
            $table->string('attribute_name')->nullable();
            $table->string('product_name')->nullable();
            $table->string('supplier_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_orders', function (Blueprint $table) {
            //
        });
    }
}
