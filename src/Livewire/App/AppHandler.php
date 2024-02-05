<?php

namespace Pardalsalcap\Hailo\Livewire\App;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\WithPagination;
use Pardalsalcap\Hailo\Forms\Form;
use Pardalsalcap\Hailo\Tables\Table;

class AppHandler extends Component
{
    use WithPagination;

    protected Table $table;

    protected Form $form;

    public array $tableData = [];

    public array $formData = [];

    public ?Model $model = null;

    public array $models = [
        'table' => null,
        'form' => null,
    ];

    public string $tableSortField = 'id';

    public string $tableSortDirection = 'ASC';

    public array $paginationAppends = [];

    public string $filter = 'all';

    protected function queryString()
    {
        return [
            'tableSortField' => ['except' => 'id', 'as' => 'sort_by'],
            'tableSortDirection' => ['except' => ['ASC', 'null'], 'as' => 'sort_direction'],
            'q' => ['except' => ''],
            'filter' => ['except' => ['all', '']],
        ];
    }

    public function mount()
    {
        $this->tableSortField = request()->get('sort_by', $this->tableSortField);
        $this->tableSortDirection = request()->get('sort_direction', $this->tableSortDirection);
    }

    protected function processFormElements(array $elements): void
    {
        foreach ($elements as $element) {
            if ($element instanceof \Pardalsalcap\Hailo\Forms\Section) {
                $this->processFormElements($element->getSchema());
            } elseif ($element instanceof \Pardalsalcap\Hailo\Forms\Fields\FormField) {

                if (empty($element->getValue()) and isset($this->models['form']->{$element->getName()}) and $element->getType() !== 'password') {
                    $element->value($this->models['form']->{$element->getName()});
                    $this->formData[$element->getName()] = $this->models['form']->{$element->getName()} ?? '';
                } else {
                    $this->formData[$element->getName()] = $element->getDefault() ?? '';
                }

            }
        }
    }

    public function validationRules($form): array
    {
        $rules = [];
        foreach ($form->getSchema() as $element) {
            if ($element instanceof \Pardalsalcap\Hailo\Forms\Section) {
                $rules = array_merge($rules, $this->validationRules($element));
            } elseif ($element instanceof \Pardalsalcap\Hailo\Forms\Fields\FormField) {
                $rules['formData.'.$element->getName()] = $element->getRules();
            }
        }

        return $rules;
    }

    public function sort($field, $direction)
    {
        $this->tableSortField = $field;
        $this->tableSortDirection = $direction;
        $this->resetPage();
    }

    public function search($q)
    {
        $this->q = $q;
        $this->resetPage();
    }

    public function getPaginationAppends(): array
    {
        return [
            'sort_by' => $this->tableSortField,
            'sort_direction' => $this->tableSortDirection,
        ];
    }

    public function filterBy(string $filter): void
    {
        $this->filter = $filter;
        debug($this->filter.' IN FILTER BY');
        $this->resetPage();
    }
}
