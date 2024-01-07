<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;
use Illuminate\Http\Request;
use App\Models\triggers;

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
        $qy = triggers::select('*');
        $qy->where('id',$job_id);
        $rec = $qy->first();
        if(!$rec)
        {
            return response()->json('invalid trigger',500);
        }
        $jobs = json_decode($rec->jobs);
        foreach ($jobs as $key => $val)
        {
            $arr = explode(":",$val);
            $job = "App\Jobs\\".$arr[0];
            $line = $arr[1]??"default";
            $dummy = $job::dispatch()->onQueue($line);
            echo $val."\n";
        }
    }
}
