<?php

namespace Pardalsalcap\Hailo\Livewire\Contents;

use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

use Pardalsalcap\Hailo\Models\Content;
use Pardalsalcap\Hailo\Models\Media;
use Pardalsalcap\Hailo\Repositories\ContentRepository;
use Pardalsalcap\Hailo\Repositories\MediaRepository;
use Pardalsalcap\Hailo\Tables\Traits\HasTables;

class SelectContentApp extends Component
{
    use HasTables;

    public bool $show = false;
    public string $input = '';
    public int $content_id;
    public array $selected = [];
    public string $form;
    public string $type;
    public string $mode;
    protected ContentRepository $repository;

    protected $listeners = [
        'selectContent' => 'init',
    ];

    public function mount(): void
    {
        $this->repository = new ContentRepository();
    }

    public function hydrate(): void
    {
        $this->repository = new ContentRepository();
    }

    public function init(string $type, string $input, string $form, string $mode, int|array|string $current): void
    {
        //dd($type, $input, $form, $mode, $current);
        $this->show = true;
        $this->input = $input;
        $this->form = $form;
        $this->type = $type;
        $this->mode = $mode;

        if ($this->mode === 'single'){
            $this->selected = [(int)$current];
        }
        else{
            $this->selected = $current;
        }
        //{type: 'image', input: '{{ $input->getName() }}', form: '{{ $form->getName() }}', mode: 'single'}
        //dd($type, $input, $form, $mode, $current, $this->selected);
    }

    public function select (int $content_id)
    {
        if($this->mode === 'single'){
            $this->selected = [$content_id];
        }
        else{
            if (in_array($content_id, $this->selected)){
                $this->selected = array_diff($this->selected, [$content_id]);
            }
            else{
                $this->selected[] = $content_id;
            }
        }
    }

    public function end()
    {
        $this->dispatch('selectionContentEnded', ['selected' => $this->selected, 'input' => $this->input, 'form' => $this->form, 'type' => $this->type, 'mode' => $this->mode]);
        $this->close();

    }

    public function close()
    {
        $this->show = false;

    }

    public function cancel(): void
    {
        $this->show = false;
    }

    public function render(): View|Factory
    {
        $table = null;
        if ($this->show)
        {

            if (!empty($this->type))
            {
                $table = $this->table('contents_table', (new ContentRepository())->table(new Content()))
                    ->sortBy($this->sort_by)
                    ->sortDirection($this->sort_direction)
                    ->search($this->q)
                    ->filterBy('filter_'.$this->type)
                    ->perPage(16)
                    ->executeQuery();
            }
            else
            {
                $table = $this->table('contents_table', (new ContentRepository())->table(new Content()))
                    ->sortBy($this->sort_by)
                    ->sortDirection($this->sort_direction)
                    ->search($this->q)
                    ->perPage(16)
                    ->executeQuery();
            }

        }

        return view('hailo::livewire.contents.select', [
            "contents_table" => $table,
        ]);
    }
}
