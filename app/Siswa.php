<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Siswa extends Model
{
    use SoftDeletes;

    protected $fillable = ['no_induk', 'nis', 'nama_siswa', 'kelas_id', 'jk', 'telp', 'tmp_lahir', 'tgl_lahir', 'foto'];

    public function absen_siswa()
    {
        return $this->hasMany('App\AbsenSiswa');
    }

    public function kelas()
    {
        return $this->belongsTo('App\Kelas')->withDefault();
    }

    public function ulangan($id)
    {
        $guru = Guru::where('id_card', Auth::user()->id_card)->first();
        $nilai = Ulangan::where('siswa_id', $id)->where('guru_id', $guru->id)->first();
        return $nilai;
    }

    public function sikap($id)
    {
        $guru = Guru::where('id_card', Auth::user()->id_card)->first();
        $nilai = Sikap::where('siswa_id', $id)->where('guru_id', $guru->id)->first();
        return $nilai;
    }

    public function nilai()
    {
        return $this->belongsToMany(Nilai::class, 'nilai_siswa', 'siswa_id', 'nilai_id')
            ->withPivot('nilai');
    }

    public function tabungan()
    {
        return $this->hasOne(Tabungan::class, 'siswa_id', 'id')->withDefault(['saldo' => 0]);
    }

    // public function nilai($id)
    // {
    //     $guru = Guru::where('id_card', Auth::user()->id_card)->first();
    //     $nilai = Rapot::where('siswa_id', $id)->where('guru_id', $guru->id)->first();
    //     return $nilai;
    // }

    protected $table = 'siswa';
}