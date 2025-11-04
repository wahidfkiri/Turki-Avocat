<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dossiers', function (Blueprint $table) {
            $table->id();
            $table->string('numero_dossier', 20)->unique();
            $table->string('nom_dossier', 255);
            $table->text('objet')->nullable();
            $table->timestamp('date_entree')->useCurrent();
            $table->foreignId('domaine_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('sous_domaine_id')->nullable()->constrained('sous_domaines')->onDelete('set null');
            $table->boolean('conseil')->default(false);
            $table->boolean('contentieux')->default(false);
            $table->string('numero_role', 50)->nullable();
            $table->enum('chambre', ['civil','commercial','social','pénal'])->nullable();
            $table->string('numero_chambre', 50)->nullable();
            $table->string('numero_parquet', 50)->nullable();
            $table->string('numero_instruction', 50)->nullable();
            $table->string('numero_plainte', 50)->nullable();
            $table->boolean('archive')->default(false);
            $table->date('date_archive')->nullable();
            $table->string('boite_archive', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dossiers');
    }
};