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

$user_id  = $request->user_id;
$time_limit_reg = 20;
$process_register_url = config('app.url') . '/process_register.php';
$getac_url = config('app.url') . '/getac.php';

echo "$user_id;SecurityKey;$time_limit_reg;$process_register_url;$getac_url";

// Terminate the application
$kernel->terminate($request, $response);
