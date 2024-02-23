<?php

namespace Pardalsalcap\Hailo\Forms;

class Section
{
    protected array $schema = [];

    protected string $name = '';

    protected string $title = '';

    protected string $help = '';

    protected int $columns = 1;

    protected int $colSpan = 1;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function make(string $name): self
    {
        return new static($name);
    }

    // Instance method for setting navigation
    public function schema(array $schema): self
    {
        $this->schema = $schema;

        return $this;
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function help(string $help): self
    {
        $this->help = $help;

        return $this;
    }

    public function columns(int $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    public function colSpan(int $colSpan): self
    {
        $this->colSpan = $colSpan;

        return $this;
    }

    public function getSchema(): array
    {
        return $this->schema;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getHelp(): string
    {
        return $this->help;
    }

    public function getColumns(): int
    {
        return $this->columns;
    }

    public function getColSpan(): int
    {
        return $this->colSpan;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'title' => $this->title,
            'help' => $this->help,
            'columns' => $this->columns,
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
