<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\LogController;
use App\Http\Controllers\Web\SerialController;

class AllApiController extends Controller
{
    public function __contruct()
    {
    }
    public function list_log_files(Request $req)
    {
        $files = LogController::list_log_file();
        return response()->json($files, 200);
    }
    public function view_log_files(Request $req)
    {
        $files = LogController::view_log_file($req);
        return response()->json($files, 200);
    }
    public function write_log_files(Request $req)
    {
        $files = LogController::write_log_file($req);
        return response()->json($files,200);
    }
    public function list_serial_devices(Request $req)
    {
        $files = SerialController::list_serial_devices($req);
        return response()->json($files, 200);
    }    
}
