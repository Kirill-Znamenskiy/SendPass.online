<?php
namespace App\Http\Middleware;


class GitShowMiddleware  extends \KZ\MyLlComponents\Illuminate\Middleware\GitShowMiddleware {
    public function handle(\Illuminate\Http\Request $request, \Closure $next) {

        /** This variable is available globally on all your views, and sub-views */
        view()->share([
            'BUILD_GIT_SHOW_VERSION' => config('app.BUILD_GIT_SHOW_VERSION'),
        ]);

        return $next($request);
    }
}
