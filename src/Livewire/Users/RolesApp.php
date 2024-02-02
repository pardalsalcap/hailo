<?php

namespace Pardalsalcap\Hailo\Livewire\Users;

use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Pardalsalcap\Hailo\Actions\Roles\DestroyRole;
use Pardalsalcap\Hailo\Actions\Roles\StoreRole;
use Pardalsalcap\Hailo\Actions\Roles\UpdateRole;
use Pardalsalcap\Hailo\Forms\Traits\HasForms;
use Pardalsalcap\Hailo\Repositories\RoleRepository;
use Pardalsalcap\Hailo\Repositories\UserRepository;
use Pardalsalcap\Hailo\Tables\Traits\CanDelete;
use Pardalsalcap\Hailo\Tables\Traits\HasActions;
use Pardalsalcap\Hailo\Tables\Traits\HasTables;
use Spatie\Permission\Models\Role;
use Throwable;

class RolesApp extends Component
{
    use HasTables, HasActions, CanDelete, HasForms;

    public string $roles_form_title = "";

    protected $listeners = [
        'searchUpdated' => 'search',
        'destroyRole' => 'destroy',
    ];

    protected $queryString = [
        'sort_by' => ['except' => 'id', 'as' => 'sort_by'],
        'sort_direction' => ['except' => ['ASC', "null"], 'as' => 'sort_direction'],
        'q' => ['except' => '']
    ];

    public function mount(): void
    {
        $this->deleting_configuration = [
            'title' => __('hailo::roles.confirm_delete_title'),
            'text' => __('hailo::roles.confirm_delete_text'),
            'confirmButtonText' => __('hailo::hailo.confirm_yes'),
            'cancelButtonText' => __('hailo::hailo.confirm_no'),
            'livewireAction' => 'destroyRole',
        ];

        $this->roles_form_title = __('hailo::roles.role_form_title');
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
            DestroyRole::run($this->deleting_id);
            $this->dispatch('deletedRole', ['id' => $this->deleting_id]);
            $this->dispatch('toast-success', ['title' => __('hailo::roles.deleted')]);
            $this->deleting_id = null;
        } catch (Exception $e) {
            $this->dispatch('toast-error', ['title' => __('hailo::roles.not_deleted') . ':<br /> ' . $e->getMessage()]);
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
        $this->roles_form_title = __('hailo::roles.role_form_title');
    }

    public function store(): void
    {
        try {
            DB::beginTransaction();
            $this->load = false;
            $this->form(RoleRepository::form($this->loadModel()));
            $this->validate($this->validationRules($this->getForm('role_form')));
            StoreRole::run($this->getFormData('role_form'));
            $this->cancel();
            $this->dispatch('toast-success', ['title' => __('hailo::roles.saved')]);
            $this->load = true;
            DB::commit();

        } catch (Throwable $e) {
            DB::rollBack();
            $this->addValidationErrors('role_form', $e->errors());
            $this->clearValidation();
        }
    }

    public function update(): void
    {
        try {
            DB::beginTransaction();
            $this->load = false;
            $role = $this->loadModel();
            $this->form(RoleRepository::form($role));
            $this->validate($this->validationRules($this->getForm('role_form')));
            UpdateRole::run($this->getFormData('role_form'), $role);
            $this->cancel();
            $this->dispatch('toast-success', ['title' => __('hailo::roles.saved')]);
            $this->load = true;
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            $this->addValidationErrors('role_form', $e->errors());
            $this->clearValidation();
        }
    }

    public function loadModel(): Model
    {
        if ($this->action == 'edit') {
            $role = Role::find($this->register_id);
            if (!$role) {
                $this->cancel();
                $this->dispatch('toast-error', ['title' => __('hailo::roles.not_found')]);
            } else {
                $this->roles_form_title = __('hailo::roles.role_form_title_edit', ['u' => $role->name]);
                return $role;
            }
        }
        return new Role();

    }

    public function search($q)
    {
        $this->q = $q;
        $this->cancel();
        $this->resetPage();
    }

    public function render(): View|Factory
    {
        $this->table('roles_table', RoleRepository::table(new Role()))
            ->sortBy($this->sort_by)
            ->sortDirection($this->sort_direction)
            ->search($this->q)
            ->filterBy($this->filter)
            ->executeQuery();


        $this->form(RoleRepository::form($this->loadModel()))
            ->action($this->action == 'edit' ? 'update' : 'store')
            ->title($this->roles_form_title);
        $this->processFormElements($this->getForm('role_form'), $this->getForm('role_form')->getSchema());

        return view('hailo::livewire.permissions.roles', [
            'roles_table' => $this->getTable('roles_table'),
            'role_form' => $this->getForm('role_form'),
            'validation_errors' => $this->getValidationErrors()
        ])
            ->layout('hailo::layouts.main')
            ->title(__('hailo::roles.html_title', ['name' => config('app.name')]));
    }
}
