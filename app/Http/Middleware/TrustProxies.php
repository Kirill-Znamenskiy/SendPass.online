<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class TrustProxies extends \Fideloper\Proxy\TrustProxies
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string
     */
    protected $proxies;

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
