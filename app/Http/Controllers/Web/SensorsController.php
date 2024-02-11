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
    /**
     * @OA\GET(
     *     path="/api/sensors",
     *     summary="list of all Sensor",
     *     description="returns paginated list with size and page no",
     *     tags={"Sensor"},
     *     security = {{ "Authorization": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="json body",
     *        @OA\JsonContent(
     *          required={"size","page"},
     *          type="object",
     *            @OA\Property(property="size", type="string", example="10", description="pagination limit"),
     *            @OA\Property(property="page", type="string", example="1", description="page no."),
     *        ),
     *     ),
     *    @OA\Response(response=200,description="OK")
     * )
     * 
     * @OA\POST(
     *     path="/api/sensors",
     *     summary="api to update or insert",
     *     description="performs update with id or insert with given details",
     *     tags={"Sensor"},
     *     security = {{ "Authorization": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="json body",
     *        @OA\JsonContent(
     *          required={"size","page","name","sheet","node"},
     *          type="object",
     *            @OA\Property(property="id", type="string", example="5", description="id of record to be updated, leave eempty if inserting new record"),
     *            @OA\Property(property="size", type="string", example="10", description="pagination limit"),
     *            @OA\Property(property="page", type="string", example="1", description="page no."),
     *            @OA\Property(property="name", type="string", example="temp_sensor-01", description="name of sensor"),
     *            @OA\Property(property="sheet", type="string", example="1", description="id of sheet"),
     *            @OA\Property(property="node", type="string", example="1", description="id of cip or serial device"),
     *        ),
     *     ),
     *    @OA\Response(response=200,description="OK")
     * )
     * 
     * @OA\DELETE(
     *     path="/api/sensors",
     *     summary="delete a Sensor",
     *     description="delete a Sensor",
     *     tags={"Sensor"},
     *     security = {{ "Authorization": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="json body",
     *        @OA\JsonContent(
     *          required={"size","page","id"},
     *          type="object",
     *            @OA\Property(property="size", type="string", example="10", description="pagination limit"),
     *            @OA\Property(property="page", type="string", example="1", description="page no."),
     *            @OA\Property(property="id", type="string", example="5", description="id of record to be deleted"),
     *        ),
     *     ),
     *    @OA\Response(response=200,description="OK")
     * )
     */
    public static function sensors($req)
    {
        if ($req->isMethod('post')) {
            if(empty($_POST['name'])) goto chk;
            $name = $_POST['name'];
            if (!isset($_POST['id'])) {
                $svg = '<?xml version="1.0" encoding="utf-8"?>
                <svg width="80px" height="80px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>';
                if(!empty($_POST['svg']))
                {
                    echo "svg not empty";
                    $svg = $_POST['svg'];
                }
                $query = Sensor::create([
                    "name" => $_POST['name'],
                    "sheet" => $_POST['sheet'],
                    "value" => "{}",
                    "ui_x" =>"0",
                    "ui_y" =>"0",
                    "svg" => $svg,
                    "node" => $_POST['node']??"1",
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
