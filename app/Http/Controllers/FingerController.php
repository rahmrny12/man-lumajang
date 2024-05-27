<?php

namespace App\Http\Controllers;

use App\FingerSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FingerController extends Controller
{
  public function checkUser(Request $request)
  {
    $count = FingerSiswa::where('user_id', $request->user_id)->count();

    if ((int) $count > (int) $request->current) {
      return response()->json([
        'result' => true,
        'current' => $count,
      ]);
    } else {
      return response()->json([
        'result' => false,
      ]);
    }
  }
}
