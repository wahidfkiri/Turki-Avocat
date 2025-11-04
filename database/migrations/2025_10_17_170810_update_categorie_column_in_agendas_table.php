<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateCategorieColumnInAgendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Using raw SQL for MySQL
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE agendas MODIFY categorie VARCHAR(255) NULL');
        }
        
        // For PostgreSQL
        elseif (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE agendas ALTER COLUMN categorie TYPE VARCHAR(255), ALTER COLUMN categorie DROP NOT NULL');
        }
        
        // For SQLite - you'll need a more complex approach
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert back to enum - you'll need to know the original enum values
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE agendas MODIFY categorie ENUM('value1', 'value2', 'value3') NULL");
        }
    }
}