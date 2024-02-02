<?php

namespace Pardalsalcap\Hailo\View\Components\Tables;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;
use Pardalsalcap\Hailo\Tables\Columns\TextColumn;

class RowActions extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public \Pardalsalcap\Hailo\Tables\Table $table, public $element, public Model $model)
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string|null
    {
        return view('hailo::components.tables.row-actions', ['column' => $this->element, 'model' => $this->model]);
    }
}
