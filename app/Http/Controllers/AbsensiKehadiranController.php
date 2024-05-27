<?php

namespace App\Http\Controllers;

use App\AbsensiKehadiranGuru;
use App\AbsensiKehadiran;
use App\AturanJamSiswa;
use App\Guru;
use App\Siswa;
use App\Kelas;
use Crypt;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiSiswaExport;
use App\Exports\AbsensiGuruExport;
use App\FingerLog;
use App\FingerSiswa;

class AbsensiKehadiranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kelas = Kelas::OrderBy('nama_kelas', 'asc')->get();
        return view('absensi_kehadiran.index', compact('kelas'));
    }

    public function kelas($id)
    {
        $id = Crypt::decrypt($id);
        $absensi_kehadiran = AbsensiKehadiran::whereHas('siswa', function ($query) use ($id) {
            $query->where('kelas_id', $id);
        })->orderByDesc('id')->get();
        $kelas = Kelas::findorfail($id);
        return view('absensi_kehadiran.show', compact('absensi_kehadiran', 'kelas'));
    }

    public function guru()
    {
        $absensi_kehadiran = AbsensiKehadiranGuru::orderByDesc('id')->get();

        return view('absensi_kehadiran.guru', compact('absensi_kehadiran'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
        return view('absensi_kehadiran.absen', compact('type'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'keyword' => 'required',
        ]);

        $is_siswa = true;
        $user = Siswa::with('kelas')->where('rfid', $request->keyword)->first();
        if (!$user) {
            $is_siswa = false;
            $user = Guru::where('rfid', $request->keyword)->first();
        }

        if ($user) {
            $aturan_jam_siswa = AturanJamSiswa::where('status', 1)->first();

            if (!$aturan_jam_siswa)
                return response()->json(['type' => 'warning', 'message' => 'Tidak ada jam mengajar yang aktif']);

            $jam_sekarang = Carbon::now()->toTimeString();

            if ($is_siswa) {
                $existing_absen = AbsensiKehadiran::where(['id_siswa' => $user->id, 'tanggal' => Carbon::now()->format('Y-m-d')])->first();
            } else {
                $existing_absen = AbsensiKehadiranGuru::where(['id_guru' => $user->id, 'tanggal' => Carbon::now()->format('Y-m-d')])->first();
            }

            if ($existing_absen) {
                if ($existing_absen->jam_pulang) {
                    return response()->json(['type' => 'success', 'message' => 'Absensi pulang sudah berhasil']);
                } else {
                    if ($aturan_jam_siswa->jam_pulang > $jam_sekarang) {
                        return response()->json(['type' => 'warning', 'message' => 'Jam pulang belum tercapai']);
                    }

                    $existing_absen->update([
                        'jam_pulang' => $jam_sekarang,
                    ]);

                    $existing_absen->refresh();

                    if ($user->no_telp != 0) {
                        $formattedDate = Carbon::parse($existing_absen->tanggal)->locale('id')->isoFormat('D MMMM YYYY');

                        if ($is_siswa) {
                            $message = "INFO ABSENSI MIS JAMBE ARUM\n\nNAMA : {$user->nama_siswa}\nKELAS : {$user->kelas->nama_kelas}\n\nTELAH MELAKUKAN ABSENSI PULANG PADA\nPUKUL: {$existing_absen->jam_pulang}\nTANGGAL: {$formattedDate}\n";
                        } else {
                            $message = "INFO ABSENSI MIS JAMBE ARUM\n\nNAMA : {$user->nama_guru}\n\nTELAH MELAKUKAN ABSENSI PULANG PADA\nPUKUL: {$existing_absen->jam_pulang}\nTANGGAL: {$formattedDate}\n";
                        }

                        try {
                            $client = new Client;
                            $request = $client->post('http://128.199.217.52/send-message', [
                                'form_params' => [
                                    'text' => $message,
                                    'to' => '62' . substr($user->no_telp, 1),
                                    'session' => 'mysession'
                                ]
                            ]);
                        } catch (\Throwable $e) {
                            return response()->json([
                                'type' => 'warning',
                                'message' => 'Absensi pulang berhasil. Gagal mengirimkan pesan notifikasi',
                                'data' => $e->getMessage(),
                            ]);
                        }
                    }

                    return response()->json(['type' => 'success', 'message' => 'Absensi pulang berhasil']);
                }
            }

            $jam_masuk = $aturan_jam_siswa->jam_masuk;
            $keterangan = 'Tepat Waktu';

            if ($jam_sekarang > $jam_masuk) {
                $keterangan = 'Terlambat';
            }

            if ($is_siswa) {
                $inserted_absensi = AbsensiKehadiran::create([
                    'id_siswa' => $user->id,
                    'jam_masuk' => $jam_sekarang,
                    'jam_pulang' => null,
                    'status_masuk' => $keterangan,
                    'tanggal' => Carbon::now()->format('Y-m-d'),
                ]);
            } else {
                $inserted_absensi = AbsensiKehadiranGuru::create([
                    'id_guru' => $user->id,
                    'jam_masuk' => $jam_sekarang,
                    'jam_pulang' => null,
                    'status_masuk' => $keterangan,
                    'tanggal' => Carbon::now()->format('Y-m-d'),
                ]);
            }

            if ($user->no_telp != 0) {
                $formattedDate = Carbon::parse($inserted_absensi->tanggal)->locale('id')->isoFormat('D MMMM YYYY');

                if ($is_siswa) {
                    $message = "INFO ABSENSI MIS JAMBE ARUM\n\nNAMA : {$user->nama_siswa}\nKELAS : {$user->kelas->nama_kelas}\n\nTELAH MELAKUKAN ABSENSI PADA\nPUKUL: {$inserted_absensi->jam_masuk}\nTANGGAL: {$formattedDate}\nSTATUS: {$keterangan}";
                } else {
                    $message = "INFO ABSENSI MIS JAMBE ARUM\n\nNAMA : {$user->nama_guru}\n\nTELAH MELAKUKAN ABSENSI PADA\nPUKUL: {$inserted_absensi->jam_masuk}\nTANGGAL: {$formattedDate}\n";
                }

                try {
                    $client = new Client;
                    $request = $client->post('http://128.199.217.52/send-message', [
                        'form_params' => [
                            'text' => $message,
                            'to' => '62' . substr($user->no_telp, 1),
                            'session' => 'mysession'
                        ]
                    ]);
                } catch (\Throwable $e) {
                    return response()->json([
                        'type' => 'warning',
                        'message' => 'Absensi masuk berhasil. Gagal mengirimkan pesan notifikasi',
                        'data' => $e->getMessage(),
                    ]);
                }
            }

            return response()->json(['type' => 'success', 'message' => 'Absensi masuk berhasil']);
        } else {
            return response()->json(['type' => 'error', 'message' => 'Siswa tidak ditemukan']);
        }
    }

    public function check_finger(Request $request)
    {
        $finger_log = FingerLog::where('user_id', $request->id)
            ->where('is_confirmed', false)->latest();

        if ($finger_log->exists()) {
            $finger_log->update(['is_confirmed' => true]);
            
            return response()->json(['type' => 'success', 'message' => 'Sidik jari berhasil diverifikasi', 'data' => $finger_log]);
        }

        return response()->json(['type' => 'error', 'message' => 'Sidik jari tidak sesuai'], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AbsensiKehadiran  $absensiKehadiran
     * @return \Illuminate\Http\Response
     */
    public function search_by_rfid(Request $request)
    {
        $request->validate([
            'keyword' => 'required',
        ]);

        $user = Siswa::with('kelas')->where('rfid', $request->keyword)->first();
        if (!$user)
            $user = Guru::where('rfid', $request->keyword)->first();

        if (!$user)
            return response()->json(['type' => 'error', 'message' => 'Siswa tidak ditemukan'], 404);

        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AbsensiKehadiran  $absensiKehadiran
     * @return \Illuminate\Http\Response
     */
    public function edit(AbsensiKehadiran $absensiKehadiran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AbsensiKehadiran  $absensiKehadiran
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AbsensiKehadiran $absensiKehadiran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AbsensiKehadiran  $absensiKehadiran
     * @return \Illuminate\Http\Response
     */
    public function destroy_siswa($id)
    {
        AbsensiKehadiran::find($id)->delete();

        return redirect()->back()->with('success', 'Absensi siswa berhasil dihapus');
    }

    public function destroy_guru($id)
    {
        AbsensiKehadiranGuru::find($id)->delete();

        return redirect()->back()->with('success', 'Absensi guru berhasil dihapus');
    }

    public function export_excel_siswa($id)
    {
        $id = Crypt::decrypt($id);
        $date = Carbon::now()->toDateString();
        return Excel::download(new AbsensiSiswaExport($id), "data-absensi-siswa-$date.xlsx");
    }

    public function export_excel_guru()
    {
        $date = Carbon::now()->toDateString();
        return Excel::download(new AbsensiGuruExport(), "data-absensi-guru-$date.xlsx");
    }
}
