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

class UserRepository
{
    public static function form(Model $user)
    {
        return Form::make('user_form', $user)
            ->livewire(true)
            ->title(__('hailo::users.user_form_title'))
            ->action('store')
            ->button(__('hailo::hailo.save'))
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->label(__('hailo::users.field_label_name'))
                    ->placeholder(__('hailo::users.field_label_name'))
                    ->required(),
                TextInput::make('email')
                    ->type('email')
                    ->label(__('hailo::users.field_label_email'))
                    ->placeholder('example@example.com')
                    ->rules(function ($form) {
                        if ($form->getModel()->id) {
                            return [
                                'required',
                                'email',
                                'unique:users,email,' . $form->getModel()->id
                            ];
                        }
                        return [
                            'required',
                            'email',
                            'unique:users,email',
                        ];
                    }),
                TextInput::make('password')
                    ->label(__('hailo::users.field_label_password'))
                    ->type('password')
                    ->placeholder(__('hailo::users.field_label_password'))
                    ->rules([Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                        ->uncompromised()
                    ])
                    ->required(!$user->id),
                SelectInput::make('rol')
                    ->label(__("hailo::users.field_label_rol"))
                    ->placeholder(__("hailo::users.field_label_rol"))
                    ->relation('roles', 'name')
                    ->options((new UserRepository())->roles())
                    ->required(),
            ]);
    }

    public static function profile(Model $user)
    {
        return Form::make('profile_form', $user)
            ->livewire(true)
            ->action('update')
            ->name('profile_form')
            ->title(__('hailo::hailo.profile_personal_data_title'))
            ->button(__('hailo::hailo.save'))
            ->schema([
                Section::make('personal_data')
                    ->title(__("hailo::profile.profile_personal_data_section"))
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__("hailo::profile.field_label_name"))
                            ->placeholder(__("hailo::profile.field_label_name"))
                            ->required(),
                        TextInput::make('email')
                            ->type('email')
                            ->label(__('hailo::profile.field_label_email'))
                            ->placeholder('example@example.com')
                            ->rules( [
                                'required',
                                'email',
                                'unique:users,email,' . auth()->id()
                            ]),
                    ]),
                Section::make('preferences-section')
                    ->title(__("hailo::profile.profile_preferences_section"))
                    ->columns(2)
                    ->schema([
                        SelectInput::make('mode')
                            ->label('Mode')
                            ->placeholder('Mode')
                            ->default('light')
                            ->options([
                                'light' => 'Light',
                                'dark' => 'Dark',
                                'system' => 'System',
                            ])
                            ->required(),

                    ]),
            ]);
    }

    public static function security(Model $user)
    {
        return Form::make('security_form', $user)
            ->livewire(true)
            ->action('update')
            ->name('security_form')
            ->title(__('hailo::profile.profile_security_title'))
            ->button(__('hailo::profile.profile_security_save'))
            ->schema([
                Section::make('security-form')

                    ->columns(1)
                    ->schema([
                        TextInput::make('password_current')
                            ->label(__("hailo::profile.field_label_current_password"))
                            ->type('password')
                            ->placeholder(__("hailo::profile.field_label_current_password"))
                            ->rules(['required', new MatchOldPassword()])
                        ,
                        TextInput::make('password')
                            ->label(__("hailo::profile.field_label_new_password"))
                            ->type('password')
                            ->placeholder(__("hailo::profile.field_label_new_password"))
                            ->rules(['confirmed', 'required', Password::min(8)
                                ->letters()
                                ->mixedCase()
                                ->numbers()
                                ->symbols()
                                ->uncompromised()
                            ])
                        ,
                        TextInput::make('password_confirmation')
                            ->type('password')
                            ->label(__("hailo::profile.field_label_new_password_confirmation"))
                            ->placeholder(__("hailo::profile.field_label_new_password_confirmation"))
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Model $user): Table
    {
        return Table::make('permissions', $user)
            ->title(__('hailo::users.table_title'))
            ->perPage(25)
            ->hasEditAction(true)
            ->hasDeleteAction(true)
            ->relations(['roles'])
            ->noRecordsFound(__("hailo::users.no_records_found"))
            ->addFilter('super_admin', function ($query) {
                return $query->role('super-admin');
            }, __("hailo::users.filter_super_admin"))
            ->addFilter('no_role', function ($query) {
                return $query->doesntHave('roles');
            }, __("hailo::users.filter_no_role"))
            ->schema([
                TextColumn::make('name')
                    ->label(__('hailo::users.field_label_name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('hailo::users.field_label_email'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rol')
                    ->relation()
                    ->label(__('hailo::users.field_label_rol'))
                    ->display(function ($model) {
                        return $model->roles->first()->name ?? '';
                    }),

            ]);
    }

    public function roles(): array
    {
        return Role::pluck('name', 'name')->toArray();
    }

    public function store(array $values): Model
    {
        $model = config('hailo.users_model');
        $model = new $model();
        $model->name = $values['name'];
        $model->email = $values['email'];
        $model->password = Hash::make($values['password']);
        $model->save();
        $model->syncRoles($values['rol']);
        return $model;
    }

    public function update(array $values, Model $model): Model
    {
        $model->name = $values['name'];
        $model->email = $values['email'];
        if ($values['password'] and !empty($values['password'])) {
            $model->password = Hash::make($values['password']);
        }
        $model->save();
        $model->syncRoles($values['rol']);
        return $model;
    }

    public function updatePersonalData(array $values, Model $model): Model
    {
        $model->name = $values['name'];
        $model->email = $values['email'];
        $model->save();
        foreach (config('hailo.user_preferences') as $preference => $default) {
            if (is_array($values[$preference])) {
                $value = $values[$preference]['value'];
            } else {
                $value = $values[$preference];
            }
            if (empty($value))
            {
                $value = $default;
            }

            $model->preferences()->updateOrCreate(['key' => $preference], ['value' => $value]);
        }
        return $model;
    }

    public function updateSecurityData(array $values, Model $model): Model
    {
        $model->password = Hash::make($values['password']);
        $model->save();
        return $model;
    }

    /**
     * @throws Exception
     */
    public function destroy(int $user_id): bool
    {
        $user = config('hailo.users_model')::find($user_id);
        if ($user) {
            if (!$user->delete()) {
                throw new \Exception(__("hailo::users.not_deleted"));
            };
            return true;
        }
        throw new \Exception(__("hailo::users.not_found"));
    }
}
