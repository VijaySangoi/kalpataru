<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ListJob;
use OpenApi\Annotations as OA;
use Illuminate\Http\Request;
use App\Models\Triggers;

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
    public static function trigger(Request $req, $job_id)
    {
        $qy = Triggers::select('*');
        $qy->where('endpoint',$job_id);
        $rec = $qy->first();
        if(!$rec)
        {
            return response()->json('invalid trigger',500);
        }
        $jobs = json_decode($rec->jobs);
        if(!$jobs)
        {
            return response()->json("no job to schedule",500);
        }
        foreach ($jobs as $key => $val)
        {
            $arr = explode(":",$val);
            $job = "App\Jobs\\".$arr[0];
            $line = $arr[1]??"default";
            $dummy = $job::dispatch()->onQueue($line);
        }
    }
    /**
     * @OA\get(
     *     path="/api/option",
     *     summary="returns list of job-files name",
     *     description="list file name",
     *     tags={"Job"},
     *     security = {{ "Authorization": {} }},
     *    @OA\Response(response=200,description="OK")
     * )
     */
    public static function option()
    {
        $files = scandir(app_path('jobs'));
        unset($files[0]);
        unset($files[1]);
        $files = collect(array_values($files));
        $files = $files->map(function ($ci) {
            $nmx = str_replace(".php", "", $ci);
            return [$nmx, $nmx];
        });
        return $files;
    }
    /**
     * 
     * @OA\get(
     *     path="/api/file/{file}",
     *     summary="returns content of job file",
     *     description="allows to read content of files listed in option api",
     *     tags={"Job"},
     *     security = {{ "Authorization": {} }},
     *     @OA\Parameter(
     *          name="file",
     *          in="path",
     *          required=true,
     *     ),
     *    @OA\Response(response=200,description="OK")
     * )
     * 
     * @OA\POST(
     *     path="/api/job",
     *     summary="api to update or insert",
     *     description="pass only name to create or pass file(list from option api) and data to be update",
     *     tags={"Job"},
     *     security = {{ "Authorization": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="json body",
     *        @OA\JsonContent(
     *          required={"data","name","file"},
     *          type="object",
     *            @OA\Property(property="name", type="string", example="samplejob01", description="name of job file to created"),
     *            @OA\Property(property="file", type="string", example="samplejob01", description="name of job file to be updated"),
     *            @OA\Property(property="data", type="string", example="filecontent", description="new content"),
     *        ),
     *     ),
     *    @OA\Response(response=200,description="OK")
     * )
     * 
     */
    public static function file(Request $req,$file)
    {
        $filename = app_path('jobs')."\\".$file.".php";
        $size = (filesize($filename)==0)?1:filesize($filename);
        $file = fopen($filename,"r");
        $data = fread($file,$size);
        return response()->json($data);
    }
    public static function job(Request $req)
    {
        $data = "";
        if(!isset($_POST['file']))
        {
            $name = $_POST['name'];
            $file = fopen(app_path('jobs')."/".$name.".php","w");
            $data ="<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\System\AIThread;

class samplejob1 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}";
            fwrite($file,$data);
            fclose($file);
            $qy = ListJob::create(
                [
                    "job_name" => $name,
                    "role_id" => '0'
                ]
            );
        }
        if(isset($_POST['file']))
        {
            $name = $_POST['file'];
            $file = fopen(app_path('jobs')."/".$name.".php","w");
            $data = $_POST['data'];
            fwrite($file,$data);
            fclose($file);
        }
        return response()->json($data);
    }
}
