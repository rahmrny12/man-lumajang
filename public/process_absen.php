<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\DB;

// Define the base path and require the autoload file
require __DIR__ . '/../vendor/autoload.php';

// Bootstrapping the application
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Run the application to setup the container and resolve the kernel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Set up Eloquent ORM
$capsule = new Capsule;
$capsule->addConnection(config('database.connections.mysql'));
$capsule->setAsGlobal();
$capsule->bootEloquent();

$data     = explode(";", $request->insertdata);
$user_id  = $data[0];

$user_name = DB::select(DB::raw(
    "SELECT nama_siswa FROM siswa WHERE id=$user_id"
))[0]->nama_siswa;

$currentDateTime = now()->format('Y-m-d H:i:s');

$result = DB::insert(DB::raw("INSERT INTO finger_logs SET name='$user_name', user_id=$user_id"));
// $result_logs = DB::select(DB::raw(
//     "INSERT INTO finger_logs SET name='$user_name', user_id=$user_id"
// ));

$res['result'] = $result ? 'true' : 'false';
$res['message'] = 'Absen berhasil';
$res['nama_pegawai'] = 'tes';

echo json_encode($res);

// Terminate the application
$kernel->terminate($request, $response);
