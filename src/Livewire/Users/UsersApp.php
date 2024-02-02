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
    use HasTables, HasActions, CanDelete, HasForms;

    public string $user_form_title = "";

    protected $listeners = [
        'searchUpdated' => 'search',
        'destroyUser' => 'destroy',
    ];

    protected $queryString = [
        'sort_by' => ['except' => 'id', 'as' => 'sort_by'],
        'sort_direction' => ['except' => ['ASC', "null"], 'as' => 'sort_direction'],
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
            $this->dispatch('toast-error', ['title' => __('hailo::users.not_deleted') . ':<br /> ' . $e->getMessage()]);
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
        $this->user_form_title = __('hailo::users.user_form_title');
    }

    public function store(): void
    {
        try {
            DB::beginTransaction();
            $this->load = false;
            $user = $this->loadModel();
            $this->form(UserRepository::form($user));
            $this->validate($this->validationRules($this->getForm('user_form')));
            StoreUser::run($this->getFormData('user_form'));
            $this->cancel();
            $this->dispatch('toast-success', ['title' => __('hailo::users.saved')]);
            $this->load = true;
            DB::commit();

        } catch (Throwable $e) {
            DB::rollBack();
            $this->addValidationErrors('user_form', $e->errors());
            $this->clearValidation();
        }
    }

    public function update(): void
    {
        try {
            DB::beginTransaction();
            $this->load = false;
            $user = $this->loadModel();
            $this->form(UserRepository::form($user));
            $this->validate($this->validationRules($this->getForm('user_form')));
            UpdateUser::run($this->getFormData('user_form'), $user);
            $this->cancel();
            $this->dispatch('toast-success', ['title' => __('hailo::users.saved')]);
            $this->load = true;
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            $this->addValidationErrors('user_form', $e->errors());
            $this->clearValidation();
        }
    }

    public function loadModel(): Model
    {
        if ($this->action == 'edit') {
            $user = config('hailo.users_model')::find($this->register_id);
            if (!$user) {
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
        $this->table('users_table', UserRepository::table(new $class()))
            ->sortBy($this->sort_by)
            ->sortDirection($this->sort_direction)
            ->search($this->q)
            ->filterBy($this->filter)
            ->executeQuery();


        $this->form(UserRepository::form($this->loadModel()))
            ->action($this->action == 'edit' ? 'update' : 'store')
            ->title($this->user_form_title);
        $this->processFormElements($this->getForm('user_form'), $this->getForm('user_form')->getSchema());

        return view('hailo::livewire.permissions.users', [
            'users_table' => $this->getTable('users_table'),
            'user_form' => $this->getForm('user_form'),
            'validation_errors' => $this->getValidationErrors()
        ])
            ->layout('hailo::layouts.main')
            ->title(__('hailo::users.html_title', ['name' => config('app.name')]));
    }
}
