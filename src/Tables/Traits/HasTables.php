<?php

namespace Pardalsalcap\Hailo\Tables\Traits;

use Livewire\WithPagination;
use Pardalsalcap\Hailo\Tables\Table;

trait HasTables
{
    use WithPagination;

    protected array $tables = [];

    public string $sort_by = 'id';

    public string $sort_direction = 'ASC';

    public string $q = '';

    public string $filter = 'all';

    public array $filters = ['all'];

    public function table($name, Table $table): Table
    {
        $this->tables[$name] = $table;

        return $this->getTable($name);
    }

    public function getTable($name): ?Table
    {
        if (! isset($this->tables[$name])) {
            return null;
        }

        return $this->tables[$name];
    }

    public function sort($field, $direction)
    {
        $this->sort_by = $field;
        $this->sort_direction = $direction;
        $this->resetPage();
    }

    public function search($q)
    {
        $this->q = $q;
        $this->resetPage();
    }

    public function filterBy(string $filter): void
    {
        //$this->filter = $filter;
        if ($filter === 'all') {
            $this->filters = ['all'];
        } else {
            // check if the filter is in the array, if it's not in the array and add it, if is in the array remove it
            if (($key = array_search($filter, $this->filters)) !== false) {
                unset($this->filters[$key]);
            } else {
                $this->filters = array_merge($this->filters, [$filter]);
            }
            // if the array is empty, add the all filter
            if (empty($this->filters)) {
                $this->filters = ['all'];
            }
        }

        $this->resetPage();
    }
}
