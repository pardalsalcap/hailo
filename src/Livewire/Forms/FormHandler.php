<?php

namespace Pardalsalcap\Hailo\Livewire\Forms;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Pardalsalcap\Hailo\Forms\Form;

class FormHandler extends Component
{
    protected Form $form;

    public array $formData = [];

    public ?Model $model = null;

    protected function processFormElements(array $elements): void
    {
        foreach ($elements as $element) {
            if ($element instanceof \Pardalsalcap\Hailo\Forms\Section) {
                $this->processFormElements($element->getSchema());
            } elseif ($element instanceof \Pardalsalcap\Hailo\Forms\Fields\FormField) {

                if (empty($element->getValue()) and isset($this->model->{$element->getName()}) and $element->getType() !== 'password') {
                    $element->value($this->model->{$element->getName()});
                    $this->formData[$element->getName()] = $this->model->{$element->getName()} ?? '';
                }
                else
                {
                    $this->formData[$element->getName()] = $element->getDefault() ?? '';
                }

            }
        }
    }



    public function validationRules($form): array
    {
        $rules = [];
        foreach ($form->getSchema() as $element) {
            if ($element instanceof \Pardalsalcap\Hailo\Forms\Section) {
                $rules = array_merge($rules, $this->validationRules($element));
            } elseif ($element instanceof \Pardalsalcap\Hailo\Forms\Fields\FormField) {
                $rules['formData.'.$element->getName()] = $element->getRules();
            }
        }
        return $rules;
    }

    public function render()
    {
        return view('hailo::livewire.forms.form-handler', ['form' => $this->form]);
    }
}
