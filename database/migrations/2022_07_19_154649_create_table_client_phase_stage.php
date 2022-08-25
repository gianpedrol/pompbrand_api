<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableClientPhaseStage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_phases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_phase');
            $table->unsignedBigInteger('id_stage');
            $table->tinyInteger('status')->comment('0 => Not finished ; 1 => Finished')->default(0);
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('id_phase')->references('id')->on('table_phases')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('id_stage')->references('id')->on('table_stages')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_client_phase_stage');
    }
}
