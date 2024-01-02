<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;

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
        $com = "COM4";
        $path = "app/Comms/InterfaceSerial.py";
        $arr = ["python", $path, $com, "115200"];
        $process = new Process($arr);
        $process->start();
        // echo "output " . $process->getOutput();
        // sleep(5);
        self::dispatch()->onQueue($this->q_name);
    }
}
