<?php

namespace App\Http\Middleware;

class EncryptCookies extends \Illuminate\Cookie\Middleware\EncryptCookies
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
    ];
}
