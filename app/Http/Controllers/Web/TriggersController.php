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
    /**
     * @OA\GET(
     *     path="/api/trigger",
     *     summary="list of all trigger",
     *     description="returns paginated list with size and page no",
     *     tags={"Trigger"},
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
     *     path="/api/trigger",
     *     summary="api to update or insert",
     *     description="performs update with id or insert with given details",
     *     tags={"Trigger"},
     *     security = {{ "Authorization": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="json body",
     *        @OA\JsonContent(
     *          required={"size","page","name","endpoint"},
     *          type="object",
     *            @OA\Property(property="id", type="string", example="5", description="id of record to be updated, leave eempty if inserting new record"),
     *            @OA\Property(property="size", type="string", example="10", description="pagination limit"),
     *            @OA\Property(property="page", type="string", example="1", description="page no."),
     *            @OA\Property(property="name", type="string", example="thread-01", description="name of thread"),
     *            @OA\Property(property="endpoint", type="string", example="3", description="number of retry after fail"),
     *        ),
     *     ),
     *    @OA\Response(response=200,description="OK")
     * )
     * 
     * @OA\DELETE(
     *     path="/api/trigger",
     *     summary="delete a trigger",
     *     description="delete a trigger from list",
     *     tags={"Trigger"},
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
