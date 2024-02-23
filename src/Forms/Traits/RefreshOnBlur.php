<?php

namespace Pardalsalcap\Hailo\Forms\Traits;

trait RefreshOnBlur
{
    protected bool $refresh_on_blur = false;

    public function blur(bool $refresh_on_blur = true): self
    {
        $this->refresh_on_blur = $refresh_on_blur;

        return $this;
    }

    public function hasBlur(): string
    {
        return $this->refresh_on_blur;
    }
}
