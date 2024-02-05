<?php

namespace Pardalsalcap\Hailo\Livewire\Profile;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ProfileApp extends Component
{
    public function render(): View|Factory
    {
        $this->dispatch('showSearch', false);

        return view('hailo::livewire.permissions.profile')
            ->layout('hailo::layouts.main')
            ->title(__('hailo::profile.html_title', ['name' => config('app.name')]));
    }
}
