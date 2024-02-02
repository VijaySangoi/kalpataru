<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\LogController;
use App\Http\Controllers\Web\SerialController;
use App\Http\Controllers\Web\WorkerController;
use App\Http\Controllers\Web\TriggersController;
use App\Http\Controllers\Web\CIPController;
use App\Http\Controllers\Web\SensorsController;

class AllApiController extends Controller
{
    public function __contruct()
    {
    }
    public function list_log_files(Request $req)
    {
        $res = LogController::list_log_file();
        return response()->json($res, 200);
    }
    public function log_files(Request $req)
    {
        $res = LogController::log_file($req);
        return response()->json($res, 200);
    }
    public function list_serial_devices(Request $req)
    {
        $res = SerialController::list_serial_devices($req);
        return response()->json($res, 200);
    }
    public function list_workers(Request $req)
    {
        $res = WorkerController::list_workers($req);
        return response()->json($res, 200);
    }
    public function list_trigger(Request $req)
    {
        $res = TriggersController::list_trigger($req);
        return response()->json($res, 200);
    }
    public function cip_register(Request $req)
    {
        $res = CIPController::cip_register($req);
        return $res;
    }
    public function cip_message(Request $req)
    {
        $res = CIPController::cip_message($req);
        return $res;
    }
    public function sensors(Request $req)
    {
        $res = SensorsController::sensors($req);
        return $res;
    }
    public function list_cip(Request $req)
    {
        $res = CIPController::list_cip($req);
        return $res;
    }
    public function sensors_pos(Request $req)
    {
        $res = SensorsController::sensors_pos($req);
        return $res;
    }
}
