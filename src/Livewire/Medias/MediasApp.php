<?php

namespace Pardalsalcap\Hailo\Livewire\Medias;

use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Pardalsalcap\Hailo\Actions\Medias\DestroyMedia;
use Pardalsalcap\Hailo\Actions\Medias\UpdateMedia;
use Pardalsalcap\Hailo\Actions\Roles\DestroyRole;
use Pardalsalcap\Hailo\Actions\Roles\StoreRole;
use Pardalsalcap\Hailo\Actions\Roles\UpdateRole;
use Pardalsalcap\Hailo\Forms\Traits\HasForms;
use Pardalsalcap\Hailo\Models\Media;
use Pardalsalcap\Hailo\Repositories\MediaRepository;
use Pardalsalcap\Hailo\Tables\Traits\CanDelete;
use Pardalsalcap\Hailo\Tables\Traits\HasActions;
use Pardalsalcap\Hailo\Tables\Traits\HasTables;
use Spatie\Permission\Models\Role;
use Throwable;

class MediasApp extends Component
{
    use CanDelete, HasActions, HasForms, HasTables;

    public string $medias_form_title = '';

    protected MediaRepository $repository;

    protected $listeners = [
        'searchUpdated' => 'search',
        'destroyMedia' => 'destroy',
        'addedMedia' => '$refresh',
        'cropEnded' => '$refresh',
    ];

    protected $queryString = [
        'sort_by' => ['except' => 'id', 'as' => 'sort_by'],
        'sort_direction' => ['except' => ['ASC', 'null'], 'as' => 'sort_direction'],
        'q' => ['except' => ''],
        'register_id'=> ['except' => [null, 'null']],
        'action' => ['except' => 'index'],
    ];

    public function mount(): void
    {
        $this->deleting_configuration = [
            'title' => __('hailo::medias.confirm_delete_title'),
            'text' => __('hailo::medias.confirm_delete_text'),
            'confirmButtonText' => __('hailo::hailo.confirm_yes'),
            'cancelButtonText' => __('hailo::hailo.confirm_no'),
            'livewireAction' => 'destroyMedia',
        ];

        $this->sort_direction = 'DESC';

        $this->repository = new MediaRepository();

        $this->medias_form_title = __('hailo::medias.media_form_title');
    }

    public function hydrate(): void
    {
        $this->repository = new MediaRepository();
    }

    public function getPaginationAppends(): array
    {
        return [
            'q' => $this->q,
            'action' => 'index',
            'sort_by' => $this->sort_by,
            'sort_direction' => $this->sort_direction,
        ];
    }

    public function destroy(): void
    {
        try {
            DestroyMedia::run($this->deleting_id);
            $this->dispatch('deletedRole', ['id' => $this->deleting_id]);
            $this->dispatch('toast-success', ['title' => __('hailo::medias.deleted')]);
            $this->deleting_id = null;
        } catch (Exception $e) {
            $this->dispatch('toast-error', ['title' => __('hailo::medias.not_deleted').':<br /> '.$e->getMessage()]);
        }
    }

    public function edit($id): void
    {
        $this->action = 'edit';
        $this->register_id = $id;
    }

    public function cancel(): void
    {
        $this->action = 'index';
        $this->load = true;
        $this->medias_form_title = __('hailo::medias.media_form_title');
    }

    public function update(): void
    {
        try {
            DB::beginTransaction();
            $this->load = false;
            $media = $this->loadModel();
            $form = $this->form($this->repository->form($media));
            //dd($this->formData, $this->validationRules($form));
            $this->validate($this->validationRules($form));
            UpdateMedia::run($this->getFormData($form->getName()), $media);
            $this->success();
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            if (isset($form)) {
                $this->handleFormException($e, $form->getName(), __('hailo::medias.not_saved'));
            } else {
                $this->dispatch('toast-error', ['title' => __('hailo::medias.not_saved')]);
            }
        }
    }

    public function success(): void
    {
        $this->cancel();
        $this->dispatch('toast-success', ['title' => __('hailo::medias.saved')]);
    }

    public function loadModel(): Model
    {
        if ($this->action == 'edit') {
            $media = Media::find($this->register_id);
            if (! $media) {
                $this->cancel();
                $this->dispatch('toast-error', ['title' => __('hailo::medias.not_found')]);
            } else {
                $this->medias_form_title = __('hailo::medias.media_form_title_edit', ['u' => $media->title? $media->title : $media->id]);

                return $media;
            }
        }

        return new Media();
    }

    public function search($q): void
    {
        $this->q = $q;
        $this->cancel();
        $this->resetPage();
    }

    public function crop ($media_id, $version): void
    {
        $this->dispatch('initCrop', ["media_id"=>$media_id, 'version'=>$version ]);
    }

    public function render(): View|Factory
    {
        $this->table('medias_table', $this->repository->table(new Media()))
            ->sortBy($this->sort_by)
            ->sortDirection($this->sort_direction)
            ->search($this->q)
            ->filterBy($this->filter)
            ->perPage(8)
            ->executeQuery();

        $form = $this->form($this->repository->form($this->loadModel()))
            ->action($this->action == 'edit' ? 'update' : 'store')
            ->title($this->medias_form_title);

        $this->processFormElements($form, $form->getSchema());
        //if ($this->action == 'edit') {dd($this->getFormData($form->getName()), $form->getModel());}
        return view('hailo::livewire.medias.app', [
            'medias_table' => $this->getTable('medias_table'),
            'medias_form' => $form,
            'validation_errors' => $this->getValidationErrors(),
        ])
            ->layout('hailo::layouts.main')
            ->title(__('hailo::medias.html_title', ['name' => config('app.name')]));
    }
}
