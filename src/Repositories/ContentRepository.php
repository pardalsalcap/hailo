<?php

namespace Pardalsalcap\Hailo\Repositories;

use Illuminate\Database\Eloquent\Model;
use Pardalsalcap\Hailo\Forms\Fields\CkEditorInput;
use Pardalsalcap\Hailo\Forms\Fields\ContentMultipleInput;
use Pardalsalcap\Hailo\Forms\Fields\HiddenInput;
use Pardalsalcap\Hailo\Forms\Fields\MediaMultipleInput;
use Pardalsalcap\Hailo\Forms\Fields\MediaSingleInput;
use Pardalsalcap\Hailo\Forms\Fields\SelectInput;
use Pardalsalcap\Hailo\Forms\Fields\SeoGoogle;
use Pardalsalcap\Hailo\Forms\Fields\TextInput;
use Pardalsalcap\Hailo\Forms\Fields\ToggleInput;
use Pardalsalcap\Hailo\Forms\Form;
use Pardalsalcap\Hailo\Forms\Section;
use Pardalsalcap\Hailo\Models\Content;
use Pardalsalcap\Hailo\Tables\Columns\TextColumn;
use Pardalsalcap\Hailo\Tables\Table;

class ContentRepository
{
    public static function form(Model $user)
    {
        return Form::make('content_form', $user)
            ->livewire(true)
            ->title(__('hailo::content.form_title'))
            ->action('store')
            ->button(__('hailo::hailo.save'))
            ->schema([
                Section::make('main_section')
                    ->columns(5)
                    ->schema([
                        Section::make('main_content')
                            ->colSpan(4)
                            ->title(__('hailo::content.main_section_title'))
                            ->schema([SelectInput::make('lang')
                                ->required()
                                ->blur()
                                ->options(config('hailo.languages'))
                                ->label(__('hailo::content.field_label_lang'))
                                ->placeholder(__('hailo::content.field_label_lang'))
                                ->default(config('app.fallback_locale')),
                                SelectInput::make('parent_id')
                                    ->blur()
                                    ->options(function ($formData) {
                                        if (! isset($formData['lang'])) {
                                            return [];
                                        }

                                        return Content::where('lang', $formData['lang'])->get()->pluck('title', 'id')->toArray();
                                    })
                                    ->label(__('hailo::content.field_label_parent_id'))
                                    ->placeholder(__('hailo::content.field_label_parent_id'))
                                    ->default(null),
                                HiddenInput::make('type')
                                    ->default('page'),
                                TextInput::make('title')
                                    ->label(__('hailo::content.field_label_title'))
                                    ->placeholder(__('hailo::content.field_label_title'))
                                    ->required(),
                                CkEditorInput::make('content')
                                    ->label(__('hailo::content.field_label_content'))
                                    ->placeholder(__('hailo::content.field_label_content'))
                                    ->required(),
                                Section::make('seo_section')
                                    ->title(__('hailo::content.seo_section_title'))
                                    ->schema(self::seoFieldsBlock()),
                            ]),
                        Section::make('metas_section')
                            ->schema([

                                Section::make('related_content')
                                    ->title(__('hailo::content.related_content_section_title'))
                                    ->schema([
                                        ContentMultipleInput::make('related_content')
                                            ->label(__('hailo::content.field_label_related_content'))
                                            ->type('home')
                                            ->relation('related', 'title', true, false, 'related', true)
                                            ->placeholder(__('hailo::content.field_label_related_content')),
                                    ]),

                                Section::make('image_featured_section')
                                    ->title(__('hailo::content.image_featured_section_title'))
                                    ->schema([
                                        MediaSingleInput::make('featured_image_id')
                                            ->label(__('hailo::content.field_label_featured_image_id'))
                                            ->type('image')
                                            ->placeholder(__('hailo::content.field_label_featured_image_id')),
                                    ]),
                                Section::make('download_featured_section')
                                    ->title(__('hailo::content.download_featured_section_title'))
                                    ->schema([
                                        MediaSingleInput::make('featured_download_id')
                                            ->label(__('hailo::content.field_label_featured_download_id'))
                                            ->type('download')
                                            ->placeholder(__('hailo::content.field_label_featured_download_id')),
                                    ]),

                                Section::make('image_gallery')
                                    ->title(__('hailo::content.image_gallery_section_title'))
                                    ->schema([
                                        MediaMultipleInput::make('image_gallery')
                                            ->label(__('hailo::content.field_label_image_gallery'))
                                            ->type('image')
                                            ->relation('imageGallery', 'title', true, true, 'image_gallery', true)
                                            ->placeholder(__('hailo::content.field_label_featured_image_id')),
                                    ]),

                                Section::make('download_gallery')
                                    ->title(__('hailo::content.download_gallery_section_title'))
                                    ->schema([
                                        MediaMultipleInput::make('download_gallery')
                                            ->label(__('hailo::content.field_label_download_gallery'))
                                            ->type('download')
                                            ->relation('dwnGallery', 'title', true, true, 'download_gallery', true)
                                            ->placeholder(__('hailo::content.field_label_download_gallery')),

                                    ]),

                                TextInput::make('meta_title')
                                    ->label(__('hailo::content.field_label_meta_title'))
                                    ->placeholder(__('hailo::content.field_label_meta_title'))
                                    ->required(),
                                ToggleInput::make('status')
                                    ->label(__('hailo::content.field_label_status'))
                                    ->value(true),

                            ]),

                    ]),
            ]);
    }

    public static function seoFieldsBlock(): array
    {
        return [
            TextInput::make('seo_title')
                ->label(__('hailo::content.field_label_seo_title'))
                ->placeholder(__('hailo::content.field_label_seo_title'))
                ->required(),
            TextInput::make('seo_breadcrumb')
                ->label(__('hailo::content.field_label_seo_breadcrumb'))
                ->placeholder(__('hailo::content.field_label_seo_breadcrumb'))
                ->required(),
            TextInput::make('seo_description')
                ->label(__('hailo::content.field_label_seo_description'))
                ->placeholder(__('hailo::content.field_label_seo_description'))
                ->required(),
            TextInput::make('seo_keywords')
                ->label(__('hailo::content.field_label_seo_keywords'))
                ->placeholder(__('hailo::content.field_label_seo_keywords'))
                ->required(),
            TextInput::make('seo_slug')
                ->blur()
                ->label(__('hailo::content.field_label_seo_slug'))
                ->placeholder(__('hailo::content.field_label_seo_slug'))
                ->required(),
            TextInput::make('seo_url')
                ->label(__('hailo::content.field_label_seo_url'))
                ->placeholder(__('hailo::content.field_label_seo_url'))
                ->readOnly()
                ->rules(function ($form) {
                    if ($form->getModel()->id) {
                        return [
                            'required',
                            'unique:contents,seo_url,'.$form->getModel()->id,
                        ];
                    }

                    return [
                        'required',
                        'unique:contents,seo_url',
                    ];
                }),
            SeoGoogle::make('google')
                ->label(__('hailo::content.field_label_seo_google')),
        ];
    }

    public static function table(Model $role): Table
    {
        return Table::make('content_table', $role)
            ->title(__('hailo::content.table_title'))
            ->perPage(25)
            ->addFilter('lang_es', function ($query) {
                return $query->where('lang', 'es');
            }, __('hailo::content.filter_lang_es'))
            ->addFilter('filter_home', function ($query) {
                return $query->where('type', 'home');
            }, __('hailo::content.filter_home'))
            ->hasEditAction(true)
            ->hasDeleteAction(true)
            ->noRecordsFound(__('hailo::content.no_records_found'))
            ->schema([
                TextColumn::make('title')
                    ->label(__('hailo::content.field_label_title'))
                    ->searchable(),
                TextColumn::make('type')
                    ->label(__('hailo::content.field_label_type'))
                    ->searchable(),
            ]);
    }

    public function store(array $values): Model
    {
        $model = new Content();
        $this->setValues($model, $values);
        $model->save();

        return $model;
    }

    public function storeMetas(Content $content, array $values): bool
    {
        foreach ($values as $key => $value) {
            $content->contentMetas()->updateOrCreate([
                'content_id' => $content->id,
                'key' => $key,
            ], [
                'content_id' => $content->id,
                'key' => $key,
                'value' => $value,
            ]);
        }

        return true;
    }

    public function storeRelations(Content $content, array $relations, array $values): bool
    {
        if (count($relations) > 0) {
            foreach ($relations as $relation) {
                if ($relation['is_content_media']) {
                    $data = $values[$relation['field_name']];
                    $data = array_values($data);
                    if (empty($data)) {
                        $data = [];
                    }
                    $arr = [];
                    foreach ($data as $index => $id) {
                        $arr[$id] = ['position' => $index + 1, 'type' => $relation['content_media_type']];
                    }
                    $content->{$relation['relation']}()->sync($arr);
                } else {
                    $data = $values[$relation['field_name']];
                    $data = array_values($data);
                    if (empty($data)) {
                        $data = [];
                    }
                    $arr = [];
                    foreach ($data as $index => $id) {
                        $arr[$id] = ['position' => $index + 1, 'type' => $relation['content_media_type']];
                    }
                    $content->{$relation['relation']}()->sync($arr);
                }
            }
        }

        return true;
    }

    public function update(array $values, Model $model): Model
    {
        $this->setValues($model, $values);
        $model->save();

        return $model;
    }

    public function setValues(Model &$model, array $values)
    {
        $model->title = $values['title'];
        $fields = (new Content())->getFillable();
        foreach ($fields as $field) {
            if (isset($values[$field])) {
                $model->$field = $values[$field];
            }
        }
        if (isset($values['featured_download_id']) and empty($values['featured_download_id'])) {
            $model->featured_download_id = null;
        }

        if (isset($values['featured_image_id']) and empty($values['featured_image_id'])) {
            $model->featured_image_id = null;
        }

        $model->type = $values['type'];
    }

    public function destroy(int $content_id): bool
    {
        $content = Content::find($content_id);
        if ($content) {
            if (! $content->delete()) {
                throw new \Exception(__('hailo::contents.not_deleted'));
            }

            return true;
        }
        throw new \Exception(__('hailo::contents.not_found'));
    }
}
