<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\LogController;
use App\Http\Controllers\Web\NodesController;
use App\Http\Controllers\Web\DevicesController;
use App\Http\Controllers\Web\WorkerController;
use App\Http\Controllers\Web\TriggersController;
use App\Http\Controllers\Web\HomeController;

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
    public function nodes(Request $req)
    {
        $res = NodesController::nodes($req);
        return response()->json($res, 200);
    }
    public function devices(Request $req)
    {
        $res = DevicesController::devices($req);
        return response()->json($res, 200);
    }
    public function dev_pos(Request $req)
    {
        $res = DevicesController::dev_pos($req);
        return response()->json($res, 200);
    }
    public function devices_message(Request $req)
    {
        $res = DevicesController::devices_message($req);
        return $res;
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
    public function dashboard_component(Request $req)
    {
        $res = HomeController::dashboard_component($req);
        return $res;
    }
    public function add_sheet(Request $req)
    {
        $res = HomeController::add_sheet($req);
        return $res;
    }
    public function trigger(Request $req,$job_id)
    {
        $res = JobController::trigger($req,$job_id);
        return $res;
    }
    public function option(Request $req)
    {
        $res = JobController::option($req);
        return $res;
    }
    public function file(Request $req,$file)
    {
        $res = JobController::file($req,$file);
        return $res;
    }
    public function job(Request $req)
    {
        $res = JobController::job($req);
        return $res;
    }
}
