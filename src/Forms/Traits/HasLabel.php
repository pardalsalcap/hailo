<?php

namespace Pardalsalcap\Hailo\Forms\Traits;

trait HasLabel
{
    protected string $label = '';

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}
