<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'no_induk',
        'id_card',
        'tingkatan_kelas',
        'roles'
    ];

    public function guru($id)
    {
        $guru = Guru::where('id_card', $id)->first();
        return $guru;
    }

    public function siswa($id)
    {
        $siswa = Siswa::where('no_induk', $id)->first();
        return $siswa;
    }

    public function aktivitas_tambahan()
    {
        return $this->hasMany(AktivitasTambahan::class)->withDefault();
    }

    public function jadwal_cs()
    {
        return $this->hasMany(JadwalCS::class, 'user_id', 'id');
    }

    public function getRoleAttribute()
    {
        return session('role');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}