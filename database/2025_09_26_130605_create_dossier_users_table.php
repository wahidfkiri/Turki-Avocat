<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dossier_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('ordre');
            $table->enum('role', ['avocat','clerc','secrétaire','stagiaire'])->default('avocat');
            $table->timestamps();
            
            $table->unique(['dossier_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('dossier_user');
    }
};