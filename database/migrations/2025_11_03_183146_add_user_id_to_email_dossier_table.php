<?php
// database/migrations/2024_01_01_000001_add_user_id_to_email_dossier_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToEmailDossierTable extends Migration
{
    public function up()
    {
        Schema::table('email_dossier', function (Blueprint $table) {
            // Ajouter la colonne user_id
            $table->foreignId('user_id')
                  ->nullable() // Temporairement nullable pour les données existantes
                  ->constrained()
                  ->onDelete('cascade');
            
            // Mettre à jour l'index unique pour inclure user_id
            $table->dropUnique(['dossier_id', 'email_uid', 'folder_name']);
            
            $table->unique(['dossier_id', 'email_uid', 'folder_name', 'user_id'], 'email_dossier_unique');
        });
    }

    public function down()
    {
        Schema::table('email_dossier', function (Blueprint $table) {
            // Supprimer l'index unique
            $table->dropUnique('email_dossier_unique');
            
            // Rétablir l'ancien index unique
            $table->unique(['dossier_id', 'email_uid', 'folder_name']);
            
            // Supprimer la clé étrangère et la colonne
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}