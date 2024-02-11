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
    /**
     * @OA\GET(
     *     path="/api/workers",
     *     summary="list of all worker thread",
     *     description="returns paginated list with size and page no",
     *     tags={"Worker"},
     *     security = {{ "Authorization": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="json body",
     *        @OA\JsonContent(
     *          required={"size","page"},
     *          type="object",
     *            @OA\Property(property="size", type="string", example="10", description="pagination limit"),
     *            @OA\Property(property="page", type="string", example="1", description="page no."),
     *        ),
     *     ),
     *    @OA\Response(response=200,description="OK")
     * )
     * 
     * @OA\POST(
     *     path="/api/workers",
     *     summary="api to update or insert",
     *     description="performs update with id or insert with given details",
     *     tags={"Worker"},
     *     security = {{ "Authorization": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="json body",
     *        @OA\JsonContent(
     *          required={"size","page","name","tries","memory","timeout"},
     *          type="object",
     *            @OA\Property(property="id", type="string", example="5", description="id of record to be updated, leave eempty if inserting new record"),
     *            @OA\Property(property="size", type="string", example="10", description="pagination limit"),
     *            @OA\Property(property="page", type="string", example="1", description="page no."),
     *            @OA\Property(property="name", type="string", example="thread-01", description="name of worker thread"),
     *            @OA\Property(property="tries", type="string", example="3", description="number of retry after fail"),
     *            @OA\Property(property="memory", type="string", example="512", description="amount of memory in mb allocated for this worker thread"),
     *            @OA\Property(property="timeout", type="string", example="60", description="deadline in second to fail job if it takes too long"),
     *        ),
     *     ),
     *    @OA\Response(response=200,description="OK")
     * )
     * 
     * @OA\DELETE(
     *     path="/api/workers",
     *     summary="delete a worker thread",
     *     description="delete a worker thread from list",
     *     tags={"Worker"},
     *     security = {{ "Authorization": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="json body",
     *        @OA\JsonContent(
     *          required={"size","page","id"},
     *          type="object",
     *            @OA\Property(property="size", type="string", example="10", description="pagination limit"),
     *            @OA\Property(property="page", type="string", example="1", description="page no."),
     *            @OA\Property(property="id", type="string", example="5", description="id of record to be deleted"),
     *        ),
     *     ),
     *    @OA\Response(response=200,description="OK")
     * )
     */
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
    public function index()
    {
        return view('web.worker');
    }
}
