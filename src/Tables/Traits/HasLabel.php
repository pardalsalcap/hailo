<?php

namespace Pardalsalcap\Hailo\Tables\Traits;

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
