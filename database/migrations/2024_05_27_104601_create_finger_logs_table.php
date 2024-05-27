<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFingerLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finger_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('log_time');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('siswa');
            $table->string('name');
            $table->text('data');
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
        Schema::dropIfExists('finger_logs');
    }
}
