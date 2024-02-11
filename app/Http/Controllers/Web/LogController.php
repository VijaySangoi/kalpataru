<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Helpers\SerialHelper;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function __construct()
    {
    }
    public function index()
    {
        return view('web.log');
    }
    /**
     * @OA\GET(
     *     path="/api/log-file",
     *     summary="reads serial connection & historical connection",
     *     description="reads serial log, can read live connection",
     *     tags={"Log"},
     *     security = {{ "Authorization": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="json body",
     *        @OA\JsonContent(
     *          required={"fl_name"},
     *          type="object",
     *            @OA\Property(property="fl_name", type="string", example="COM{port}-log-{dd}_{m}_{yyyy}.txt", description="reads log file name"),
     *        ),
     *     ),
     *    @OA\Response(response=200,description="OK")
     * )
     * 
     * @OA\POST(
     *     path="/api/log-file",
     *     summary="write to serial connection",
     *     description="manual write to serial pipe file, can write to live connection",
     *     tags={"Log"},
     *     security = {{ "Authorization": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="json body",
     *        @OA\JsonContent(
     *          required={"fl_name"},
     *          type="object",
     *            @OA\Property(property="fl_name", type="json", example="COM{port}-pipe-{dd}_{m}_{yyyy}.txt", description="write to pipe file"),
     *        ),
     *     ),
     *    @OA\Response(response=200,description="OK")
     * )
     */
    public static function log_file(Request $req): array
    {
        $fl_name = $_POST['fl_name'] ?? $_GET['fl_name'];
        if ($req->isMethod('post')) {
            $arr = explode('-',$_POST['fl_name']);
            SerialHelper::write($arr[0],$_POST['message']);
        }
        return SerialHelper::reader($fl_name);
    }
    /**
     * @OA\GET(
     *     path="/api/list-log-file",
     *     summary="list all log file",
     *     description="list all historical log file available",
     *     tags={"Log"},
     *     security = {{ "Authorization": {} }},
     *    @OA\Response(response=200,description="OK")
     * )
     * 
     */
    public static function list_log_file(): array
    {
        $lst = scandir(app_path('Comms/__pipe'));
        $lsxt = array_values(array_filter($lst, "self::dir_stat"));
        return $lsxt;
    }
    public static function dir_stat($var)
    {
        if (str_contains($var, "log")) return $var;
    }
}
