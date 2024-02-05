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
use Pardalsalcap\Hailo\Tables\Traits\CanDelete;
use Pardalsalcap\Hailo\Tables\Traits\HasActions;
use Pardalsalcap\Hailo\Tables\Traits\HasTables;
use Spatie\Permission\Models\Role;
use Throwable;

class RolesApp extends Component
{
    use HasTables, HasActions, CanDelete, HasForms;

    public string $roles_form_title = "";

    protected RoleRepository $repository;

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

        $this->repository = new RoleRepository();

        $this->roles_form_title = __('hailo::roles.role_form_title');
    }

    public function hydrate(): void
    {
        $this->repository = new RoleRepository();
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
        $this->load = true;
        $this->roles_form_title = __('hailo::roles.role_form_title');
    }

    public function store(): void
    {
        try {
            DB::beginTransaction();
            $this->load = false;
            $form =$this->form($this->repository->form($this->loadModel()));
            $this->validate($this->validationRules($form));
            StoreRole::run($this->getFormData($form->getName()));
            $this->success();
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            if (isset($form))
            {
                $this->handleFormException($e, $form->getName(), __("hailo::roles.not_saved"));
            }
            else
            {
                $this->dispatch('toast-error', ['title' => __("hailo::roles.not_saved")]);
            }
        }
    }

    public function update(): void
    {
        try {
            DB::beginTransaction();
            $this->load = false;
            $role = $this->loadModel();
            $form = $this->form($this->repository->form($role));
            $this->validate($this->validationRules($form));
            UpdateRole::run($this->getFormData($form->getName()), $role);
            $this->success();
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            if (isset($form))
            {
                $this->handleFormException($e, $form->getName(), __("hailo::roles.not_saved"));
            }
            else
            {
                $this->dispatch('toast-error', ['title' => __("hailo::roles.not_saved")]);
            }
        }
    }

    public function success(): void
    {
        $this->cancel();
        $this->dispatch('toast-success', ['title' => __('hailo::roles.saved')]);
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

    public function search($q): void
    {
        $this->q = $q;
        $this->cancel();
        $this->resetPage();
    }

    public function render(): View|Factory
    {
        $this->table('roles_table', $this->repository->table(new Role()))
            ->sortBy($this->sort_by)
            ->sortDirection($this->sort_direction)
            ->search($this->q)
            ->filterBy($this->filter)
            ->executeQuery();


        $form = $this->form($this->repository->form($this->loadModel()))
            ->action($this->action == 'edit' ? 'update' : 'store')
            ->title($this->roles_form_title);
        $this->processFormElements($form, $form->getSchema());

        return view('hailo::livewire.permissions.roles', [
            'roles_table' => $this->getTable('roles_table'),
            'role_form' => $form,
            'validation_errors' => $this->getValidationErrors()
        ])
            ->layout('hailo::layouts.main')
            ->title(__('hailo::roles.html_title', ['name' => config('app.name')]));
    }
}
