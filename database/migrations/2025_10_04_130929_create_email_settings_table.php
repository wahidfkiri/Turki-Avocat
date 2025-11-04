<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_settings', function (Blueprint $table) {
            $table->id();
            // SMTP Configuration
            $table->string('smtp_host')->nullable();
            $table->integer('smtp_port')->nullable()->default(465);
            $table->string('smtp_encryption')->nullable()->default('ssl'); // tls, ssl, null
            $table->string('smtp_username')->nullable();
            $table->text('smtp_password')->nullable();
            
            // IMAP Configuration
            $table->string('imap_host')->nullable();
            $table->integer('imap_port')->nullable()->default(993);
            $table->string('imap_encryption')->nullable()->default('ssl'); // ssl, tls
            $table->string('imap_username')->nullable();
            $table->text('imap_password')->nullable();
            
            // For specific user assignment (nullable for global settings)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_settings');
    }
};