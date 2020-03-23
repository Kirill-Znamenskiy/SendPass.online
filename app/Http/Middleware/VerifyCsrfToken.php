<?php

namespace App\Http\Middleware;

class VerifyCsrfToken extends \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = false;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];
}
