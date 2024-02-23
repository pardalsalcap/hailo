<?php

namespace Pardalsalcap\Hailo\Forms\Traits;

trait HasRelation
{
    protected bool $isRelation = false;

    protected string $relationName = '';

    protected string $relationDisplayField = '';

    protected bool $relationIsMultiple = false;

    protected bool $is_content_media = true;

    protected ?string $content_media_type = null;

    protected bool $content_media_keep_position = false;

    public function relation(string $relationName, string $relationDisplayField, bool $relationIsMultiple = false, bool $is_content_media = false, ?string $content_media_type = null, bool $content_media_keep_position = false): self
    {
        if (! empty($relationName)) {
            $this->relationName = $relationName;
            $this->isRelation = true;
            $this->relationDisplayField = $relationDisplayField;
            $this->relationIsMultiple = $relationIsMultiple;
            $this->is_content_media = $is_content_media;
            $this->content_media_type = $content_media_type;
            $this->content_media_keep_position = $content_media_keep_position;
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

    public function isContentMedia(): bool
    {
        return $this->is_content_media;
    }

    public function getContentMediaType(): ?string
    {
        return $this->content_media_type;
    }

    public function getContentMediaKeepPosition(): bool
    {
        return $this->content_media_keep_position;
    }
}
