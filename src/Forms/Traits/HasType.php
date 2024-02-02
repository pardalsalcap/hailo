<?php

namespace Pardalsalcap\Hailo\Forms\Traits;

trait HasType
{
    protected string $type = 'text';

    public function type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
