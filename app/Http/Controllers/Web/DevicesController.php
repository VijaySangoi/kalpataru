<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Http\Helpers\GuzzleHelper;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Sheet;
use App\Models\Node;

class DevicesController extends Controller
{
    public function _construct()
    {}
    /**
     * @OA\POST(
     *     path="/api/devices/message",
     *     summary="send data between devices",
     *     description="api to exchange data between nodes",
     *     tags={"Devices"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="json body",
     *        @OA\JsonContent(
     *          required={"mess"},
     *          type="object",
     *            @OA\Property(property="mess", type="json", example={"to":"device_name"}, description="message"),
     *        ),
     *     ),
     *    @OA\Response(response=200,description="OK")
     * )
     * 
     * @OA\GET(
     *     path="/api/devices",
     *     summary="list of all Devices",
     *     description="returns paginated list with size and page no",
     *     tags={"Devices"},
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
     *     path="/api/devices",
     *     summary="api to update or insert",
     *     description="performs update with id or insert with given details",
     *     tags={"Devices"},
     *     security = {{ "Authorization": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="json body",
     *        @OA\JsonContent(
     *          required={"size","page","name","sheet","node","id"},
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
     *     path="/api/devices",
     *     summary="delete a Devices",
     *     description="delete a Devices",
     *     tags={"Devices"},
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
    public static function devices(Request $req)
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
                $query = Device::create([
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
                $rec = Device::where('id', $id)->first();
                $rec->name = $_POST['name'] ?? $rec->name;
                $rec->sheet = $_POST['sheet'] ?? $rec->sheet;
                $rec->svg = $_POST['svg'] ?? $rec->svg;
                $rec->node = $_POST['node'] ?? $rec->node;
                $rec->save();
            }
        }
        if ($req->isMethod('delete')) {
            $id = $_POST['id'];
            $rec = Device::where("id", $id)->first();
            $rec->delete();
        }
        chk:
        $size = $_GET['size'] ?? $_POST['size'] ?? 10;
        $page = $_GET['page'] ?? $_POST['page'] ?? 1;
        $qy = DB::table('devices');
        $qy->join('sheets', 'devices.sheet', '=', 'sheets.id');
        $qy->select('devices.id', 'devices.name', 'sheets.name as sheet', 'devices.node', 'devices.value', 'devices.svg');
        $rec = $qy->paginate($size, ['*'], 'page', $page);
        return $rec;
    }
    public static function devices_message(Request $req)
    {
        $from = $req->ip();
        $to = $req->mess["to"];
        $qy = DB::table('devices');
        $qy->select('devices.name','nodes.name','nodes.address');
        $qy->join('nodes','devices.node','=','nodes.id');
        $qy->where('devices.name',$to);
        $device = $qy->first();
        if(!$device) return 'device not found';
        $filelog = app_path('Comms\__pipe\\' . $to . '-log-' . date('d_n_Y') . '.txt');
        $filepipe = app_path('Comms\__pipe\\' . $to . '-pipe-' . date('d_n_Y') . '.txt');
        $to_ip = str_replace('"',"",$device->address);
        $res = GuzzleHelper::call($to_ip,"get");
        return response($res[0],$res[1]);
    }
    public static function dev_pos(Request $req)
    {
        $query = Device::where('name', $_POST['id'])->first();
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
        $qy = Node::select('name','id');
        $node = $qy->get();
        $node = $node->map(function($ci){
           return [$ci->name,$ci->id];
        });
        return view('web.devices')->with('sheet',$sheet)->with('node',$node);
    }
}
