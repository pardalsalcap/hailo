<?php

namespace Pardalsalcap\Hailo\Livewire\Medias;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Intervention\Image\Laravel\Facades\Image;
use Livewire\Component;
use Pardalsalcap\Hailo\Models\Media;
use Pardalsalcap\Hailo\Repositories\MediaRepository;
use Pardalsalcap\Hailo\Tables\Traits\HasTables;

class SelectMediaApp extends Component
{
    use HasTables;

    public bool $show = false;

    public string $input = '';

    public int $media_id;

    public array $selected = [];

    public string $form;

    public string $type;

    public string $mode;

    protected MediaRepository $repository;

    protected $listeners = [
        'selectMedia' => 'init',
    ];

    public function mount(): void
    {
        $this->repository = new MediaRepository();
    }

    public function hydrate(): void
    {
        $this->repository = new MediaRepository();
    }

    public function init(string $type, string $input, string $form, string $mode, int|array|string $current): void
    {
        $this->show = true;
        $this->input = $input;
        $this->form = $form;
        $this->type = $type;
        $this->mode = $mode;

        if ($this->mode === 'single') {
            $this->selected = [(int) $current];
        } else {
            $this->selected = $current;
        }
        //{type: 'image', input: '{{ $input->getName() }}', form: '{{ $form->getName() }}', mode: 'single'}
        //dd($type, $input, $form, $mode, $current, $this->selected);
    }

    public function select(int $media_id)
    {
        if ($this->mode === 'single') {
            $this->selected = [$media_id];
        } else {
            if (in_array($media_id, $this->selected)) {
                $this->selected = array_diff($this->selected, [$media_id]);
            } else {
                $this->selected[] = $media_id;
            }
        }
    }

    public function end()
    {
        $this->dispatch('selectionMediaEnded', ['selected' => $this->selected, 'input' => $this->input, 'form' => $this->form, 'type' => $this->type, 'mode' => $this->mode]);
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
        if ($this->show) {
            if ($this->type == 'image') {
                $table = $this->table('medias_table', (new MediaRepository())->table(new Media()))
                    ->sortBy($this->sort_by)
                    ->sortDirection($this->sort_direction)
                    ->search($this->q)
                    ->filterBy('images_only')
                    ->perPage(16)
                    ->executeQuery();
            } elseif ($this->type == 'download') {
                $table = $this->table('medias_table', (new MediaRepository())->table(new Media()))
                    ->sortBy($this->sort_by)
                    ->sortDirection($this->sort_direction)
                    ->search($this->q)
                    ->filterBy('downloads_only')
                    ->perPage(16)
                    ->executeQuery();
            } else {
                $table = $this->table('medias_table', (new MediaRepository())->table(new Media()))
                    ->sortBy($this->sort_by)
                    ->sortDirection($this->sort_direction)
                    ->search($this->q)
                    ->perPage(8)
                    ->executeQuery();
            }

        }

        return view('hailo::livewire.medias.select', [
            'medias_table' => $table,
        ]);
    }
}
