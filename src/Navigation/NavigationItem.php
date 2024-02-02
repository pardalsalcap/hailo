<?php

namespace Pardalsalcap\Hailo\Navigation;

use Closure;

class NavigationItem
{
    protected string $name;

    protected string $label;

    protected string $id;

    protected string $route;

    protected array $children;

    protected string $icon;

    protected bool $livewire = false;

    protected bool $active = false;

    // Static factory method
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

    public function icon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function livewire(bool $livewire): self
    {
        $this->livewire = $livewire;

        return $this;
    }

    public function children(array $children): self
    {
        $this->children = $children;

        return $this;
    }

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function active(bool|Closure $active): self
    {
        if (is_callable($active)) {
            $this->active = (bool) call_user_func($active);
            return $this;
        }
        $this->active = $active;

        return $this;
    }

    // Getters for the properties
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

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function getLivewire(): bool
    {
        return $this->livewire;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'id' => $this->getId(),
            'route' => $this->getRoute(),
            'icon' => $this->getIcon(),
            'livewire' => $this->getLivewire(),
            'children' => $this->getChildren(),
            'label' => $this->getLabel(),
            'active' => $this->isActive(),
        ];
    }

    public function checkIfChildrenIsActive(): bool
    {
        foreach ($this->getChildren() as $child) {
            if ($child->isActive()) {
                return true;
            }
        }
        return false;
    }
}
