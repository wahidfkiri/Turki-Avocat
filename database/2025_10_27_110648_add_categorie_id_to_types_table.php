<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('types', function (Blueprint $table) {
            // Add categorie_id as unsigned big integer after nom column
            $table->unsignedBigInteger('categorie_id')->nullable()->after('nom');
            
            // Add foreign key constraint
            $table->foreign('categorie_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade'); // or onDelete('restrict') depending on your needs
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('types', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['categorie_id']);
            
            // Then drop the column
            $table->dropColumn('categorie_id');
        });
    }
};