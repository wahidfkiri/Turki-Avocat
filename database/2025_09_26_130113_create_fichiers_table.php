<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fichiers', function (Blueprint $table) {
            $table->id();
            $table->enum('type_module', ['intervenant','facture','agenda','tache','timesheet']);
            $table->unsignedBigInteger('module_id');
            $table->string('nom_fichier', 255);
            $table->string('chemin_fichier', 500);
            $table->string('type_mime', 100);
            $table->unsignedBigInteger('taille')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('date_upload')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fichiers');
    }
};