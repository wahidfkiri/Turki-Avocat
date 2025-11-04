<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_agenda_categories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendaCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('agenda_categories', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('couleur')->default('#3c8dbc');
            $table->text('description')->nullable();
            $table->boolean('actif')->default(true);
            $table->integer('ordre')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('agenda_categories');
    }
}