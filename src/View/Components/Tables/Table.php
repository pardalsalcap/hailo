<?php

namespace Pardalsalcap\Hailo\View\Components\Tables;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Table extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public \Pardalsalcap\Hailo\Tables\Table $table)
    {
        $table->extractCss();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('hailo::components.tables.table', ['table' => $this->table]);
    }
}
