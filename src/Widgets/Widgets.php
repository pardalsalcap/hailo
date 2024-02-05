<?php

namespace Pardalsalcap\Hailo\Widgets;

class Widgets
{
    protected static array $widgets = [];

    protected static string $name = '';

    protected static int $columns = 3;

    // Static method to create an instance and set the name
    public static function make($name): self
    {
        $instance = new static();
        self::$name = $name;

        return $instance;
    }

    // Instance method for setting navigation
    public function widgets(array $widgets): self
    {
        self::$widgets = $widgets;

        return $this;
    }

    public function columns(int $columns): self
    {
        self::$columns = $columns;

        return $this;
    }

    public function getWidgets(): array
    {
        return self::$widgets;
    }

    public function getName(): string
    {
        return self::$name;
    }

    public function getColumns(): int
    {
        return self::$columns;
    }

}
