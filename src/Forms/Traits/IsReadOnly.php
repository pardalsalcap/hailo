<?php

namespace Pardalsalcap\Hailo\Forms\Traits;

trait IsReadOnly
{
    protected bool $is_read_only = false;

    public function readOnly(bool $is_read_only = true): self
    {
        $this->is_read_only = $is_read_only;

        return $this;
    }

    public function isReadOnly(): string
    {
        return $this->is_read_only;
    }
}
