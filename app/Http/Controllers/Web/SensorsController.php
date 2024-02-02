<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sensor;
use App\Models\Sheet;
use App\Models\CIP;

class SensorsController extends Controller
{
    public function __construct()
    {
    }
    public static function sensors($req)
    {
        if ($req->isMethod('post')) {
            if(empty($_POST['name'])) goto chk;
            $name = $_POST['name'];
            if (!isset($_POST['id'])) {
                $query = Sensor::create([
                    "name" => $_POST['name'],
                    "sheet" => $_POST['sheet'],
                    "value" => "",
                    "svg" => $_POST['svg'],
                    "node" => $_POST['node'],
                ]);
            }
            if (isset($_POST['id'])) {
                $id = $_POST['id'];
                $rec = Sensor::where('id', $id)->first();
                $rec->name = $_POST['name'] ?? $rec->name;
                $rec->sheet = $_POST['sheet'] ?? $rec->sheet;
                $rec->svg = $_POST['svg'] ?? $rec->svg;
                $rec->node = $_POST['node'] ?? $rec->node;
                $rec->save();
            }
        }
        if ($req->isMethod('delete')) {
            $id = $_POST['id'];
            $rec = Sensor::where("id", $id)->first();
            $rec->delete();
        }
        chk:
        $size = $_GET['size'] ?? $_POST['size'] ?? 10;
        $page = $_GET['page'] ?? $_POST['page'] ?? 1;
        $qy = DB::table('sensors');
        $qy->join('sheets', 'sensors.sheet', '=', 'sheets.id');
        $qy->join('cip', 'cip.id', '=', 'sensors.node');
        $qy->select('sensors.id', 'sensors.name', 'sheets.name as sheet', 'sensors.value', 'sensors.svg', 'cip.device_name as device');
        $rec = $qy->paginate($size, ['*'], 'page', $page);
        return $rec;
    }
    public static function sensors_pos(Request $req)
    {
        $query = Sensor::where('name', $_POST['id'])->first();
        if (!$query) {
            return response()->json('rec not found', 500);
        }
        $query->ui_x = $_POST['x'];
        $query->ui_y = $_POST['y'];
        $query->save();
        return response()->json('', 200);
    }
    public function index()
    {
        $qy = Sheet::select('name','id');
        $sheet = $qy->get();
        $sheet = $sheet->map(function($ci){
            return [$ci->name,$ci->id];
         });
        $qy = CIP::select('device_name as name','id');
        $cip = $qy->get();
        $cip = $cip->map(function($ci){
           return [$ci->name,$ci->id];
        });
        return view('web.sensors')->with('sheet',$sheet)->with('cip',$cip);
    }
}
