<?php

namespace Pardalsalcap\Hailo\Forms\Traits;

trait IsTranslatable
{
    protected bool $is_translatable = false;

    public function translatable(bool $is_translatable = true): self
    {
        $this->is_translatable = $is_translatable;

        return $this;
    }

    public function isTranslatable(): string
    {
        return $this->is_translatable;
    }
}
