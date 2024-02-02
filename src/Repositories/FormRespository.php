<?php

namespace Pardalsalcap\Hailo\Repositories;

use Pardalsalcap\Hailo\Forms\Fields\TextInput;
use Pardalsalcap\Hailo\Forms\Form;
use Pardalsalcap\Hailo\Forms\Section;

class FormRespository
{
    public static function testForm()
    {
        return Form::make('test_form')
            ->livewire(true)
            ->action('save')
            ->schema([
                Section::make('personal_data')
                    ->title('Personal data')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->placeholder('Name')
                            ->value('dani')
                            ->required(),
                        TextInput::make('email')
                            ->type('email')
                            ->label('Email')
                            ->placeholder('Email')
                            ->required(),
                    ]),
            ]);
    }
}
