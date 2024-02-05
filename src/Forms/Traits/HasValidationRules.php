<?php

namespace Pardalsalcap\Hailo\Forms\Traits;

use Closure;

trait HasValidationRules
{
    protected array|Closure $rules = [];

    public function rules(array|Closure $rules): self
    {
        $this->rules = $rules;

        return $this;
    }

    public function required(bool|Closure $required = true): self
    {
        if ($required === true) {
            $this->rules[] = 'required';
        }

        return $this;
    }

    public function getRules($form): array
    {
        if (is_callable($this->rules)) {
            return call_user_func($this->rules, $form);

        }

        return $this->rules;
    }
}
