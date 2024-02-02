<?php

namespace Pardalsalcap\Hailo\Navigation;

class Navigation
{
    protected static array $navigation = [];

    protected static string $name = '';

    // Static method to create an instance and set the name
    public static function make($name): self
    {
        $instance = new static();
        self::$name = $name;

        return $instance;
    }

    // Instance method for setting navigation
    public function navigation(array $navigation): self
    {
        self::$navigation = $navigation;

        return $this;
    }

    public function getItems(): array
    {
        return self::$navigation;
    }
}
