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
            if(empty($_POST['worker'])) goto chk;
            $worker = $_POST['worker'];
            if(empty($_POST['tries'])) goto chk;
            $tries = $_POST['tries'];
            if(empty($_POST['timeout'])) goto chk;
            $timeout = $_POST['timeout'];
            if(empty($_POST['memory'])) goto chk;
            $memory = $_POST['memory'];
            if (!isset($_POST['id'])) {
                $query = Worker::create(
                    [
                        "name" => $worker,
                        "tries" => $tries,
                        "timeout" => $timeout,
                        "memory" => $memory,
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
        if ($req->isMethod('delete')) {
            $id = $_POST['id'];
            $rec = Worker::where("id", $id)->first();
            $rec->delete();
        }
        chk:
        $size = $_GET['size'] ?? $_POST['size'] ?? 10;
        $page = $_GET['page'] ?? $_POST['page'] ?? 1;
        $query = Worker::select('*');
        $rec = $query->paginate($size, ['*'], 'page', $page);
        return $rec;
    }
}
