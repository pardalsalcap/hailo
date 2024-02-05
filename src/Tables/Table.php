<?php

namespace Pardalsalcap\Hailo\Tables;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class Table
{
    protected array $schema = [];

    protected string $title = '';

    protected string $no_records_found = '';

    protected string $name = '';

    protected bool $livewire = true;

    public ?Model $model = null;

    protected int $perPage = 10;

    protected $data = [];

    protected bool $hasEditAction = true;

    protected bool $hasDeleteAction = false;

    protected array $configuration = [];

    protected string $q = '';

    protected $extraFields = [];

    protected array $paginationAppends = [];

    protected string $sortField = 'id';

    protected string $sortDirection = 'ASC';

    protected array $filters = [];

    protected string $currentFilter = 'all';

    protected array $relations = [];

    public function __construct(string $name, Model $model)
    {
        $this->name = $name;
        $this->model = $model;
    }

    public static function make(string $name, Model $model): self
    {
        return new static($name, $model);
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function noRecordsFound(string $no_records_found): self
    {
        $this->no_records_found = $no_records_found;

        return $this;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function schema(array $schema): self
    {
        $this->schema = $schema;

        return $this;
    }

    public function livewire(bool $livewire): self
    {
        $this->livewire = $livewire;

        return $this;
    }

    public function perPage(int $perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function hasEditAction(bool $hasEditAction): self
    {
        $this->hasEditAction = $hasEditAction;

        return $this;
    }

    public function hasDeleteAction(bool $hasDeleteAction): self
    {
        $this->hasDeleteAction = $hasDeleteAction;

        return $this;
    }

    public function sortBy(string $field): self
    {
        $this->sortField = $field;

        return $this;
    }

    public function sortDirection(string $direction): self
    {
        $this->sortDirection = $direction;

        return $this;
    }

    public function relations(array $relations): self
    {
        $this->relations = $relations;

        return $this;
    }

    public function paginationAppends(array $appends): self
    {
        $this->paginationAppends = $appends;

        return $this;
    }

    public function addFilter($name, Closure $filter, $label): self
    {
        $this->filters[$name] = [
            'name' => $name,
            'filter' => $filter,
            'label' => $label,
        ];

        return $this;
    }

    public function filterBy(string $filter): self
    {
        $this->currentFilter = $filter;

        return $this;
    }

    public function getSchema(): array
    {
        return $this->schema;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getNoRecordsFound(): string
    {
        if (empty($this->no_records_found)) {
            $this->no_records_found = __('hailo::hailo.no_records_found');
        }

        return $this->no_records_found;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLivewire(): bool
    {
        return $this->livewire;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getHasEditAction(): bool
    {
        return $this->hasEditAction;
    }

    public function getHasDeleteAction(): bool
    {
        return $this->hasDeleteAction;
    }

    public function getData(): array|LengthAwarePaginator
    {
        return $this->data;
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function getSortField(): string
    {
        return $this->sortField;
    }

    public function getSortDirection(): string
    {
        return $this->sortDirection;
    }

    public function getRelations(): array
    {
        return $this->relations;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function getCurrentFilter(): string
    {
        return $this->currentFilter;
    }

    public function hasFilters(): bool
    {
        return count($this->filters) > 0;
    }

    public function getPaginationAppends(): array
    {
        return $this->paginationAppends;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'livewire' => $this->livewire,
            'model' => $this->model,
            'schema' => $this->schemaToArray(),
        ];
    }

    protected function schemaToArray(): array
    {
        $schema = [];
        foreach ($this->schema as $field) {
            $schema[] = $field->toArray();
        }

        return $schema;
    }

    public function extractHeadings(): array
    {
        $headings = [];
        foreach ($this->schema as $field) {
            $headings[$field->getName()] = [
                'label' => $field->getLabel(),
                'name' => $field->getName(),
                'sortable' => $field->isSortable(),
            ];
        }

        return $headings;
    }

    public function fields()
    {
        $fields = [];
        foreach ($this->schema as $field) {
            if (! $field->isRelation()) {
                $fields[] = $field->getName();
            }
        }
        if (! in_array('id', $fields)) {
            array_unshift($fields, 'id');
        }
        $fields = array_merge($fields, $this->extraFields);

        return $fields;
    }

    public function query()
    {
        return $this->model->query();
    }

    public function executeQuery(): self
    {
        $this->data = $this->query()
            ->when(! empty($this->q), function ($query) {
                foreach ($this->getSchema() as $field) {
                    if ($field->isSearchable()) {
                        $query->orWhere($field->getName(), 'like', '%'.$this->q.'%');
                    }
                }
            })
            ->when(! empty($this->relations), function ($query) {
                $query->with($this->relations);
            })
            ->when(! empty($this->currentFilter) and $this->currentFilter !== 'all', function ($query) {
                $query->where($this->filters[$this->currentFilter]['filter']);
            })
            ->select($this->fields())
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return $this;
    }

    public function extractCss(): void
    {
        foreach ($this->schema as $field) {
            $this->configuration['css'][$field->getName()] = $field->getCss();
        }
    }

    public function getColumnCss($column_name): string
    {
        return $this->configuration['css'][$column_name] ?? '';
    }

    public function extraField($field): self
    {
        $this->extraFields[] = $field;

        return $this;
    }

    public function search($q): self
    {
        if (! empty($q)) {
            $this->q = $q;
        }

        return $this;
    }

    public function resolveSortDirection($field): string
    {
        if ($this->sortField === $field) {
            return $this->sortDirection === 'ASC' ? 'DESC' : 'ASC';
        }

        return 'ASC';
    }

    public function resolveSortIcon($field): string
    {
        if ($this->sortField === $field) {
            return $this->sortDirection === 'ASC' ? 'sort-desc' : 'sort-asc';
        }

        return 'sort-asc';
    }

    public function resolveSortCss($field): string
    {
        if ($this->sortField === $field) {
            return 'text-hailo-600';
        }

        return 'text-gray-400';
    }
}
