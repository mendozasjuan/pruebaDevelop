<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Encargos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encargos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('albaran',10);
            $table->string('destinatario',28);
            $table->string('direccion',250);
            $table->string('pob',10);
            $table->string('destinatlacion',10);
            $table->string('cp',5);
            $table->string('provincia',20);
            $table->string('telefono', 10);
            $table->string('observaciones',100);
            $table->dateTime('fecha');
            //$table->rememberToken();
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
        chema::drop('encargos');
    }
}
