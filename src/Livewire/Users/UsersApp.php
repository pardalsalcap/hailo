<?php

namespace Pardalsalcap\Hailo\Livewire\Users;

use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Pardalsalcap\Hailo\Actions\Users\DestroyUser;
use Pardalsalcap\Hailo\Actions\Users\StoreUser;
use Pardalsalcap\Hailo\Actions\Users\UpdateUser;
use Pardalsalcap\Hailo\Forms\Traits\HasForms;
use Pardalsalcap\Hailo\Repositories\UserRepository;
use Pardalsalcap\Hailo\Tables\Traits\CanDelete;
use Pardalsalcap\Hailo\Tables\Traits\HasActions;
use Pardalsalcap\Hailo\Tables\Traits\HasTables;
use Throwable;

class UsersApp extends Component
{
    use CanDelete, HasActions, HasForms, HasTables;

    protected UserRepository $repository;

    public string $user_form_title = '';

    protected $listeners = [
        'searchUpdated' => 'search',
        'destroyUser' => 'destroy',
    ];

    protected $queryString = [
        'sort_by' => ['except' => 'id', 'as' => 'sort_by'],
        'sort_direction' => ['except' => ['ASC', 'null'], 'as' => 'sort_direction'],
        'q' => ['except' => ''],
        'filter' => ['except' => 'all'],
    ];

    public function mount(): void
    {
        $this->deleting_configuration = [
            'title' => __('hailo::users.confirm_delete_title'),
            'text' => __('hailo::users.confirm_delete_text'),
            'confirmButtonText' => __('hailo::hailo.confirm_yes'),
            'cancelButtonText' => __('hailo::hailo.confirm_no'),
            'livewireAction' => 'destroyUser',
        ];

        $this->user_form_title = __('hailo::users.user_form_title');
        $this->repository = new UserRepository();
        $this->loadForms();
    }

    public function loadForms()
    {
        $this->form($this->repository->form($this->loadModel()))
            ->action($this->action == 'edit' ? 'update' : 'store')
            ->title($this->user_form_title);
    }

    public function hydrate()
    {
        $this->repository = new UserRepository();
        $this->loadForms();
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

    public function destroy($id): void
    {
        try {
            DestroyUser::run($this->deleting_id);
            $this->dispatch('deletedUser', ['id' => $this->deleting_id]);
            $this->dispatch('toast-success', ['title' => __('hailo::users.deleted')]);
            $this->deleting_id = null;
        } catch (Exception $e) {
            $this->dispatch('toast-error', ['title' => __('hailo::users.not_deleted').':<br /> '.$e->getMessage()]);
        }
    }

    public function edit($id): void
    {
        $this->action = 'edit';
        $this->register_id = $id;
        $this->loadForms();
    }

    public function cancel(): void
    {
        $this->action = 'index';
        $this->user_form_title = __('hailo::users.user_form_title');
        $this->loadForms();
    }

    public function store(): void
    {
        try {
            DB::beginTransaction();
            $this->load = false;
            $form = $this->form($this->repository->form($this->loadModel()));
            $this->validate($this->validationRules($form));
            StoreUser::run($this->getFormData($form->getName()));
            $this->cancel();
            $this->dispatch('toast-success', ['title' => __('hailo::users.saved')]);
            $this->load = true;
            DB::commit();

        } catch (Throwable $e) {
            DB::rollBack();
            $this->handleFormException($e, $form->getName(), __('hailo::users.not_saved'));
        }
    }

    public function update(): void
    {
        try {
            DB::beginTransaction();
            $this->load = false;
            $user = $this->loadModel();
            $form = $this->form($this->repository->form($user));
            $this->validate($this->validationRules($form));
            UpdateUser::run($this->getFormData($form->getName()), $user);
            $this->cancel();
            $this->dispatch('toast-success', ['title' => __('hailo::users.saved')]);
            $this->load = true;
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            $this->handleFormException($e, $form?->getName(), __('hailo::users.not_saved'));
        }
    }

    public function loadModel(): Model
    {
        if ($this->action == 'edit') {
            $user = config('hailo.users_model')::find($this->register_id);
            if (! $user) {
                $this->cancel();
                $this->dispatch('toast-error', ['title' => __('hailo::users.not_found')]);
            } else {
                $this->user_form_title = __('hailo::users.user_form_title_edit', ['u' => $user->name]);

                return $user;
            }
        }
        $class = config('hailo.users_model');

        return new $class;

    }

    public function search($q)
    {
        $this->q = $q;
        $this->cancel();
        $this->resetPage();
    }

    public function render(): View|Factory
    {
        $class = config('hailo.users_model');
        $this->table('users_table', $this->repository->table(new $class()))
            ->sortBy($this->sort_by)
            ->sortDirection($this->sort_direction)
            ->search($this->q)
            ->filterBy($this->filter)
            ->executeQuery();

        $this->processFormElements($this->getForm('user_form'), $this->getForm('user_form')->getSchema());

        return view('hailo::livewire.permissions.users', [
            'users_table' => $this->getTable('users_table'),
            'user_form' => $this->getForm('user_form'),
            'validation_errors' => $this->getValidationErrors(),
        ])
            ->layout('hailo::layouts.main')
            ->title(__('hailo::users.html_title', ['name' => config('app.name')]));
    }
}
