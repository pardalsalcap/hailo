<?php

namespace Pardalsalcap\Hailo\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Pardalsalcap\Hailo\Hailo
 */
class Hailo extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Pardalsalcap\Hailo\Hailo::class;
    }
}
