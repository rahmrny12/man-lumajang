<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FingerSiswa extends Model
{
    protected $guarded = ['id'];

    public function siswa()
    {
        return $this->belongsTo('App\Siswa');
    }

    protected $table = 'finger_siswa';
}
