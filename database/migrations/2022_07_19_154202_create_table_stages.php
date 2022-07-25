<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableStages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_stages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('phase_id');
            $table->text('stage');
            $table->timestamps();

            $table->foreign('phase_id')->references('id')->on('table_phases')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_stages');
    }
}
