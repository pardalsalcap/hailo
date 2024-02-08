<?php

namespace Pardalsalcap\Hailo\Tables\Traits;

trait HasCard
{
    public bool $hasCard = false;
    public string $card = '';

    public function card(string $card): self
    {
        $this->card = $card;
        $this->hasCard = true;
        return $this;
    }

    public function hasCard(): bool
    {
        return $this->hasCard;
    }
}
