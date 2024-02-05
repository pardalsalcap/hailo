<?php

namespace Pardalsalcap\Hailo\Tables\Traits;

use Closure;
use Illuminate\Database\Eloquent\Model;

trait HasUrl
{
    protected string $url = '';

    protected bool $hasUrl = false;

    protected bool $openInNewTab = true;

    protected string|Closure $getUrlFn = '';

    public function url(string|Closure $url): self
    {
        if (is_callable($url)) {
            $this->getUrlFn = $url;

            return $this;
        }
        $this->url = $url;

        return $this;
    }

    public function hasUrl(bool $hasUrl): self
    {
        $this->hasUrl = $hasUrl;

        return $this;
    }

    public function openInNewTab(bool $openInNewTab): self
    {
        $this->openInNewTab = $openInNewTab;

        return $this;
    }

    public function getUrl(?Model $model): ?string
    {
        if (is_callable($this->getUrlFn)) {
            return call_user_func($this->getUrlFn, $model);
        }

        return $this->url;
    }

    public function getHasUrl(): bool
    {
        return $this->hasUrl;
    }

    public function getOpenInNewTab(): bool
    {
        return $this->openInNewTab;
    }
}
