<?php

namespace Pardalsalcap\Hailo\Repositories;

use Pardalsalcap\Hailo\Widgets\Widgets;

class DashboardRepository
{
    public function widgets(): Widgets
    {
        return Widgets::make('dashboard')
            ->widgets([
                [

                ],
            ]);
    }
}
