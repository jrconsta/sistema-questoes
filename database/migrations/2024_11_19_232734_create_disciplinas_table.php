<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('disciplinas', function (Blueprint $table) {
            $table->id('id_disciplina');  // usando id() que é auto_increment
            $table->string('nome', 100)->nullable();
            $table->string('nome_sigla', 100)->nullable();
            $table->timestamps(); // created_at e updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('disciplinas');
    }
};
