<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Node;

class NodesController extends Controller
{
    public function _construct()
    {}
    /**
     * @OA\GET(
     *     path="/api/nodes",
     *     summary="list of all nodes",
     *     description="returns paginated list with size and page no",
     *     tags={"Nodes"},
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
     *     path="/api/nodes",
     *     summary="api to update or insert",
     *     description="performs update with id or insert with given details",
     *     tags={"Nodes"},
     *     security = {{ "Authorization": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="json body",
     *        @OA\JsonContent(
     *          required={"address","type","name","status","id"},
     *          type="object",
     *            @OA\Property(property="id", type="string", example="5", description="id of record to be updated, leave eempty if inserting new record"),
     *            @OA\Property(property="size", type="string", example="10", description="pagination limit"),
     *            @OA\Property(property="page", type="string", example="1", description="page no."),
     *            @OA\Property(property="name", type="string", example="temp_sensor-01", description="name of sensor"),
     *            @OA\Property(property="type", type="string", example="1", description="1 - serial or 2 - cip"),
     *            @OA\Property(property="address", type="string", example="1", description="ipaddress for cip or com details (json)"),
     *            @OA\Property(property="status", type="string", example="1", description="ipaddress for cip or com details (json)"),
     *        ),
     *     ),
     *    @OA\Response(response=200,description="OK")
     * )
     * 
     * @OA\DELETE(
     *     path="/api/nodes",
     *     summary="delete a nodes",
     *     description="delete a nodes",
     *     tags={"Nodes"},
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
    public static function nodes(Request $req)
    {
        if ($req->isMethod('post')) {
            if(empty($_POST['name'])) goto chk;
            $name = $_POST['name'];
            if(empty($_POST['type'])) goto chk;
            $type = $_POST['type'];
            if(empty($_POST['address'])) goto chk;
            $address = $_POST['address'];
            if(empty($_POST['status'])) goto chk;
            $status = $_POST['status'];
            if (!isset($_POST['id'])) {
                $query = Node::create(
                    [
                        "name" => $name,
                        "type" => $type,
                        "address" => json_encode($address),
                        "status" => $status
                    ],
                );
            }
            if (isset($_POST['id'])) {
                $id = $_POST['id'];
                $rec = Node::where("id", $id)->first();
                $rec->name = $name ?? $rec->name;
                $rec->type = $type ?? $rec->type;
                $rec->address = $address ?? $rec->address;
                $rec->status = $status ?? $rec->status;
                $rec->save();
            }
        }
        if ($req->isMethod('delete')) {
            $id = $_POST['id'];
            $rec = Node::where("id", $id)->first();
            $rec->delete();
        }
        chk:
        $size = $_GET['size'] ?? $_POST['size'] ?? 10;
        $page = $_GET['page'] ?? $_POST['page'] ?? 1;
        $query = Node::select('*');
        $rec = $query->paginate($size, ['*'], 'page', $page);
        return $rec;
    }
    public function index()
    {
        return view('web.nodes');
    }
}
