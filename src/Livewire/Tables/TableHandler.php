<?php

namespace Pardalsalcap\Hailo\Livewire\Tables;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\WithPagination;
use Pardalsalcap\Hailo\Tables\Table;

class TableHandler extends Component
{
    use WithPagination;

    protected Table $table;

    public array $tableData = [];

    public ?Model $model = null;

    /*protected function processTableElements(array $elements): void
    {
        $this->tableData[$element->getName()] = $element->getDefault() ?? '';
    }*/

    public function render()
    {
        $this->table->executeQuery();
        $this->table->extractCss();
        dump($this->table->getConfiguration());

        return view('hailo::livewire.tables.table-handler', ['table' => $this->table]);
    }
}
