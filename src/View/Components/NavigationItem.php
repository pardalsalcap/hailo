<?php

namespace Pardalsalcap\Hailo\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NavigationItem extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public \Pardalsalcap\Hailo\Navigation\NavigationItem $navigationItem)
    {

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if (count($this->navigationItem->getChildren()) > 0) {

            return view('hailo::components.navigation.dropdown');
        }

        return view('hailo::components.navigation.item');
    }
}
