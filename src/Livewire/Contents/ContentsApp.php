<?php

namespace Pardalsalcap\Hailo\Livewire\Contents;

use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Pardalsalcap\Hailo\Actions\Contents\DestroyContent;
use Pardalsalcap\Hailo\Actions\Contents\StoreContent;
use Pardalsalcap\Hailo\Actions\Contents\StoreContentMetas;
use Pardalsalcap\Hailo\Actions\Contents\StoreContentRelations;
use Pardalsalcap\Hailo\Actions\Contents\UpdateContent;
use Pardalsalcap\Hailo\Forms\Form;
use Pardalsalcap\Hailo\Forms\Traits\HasForms;
use Pardalsalcap\Hailo\Models\Content;
use Pardalsalcap\Hailo\Repositories\ContentRepository;
use Pardalsalcap\Hailo\Tables\Traits\CanDelete;
use Pardalsalcap\Hailo\Tables\Traits\HasActions;
use Pardalsalcap\Hailo\Tables\Traits\HasTables;
use Throwable;

class ContentsApp extends Component
{
    use CanDelete, HasActions, HasForms, HasTables;

    public string $form_title = '';

    public string $content_type = 'page';

    public array $metas = [
        'meta_title' => 'title',
    ];

    protected ContentRepository $repository;

    protected $listeners = [
        'searchUpdated' => 'search',
        'destroyContent' => 'destroy',
        'languageChanged' => '$refresh',
        'addedMedia' => 'addMedia',
        'selectionMediaEnded' => 'selectedMedia',
        'selectionContentEnded' => 'selectedContent',
    ];

    protected $queryString = [
        'sort_by' => ['except' => 'id'],
        'sort_direction' => ['except' => ['ASC', 'null']],
        'q' => ['except' => ''],
        'action' => ['except' => 'index'],
        'content_type' => ['except' => 'page', 'as' => 'type'],
        'register_id' => ['except' => [null, 'null']],
        'filter' => ['except' => 'all'],
    ];

    public function mount(): void
    {
        $this->deleting_configuration = [
            'title' => __('hailo::content.confirm_delete_title'),
            'text' => __('hailo::content.confirm_delete_text'),
            'confirmButtonText' => __('hailo::hailo.confirm_yes'),
            'cancelButtonText' => __('hailo::hailo.confirm_no'),
            'livewireAction' => 'destroyContent',
        ];

        $this->sort_direction = 'DESC';
        $this->repository = new ContentRepository();
        $this->form_title = __('hailo::content.form_title');
    }

    public function hydrate(): void
    {
        $this->repository = new ContentRepository();
        if ($this->action == 'create' or $this->action == 'edit') {
            $this->load = false;
        }
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
            DestroyContent::run($this->deleting_id);
            $this->dispatch('deletedContent', ['id' => $this->deleting_id]);
            $this->dispatch('toast-success', ['title' => __('hailo::content.deleted')]);
            $this->deleting_id = null;
        } catch (Exception $e) {
            $this->dispatch('toast-error', ['title' => __('hailo::content.not_deleted') . ':<br /> ' . $e->getMessage()]);
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
        $this->content_type = 'page';
        $this->form_title = __('hailo::content.form_title');
    }

    public function store(): void
    {
        try {
            DB::beginTransaction();
            $this->load = false;
            $form = $this->form($this->repository->form($this->loadModel()));
            $this->cleaup($form);
            $this->validate($this->validationRules($form));
            $content = StoreContent::run($this->getFormData($form->getName()));
            StoreContentMetas::run($content, $this->prepareContentMetas($this->getFormData($form->getName())));
            StoreContentRelations::run($content, $this->relationalFields($form), $this->getFormData($form->getName()));
            $this->success();
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            if (isset($form)) {
                $this->handleFormException($e, $form->getName(), __('hailo::content.not_saved'));
            } else {
                $this->dispatch('toast-error', ['title' => __('hailo::content.not_saved')]);
            }
        }
    }

    public function update(): void
    {
        try {
            DB::beginTransaction();
            $this->load = false;
            $content = $this->loadModel();
            $form = $this->form($this->repository->form($content));

            $this->cleaup($form);
            $this->validate($this->validationRules($form));
            $content = UpdateContent::run($this->getFormData($form->getName()), $content);
            StoreContentMetas::run($content, $this->prepareContentMetas($this->getFormData($form->getName())));
            StoreContentRelations::run($content, $this->relationalFields($form), $this->getFormData($form->getName()));
            $this->success();
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            if (isset($form)) {
                $this->handleFormException($e, $form->getName(), __('hailo::content.not_saved'));
            } else {
                $this->dispatch('toast-error', ['title' => __('hailo::content.not_saved')]);
            }
        }
    }

    public function cleaup($form): void
    {
        $data = $this->getFormData($form->getName());
        if (isset($data['seo_url'])) {
            $data['seo_url'] = str_replace(config('app.url'), "", (new Content())->setUrl($data));
            $this->addFormData($form->getName(), 'seo_url', $data['seo_url']);
        }
        if (isset($data['parent_id']) and empty($data['parent_id'])) {
            $this->addFormData($form->getName(), 'parent_id', null);
        }
    }

    public function prepareContentMetas($values): array
    {
        $result = [];
        foreach ($this->metas as $key => $value) {
            $result[$value] = $values[$key] ?? '';
        }
        return $result;
    }

    public function success(): void
    {
        $this->cancel();
        $this->dispatch('toast-success', ['title' => __('hailo::content.saved')]);
    }

    public function updateMediaOrder ($data)
    {
        $groups = [];
        foreach ($data as $group)
        {
            $groups[$group['value']] = [];
            if (isset($group['items']))
            {
                foreach ($group['items'] as $item)
                {
                    $groups[$group['value']][] = $item['value'];
                }
            }
        }

        $form = $this->form($this->repository->form($this->loadModel()));

        foreach ($groups as $key => $value)
        {
            $this->addFormData($form->getName(), $key, $value);
        }
    }

    public function loadModel(): Model
    {
        if ($this->action == 'edit') {
            $relations = collect($this->relationalFields($this->repository->form(new Content())));
            $relations = $relations->pluck("relation")->push('contentMetas');

            $content = Content::with($relations->toArray())->find($this->register_id);
            if (!$content) {
                $this->cancel();
                $this->dispatch('toast-error', ['title' => __('hailo::content.not_found')]);
            } else {
                $this->form_title = __('hailo::content.form_title_edit', ['c' => $content->title ? $content->title : $content->id]);

                return $content;
            }
        }

        return new Content();
    }

    public function toggle($field, $form)
    {
        $current_value = $this->getFormDataField($form, $field);
        $this->addFormData($form, $field, !$current_value);
    }

    public function addMedia($media_ids, $input, $form, $mode)
    {
        //dd($media_ids, $input, $form, $mode);
        if ($mode == 'single') {
            $this->addFormData($form, $input, $media_ids[0]['id']);
        } elseif ($mode == 'multiple') {
            $current = $this->getFormDataField($form, $input);
            if (empty($current)) {
                $current = [];
            }
            foreach ($media_ids as $media_id) {
                $current[] = $media_id['id'];
            }
            $this->addFormData($form, $input, $current);
        }
    }

    public function selectedMedia(array $data)
    {
        if ($data['mode'] == 'single') {
            $this->addFormData($data['form'], $data['input'], $data['selected'][0]);
        }
        elseif ($data['mode']=='multiple')
        {
            $current = [];
            foreach ($data['selected'] as $media_id) {
                $current[] = $media_id;
            }
            $this->addFormData($data['form'], $data['input'], $current);
        }
        //dd($data, $this->formData);
        //'selected' => $this->selected, 'input' => $this->input, 'form' => $this->form, 'type' => $this->type, 'mode' => $this->mode
    }

    public function selectedContent(array $data)
    {
        if ($data['mode'] == 'single') {
            $this->addFormData($data['form'], $data['input'], $data['selected'][0]);
        }
        elseif ($data['mode']=='multiple')
        {
            $current = [];
            foreach ($data['selected'] as $content_id) {
                $current[] = $content_id;
            }
            $this->addFormData($data['form'], $data['input'], $current);
        }
        //dd($data, $this->formData);
        //'selected' => $this->selected, 'input' => $this->input, 'form' => $this->form, 'type' => $this->type, 'mode' => $this->mode
    }

    public function removeMedia($id, $input, $form, $mode)
    {
        if ($mode=='single')
        {
            $this->addFormData($form, $input, null);
        }
        elseif ($mode == 'multiple')
        {
            $current = $this->getFormDataField($form, $input);
            if (empty($current)) {
                $current = [];
            }
            if (($key = array_search($id, $current)) !== false) {
                unset($current[$key]);
            }
            $this->addFormData($form, $input, $current);
        }
    }

    public function removeContent($id, $input, $form, $mode)
    {
        if ($mode=='single')
        {
            $this->addFormData($form, $input, null);
        }
        elseif ($mode == 'multiple')
        {
            $current = $this->getFormDataField($form, $input);
            if (empty($current)) {
                $current = [];
            }
            if (($key = array_search($id, $current)) !== false) {
                unset($current[$key]);
            }
            $this->addFormData($form, $input, $current);
        }
    }



    public function search($q): void
    {
        $this->q = $q;
        $this->cancel();
        $this->resetPage();
    }

    public function render(): View|Factory
    {

        $this->table('contents_table', $this->repository->table(new Content()))
            ->sortBy($this->sort_by)
            ->sortDirection($this->sort_direction)
            ->search($this->q)
            ->filterBy($this->filter)
            ->perPage(8)
            ->executeQuery();


        $form = $this->form($this->repository->form($this->loadModel()))
            ->action($this->action == 'edit' ? 'update' : 'store')
            ->title($this->form_title);

        $this->processFormElements($form, $form->getSchema());
        if ($this->load and count($this->metas) > 0) {
            foreach ($this->metas as $key => $value) {
                $this->addFormData($form->getName(), $key, $form->getModel()->contentMetas->where('key', $value)->first()?->value);
            }
        }

        if ($this->action == 'create') {
            $this->addFormData($form->getName(), 'type', $this->content_type);
        }

        if ($this->action == 'edit' or $this->action == 'create') {
            $check = $this->getFormData($form->getName());
            if (isset($check['seo_url'])) {
                if (!empty($check['seo_slug'])) {
                    $this->addFormData($form->getName(), 'seo_slug', Str::slug($check['seo_slug']));
                    $check = $this->getFormData($form->getName());
                }
                $this->addFormData($form->getName(), 'seo_url', (new Content())->setUrl($check));
            }
        }
        //dd($this->formData);

        return view('hailo::livewire.contents.app', [
            'contents_table' => $this->getTable('contents_table'),
            'contents_form' => $form,
            'validation_errors' => $this->getValidationErrors(),
        ])
            ->layout('hailo::layouts.main')
            ->title(__('hailo::content.html_title', ['name' => config('app.name')]));
    }
}
