<?php

namespace Pardalsalcap\Hailo\Forms\Traits;

use Pardalsalcap\Hailo\Forms\Fields\FormField;
use Pardalsalcap\Hailo\Forms\Form;

trait HasForms
{
    protected array $forms = [];
    protected array $validation_errors = [];
    public array $formData = [];

    public int|null $register_id = null;

    public bool $load = true;

    public function form(Form $form): Form
    {
        $this->forms[$form->getName()] = $form;
        return $this->getForm($form->getName());
    }

    public function getForm($name): Form
    {
        return $this->forms[$name];
    }

    public function addFormData($form_name, $field, $value): void
    {
        $this->formData[$form_name][$field] = $value;
    }

    public function getFormData($form_name)
    {
        return $this->formData[$form_name];
    }

    protected function processFormElements(Form $form, array $elements): void
    {
        if ($this->load) {
            foreach ($elements as $element) {
                if ($element instanceof \Pardalsalcap\Hailo\Forms\Section) {
                    $this->processFormElements($form, $element->getSchema());
                } elseif ($element instanceof \Pardalsalcap\Hailo\Forms\Fields\FormField) {
                    if($element->isRelation()){
                        $element->value($form->getModel()->{$element->getRelationName()});
                        if ($element->isRelationMultiple()) {
                            $this->addFormData($form->getName(), $element->getName(), $form->getModel()->{$element->getRelationName()}->pluck('id')->toArray());
                        } else {
                            $element->value($form->getModel()->{$element->getRelationName()}->first()?->{$element->getRelationDisplayField()} ?? '');
                            $this->addFormData($form->getName(), $element->getName(), $form->getModel()->{$element->getRelationName()}->first()?->{$element->getRelationDisplayField()} ?? '');
                        }
                    } elseif (empty($element->getValue()) and isset($form->getModel()->{$element->getName()}) and $element->getType() !== 'password') {
                        if($element->getName()=='mode') { dd("****1"); }
                        $element->value($form->getModel()->{$element->getName()});
                        $this->addFormData($form->getName(), $element->getName(), $form->getModel()->{$element->getName()} ?? '');
                        //$this->formData[$element->getName()] = $form->getModel()->{$element->getName()} ?? '';
                    } else {
                        if (!empty($element->getValue()))
                        {
                            $element->value($element->getValue());
                            $this->addFormData($form->getName(), $element->getName(), $element->getValue());
                        }
                        else
                        {
                            $element->value($element->getValue()?? $element->getDefault() ?? '');
                            $this->addFormData($form->getName(), $element->getName(), $element->getDefault() ?? '');
                        }
                    }
                }
            }
        }
    }

    public function validationRules(Form $form, $elements = null): array
    {
        $rules = [];
        if (is_null($elements)) {
            $elements = $form->getSchema();
        }

        foreach ($elements as $element) {
            if ($element instanceof \Pardalsalcap\Hailo\Forms\Section) {
                $rules = array_merge($rules, $this->validationRules($form, $element->getSchema()));
            } elseif ($element instanceof \Pardalsalcap\Hailo\Forms\Fields\FormField) {
                $rules['formData.' . $form->getName() . "." . $element->getName()] = $element->getRules($form);
            }
        }
        return $rules;
    }

    public function addValidationErrors($form_name, $errors): void
    {
        $this->validation_errors[$form_name] = $errors;
    }

    public function getValidationErrors (): array
    {
        return $this->validation_errors;
    }
}
