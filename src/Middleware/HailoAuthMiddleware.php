<?php

namespace Pardalsalcap\Hailo\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class HailoAuthMiddleware extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('hailo.login');
    }
}
