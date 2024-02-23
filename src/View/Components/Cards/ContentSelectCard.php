<?php

namespace Pardalsalcap\Hailo\View\Components\Cards;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pardalsalcap\Hailo\Models\Content;
use Pardalsalcap\Hailo\Tables\Table;

class ContentSelectCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public Table $table, public Content $content, public bool $selected)
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string|null
    {

        return view('hailo::components.tables.cards.content-select-card', ['media' => $this->content, 'table' => $this->table]);
    }
}
