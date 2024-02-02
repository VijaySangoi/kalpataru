<?php

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\CIP;
use App\Models\Sensor;

class CIPController extends Controller
{
    public function __construct()
    {
    }
    /**
     * @OA\POST(
     *     path="/api/cip/register",
     *     summary="register endpoint",
     *     description="hit this endpoint with microcontroller and register/list/notify your ip address and details to server ",
     *     tags={"CIP"},
     *     security = {{ "Authorization": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="json body",
     *        @OA\JsonContent(
     *          required={"device_name"},
     *          type="object",
     *            @OA\Property(property="device_name", type="string", example="3d_printer", description="allowed only camel_case, if not passed as camel case the server will convert it to camel_case"),
     *        ),
     *     ),
     *    @OA\Response(response=200,description="OK")
     * )
     */
    public static function cip_register(Request $req)
    {
        $validated = Validator::make($req->all(), [
            'device_name' => 'required|max:32|unique:App\Models\cip,device_name',
        ]);
        if ($validated->fails()) return response()->json($validated->errors(), 500);
        $qy = CIP::select('*');
        $qy->where('peer', $req->ip());
        $rec = $qy->first();
        if ($rec) {
            return response()->json('ip already registered', 500);
        }
        $rec = new CIP();
        $rec->device_name = Str::snake($req->input('device_name', '_'));
        $rec->peer = $req->ip();
        $rec->save();
        return $rec;
    }
    /**
     * @OA\GET(
     *     path="/api/cip/message",
     *     summary="register endpoint",
     *     description="endpoint for MCU to fetch data. aka-rx of uart",
     *     tags={"CIP"},
     *     security = {{ "Authorization": {} }},
     *    @OA\Response(response=200,description="OK")
     * )
     * 
     * @OA\POST(
     *     path="/api/cip/message",
     *     summary="register endpoint",
     *     description="enpoint for MCU to deliver data. aka-tx of uart",
     *     tags={"CIP"},
     *     security = {{ "Authorization": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="json body",
     *        @OA\JsonContent(
     *          required={"sensors"},
     *          type="object",
     *            @OA\Property(property="sensors", type="json", example={"robot_arm":{"":"G1 X0 Y120 Z120"},"3d_printer":{"hot_end":"220c","ins":"G1 F2700 E-5"},"miner":{"hash":"120h/s"}}, description="valid json only"),
     *        ),
     *     ),
     *    @OA\Response(response=200,description="OK")
     * )
     */
    public static function cip_message(Request $req)
    {
        $ip = $req->ip();
        $qy = CIP::select('*');
        $qy->where('peer', $ip);
        $rec = $qy->first();
        $filelog = app_path('Comms\__pipe\\' . $rec->device_name . '-log-' . date('d_n_Y') . '.txt');
        $filepipe = app_path('Comms\__pipe\\' . $rec->device_name . '-pipe-' . date('d_n_Y') . '.txt');
        if ($req->isMethod('post')) {
            $file = fopen($filelog, 'a+');
            fwrite($file, "[" . date('d-m-Y, H:i:s') . "]" . json_encode($req->input('sensors')) . "\n");
            foreach ($req->input('sensors') as $ky => $val) {
                $name = $ky;
                $qy = Sensor::updateOrCreate(
                    ['name' => $name],
                    ['value' => json_encode($val), 'node' => $rec->id]
                );
            }
            fclose($file);
        }
        if ($req->isMethod('get')) {
            $file = fopen($filepipe, 'a+');
            $size = filesize($filepipe) + 1;
            $data = fread($file, $size);
            $file = fopen($filepipe, 'w');
            fclose($file);
            return response()->json($data, 200);
        }
    }
    public static function list_cip(Request $req)
    {
        $size = $_GET['size'] ?? $_POST['size'] ?? 10;
        $page = $_GET['page'] ?? $_POST['page'] ?? 1;
        $query = CIP::select('*');
        $rec = $query->paginate($size,['*'],'page',$page);
        return $rec;
    }
    public function index()
    {
        return view('web.cip');
    }
}
