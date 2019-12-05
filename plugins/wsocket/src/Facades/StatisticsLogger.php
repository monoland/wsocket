<?php

namespace WSocket\Facades;

use Illuminate\Support\Facades\Facade;
use WSocket\Statistics\Logger\StatisticsLogger as StatisticsLoggerInterface;

/** @see \WSocket\Statistics\Logger\HttpStatisticsLogger */
class StatisticsLogger extends Facade
{
    protected static function getFacadeAccessor()
    {
        return StatisticsLoggerInterface::class;
    }
}
