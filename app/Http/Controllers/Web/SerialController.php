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
