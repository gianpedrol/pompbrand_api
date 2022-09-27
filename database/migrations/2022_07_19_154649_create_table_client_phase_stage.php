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
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->id();
            
            $table->unsignedBigInteger('id_user')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->unsignedBigInteger('id_phase')->references('id')->on('phases')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->unsignedBigInteger('id_stage')->references('id')->on('stages')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->tinyInteger('status')->comment('0 => Not finished ; 1 => Finished')->default(0);
            $table->timestamps();

      /*      $table->foreign('id_user')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('id_phase')->references('id')->on('phases')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('id_stage')->references('id')->on('stages')->onUpdate('NO ACTION')->onDelete('CASCADE');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('client_phases');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
