<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dossier_dossier', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_id')->constrained()->onDelete('cascade');
            $table->foreignId('dossier_lie_id')->constrained('dossiers')->onDelete('cascade');
            $table->enum('relation', ['appel','cassation','opposition','renvoi_premiere_instance','autre'])->default('autre');
            $table->timestamps();
            
            $table->unique(['dossier_id', 'dossier_lie_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('dossier_dossier');
    }
};