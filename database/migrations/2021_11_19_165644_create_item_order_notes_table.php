<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemOrderNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_order_notes', function (Blueprint $table) {
            $table->id();
            $table->integer('item_order_id');
            $table->integer('user_id');
            $table->mediumText('note')->nullable();
            $table->integer('old_status_id')->nullable();
            $table->integer('new_status_id')->nullable();
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
        Schema::dropIfExists('item_order_notes');
    }
}
