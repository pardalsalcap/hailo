<?php

namespace Pardalsalcap\Hailo\Forms\Traits;

trait HasHelpText
{
    protected string $help = '';

    public function help(string $help): self
    {
        $this->help = $help;

        return $this;
    }

    public function getHelp(): string
    {
        return $this->help;
    }
}
