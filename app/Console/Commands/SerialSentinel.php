<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\System\SerialThread;

class SerialSentinel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:serial-sentinel';

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
        SerialThread::dispatch()->onQueue("system");
    }
}
