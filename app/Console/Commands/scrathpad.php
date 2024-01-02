<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\samplejob;
use App\Jobs\samplejob2;

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
        samplejob::dispatch()->onQueue("rn1");
        // samplejob2::dispatch()->onQueue("rx2");
        // samplejob::dispatch()->onQueue("rn1");
        // samplejob2::dispatch()->onQueue("rx2");
    }
}
