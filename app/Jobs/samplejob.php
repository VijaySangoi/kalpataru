<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;
use App\Models\Com;

class samplejob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $q_name;
    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->q_name = "rn1";
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $rec = Com::select('Port','BaudRate')->get();
        foreach($rec as $ky => $val)
        {
            $com = strtoupper($val->Port);
            $path = "app/Comms/InterfaceSerial.py";
            $arr = ["python", $path, $com, $val->BaudRate];
            $process = new Process($arr);
            $process->start();
        }
        sleep(5);
        self::dispatch()->onQueue($this->q_name);
    }
}
