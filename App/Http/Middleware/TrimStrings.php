<?php

namespace App\Http\Middleware;

class TrimStrings extends \Illuminate\Foundation\Http\Middleware\TrimStrings
{
    /**
     * The names of the attributes that should not be trimmed.
     *
     * @var array
     */
    protected $except = [
        'password',
        'password_confirmation',
    ];
}
