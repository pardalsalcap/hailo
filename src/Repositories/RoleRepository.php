<?php

namespace Pardalsalcap\Hailo\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Pardalsalcap\Hailo\Forms\Fields\SelectInput;
use Pardalsalcap\Hailo\Forms\Fields\TextInput;
use Pardalsalcap\Hailo\Forms\Form;
use Pardalsalcap\Hailo\Forms\Section;
use Pardalsalcap\Hailo\Rules\MatchOldPassword;
use Pardalsalcap\Hailo\Tables\Columns\TextColumn;
use Pardalsalcap\Hailo\Tables\Table;
use Spatie\Permission\Models\Role;
use Exception;

class RoleRepository
{
    public static function form(Model $user)
    {
        return Form::make('role_form', $user)
            ->livewire(true)
            ->title(__('hailo::roles.user_form_title'))
            ->action('store')
            ->button(__('hailo::hailo.save'))
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->label(__('hailo::roles.field_label_name'))
                    ->rules(function ($form) {
                        return $form->getModel()? ['required', 'unique:roles,name,' . $form->getModel()?->id] : ['required', 'unique:roles,name'];
                    }),
            ]);
    }

    public static function table(Model $role): Table
    {
        return Table::make('roles_table', $role)
            ->title(__('hailo::roles.table_title'))
            ->perPage(25)
            ->hasEditAction(true)
            ->hasDeleteAction(true)
            ->noRecordsFound(__("hailo::roles.no_records_found"))
            ->schema([
                TextColumn::make('name')
                    ->label(__('hailo::roles.field_label_name'))
                    ->searchable()
            ]);
    }

    public function store(array $values): Model
    {
        $model = new Role();
        $model->name = $values['name'];
        $model->guard_name = config('auth.defaults.guard');
        $model->save();
        return $model;
    }

    public function update(array $values, Role $model): Role
    {
        $model->name = $values['name'];
        $model->save();
        return $model;
    }

    /**
     * @throws Exception
     */
    public function destroy(int $role_id): bool
    {
        $role = Role::find($role_id);
        if ($role) {
            if (!$role->delete()) {
                throw new \Exception(__("hailo::roles.not_deleted"));
            };
            return true;
        }
        throw new \Exception(__("hailo::roles.not_found"));
    }
}
