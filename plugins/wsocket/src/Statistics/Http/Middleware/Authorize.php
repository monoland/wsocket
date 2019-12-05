<?php

namespace WSocket\Statistics\Http\Middleware;

use WSocket\Apps\App;

class Authorize
{
    public function handle($request, $next)
    {
        return is_null(App::findBySecret($request->secret)) ? abort(403) : $next($request);
    }
}
