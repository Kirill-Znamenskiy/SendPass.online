<?php

namespace App\Http\Middleware;

class CheckForMaintenanceMode extends \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array
     */
    protected $except = [
        //
    ];
}
