<?php

namespace Pardalsalcap\Hailo\Livewire\Dashboard;

use Livewire\Component;

class Dashboard extends Component
{
    public $formData = [];

    public function mount()
    {

    }

    public function render()
    {

        return view('hailo::livewire.dashboard.show')
            ->layout('hailo::layouts.main')
            ->title(__('hailo::hailo.dashboard_html_title', ['name' => config('app.name')]));
    }
}
