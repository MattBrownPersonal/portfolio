<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('supplier_id');
            $table->string('name');
            $table->mediumText('description');
            $table->float('price');
            $table->integer('site_id');
            $table->integer('memorialisation_id')->nullable();
            $table->integer('featured')->default(0);
            $table->integer('saved')->default(0);
            $table->integer('draft')->default(0);
            $table->string('sku');
            $table->string('date_layout')->nullable();
            $table->string('thumbnail_image')->nullable();
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
        Schema::dropIfExists('products');
    }
}
