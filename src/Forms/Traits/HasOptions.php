<?php

namespace Pardalsalcap\Hailo\Forms\Traits;

use Closure;

trait HasOptions
{
    protected array $options = [];

    protected ?Closure $optionsFn = null;

    public function options(array|Closure $options): self
    {
        if (is_callable($options)) {
            $this->optionsFn = $options;

            return $this;
        }
        $this->options = $options;

        return $this;
    }

    public function getOptions(?array $formData): array
    {
        if (! is_null($this->optionsFn)) {
            return call_user_func($this->optionsFn, $formData);
        }

        return $this->options;
    }
}
