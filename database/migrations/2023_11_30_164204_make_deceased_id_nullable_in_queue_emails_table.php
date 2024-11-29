<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeDeceasedIdNullableInQueueEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('queue_emails', function (Blueprint $table) {
            $table->dropForeign(['deceased_id']);
        });

        Schema::table('queue_emails', function (Blueprint $table) {
            $table->integer('deceased_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('queue_emails', function (Blueprint $table) {
            //
        });
    }
}
