<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained('intervenants')->onDelete('restrict');
            $table->enum('type_piece', ['facture','note_frais','note_provision','avoir'])->default('facture');
            $table->string('numero', 100);
            $table->date('date_emission');
            $table->decimal('montant_ht', 12, 2)->default(0.00);
            $table->decimal('montant_tva', 12, 2)->default(0.00);
            $table->decimal('montant', 12, 2)->default(0.00);
            $table->enum('statut', ['payé','non_payé'])->default('non_payé');
            $table->text('commentaires')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('factures');
    }
};