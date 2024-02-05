<?php

namespace Pardalsalcap\Hailo\Widgets;

use Closure;
use Pardalsalcap\Hailo\Tables\Table;

class Widget
{
    protected string $name, $id, $route="", $type;
    protected Table $table;

    public static function make(string $name): self
    {
        $instance = new static();
        $instance->name = $name;

        return $instance;
    }

    public function id(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function route(string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function table(Table $table): self
    {
        $this->table = $table;
        $this->type = 'table';

        return $this;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTable(): Table
    {
        return $this->table;
    }


    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'id' => $this->getId(),
            'route' => $this->getRoute(),
        ];
    }
}
