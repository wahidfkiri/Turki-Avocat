<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 255);
            $table->text('description')->nullable();
            $table->date('date_debut');
            $table->time('heure_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->time('heure_fin')->nullable();
            $table->boolean('all_day')->default(false);
            $table->foreignId('dossier_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('intervenant_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('utilisateur_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('categorie', ['rdv','audience','delai','tache','autre'])->default('rdv');
            $table->string('couleur', 20)->default('#3c8dbc');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('agendas');
    }
};