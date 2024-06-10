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

$data = explode(";", $request->registrasitemp);
$user_id = $data[0];
$regTemp = $data[1];
// $data = json_encode($data);

// $res['result'] = DB::select(DB::raw(
//     "INSERT INTO finger_logs SET data='$data' "
// ));

$result = DB::select(DB::raw(
    "UPDATE siswa SET finger_data='$regTemp', finger_id='1' WHERE id='$user_id'"
));

$data = [
    'result' => "true",
	'message' => "Data Berhasil Diedit"
];

echo json_encode($data);

// Terminate the application
$kernel->terminate($request, $response);
