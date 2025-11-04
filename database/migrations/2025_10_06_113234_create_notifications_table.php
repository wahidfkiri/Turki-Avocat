<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The recipient user
            $table->foreignId('task_id')->nullable()->constrained()->onDelete('cascade'); // Optional link to a task

            // Notification details
            $table->string('title');
            $table->text('message')->nullable();
            $table->integer('is_read')->nullable()->default(0); // Track read/unread status

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
