<?php

namespace Pardalsalcap\Hailo\Repositories;

use Pardalsalcap\Hailo\Navigation\Navigation;
use Pardalsalcap\Hailo\Navigation\NavigationItem;

class NavigationRepository
{
    public function navigation(): Navigation
    {
        return Navigation::make('desktop')
            ->navigation([
                NavigationItem::make('dashboard')
                    ->label(__('hailo::navigation.dashboard'))
                    ->icon('dashboard')
                    ->route(route('hailo.dashboard'))
                    ->active(function () {
                        return request()->routeIs('hailo.dashboard');
                    })
                    ->children([])
                    ->id('dashboard')
                    ->livewire(true),
                NavigationItem::make('medias')
                    ->label(__('hailo::navigation.medias'))
                    ->icon('medias')
                    ->route(route('hailo.medias'))
                    ->active(function () {
                        return request()->routeIs('hailo.medias');
                    })
                    ->children([])
                    ->id('medias')
                    ->livewire(true),
                NavigationItem::make('users_management')
                    ->label(__('hailo::navigation.permissions'))
                    ->icon('permissions')
                    ->route(route('hailo.permissions'))
                    ->active(function () {
                        return request()->routeIs('hailo.permissions');
                    })
                    ->children([
                        NavigationItem::make('permissions')
                            ->label(__('hailo::navigation.permissions'))
                            ->icon('permissions')
                            ->route(route('hailo.permissions'))
                            ->active(function () {
                                return request()->routeIs('hailo.permissions');
                            })
                            ->children([])
                            ->id('permissions')
                            ->livewire(true),
                        NavigationItem::make('roles')
                            ->label(__('hailo::navigation.roles'))
                            ->icon('roles')
                            ->route(route('hailo.roles'))
                            ->active(function () {
                                return request()->routeIs('hailo.roles');
                            })
                            ->children([])
                            ->id('roles')
                            ->livewire(true),
                    ])
                    ->id('permissions')
                    ->livewire(true),
                NavigationItem::make('maintenance')
                    ->label(__('hailo::navigation.maintenance'))
                    ->icon('settings')
                    ->route(route('hailo.maintenance'))
                    ->active(function () {
                        return request()->routeIs('hailo.maintenance');
                    })
                    ->children([
                        NavigationItem::make('redirections')
                            ->label(__('hailo::navigation.redirections'))
                            ->icon('redirections')
                            ->route(route('hailo.redirections'))
                            ->active(function () {
                                return request()->routeIs('hailo.redirections');
                            })
                            ->children([

                            ])
                            ->id('permissions')
                            ->livewire(true),
                    ])
                    ->id('test')
                    ->livewire(true),
            ]);
    }
}
