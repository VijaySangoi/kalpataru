<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function __construct()
    {
    }
    /**
     * @OA\get(
     *     path="/api/trigger/{job_id}",
     *     summary="trigger",
     *     description="trigger",
     *     tags={"Job"},
     *     security = {{ "Authorization": {} }},
     *     @OA\Parameter(
     *          name="job_id",
     *          in="path",
     *          required=true,
     *     ),
     *    @OA\Response(response=200,description="OK")
     * )
     */
    public function trigger(Request $req, $job_id)
    {
        echo $job_id;
    }
}
