<?php

namespace App\System;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;
use App\Models\Node;

class SerialThread implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $q_name;
    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->q_name = "system";
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $rec = Node::select('address')->where('type','ser')->get();
        foreach($rec as $ky => $val)
        {
            $param = explode(",",$val->address);
            $com = strtoupper($param[0]);
            $path = "app/Comms/InterfaceSerial.py";
            $arr = ["python", $path, $com, $param[1]];
            try{
                $process = new Process($arr);
                $process->start();
            }
            catch(\exception $e)
            {
                var_dump($e);
            }
        }
        sleep(5);
        self::dispatch()->onQueue($this->q_name);
    }
}
