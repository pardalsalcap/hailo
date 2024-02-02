<?php

namespace Pardalsalcap\Hailo;

use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use Pardalsalcap\Hailo\Commands\HailoCommand;
use Pardalsalcap\Hailo\Commands\HailoCreateUserCommand;
use Pardalsalcap\Hailo\Livewire\Auth\Login;
use Pardalsalcap\Hailo\Livewire\Dashboard\Dashboard;
use Pardalsalcap\Hailo\Livewire\Forms\FormHandler;
use Pardalsalcap\Hailo\Livewire\Search\SearchBar;
use Pardalsalcap\Hailo\Livewire\Tables\TableHandler;
use Pardalsalcap\Hailo\Livewire\Profile\ProfileApp;
use Pardalsalcap\Hailo\Livewire\Profile\ProfileMenu;
use Pardalsalcap\Hailo\Livewire\Profile\ProfilePersonalDataForm;
use Pardalsalcap\Hailo\Livewire\Profile\ProfileSecurityForm;
use Pardalsalcap\Hailo\Livewire\Users\RolesApp;
use Pardalsalcap\Hailo\Livewire\Users\UsersApp;
use Pardalsalcap\Hailo\Livewire\Users\UsersForm;
use Pardalsalcap\Hailo\Livewire\Users\UsersTable;

use Pardalsalcap\Hailo\View\Components\Forms\Form;
use Pardalsalcap\Hailo\View\Components\Forms\FormElement;
use Pardalsalcap\Hailo\View\Components\NavigationItem;
use Pardalsalcap\Hailo\View\Components\Tables\RowActions;
use Pardalsalcap\Hailo\View\Components\Tables\Table;
use Pardalsalcap\Hailo\View\Components\Tables\TableElement;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class HailoServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('hailo')
            ->hasConfigFile()
            ->hasViews()
            ->hasRoute('hailo')
            ->hasTranslations()
            ->hasMigration('create_user_preferences')
            ->hasViewComponent('hailo', NavigationItem::class)
            ->hasCommand(HailoCommand::class)
            ->hasCommand(HailoCreateUserCommand::class);
    }

    public function bootingPackage()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'hailo');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'hailo');
        $this->loadRoutesFrom(__DIR__.'/../routes/hailo.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        Livewire::component('login', Login::class);
        Livewire::component('dashboard', Dashboard::class);
        Livewire::component('form-handler', FormHandler::class);
        Livewire::component('table-handler', TableHandler::class);
        Livewire::component('permissions-app', UsersApp::class);
        Livewire::component('roles-app', RolesApp::class);
        Livewire::component('search-bar', SearchBar::class);

        Livewire::component('profile-menu', ProfileMenu::class);
        Livewire::component('profile-app', ProfileApp::class);
        Livewire::component('profile-security-form', ProfileSecurityForm::class);
        Livewire::component('profile-personal-form', ProfilePersonalDataForm::class);

        Blade::component('hailo::navigation-item', NavigationItem::class);
        Blade::component('hailo::form-element', FormElement::class);
        Blade::component('hailo::form', Form::class);
        Blade::component('hailo::table', Table::class);
        Blade::component('hailo::table-element', TableElement::class);
        Blade::component('hailo::row-actions', RowActions::class);

    }
}
