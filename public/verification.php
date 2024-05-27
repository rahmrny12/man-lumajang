<?php

use App\FingerSiswa;
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

$user_id = $request->user_id;
$time_limit_ver = 15;
$finger_data = DB::select(DB::raw(
	"SELECT * FROM finger_siswa WHERE user_id=$user_id"
))[0]->finger_data;

$process_verification_url = config('app.url') . '/process_verification.php';
$getac_url = config('app.url') . '/getac.php';

echo "$user_id;$finger_data;SecurityKey;$time_limit_ver;$process_verification_url;$getac_url;extraParams";

// Terminate the application
$kernel->terminate($request, $response);
