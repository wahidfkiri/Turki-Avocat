<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('intervenants', function (Blueprint $table) {
            $table->id();
            $table->string('identite_fr', 255);
            $table->string('identite_ar', 255)->nullable();
            $table->enum('type', ['personne physique','personne morale','entreprise individuelle']);
            $table->string('numero_cni', 50)->nullable();
            $table->string('rne', 50)->nullable();
            $table->string('numero_cnss', 50)->nullable();
            $table->foreignId('forme_sociale_id')->nullable()->constrained('forme_sociales')->onDelete('set null');
            $table->enum('categorie', [
                'contact','client','avocat','notaire','huissier',
                'juridiction','administrateur_judiciaire','mandataire_judiciaire',
                'adversaire','expert_judiciaire'
            ])->default('contact');
            $table->string('fonction', 255)->nullable();
            $table->string('adresse1', 255)->nullable();
            $table->string('adresse2', 255)->nullable();
            $table->string('portable1', 30)->nullable();
            $table->string('portable2', 30)->nullable();
            $table->string('mail1', 255)->nullable();
            $table->string('mail2', 255)->nullable();
            $table->string('site_internet', 255)->nullable();
            $table->string('fixe1', 30)->nullable();
            $table->string('fixe2', 30)->nullable();
            $table->string('fax', 30)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('archive')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('intervenants');
    }
};