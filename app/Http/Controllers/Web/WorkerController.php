<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Worker;

class WorkerController extends Controller
{
    public function __construct()
    {
    }
    public function index()
    {
        return view('web.worker');
    }
    public static function list_workers(Request $req)
    {
        if ($req->isMethod('post')) {
            if (!isset($_POST['id'])) {
                $query = Worker::create(
                    [
                        "name" => $_POST['worker'],
                        "tries" => $_POST['tries'],
                        "timeout" => $_POST['timeout'],
                        "memory" => $_POST['memory']
                    ],
                );
            }
            if (isset($_POST['id'])) {
                $id = $_POST['id'];
                $rec = Worker::where("id", $id)->first();
                $rec->name = $_POST['worker'] ?? $rec->name;
                $rec->tries = $_POST['tries'] ?? $rec->tries;
                $rec->timeout = $_POST['timeout'] ?? $rec->timeout;
                $rec->memory = $_POST['memory'] ?? $rec->memory;
                $rec->save();
            }
        }
        $size = $_GET['size'] ?? $_POST['size'] ?? 10;
        $page = $_GET['page'] ?? $_POST['page'] ?? 1;
        $query = Worker::select('*');
        $rec = $query->paginate($size, ['*'], 'page', $page);
        return $rec;
    }
}
