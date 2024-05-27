<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotificationStatusToAbsenKehadiranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('absen_kehadiran_siswa', function (Blueprint $table) {
            $table->boolean('notifikasi_absen_masuk')->default(false);
            $table->boolean('notifikasi_absen_pulang')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('absen_kehadiran', function (Blueprint $table) {
            $table->dropColumn('notifikasi_absen_masuk');
            $table->dropColumn('notifikasi_absen_pulang');
        });
    }
}
