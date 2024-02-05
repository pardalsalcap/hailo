<?php

namespace Pardalsalcap\Hailo\Forms\Fields;

use Pardalsalcap\Hailo\Forms\Traits\HasDefault;
use Pardalsalcap\Hailo\Forms\Traits\HasLabel;
use Pardalsalcap\Hailo\Forms\Traits\HasOptions;
use Pardalsalcap\Hailo\Forms\Traits\HasPlaceholder;
use Pardalsalcap\Hailo\Forms\Traits\HasRelation;
use Pardalsalcap\Hailo\Forms\Traits\HasType;
use Pardalsalcap\Hailo\Forms\Traits\HasValidationRules;

class SelectInput implements FormField
{
    use HasDefault, HasLabel, HasOptions, HasPlaceholder, HasRelation, HasType, HasValidationRules;

    protected string $name = '';

    protected mixed $value = null;

    protected bool $multiple = false;

    protected bool $null_option = true;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->type = 'select';
        $this->null_option = true;
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'label' => $this->label,
            'value' => $this->value,
            'type' => $this->type,

        ];
    }

    public function value($value): self
    {
        $this->value = $value;

        return $this;
    }

    public function multiple(bool $multiple): self
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function nullOption(bool $null_option): self
    {
        $this->null_option = $null_option;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMultiple(): bool
    {
        return $this->multiple;
    }

    public function getNullOption(): bool
    {
        return $this->null_option;
    }
}
