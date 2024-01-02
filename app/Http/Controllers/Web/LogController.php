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
    public static function view_log_file(Request $req): array
    {
        return SerialHelper::reader($_POST['fl_name']);
    }
    public static function write_log_file(Request $req)
    {
        $com = "COM4";
        SerialHelper::write($com,$_POST['message']);
        return ;
    }
    public static function list_log_file(): array
    {
        $lst = scandir(app_path('Comms/__pipe'));
        $lsxt = array_values(array_filter($lst, "self::log_file"));
        return $lsxt;
    }
    public static function log_file($var)
    {
        if (str_contains($var, "log")) return $var;
    }
}
