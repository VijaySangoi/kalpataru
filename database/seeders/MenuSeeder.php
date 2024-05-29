<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('menu')->insert([
            [
                'name' => 'Dashboard',
                'pageslug' => 'dashboard',
                'url' => '/home',
                'icon' => 'icon-chart-pie-36'
            ],
            [
                'name' => 'Triggers',
                'pageslug' => 'triggers',
                'url' => '/triggers',
                'icon' => 'icon-controller'
            ],
            [
                'name' => 'Job',
                'pageslug' => 'job',
                'url' => '/job',
                'icon' => 'icon-chart-pie-36'
            ],
            [
                'name' => 'Nodes',
                'pageslug' => 'nodes',
                'url' => '/nodes',
                'icon' => 'icon-puzzle-10'
            ],
            [
                'name' => 'Devices',
                'pageslug' => 'devices',
                'url' => '/devices',
                'icon' => 'icon-controller'
            ],
            [
                'name' => 'Workers',
                'pageslug' => 'workers',
                'url' => '/workers',
                'icon' => 'icon-puzzle-10'
            ],
            [
                'name' => 'Logs',
                'pageslug' => 'logs',
                'url' => '/logs',
                'icon' => 'icon-palette'
            ],
        ]);
    }
}
