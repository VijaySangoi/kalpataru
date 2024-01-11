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
    public static function list_serial_devices(Request $req)
    {
        if ($req->isMethod('post')) {
            if(!isset($_POST['id']))
            {
                $query = Com::create(
                    [
                        "name" => $_POST['name'],
                        "port" => $_POST['port'],
                        "baudrate" => $_POST['baudrate'],
                    ],
                );
            }
            if(isset($_POST['id']))
            {
                $id = $_POST['id'];
                $rec = Com::where("id",$id)->first();
                $rec->name = $_POST['name'];
                $rec->port = $_POST['port'];
                $rec->baudrate = $_POST['baudrate'];
                $rec->save();
            }
        }
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
