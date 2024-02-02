<?php

namespace Pardalsalcap\Hailo\Forms\Traits;

trait HasPlaceholder
{
    protected string $placeholder = '';

    public function placeholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }
}
