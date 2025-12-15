<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_sent_emails_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSentEmailsTable extends Migration
{
    public function up()
    {
        Schema::create('sent_emails', function (Blueprint $table) {
            $table->id();
            $table->string('from_email');
            $table->string('from_name');
            $table->string('to_email');
            $table->string('subject');
            $table->text('content');
            $table->json('cc')->nullable();
            $table->json('bcc')->nullable();
            $table->timestamp('sent_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sent_emails');
    }
}