<?php

namespace Pardalsalcap\Hailo\Tables\Columns;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Pardalsalcap\Hailo\Tables\Traits\HasCss;
use Pardalsalcap\Hailo\Tables\Traits\HasLabel;
use Pardalsalcap\Hailo\Tables\Traits\HasSearch;
use Pardalsalcap\Hailo\Tables\Traits\HasUrl;
use Pardalsalcap\Hailo\Tables\Traits\IsRelation;
use Pardalsalcap\Hailo\Tables\Traits\IsSortable;

class MediaColumn
{
    use HasCss, HasLabel, HasSearch, HasUrl, IsRelation, IsSortable;

    protected string $name = '';

    protected mixed $value = null;

    protected mixed $display = null;

    protected ?Closure $displayFn = null;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'label' => $this->label,
            'value' => $this->value,
            'type' => $this->type,

        ];
    }

    public function value($value): self
    {
        $this->value = $value;

        return $this;
    }

    public function display(?Closure $display = null): self
    {
        if (! is_null($display)) {
            $this->displayFn = $display;
        }

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDisplay(Model $model)
    {
        if (! is_null($this->displayFn)) {
            return call_user_func($this->displayFn, $model);
        }

        return ! empty($this->getValue()) ? $this->getValue() : $model->{$this->getName()};
    }
}
