<?php

use Illuminate\Support\Facades\Route;
use Pardalsalcap\Hailo\Http\Controllers\AuthenticatedSessionController;
use Pardalsalcap\Hailo\Livewire\Auth\ForgotApp;
use Pardalsalcap\Hailo\Livewire\Auth\Login;
use Pardalsalcap\Hailo\Livewire\Auth\ResetApp;
use Pardalsalcap\Hailo\Livewire\Dashboard\Dashboard;
use Pardalsalcap\Hailo\Livewire\Profile\ProfileApp;
use Pardalsalcap\Hailo\Livewire\Users\RolesApp;
use Pardalsalcap\Hailo\Livewire\Users\UsersApp;

Route::middleware(['web'])
    ->prefix(config('hailo.route'))
    ->group(function () {
        Route::get('/reset-password/{token}', ResetApp::class)
            ->middleware('guest')
            ->name('password.reset');
    });
Route::middleware(['web'])
    ->prefix(config('hailo.route'))
    ->name('hailo.')
    ->group(function () {
        Route::get('/login', Login::class)
            ->middleware('guest')
            ->name('login');
        Route::get('/forgot', ForgotApp::class)
            ->middleware('guest')
            ->name('forgot');

        Route::middleware([\Pardalsalcap\Hailo\Middleware\HailoAuthMiddleware::class, 'role:admin|super-admin'])->group(function () {
            Route::get('/', Dashboard::class)->name('dashboard');
            Route::get('/profile', ProfileApp::class)->name('profile');
            Route::get('/permissions/permissions', UsersApp::class)->name('permissions');
            Route::get('/permissions/roles', RolesApp::class)->name('roles');
            Route::get('/maintenance', UsersApp::class)->name('maintenance');
            Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
        });
    });
