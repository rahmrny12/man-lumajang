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

$data 		= explode(";", $_POST['VerPas']);
$user_id	= $data[0];
$vStamp 	= $data[1];
$time 		= $data[2];
$sn 		= $data[3];

$fingerData = DB::select(DB::raw(
	"SELECT * FROM finger_siswa WHERE user_id=$user_id"
))[0]->finger_data;

$device = DB::select(DB::raw(
	"SELECT * FROM finger_devices"
))[0];

$user_name = DB::select(DB::raw(
	"SELECT nama_siswa FROM siswa WHERE id=$user_id"
))[0]->nama_siswa;

$salt = md5($sn . $fingerData . $device->vc . $time . $user_id . $device->vkey);

$redirect_url = config('app.url') . '/absensi/siswa';

if (strtoupper($vStamp) == strtoupper($salt)) {
	try {
		$result = DB::select(DB::raw(
			"INSERT INTO finger_logs SET name='$user_name', user_id=$user_id, data='" . date('Y-m-d H:i:s', strtotime($time)) . " (PC Time) | " . $sn . " (SN)" . "' "
		));

		echo 'empty';
	} catch (\Throwable $th) {
		echo $redirect_url . "?msg=save_log_error";
	}
} else {
	$msg = "Perangkat tidak valid";
	echo $redirect_url . "?msg=$msg";
}

// Terminate the application
$kernel->terminate($request, $response);
