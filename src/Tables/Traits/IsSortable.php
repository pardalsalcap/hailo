<?php

namespace Pardalsalcap\Hailo\Tables\Traits;

trait IsSortable
{
    protected bool $isSortable = false;

    public function sortable(bool $isSortable = true): self
    {
        $this->isSortable = $isSortable;

        return $this;
    }

    public function isSortable(): string
    {
        return $this->isSortable;
    }
}
