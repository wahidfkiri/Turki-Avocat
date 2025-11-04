<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('intervenant_intervenant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intervenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('intervenant_lie_id')->constrained('intervenants')->onDelete('cascade');
            $table->enum('relation', [
                'représente','représentant_légal','administrateur_judiciaire',
                'mandataire','lié','autre'
            ])->default('lié');
            $table->timestamps();
            
            $table->unique(['intervenant_id', 'intervenant_lie_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('intervenant_intervenant');
    }
};