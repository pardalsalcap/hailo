<?php

namespace Pardalsalcap\Hailo\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pardalsalcap\Hailo\Forms\Fields\CkEditorInput;
use Pardalsalcap\Hailo\Forms\Fields\ContentMultipleInput;
use Pardalsalcap\Hailo\Forms\Fields\HiddenInput;
use Pardalsalcap\Hailo\Forms\Fields\MediaInput;
use Pardalsalcap\Hailo\Forms\Fields\MediaMultipleInput;
use Pardalsalcap\Hailo\Forms\Fields\MediaSingleInput;
use Pardalsalcap\Hailo\Forms\Fields\SelectInput;
use Pardalsalcap\Hailo\Forms\Fields\SeoGoogle;
use Pardalsalcap\Hailo\Forms\Fields\TextInput;
use Pardalsalcap\Hailo\Forms\Fields\ToggleInput;
use Pardalsalcap\Hailo\Forms\Section;

class FormElement extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public $element, public \Pardalsalcap\Hailo\Forms\Form $form, public array $data = [])
    {

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string|null
    {
        if ($this->element instanceof Section) {
            return view('hailo::components.forms.section', ['section' => $this->element, "data" => $this->data]);
        } elseif ($this->element instanceof TextInput) {
            return view('hailo::components.forms.text-input', ['input' => $this->element, "data" => $this->data]);
        } elseif ($this->element instanceof SelectInput) {
            return view('hailo::components.forms.select-input', ['input' => $this->element, "data" => $this->data]);
        } elseif ($this->element instanceof HiddenInput) {
            return view('hailo::components.forms.hidden-input', ['input' => $this->element, "data" => $this->data]);
        } elseif ($this->element instanceof MediaInput) {
            return view('hailo::components.forms.media-input', ['input' => $this->element, "data" => $this->data]);
        } elseif ($this->element instanceof CkEditorInput) {
            return view('hailo::components.forms.ck_editor-input', ['input' => $this->element, "data" => $this->data]);
        } elseif ($this->element instanceof SeoGoogle) {
            return view('hailo::components.forms.seo-google', ['input' => $this->element, "data" => $this->data]);
        } elseif ($this->element instanceof ToggleInput) {
            return view('hailo::components.forms.toggle-input', ['input' => $this->element, "data" => $this->data]);
        } elseif ($this->element instanceof MediaSingleInput) {
            return view('hailo::components.forms.media-single-input', ['input' => $this->element, "data" => $this->data]);
        } elseif ($this->element instanceof MediaMultipleInput) {
            return view('hailo::components.forms.media-multiple-input', ['input' => $this->element, "data" => $this->data]);
        } elseif ($this->element instanceof ContentMultipleInput) {
            return view('hailo::components.forms.content-multiple-input', ['input' => $this->element, "data" => $this->data]);
        }

        return null;
    }
}
