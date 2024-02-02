<?php

namespace Pardalsalcap\Hailo\Forms\Fields;

use Pardalsalcap\Hailo\Forms\Form;

interface FormField
{
    public static function make(string $name): self;

    public function getName(): string;

    /**
     * Set the label of the form field.
     */
    public function label(string $label): self;

    public function getLabel(): string;

    /**
     * Set the value of the form field.
     *
     * @param  mixed  $value
     */
    public function value($value): self;

    public function getValue();

    /**
     * Add validation rules for the form field.
     *
     * @param  array|string  $rules
     */
    public function rules(array $rules): self;

    public function getRules($form): array;

    /**
     * Get the array representation of the form field, useful for JSON serialization.
     */
    public function toArray(): array;
}
