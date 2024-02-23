<?php

namespace Pardalsalcap\Hailo\Forms\Fields;

class ButtonInput
{
    public function render(): string
    {
        // Implement render() method.
    }

    public function validate($input)
    {
        // Implement validate() method.
    }

    public function getName()
    {
        // Implement getName() method.
    }

    public function getValue()
    {
        // Implement getValue() method.
    }

    public function setValue($value)
    {
        // Implement setValue() method.
    }

    public function name(string $name): FormField
    {
        // TODO: Implement name() method.
        return $this;
    }

    public function label(string $label): FormField
    {
        // TODO: Implement label() method.
        return $this;
    }

    public function value($value): FormField
    {
        // TODO: Implement value() method.
        return $this;
    }

    public function rules($rules): FormField
    {
        // TODO: Implement rules() method.
        return $this;
    }

    public function toArray(): array
    {
        // TODO: Implement toArray() method.
        return $this;
    }
}
