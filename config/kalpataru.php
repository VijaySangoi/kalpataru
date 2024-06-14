<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [
    'refresh_interval'=> env('DASHBOARD_REFRESH_INTERVAL',60)
];