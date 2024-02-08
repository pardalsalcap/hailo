<?php

namespace Pardalsalcap\Hailo\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pardalsalcap\Hailo\Forms\Fields\HiddenInput;
use Pardalsalcap\Hailo\Forms\Fields\MediaInput;
use Pardalsalcap\Hailo\Forms\Fields\SelectInput;
use Pardalsalcap\Hailo\Forms\Fields\TextInput;
use Pardalsalcap\Hailo\Forms\Section;

class FormElement extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public $element, public \Pardalsalcap\Hailo\Forms\Form $form)
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string|null
    {

        if ($this->element instanceof Section) {
            return view('hailo::components.forms.section', ['section' => $this->element]);
        } elseif ($this->element instanceof TextInput) {
            return view('hailo::components.forms.text-input', ['input' => $this->element]);
        } elseif ($this->element instanceof SelectInput) {
            return view('hailo::components.forms.select-input', ['input' => $this->element]);
        } elseif ($this->element instanceof HiddenInput) {
            return view('hailo::components.forms.hidden-input', ['input' => $this->element]);
        } elseif ($this->element instanceof MediaInput) {
            return view('hailo::components.forms.media-input', ['input' => $this->element]);
        }

        return null;
    }
}
