<?php

// config for Pardalsalcap/Hailo
return [
    'project' => 'Hailo',
    'route' => 'hailo',
    'user_preferences'=>[
        'mode' => 'light', // 'dark' or 'light'
    ],
    'navigation'=>new \Pardalsalcap\Hailo\Repositories\NavigationRepository(),
    'users_model'=>\Pardalsalcap\Hailo\Models\User::class,

];
