<?php

namespace Pardalsalcap\Hailo\Tables\Traits;

trait HasCss
{
    protected string $css = '';

    public function css(string $css): self
    {
        $this->css = $css;

        return $this;
    }

    public function getCss(): string
    {
        return $this->css;
    }
}
