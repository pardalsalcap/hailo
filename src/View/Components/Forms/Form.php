<?php

namespace Pardalsalcap\Hailo\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\MessageBag;
use Illuminate\View\Component;

class Form extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public \Pardalsalcap\Hailo\Forms\Form $form, public $validation = null)
    {
        if (!is_null($this->validation)) {
            $this->validation = new MessageBag($this->validation);
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('hailo::components.forms.form', ['form' => $this->form]);
    }
}
