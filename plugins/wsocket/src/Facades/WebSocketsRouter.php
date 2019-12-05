<?php

namespace WSocket\Facades;

use Illuminate\Support\Facades\Facade;

/** @see \WSocket\Server\Router */
class WebSocketsRouter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'websockets.router';
    }
}
