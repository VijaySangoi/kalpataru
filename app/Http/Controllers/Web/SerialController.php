<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Com;
use Symfony\Component\Process\Process;
use Illuminate\Http\Request;

class SerialController extends Controller
{
    public function __construct()
    {
    }
    public static function list_serial_devices(Request $req)
    {
        if ($req->isMethod('post')) {
            $port = $_POST['port'];
            $query = Com::firstOrCreate(
                ["Port" => $port],
            );
        }
        $size = $_GET['size'] ?? 10;
        $rec = Com::select('*')->paginate($size);
        return $rec;
    }
    public function index()
    {
        return view('web.serial');
    }
}
