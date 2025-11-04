<?php
// database/migrations/2024_01_01_000000_create_email_dossier_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailDossierTable extends Migration
{
    public function up()
    {
        Schema::create('email_dossier', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_id')->constrained()->onDelete('cascade');
            $table->string('email_uid'); // UID de l'email IMAP
            $table->string('folder_name'); // Nom du dossier IMAP source
            $table->text('subject')->nullable();
            $table->text('from')->nullable();
            $table->timestamp('email_date')->nullable();
            $table->timestamps();

            // Index pour les performances
            $table->index(['dossier_id', 'email_uid']);
            $table->index('email_uid');
            $table->unique(['dossier_id', 'email_uid', 'folder_name']); // Éviter les doublons
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_dossier');
    }
}