<?php

namespace Pardalsalcap\Hailo\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormValidation extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public $errors = null)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('hailo::components.form-validation');
    }
}
