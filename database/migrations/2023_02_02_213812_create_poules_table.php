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
        Schema::create('poules', function (Blueprint $table) {
            $table->id();
            //$table->integer('tournement_id');
            $table->foreignID('tournement_id')->constrained('tournements')->onDelete('cascade');
            $table->string('poule_name');
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
        Schema::dropIfExists('poules');
    }
};
