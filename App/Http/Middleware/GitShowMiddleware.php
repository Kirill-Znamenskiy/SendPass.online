<?php
namespace App\Http\Middleware;


class GitShowMiddleware {


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @throws \RuntimeException
     */
    public function handle(\Illuminate\Http\Request $request, \Closure $next) {

        $cwd = getcwd();
        chdir(app()->basePath());
        $exec_lines = [];
        exec('git show --no-color --no-patch --decorate',$exec_lines);
        $git_show = reset($exec_lines);
        chdir($cwd);

        /** This variable is available globally on all your views, and sub-views */
        view()->share([
            'git_show' => $git_show,
        ]);

        return $next($request);
    }
}
