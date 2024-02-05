<?php

namespace Pardalsalcap\Hailo\Forms\Traits;

trait HasDefault
{
    protected mixed $default = null;

    public function default(mixed $default): self
    {
        $this->default = $default;

        return $this;
    }

    public function getDefault(): ?string
    {
        return $this->default;
    }
}
