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

    public function table($name, Table $table): Table
    {
        $this->tables[$name] = $table;
        return $this->getTable($name);
    }

    public function getTable($name): Table
    {
        return $this->tables[$name];
    }

    public function sort ($field, $direction)
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
        $this->filter = $filter;
        $this->resetPage();
    }
}
