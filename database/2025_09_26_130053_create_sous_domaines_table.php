<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sous_domaines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domaine_id')->constrained()->onDelete('cascade');
            $table->string('nom', 150);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sous_domaines');
    }
};