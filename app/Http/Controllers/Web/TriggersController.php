<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Triggers;
use App\Models\Worker;

class TriggersController extends Controller
{
    public function __construct()
    {
    }
    public static function list_trigger(Request $req)
    {
        if ($req->isMethod('post')) {
            if(empty($_POST['name'])) goto chk;
            $name = $_POST['name'];
            if (!isset($_POST['id'])) {
                $query = Triggers::create(
                    [
                        "name" => $name,
                        "endpoint" => Str::uuid(),
                    ],
                );
            }
            if (isset($_POST['id'])) {
                $id = $_POST['id'];
                $rec = Triggers::where("id", $id)->first();
                $rec->name = $name;
                $rec->save();
            }
        }
        if ($req->isMethod('delete')) {
            $id = $_POST['id'];
            $rec = Triggers::where("id", $id)->first();
            $rec->delete();
        }
        chk:
        $size = $_GET['size'] ?? $_POST['size'] ?? 10;
        $page = $_GET['page'] ?? $_POST['page'] ?? 1;
        $query = Triggers::select('id', 'name', 'jobs', db::raw("concat('api/trigger/',endpoint) as url"), 'created_at', 'updated_at');
        $rec = $query->paginate($size, ['*'], 'page', $page);
        return $rec;
    }
    public function index()
    {
        $qy = Worker::select('name', 'id');
        $work = $qy->get();
        $work = $work->map(function ($ci) {
            return [$ci->name, $ci->id];
        });
        $files = scandir(app_path('Jobs'));
        unset($files[0]);
        unset($files[1]);
        $files = collect(array_values($files));
        $files = $files->map(function ($ci) {
            $nmx = str_replace(".php", "", $ci);
            return [$nmx, $nmx];
        });
        return view('web.triggers')->with('wo', $work)->with('ff', collect($files));
    }
}
