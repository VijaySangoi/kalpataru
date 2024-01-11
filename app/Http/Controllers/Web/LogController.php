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
    public static function log_file(Request $req): array
    {
        $fl_name = $_POST['fl_name'] ?? $_GET['fl_name'];
        if ($req->isMethod('post')) {
            $arr = explode('-',$_POST['fl_name']);
            SerialHelper::write($arr[0],$_POST['message']);
        }
        return SerialHelper::reader($fl_name);
    }
    public static function write_log_file(Request $req)
    {
        $com = "COM4";
        SerialHelper::write($com, $_POST['message']);
        return;
    }
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
