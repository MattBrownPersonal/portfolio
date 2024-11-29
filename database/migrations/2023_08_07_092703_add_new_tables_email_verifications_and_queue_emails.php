<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewTablesEmailVerificationsAndQueueEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('email_hash');
            $table->boolean('verified')->default(false);
            $table->timestamps();
        });
        Schema::create('queue_emails', function (Blueprint $table) {
            $table->id();
            $table->string('to_email');
            $table->unsignedBigInteger('email_verification_id');
            $table->text('email_body');
            $table->string('email_subject');
            $table->mediumText('email_json');
            $table->string('email_type')->default('plain');
            $table->unsignedBigInteger('deceased_id');
            $table->boolean('sent')->default(false);
            $table->timestamps();
        });
        Schema::table('queue_emails', function (Blueprint $table) {
            $table->foreign('email_verification_id')->references('id')->on('email_verifications');
            $table->foreign('deceased_id')->references('id')->on('deceaseds');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('queue_emails');
        Schema::drop('email_verifications');
    }
}
