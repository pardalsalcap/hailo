<?php

namespace Pardalsalcap\Hailo\Tables\Traits;

trait HasActions
{
    public string $action = 'index';

    public function action(string $action): void
    {
        $this->action = $action;

    }
}
