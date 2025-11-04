<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('types', function (Blueprint $table) {
            // Drop unique index if it exists
            $table->dropUnique(['nom']);
        });
    }

    public function down(): void
    {
        Schema::table('types', function (Blueprint $table) {
            // Restore unique constraint if needed
            $table->unique(['nom']);
        });
    }
};