<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('questoes', function (Blueprint $table) {
            $table->id('id_questao');
            $table->string('enunciado', 1000)->nullable();
            $table->string('a', 500)->nullable();
            $table->string('b', 500)->nullable();
            $table->string('c', 500)->nullable();
            $table->string('d', 500)->nullable();
            $table->string('e', 500)->nullable();
            $table->char('resposta_correta', 1)->nullable();
            $table->string('Materia', 100)->nullable();
            $table->string('Nome_materia', 100)->nullable();
            $table->unsignedInteger('id_disciplina_fk')->nullable();
            $table->string('resposta_v_f', 500)->nullable();
            $table->timestamps();

            // Adiciona a chave estrangeira
            $table->foreign('id_disciplina_fk')
                  ->references('id_disciplina')
                  ->on('disciplinas')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('questoes');
    }
};