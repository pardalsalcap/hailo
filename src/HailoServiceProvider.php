<?php

namespace Pardalsalcap\Hailo;

use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use Pardalsalcap\Hailo\Commands\HailoCommand;
use Pardalsalcap\Hailo\Commands\HailoCreateUserCommand;
use Pardalsalcap\Hailo\Livewire\Auth\ForgotApp;
use Pardalsalcap\Hailo\Livewire\Auth\Login;
use Pardalsalcap\Hailo\Livewire\Auth\ResetApp;
use Pardalsalcap\Hailo\Livewire\Contents\ContentsApp;
use Pardalsalcap\Hailo\Livewire\Contents\SelectContentApp;
use Pardalsalcap\Hailo\Livewire\Dashboard\DashboardApp;
use Pardalsalcap\Hailo\Livewire\Forms\FormHandler;
use Pardalsalcap\Hailo\Livewire\Medias\CropApp;
use Pardalsalcap\Hailo\Livewire\Medias\MediasApp;
use Pardalsalcap\Hailo\Livewire\Medias\SelectMediaApp;
use Pardalsalcap\Hailo\Livewire\Profile\ProfileApp;
use Pardalsalcap\Hailo\Livewire\Profile\ProfileMenu;
use Pardalsalcap\Hailo\Livewire\Profile\ProfilePersonalDataForm;
use Pardalsalcap\Hailo\Livewire\Profile\ProfileSecurityForm;
use Pardalsalcap\Hailo\Livewire\Search\SearchBar;
use Pardalsalcap\Hailo\Livewire\Tables\TableHandler;
use Pardalsalcap\Hailo\Livewire\Users\RolesApp;
use Pardalsalcap\Hailo\Livewire\Users\UsersApp;
use Pardalsalcap\Hailo\View\Components\Cards\ContentSelectCard;
use Pardalsalcap\Hailo\View\Components\Cards\MediaCard;
use Pardalsalcap\Hailo\View\Components\Cards\MediaSelectCard;
use Pardalsalcap\Hailo\View\Components\Forms\Form;
use Pardalsalcap\Hailo\View\Components\Forms\FormElement;
use Pardalsalcap\Hailo\View\Components\NavigationItem;
use Pardalsalcap\Hailo\View\Components\Tables\RowActions;
use Pardalsalcap\Hailo\View\Components\Tables\Table;
use Pardalsalcap\Hailo\View\Components\Tables\TableElement;
use Pardalsalcap\Hailo\View\Components\Widgets\TableWidget;
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
            ->hasAssets()
            ->hasMigration('create_user_preferences')
            ->hasMigration('create_medias_table')
            ->hasMigration('create_content_table')
            ->hasMigration('create_content2medias_table')
            ->hasMigration('create_content2content_table')
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

        // borrar estos componentes
        //Livewire::component('form-handler', FormHandler::class);
        //Livewire::component('table-handler', TableHandler::class);

        Livewire::component('login', Login::class);
        Livewire::component('forgot', ForgotApp::class);
        Livewire::component('reset', ResetApp::class);
        Livewire::component('dashboard', DashboardApp::class);
        Livewire::component('permissions-app', UsersApp::class);
        Livewire::component('roles-app', RolesApp::class);
        Livewire::component('search-bar', SearchBar::class);

        Livewire::component('profile-menu', ProfileMenu::class);
        Livewire::component('profile-app', ProfileApp::class);
        Livewire::component('profile-security-form', ProfileSecurityForm::class);
        Livewire::component('profile-personal-form', ProfilePersonalDataForm::class);

        Livewire::component('medias-app', MediasApp::class);
        Livewire::component('crop-app', CropApp::class);
        Livewire::component('select-media-app', SelectMediaApp::class);

        Livewire::component('contents-app', ContentsApp::class);
        Livewire::component('select-content-app', SelectContentApp::class);

        Blade::component('hailo::navigation-item', NavigationItem::class);
        Blade::component('hailo::form-element', FormElement::class);
        Blade::component('hailo::form', Form::class);
        Blade::component('hailo::table', Table::class);
        Blade::component('hailo::table-element', TableElement::class);
        Blade::component('hailo::row-actions', RowActions::class);
        Blade::component('hailo::table-widget', TableWidget::class);

        Blade::component('hailo::media-card', MediaCard::class);
        Blade::component('hailo::media-select-card', MediaSelectCard::class);
        Blade::component('hailo::content-select-card', ContentSelectCard::class);
        Blade::component('hailo::media-form-card', \Pardalsalcap\Hailo\View\Components\Forms\Cards\MediaCard::class);
        Blade::component('hailo::content-form-card', \Pardalsalcap\Hailo\View\Components\Forms\Cards\ContentCard::class);

    }
}
