<?php

namespace Pardalsalcap\Hailo\Livewire\Search;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Component;

class SearchBar extends Component
{
    public bool $show = true;
    public string $q = "";
    protected $queryString = [
        'q' => ['except' => ''],
    ];

    protected $listeners = [
        'showSearch' => 'toogle',
    ];

    public function dehydrate()
    {
        if (!empty($this->q)) {
            $this->dispatch('searchUpdated', $this->q);
        }
    }

    /**
     * @param $show
     * @return void
     */
    #[NoReturn] public function toogle($show)
    {
        $this->show = $show;
    }

    public function render(): View|Factory
    {
        return view('hailo::livewire.search.bar');
    }

    public function search()
    {
        $this->dispatch('searchUpdated', $this->q);
    }

    public function clear ()
    {
        $this->q = "";
        $this->dispatch('searchUpdated', $this->q);
    }
}
