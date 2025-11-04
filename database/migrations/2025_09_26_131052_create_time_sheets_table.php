<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('time_sheets', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date_timesheet')->useCurrent();
            $table->foreignId('utilisateur_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('dossier_id')->nullable()->constrained()->onDelete('set null');
            $table->text('description')->nullable();
            $table->foreignId('categorie')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('type')->nullable()->constrained('types')->onDelete('set null');
            $table->decimal('quantite', 12, 2)->default(0.00);
            $table->decimal('prix', 12, 2)->default(0.00);
            $table->decimal('total', 12, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('time_sheets');
    }
};