<?php

namespace Pardalsalcap\Hailo\Livewire\Profile;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ProfileMenu extends Component
{
    protected $listeners = ['profileUpdated' => '$refresh'];

    public function render(): View|Factory
    {
        return view('hailo::livewire.ui.profile_menu');
    }
}
