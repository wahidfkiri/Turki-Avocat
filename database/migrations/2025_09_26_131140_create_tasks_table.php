<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 255);
            $table->text('description')->nullable();
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->enum('priorite', ['basse','normale','haute','urgente'])->default('normale');
            $table->enum('statut', ['a_faire','en_cours','terminee','en_retard'])->default('a_faire');
            $table->foreignId('dossier_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('intervenant_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('utilisateur_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};