<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\Transformer;
use Illuminate\Http\Request;

class TransformerController extends Controller
{
    public function _construct()
    {}
    public function index()
    {
        return view('web.transformer');
    }
    public static function transformer(Request $req)
    {
        if($req->isMethod('post')){
            if(empty($_POST['name'])) goto chk;
            $name = $_POST['name'];
            if (!isset($_POST['id'])) {
                $query = Transformer::create(
                    [
                        "name" => $name,
                        "full_namespace" => $_POST['namespace'],
                        "type" => $_POST['type']
                    ],
                );
            }
            if (isset($_POST['id'])) {
                $id = $_POST['id'];
                $rec = Transformer::where("id", $id)->first();
                $rec->name = $name;
                $rec->save();
            }
        }
        if($req->isMethod('delete')){
            $id = $_POST['id'];
            $rec = Transformer::where("transformer_id", $id)->first();
            $rec->delete();
        }
        chk:
        $size = $_GET['size'] ?? $_POST['size'] ?? 10;
        $page = $_GET['page'] ?? $_POST['page'] ?? 1;
        $query = Transformer::select('transformer_id as id','name','full_namespace as namespace','type');
        $rec = $query->paginate($size, ['*'], 'page', $page);
        return $rec;
    }
}