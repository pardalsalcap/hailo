<?php

namespace Pardalsalcap\Hailo\Tables\Traits;

trait IsRelation
{
    protected bool $isRelation = false;

    public function relation(bool $isRelation = true): self
    {
        $this->isRelation = $isRelation;

        return $this;
    }

    public function isRelation(): string
    {
        return $this->isRelation;
    }
}
