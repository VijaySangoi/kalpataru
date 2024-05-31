<?php

namespace App\System;

use App\Models\Transformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class AIThread
{
    public function __construct()
    {}

    public function handle(string $transformer,$input)
    {
        $qy = Transformer::select('*');
        $qy->where('name',$transformer);
        $rec = $qy->first();
        if(!$rec) Log::error('transformer not valid');
        $path = "app/Comms/AiInterface.py";
        $arr = ["python",$path,$rec->type,"transformers/".$rec->full_namespace,$input];
        try {
            $process = new Process($arr);
            $process->run();
        }
        catch(\Exception $e)
        {
            Log::error($e);
        }
    }
}
