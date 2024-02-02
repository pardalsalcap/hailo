<?php

namespace Pardalsalcap\Hailo\Forms\Traits;

trait HasOptions
{
    protected array $options = [];

    public function options(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
