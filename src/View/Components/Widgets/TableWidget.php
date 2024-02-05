<?php

namespace Pardalsalcap\Hailo\View\Components\Widgets;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Pardalsalcap\Hailo\Tables\Table;
use Pardalsalcap\Hailo\Widgets\Widget;

class TableWidget extends Component
{
    public Table $table;
    /**
     * Create a new component instance.
     */
    public function __construct(public Widget $widget)
    {
        $this->table = $this->widget->getTable()->executeQuery();
        $this->table->extractCss();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('hailo::components.tables.table-widget');
    }
}
