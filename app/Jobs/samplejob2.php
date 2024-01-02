<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class samplejob2 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $q_name;
    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->q_name = "rx2";
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $j = 0;
        while ($j < 5) {
            echo "job 2\n";
            echo $this->q_name . "\n";
            echo date("H:i:s") . "\n";
            $j++;
            sleep(5);
        }
        self::dispatch()->onQueue($this->q_name);
    }
}
