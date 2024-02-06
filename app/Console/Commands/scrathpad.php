<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\System\SerialThread;

class scrathpad extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scrathpad';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        SerialThread::dispatch()->onQueue("rn1");
    }
}
