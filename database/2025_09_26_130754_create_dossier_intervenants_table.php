<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dossier_intervenant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_id')->constrained()->onDelete('cascade');
            $table->foreignId('intervenant_id')->constrained()->onDelete('cascade');
            $table->enum('role', [
                'client','avocat','avocat_secondaire','adversaire','huissier',
                'notaire','expert','juridiction','administrateur_judiciaire',
                'mandataire_judiciaire','autre'
            ])->default('client');
            $table->timestamps();
            
            $table->unique(['dossier_id', 'intervenant_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('dossier_intervenant');
    }
};