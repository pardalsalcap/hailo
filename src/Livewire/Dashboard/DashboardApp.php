<?php

namespace Pardalsalcap\Hailo\Livewire\Dashboard;

use Livewire\Component;
use Pardalsalcap\Hailo\Repositories\DashboardRepository;

class DashboardApp extends Component
{
    public $formData = [];

    public function mount()
    {

    }

    public function render()
    {
        return view('hailo::livewire.dashboard.show', [
            'widgets' => config('hailo.dashboard', new DashboardRepository()),
        ])
            ->layout('hailo::layouts.main')
            ->title(__('hailo::hailo.dashboard_html_title', ['name' => config('app.name')]));
    }
}
