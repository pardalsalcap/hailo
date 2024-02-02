<?php

namespace Pardalsalcap\Hailo\Forms;

use Illuminate\Database\Eloquent\Model;

class Form
{
    protected array $schema = [];

    protected string $title = '';

    protected string $name = '';

    protected string $action = '';

    protected string $method = 'POST';

    protected bool $livewire = false;

    protected string $button = 'Save';

    public ?Model $model = null;

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

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getModel ()
    {
        return $this->model;
    }

    public function schema(array $schema): self
    {
        $this->schema = $schema;

        return $this;
    }

    public function action(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function method(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function livewire(bool $livewire): self
    {
        $this->livewire = $livewire;

        return $this;
    }

    public function button(string $button): self
    {
        $this->button = $button;

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

    public function getName(): string
    {
        return $this->name;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getLivewire(): bool
    {
        return $this->livewire;
    }

    public function getButton(): string
    {
        return $this->button;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'action' => $this->action,
            'method' => $this->method,
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
}
