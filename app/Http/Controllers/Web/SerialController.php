<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Com;
use Illuminate\Http\Request;

class SerialController extends Controller
{
    public function __construct()
    {
    }
    /**
     * @OA\GET(
     *     path="/api/list_serial_devices",
     *     summary="list of all serial device",
     *     description="returns paginated list with size and page no",
     *     tags={"Serial"},
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
     *     path="/api/list_serial_devices",
     *     summary="api to update or insert",
     *     description="performs update with id or insert with given details",
     *     tags={"Serial"},
     *     security = {{ "Authorization": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="json body",
     *        @OA\JsonContent(
     *          required={"size","page","name","port","baudrate"},
     *          type="object",
     *            @OA\Property(property="id", type="string", example="5", description="id of record to be updated, leave eempty if inserting new record"),
     *            @OA\Property(property="size", type="string", example="10", description="pagination limit"),
     *            @OA\Property(property="page", type="string", example="1", description="page no."),
     *            @OA\Property(property="name", type="string", example="CNC-01", description="name of sensor"),
     *            @OA\Property(property="port", type="string", example="COM8", description="name of com port"),
     *            @OA\Property(property="baudrate", type="string", example="115200", description="baudrate of receiving microcontroller node"),
     *        ),
     *     ),
     *    @OA\Response(response=200,description="OK")
     * )
     * 
     * @OA\DELETE(
     *     path="/api/list_serial_devices",
     *     summary="delete a serial device",
     *     description="delete a serial device from monitoring list",
     *     tags={"Serial"},
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
    public static function list_serial_devices(Request $req)
    {
        if ($req->isMethod('post')) {
            if(empty($_POST['name'])) goto chk;
            $name = $_POST['name'];
            if(empty($_POST['port'])) goto chk;
            $port = $_POST['port'];
            if(empty($_POST['baudrate'])) goto chk;
            $baudrate = $_POST['baudrate'];
            if(!isset($_POST['id']))
            {
                $query = Com::create(
                    [
                        "name" => $name,
                        "port" => $port,
                        "baudrate" => $baudrate,
                    ],
                );
            }
            if(isset($_POST['id']))
            {
                $id = $_POST['id'];
                $rec = Com::where("id",$id)->first();
                $rec->name = $name ?? $rec->name;
                $rec->port = $port ?? $rec->port;
                $rec->baudrate = $baudrate ?? $rec->baudrate;
                $rec->save();
            }
        }
        if ($req->isMethod('delete')) {
            $id = $_POST['id'];
            $rec = Com::where("id", $id)->first();
            $rec->delete();
        }
        chk:
        $size = $_GET['size'] ?? $_POST['size'] ?? 10;
        $page = $_GET['page'] ?? $_POST['page'] ?? 1;
        $query = Com::select('*');
        $rec = $query->paginate($size,['*'],'page',$page);
        return $rec;
    }
    public function index()
    {
        return view('web.serial');
    }
}
