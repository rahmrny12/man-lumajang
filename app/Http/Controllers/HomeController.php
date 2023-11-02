<?php

namespace App\Http\Controllers;

use App\Hari;
use App\Jadwal;
use App\Guru;
use App\Kehadiran;
use App\Kelas;
use App\Siswa;
use App\Mapel;
use App\User;
use App\Paket;
use App\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $user = auth()->user();
        $hari = date('w');
        $jam = date('H:i:s', strtotime('-10 minutes'));
        $jadwal = Jadwal::with("tukar_jadwal")->OrderBy('jadwal.jam_mulai')->OrderBy('jadwal.jam_selesai')->OrderBy('jadwal.kelas_id');

        $tukar_jadwal = [];
        if ($user->role == "Guru") {
            $tukar_jadwal = Jadwal::whereNotIn('guru_id', [$user->guru($user->id_card)->id])->get();
        }

        $days = Hari::get();

        if ($user->role == 'Guru') {
            $jadwal = $jadwal->where('guru_id', $user->guru($user->id_card)->id);
        }

        $jadwal = $jadwal->get();
        $pengumuman = Pengumuman::first();
        $kehadiran = Kehadiran::all();
        return view('home', compact('jadwal', 'pengumuman', 'kehadiran', 'days', 'tukar_jadwal'));
    }

    public function admin()
    {
        $jadwal = Jadwal::count();
        $guru = Guru::count();
        $gurulk = Guru::where('jk', 'L')->count();
        $gurupr = Guru::where('jk', 'P')->count();
        $siswa = Siswa::count();
        $siswalk = Siswa::where('jk', 'L')->count();
        $siswapr = Siswa::where('jk', 'P')->count();
        $kelas = Kelas::count();
        $bkp = Kelas::where('paket_id', '1')->count();
        $dpib = Kelas::where('paket_id', '2')->count();
        $ei = Kelas::where('paket_id', '3')->count();
        $oi = Kelas::where('paket_id', '4')->count();
        $tbsm = Kelas::where('paket_id', '6')->count();
        $rpl = Kelas::where('paket_id', '7')->count();
        $tpm = Kelas::where('paket_id', '5')->count();
        $las = Kelas::where('paket_id', '8')->count();
        $mapel = Mapel::count();
        $user = User::count();
        $paket = Paket::all();
        return view(
            'admin.index',
            compact(
                'jadwal',
                'guru',
                'gurulk',
                'gurupr',
                'siswalk',
                'siswapr',
                'siswa',
                'kelas',
                'bkp',
                'dpib',
                'ei',
                'oi',
                'tbsm',
                'rpl',
                'tpm',
                'las',
                'mapel',
                'user',
                'paket'
            )
        );
    }
}