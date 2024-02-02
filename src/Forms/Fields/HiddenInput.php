<?php

namespace Pardalsalcap\Hailo\Forms\Fields;

use Pardalsalcap\Hailo\Forms\Traits\HasDefault;
use Pardalsalcap\Hailo\Forms\Traits\HasLabel;
use Pardalsalcap\Hailo\Forms\Traits\HasPlaceholder;
use Pardalsalcap\Hailo\Forms\Traits\HasRelation;
use Pardalsalcap\Hailo\Forms\Traits\HasType;
use Pardalsalcap\Hailo\Forms\Traits\HasValidationRules;

class HiddenInput implements FormField
{
    use HasDefault, HasLabel, HasPlaceholder, HasType, HasValidationRules, HasRelation;

    protected string $name = '';

    protected mixed $value = null;

    public function __construct(string $name)
    {
        $this->name = $name;
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

    public function getValue()
    {
        return $this->value;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
