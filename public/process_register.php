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

$data     = explode(";", $request->RegTemp);
$vStamp   = $data[0];
$sn       = $data[1];
$user_id  = $data[2];
$regTemp  = $data[3];

$device = DB::select(DB::raw(
    "SELECT * FROM finger_devices"
))[0];

$salt = md5($device->ac . $device->vkey . $regTemp . $sn . $user_id);

if (strtoupper($vStamp) == strtoupper($salt)) {

    $data = DB::select(DB::raw(
        "SELECT MAX(finger_id) as fid FROM finger_siswa WHERE user_id=$user_id"
    ))[0];
    $fid   = $data->fid;

    if ($fid == 0) {
        DB::select(DB::raw(
            "UPDATE siswa SET finger_data='$regTemp' WHERE id='$user_id'"
        ));
        
        $res['result'] = DB::select(DB::raw(
            "INSERT INTO finger_siswa SET user_id='$user_id', finger_id=" . ($fid + 1) . ", finger_data='$regTemp'"
        ));
    } else {
        DB::select(DB::raw(
            "UPDATE siswa SET finger_data='$regTemp' WHERE id='$user_id'"
        ));
        
        $res['result'] = DB::select(DB::raw(
            "UPDATE finger_siswa SET finger_data='$regTemp' WHERE finger_id=$fid AND user_id='$user_id'"
        ));
        // $res['result'] = false;
        // $res['user_finger_' . $user_id] = "Finger data sudah ada";
    }

    echo "empty";
} else {
    $res['result'] = false;
    $res['user_finger_' . $user_id] = "Perangkat tidak valid";
}

// Terminate the application
$kernel->terminate($request, $response);
