<?php

use App\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/welcome', function () {
  return view('welcome');
});

Route::get('/clear-cache', function () {
  Artisan::call('config:clear');
  Artisan::call('cache:clear');
  Artisan::call('config:cache');
  return 'DONE';
});

Auth::routes();
Route::get('/login/cek_email/json', 'UserController@cek_email');
Route::get('/login/cek_password/json', 'UserController@cek_password');
Route::post('/cek-email', 'UserController@email')->name('cek-email')->middleware('guest');
Route::get('/reset/password/{id}', 'UserController@password')->name('reset.password')->middleware('guest');
Route::patch('/reset/password/update/{id}', 'UserController@update_password')->name('reset.password.update')->middleware('guest');

Route::middleware(['auth'])->group(function () {
  Route::get('/', 'HomeController@index')->name('home');
  Route::get('/home', 'HomeController@index')->name('home');
  Route::get('/jadwal/sekarang', 'JadwalController@jadwalSekarang');
  Route::get('/profile', 'UserController@profile')->name('profile');
  Route::get('/pengaturan/profile', 'UserController@edit_profile')->name('pengaturan.profile');
  Route::post('/pengaturan/ubah-profile', 'UserController@ubah_profile')->name('pengaturan.ubah-profile');
  Route::get('/pengaturan/edit-foto', 'UserController@edit_foto')->name('pengaturan.edit-foto');
  Route::post('/pengaturan/ubah-foto', 'UserController@ubah_foto')->name('pengaturan.ubah-foto');
  Route::get('/pengaturan/email', 'UserController@edit_email')->name('pengaturan.email');
  Route::post('/pengaturan/ubah-email', 'UserController@ubah_email')->name('pengaturan.ubah-email');
  Route::get('/pengaturan/password', 'UserController@edit_password')->name('pengaturan.password');
  Route::post('/pengaturan/ubah-password', 'UserController@ubah_password')->name('pengaturan.ubah-password');

  // Absen Guru
  Route::get('/absensi/guru/{id}', 'GuruController@absen_guru')->name('absen.show');
  Route::get('/absen/detail/{id}', 'GuruController@absen_detail')->name('absen.detail');

  // Aktivitas Tambahan
  Route::resource('/aktivitas-tambahan', 'AktivitasTambahanController');
  // Route::get('aktivitas-tambahan/all', 'AktivitasTambahanController@aktivitas_tambahan_all')->name('aktivitas-tambahan.all');
  Route::get('karyawan/dashboard', 'KaryawanController@dashboard')->name('karyawan.dashboard');
  Route::get('karyawan/aktivitas-tambahan', 'KaryawanController@index')->name('karyawan.index');
  Route::post('karyawan/aktivitas-tambahan', 'KaryawanController@store')->name('karyawan.store');
  Route::delete('karyawan/aktivitas-tambahan/{id}', 'KaryawanController@destroy')->name('cs.destroy');

//   Route::middleware(['siswa'])->group(function () {
    Route::get('/jadwal/siswa', 'JadwalController@siswa')->name('jadwal.siswa');
    Route::get('/ulangan/siswa', 'UlanganController@siswa')->name('ulangan.siswa');
    Route::get('/sikap/siswa', 'SikapController@siswa')->name('sikap.siswa');
    Route::get('/rapot/siswa', 'RapotController@siswa')->name('rapot.siswa');
//   });

  Route::get('/karyawan/absen/harian', 'KaryawanController@absen')->name('karyawan.absen.harian');
  Route::post('/karyawan/absen/simpan', 'KaryawanController@simpan_absen')->name('karyawan.absen.simpan');
  Route::get('/karyawan/{role}/all', 'KaryawanController@karyawan_all')->name('karyawan.all');
  Route::get('/karyawan/rekap/absen', 'KaryawanController@rekap_absen')->name('absensikaryawan.index');
  Route::put('/karyawan/rekap/absen/confirm/{id}', 'KaryawanController@konfirmasi_absen')->name('absensikaryawan.confirm');
  Route::put('/karyawan/rekap/absen/reject/{id}', 'KaryawanController@tolak_absen')->name('absensikaryawan.reject');
  Route::put('/absen/confirm/{id}', 'GuruController@konfirmasi_absen')->name('absen.confirm');
  Route::put('/absen/reject/{id}', 'GuruController@tolak_absen')->name('absen.reject');

//   Route::middleware(['guru'])->group(function () {
    // Route::get('/jurnal', 'JurnalController@index')->name('jurnal.index');
    Route::get('/absen/harian', 'GuruController@absen')->name('absen.harian');
    Route::post('/absen/simpan', 'GuruController@simpan')->name('absen.simpan');
    Route::post('/absen/akhiri-absen/{id}', 'GuruController@akhiri_absen')->name('absen.akhiri_absen');
    Route::get('/jadwal/guru', 'JadwalController@guru')->name('jadwal.guru');
    Route::put('/pindah/jadwal', 'JadwalController@tukar_jadwal')->name('tukar_jadwal');
    Route::get('/nilai/get-nilai-siswa', 'NilaiController@get_nilai_siswa');
    Route::get('/nilai/get-siswa', 'NilaiController@get_siswa');
    Route::resource('/modul', 'ModulController');
    Route::resource('/nilai', 'NilaiController');
    Route::resource('/ulangan', 'UlanganController');
    Route::resource('/sikap', 'SikapController');
    Route::get('/rapot/predikat', 'RapotController@predikat');
    Route::resource('/rapot', 'RapotController');
//   });

//   Route::middleware(['bk'])->group(function () {
    Route::get('/absensi-siswa', 'BKController@index')->name('bk.absensi');
    Route::get('/konseling-siswa', 'BKController@create')->name('bk.konseling');
    Route::post('/konseling-siswa', 'BKController@store')->name('bk.store');
    Route::get('/get-siswa', 'BKController@get_siswa');
    Route::get('/bk/get-siswa', 'BKController@get_siswa_from_api');
    Route::get('/bk/get-kelas', 'BKController@get_kelas');
    Route::get('/bk/get-absensi-siswa', 'BKController@get_absensi_siswa');
    Route::get('/bk/konseling-siswa', 'BKController@get_konseling_siswa');
//   });

  Route::post('/bk/edit-kelas/{id}', 'BKController@edit_tingkatan_kelas');
  Route::get('/guru/absensi', 'GuruController@absensi')->name('guru.absensi');

  // Route::middleware(['admin'])->group(function () {
    // Route::middleware(['trash'])->group(function () {
      Route::get('/jadwal/trash', 'JadwalController@trash')->name('jadwal.trash');
      Route::get('/jadwal/restore/{id}', 'JadwalController@restore')->name('jadwal.restore');
      Route::delete('/jadwal/kill/{id}', 'JadwalController@kill')->name('jadwal.kill');
      Route::get('/guru/trash', 'GuruController@trash')->name('guru.trash');
      Route::get('/guru/restore/{id}', 'GuruController@restore')->name('guru.restore');
      Route::delete('/guru/kill/{id}', 'GuruController@kill')->name('guru.kill');
      Route::get('/kelas/trash', 'KelasController@trash')->name('kelas.trash');
      Route::get('/kelas/restore/{id}', 'KelasController@restore')->name('kelas.restore');
      Route::delete('/kelas/kill/{id}', 'KelasController@kill')->name('kelas.kill');
      Route::get('/siswa/trash', 'SiswaController@trash')->name('siswa.trash');
      Route::get('/siswa/restore/{id}', 'SiswaController@restore')->name('siswa.restore');
      Route::delete('/siswa/kill/{id}', 'SiswaController@kill')->name('siswa.kill');
      Route::get('/mapel/trash', 'MapelController@trash')->name('mapel.trash');
      Route::get('/mapel/restore/{id}', 'MapelController@restore')->name('mapel.restore');
      Route::delete('/mapel/kill/{id}', 'MapelController@kill')->name('mapel.kill');
      Route::get('/user/trash', 'UserController@trash')->name('user.trash');
      Route::get('/user/restore/{id}', 'UserController@restore')->name('user.restore');
      Route::delete('/user/kill/{id}', 'UserController@kill')->name('user.kill');
    // });

    Route::get('/admin/home', 'HomeController@admin')->name('admin.home');
    Route::get('/admin/pengumuman', 'PengumumanController@index')->name('admin.pengumuman');
    Route::post('/admin/pengumuman/simpan', 'PengumumanController@simpan')->name('admin.pengumuman.simpan');
    Route::get('/admin/menu', 'MenuController@index')->name('admin.menu');
    Route::post('/admin/menu/update/{role_id}', 'MenuController@update')->name('admin.update-menu');
    Route::get('/guru/kehadiran/{id}', 'GuruController@kehadiran')->name('guru.kehadiran');
    Route::get('/absen/json', 'GuruController@json');
    Route::get('/guru/mapel/{id}', 'GuruController@mapel')->name('guru.mapel');
    Route::get('/guru/ubah-foto/{id}', 'GuruController@ubah_foto')->name('guru.ubah-foto');
    Route::post('/guru/update-foto/{id}', 'GuruController@update_foto')->name('guru.update-foto');
    Route::post('/guru/upload', 'GuruController@upload')->name('guru.upload');
    Route::get('/guru/export_excel', 'GuruController@export_excel')->name('guru.export_excel');
    Route::post('/guru/import_excel', 'GuruController@import_excel')->name('guru.import_excel');
    Route::delete('/guru/deleteAll', 'GuruController@deleteAll')->name('guru.deleteAll');
    Route::resource('/guru', 'GuruController');
    Route::get('/paket/edit/json', 'PaketController@getEdit');
    Route::resource('/paket', 'PaketController');
    Route::get('/kelas/edit/json', 'KelasController@getEdit');
    Route::resource('/kelas', 'KelasController');
    Route::get('/siswa/kelas/{id}', 'SiswaController@kelas')->name('siswa.kelas');
    Route::post('/naik_kelas', 'KelasController@naik_kelas')->name('siswa.naik_kelas');
    Route::get('/siswa/view/json', 'SiswaController@view');
    Route::get('/listsiswapdf/{id}', 'SiswaController@cetak_pdf');
    Route::get('/siswa/ubah-foto/{id}', 'SiswaController@ubah_foto')->name('siswa.ubah-foto');
    Route::post('/siswa/update-foto/{id}', 'SiswaController@update_foto')->name('siswa.update-foto');
    Route::get('/siswa/export_excel', 'SiswaController@export_excel')->name('siswa.export_excel');
    Route::post('/siswa/import_excel', 'SiswaController@import_excel')->name('siswa.import_excel');
    Route::delete('/siswa/deleteAll', 'SiswaController@deleteAll')->name('siswa.deleteAll');
    Route::resource('/siswa', 'SiswaController');
    Route::get('/mapel/getMapelJson', 'MapelController@getMapelJson');
    Route::resource('/mapel', 'MapelController');
    Route::get('/jadwal/view/json', 'JadwalController@view');
    Route::get('/jadwalkelaspdf/{id}', 'JadwalController@cetak_pdf');
    Route::get('/jadwal/export_excel', 'JadwalController@export_excel')->name('jadwal.export_excel');
    Route::post('/jadwal/import_excel', 'JadwalController@import_excel')->name('jadwal.import_excel');
    Route::delete('/jadwal/deleteAll', 'JadwalController@deleteAll')->name('jadwal.deleteAll');
    Route::delete('/jadwalkaryawan/deleteAll', 'JadwalKaryawanController@deleteAll')->name('jadwalkaryawan.deleteAll');
    Route::resource('/jadwal', 'JadwalController');
    Route::get('/jadwalkaryawan/edit/{id}', 'JadwalKaryawanController@edit')->name('jadwalkaryawan.edit');
    Route::get('/jadwalkaryawan/{role}/all', 'JadwalKaryawanController@index')->name('jadwal.karyawan.index');
    Route::get('/jadwalkaryawan/hari/{id}', 'JadwalKaryawanController@show_hari')->name('jadwal.karyawan.hari');
    Route::get('/jadwalkaryawan/{id}/{user_id}', 'JadwalKaryawanController@show')->name('jadwalkaryawan.show');
    Route::resource('/jadwalkaryawan', 'JadwalKaryawanController')->except(['index', 'show', 'edit']);
    Route::get('/ulangan-kelas', 'UlanganController@create')->name('ulangan-kelas');
    Route::get('/ulangan-siswa/{id}', 'UlanganController@edit')->name('ulangan-siswa');
    Route::get('/ulangan-show/{id}', 'UlanganController@ulangan')->name('ulangan-show');
    Route::get('/sikap-kelas', 'SikapController@create')->name('sikap-kelas');
    Route::get('/sikap-siswa/{id}', 'SikapController@edit')->name('sikap-siswa');
    Route::get('/sikap-show/{id}', 'SikapController@sikap')->name('sikap-show');
    Route::get('/rapot-kelas', 'RapotController@create')->name('rapot-kelas');
    Route::get('/rapot-siswa/{id}', 'RapotController@edit')->name('rapot-siswa');
    Route::get('/rapot-show/{id}', 'RapotController@rapot')->name('rapot-show');
    Route::get('/predikat', 'NilaiController@create')->name('predikat');
    Route::resource('/user', 'UserController');

    Route::get('permintaan/guru', 'RequestController@index')->name('request.jadwal');
    Route::get('permintaan/guru/tukar-jadwal/{id}', 'RequestController@show')->name('request.show');
    Route::get('permintaan/approve', 'RequestController@approve')->name('approve');
    Route::get('permintaan/guru/detail/{id}', 'RequestController@detail')->name('request.detail');
    Route::get('/history-tukar-jadwal', 'HistoryTukarJadwalController@index')->name('jadwal.history_tukar_jadwal');
    Route::get('/history-tukar-jadwal/show/{id}', 'HistoryTukarJadwalController@show')->name('jadwal.history_tukar_jadwal.show');
    Route::get('/history-tukar-jadwal/detail/{id}', 'HistoryTukarJadwalController@detail')->name('jadwal.history_tukar_jadwal.detail');
    Route::get('/nilai-siswa', 'NilaiController@show')->name('nilai.all');
    Route::get('/nilai-siswa/get-mapel-guru/{id}', 'NilaiController@get_mapel_guru')->name('nilai.get_mapel_guru');
    Route::get('/nilai-siswa/get-nilai-siswa', 'NilaiController@get_nilai_siswa');
    Route::get('/modul-guru', 'ModulController@show')->name('modul.all');
    Route::get('/modul-guru/get-mapel-guru/{id}', 'ModulController@get_mapel_guru')->name('nilai.get_mapel_guru');

    // Tabungan
    Route::get('/kelas/tabungan/siswa', 'TabunganController@kelas_siswa')->name('kelas.tabungan.index');
    Route::get('/kelas/tabungan/siswa/{id}', 'TabunganController@kelas_siswa_show')->name('kelas.tabungan.show');
    Route::resource('/tabungan', TabunganController::class);
    
    // Infaq
    Route::get('/kelas/infaq/siswa', 'InfaqController@kelas_siswa')->name('kelas.infaq.index');
    Route::get('/kelas/infaq/siswa/{id}', 'InfaqController@kelas_siswa_show')->name('kelas.infaq.show');
    Route::resource('/infaq', InfaqController::class);

    // Pembelian Lks
    Route::get('/kelas/pembelian-lks/siswa', 'LksController@kelas_siswa')->name('kelas.lks.index');
    Route::get('/kelas/pembelian-lks/siswa/{id}', 'LksController@kelas_siswa_show')->name('kelas.lks.show');
    Route::resource('/pembelian-lks', LksController::class);
    //   });

  Route::get('/modul/show-file/{id}', 'ModulController@show_file')->name('modul.show_file');
});

Route::resource('/absensi-kehadiran', 'AbsensiKehadiranController');
Route::get('/absensi/{type}', 'AbsensiKehadiranController@create')->name('absensi-kehadiran.landing-absen');
Route::post('/absensi/cari-siswa', 'AbsensiKehadiranController@cari_siswa');
