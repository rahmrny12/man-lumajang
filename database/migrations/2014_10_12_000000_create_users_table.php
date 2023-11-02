<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', [
                'Admin',
                'Guru',
                'Siswa',
                'Tata Usaha',
                'BK',
                'CS',
                'Satpam',
                'Waka Kurikulum',
                'Pokja Kurikulum',
                'Waka Sarpras',
                'Pokja Sarpras',
                'Kaprogli'
            ]);
            $table->string('no_induk')->nullable();
            $table->string('tingkatan_kelas')->nullable();
            $table->string('id_card')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}