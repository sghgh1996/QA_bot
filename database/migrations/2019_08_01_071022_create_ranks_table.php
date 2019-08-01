<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ranks', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('choice_id')->unsigned();
            $table->foreign('choice_id')
                ->references('id')
                ->on('choices')
                ->onDelete('cascade');
            $table->integer('algorithm_id')->unsigned();
            $table->foreign('algorithm_id')
                ->references('id')
                ->on('algorithms')
                ->onDelete('cascade');
            $table->integer('value');
            
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
        Schema::dropIfExists('ranks');
    }
}
