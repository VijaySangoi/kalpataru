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
                'name' => 'Job',
                'pageslug' => 'job',
                'url' => '/job',
                'icon' => 'icon-chart-pie-36'
            ],
            [
                'name' => 'Triggers',
                'pageslug' => 'triggers',
                'url' => '/triggers',
                'icon' => 'icon-controller'
            ],
            [
                'name' => 'Sensors',
                'pageslug' => 'sensors',
                'url' => '/sensors',
                'icon' => 'icon-controller'
            ],
            [
                'name' => 'Serial',
                'pageslug' => 'serial',
                'url' => '/serial',
                'icon' => 'icon-puzzle-10'
            ],
            [
                'name' => 'Workers',
                'pageslug' => 'workers',
                'url' => '/workers',
                'icon' => 'icon-puzzle-10'
            ],
            [
                'name' => 'CIP',
                'pageslug' => 'cip',
                'url' => '/cip',
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
