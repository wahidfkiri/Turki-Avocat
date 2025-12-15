<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intervenant_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('intervenant_id');
            $table->string('file_path');
            $table->string('description')->nullable();
            $table->foreign('intervenant_id')->references('id')->on('intervenants')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intervenant_files');
    }
};
