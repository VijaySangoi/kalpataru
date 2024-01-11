<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Triggers;

class TriggersController extends Controller
{
    public function __construct()
    {
    }
    public static function list_trigger(Request $req)
    {
        if ($req->isMethod('post')) {
            $name = $_POST['name'];
            if(!isset($_POST['id']))
            {
                $query = Triggers::create(
                    ["name" => $name],
                );
            }
            if(isset($_POST['id']))
            {
                $id = $_POST['id'];
                $rec = Triggers::where("id",$id)->first();
                $rec->name = $name;
                $rec->save();
            }
        }
        $size = $_GET['size'] ?? $_POST['size'] ?? 10;
        $page = $_GET['page'] ?? $_POST['page'] ?? 1;
        $query = Triggers::select('*');
        $rec = $query->paginate($size,['*'],'page',$page);
        return $rec;
    }
    public function index()
    {
        return view('web.triggers');
    }
}
