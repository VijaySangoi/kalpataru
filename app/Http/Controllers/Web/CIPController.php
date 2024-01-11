<?php

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CIP;

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
        $rec = CIP::select('device_name','peer')->get();
        return $rec;
    }
     /**
     * @OA\GET(
     *     path="/api/cip/message",
     *     summary="register endpoint",
     *     description="hit this endpoint with microcontroller and register/list/notify your ip address and details to server ",
     *     tags={"CIP"},
     *     security = {{ "Authorization": {} }},
     *    @OA\Response(response=200,description="OK")
     * )
     * 
     * @OA\POST(
     *     path="/api/cip/message",
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
    public static function cip_message(Request $req)
    {
        $ip = $req->ip();
        $qy = CIP::select('*');
        $qy->where('peer',$ip);
        $rec = $qy->first();
        var_dump($rec);
        $file = fopen(app_path('Comms\__pipe\\'.$rec->device_name.'-log-'.date('d_n_Y').'.txt'),'w+');
        // if($req->isMethod('post'))
        // {
        //     //post write

        // }
        // //get read
    }
    public function index()
    {
        return view('web.cip');
    }
}
