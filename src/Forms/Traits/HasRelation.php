<?php

namespace Pardalsalcap\Hailo\Forms\Traits;

trait HasRelation
{
    protected bool $isRelation = false;
    protected string $relationName = '';
    protected string $relationDisplayField = '';


    protected bool $relationIsMultiple = false;

    public function relation(string $relationName, string $relationDisplayField, bool $relationIsMultiple = false): self
    {
        if (!empty($relationName)) {
            $this->relationName = $relationName;
            $this->isRelation = true;
            $this->relationDisplayField = $relationDisplayField;
            $this->relationIsMultiple = $relationIsMultiple;
        }
        return $this;
    }

    public function isRelation(): bool
    {
        return $this->isRelation;
    }

    public function getRelationName(): string
    {
        return $this->relationName;
    }

    public function isRelationMultiple(): bool
    {
        return $this->relationIsMultiple;
    }

    public function getRelationDisplayField(): string
    {
        return $this->relationDisplayField;
    }
}
