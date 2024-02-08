<?php

namespace Pardalsalcap\Hailo\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Pardalsalcap\Hailo\Forms\Fields\TextInput;
use Pardalsalcap\Hailo\Forms\Form;
use Pardalsalcap\Hailo\Tables\Columns\MediaColumn;
use Pardalsalcap\Hailo\Tables\Columns\TextColumn;
use Pardalsalcap\Hailo\Tables\Table;
use Pardalsalcap\Hailo\Tools\Upload;
use Spatie\Permission\Models\Role;
use Pardalsalcap\Hailo\Models\Media;


class MediaRepository
{
    public static function form(Model $user)
    {
        return Form::make('media_form', $user)
            ->livewire(true)
            ->title(__('hailo::medias.user_form_title'))
            ->action('store')
            ->button(__('hailo::hailo.save'))
            ->schema([
                TextInput::make('title')
                    ->label(__('hailo::medias.field_label_title'))
                    ->placeholder(__('hailo::medias.field_placeholder_title'))
                    ->translatable()
                    ->required(),
                TextInput::make('alt')
                    ->label(__('hailo::medias.field_label_alt'))
                    ->placeholder(__('hailo::medias.field_placeholder_alt'))
                    ->translatable()
                    ->required(),
            ]);
    }


    public static function table(Model $media): Table
    {
        return Table::make('media_table', $media)
            ->title(__('hailo::medias.table_title'))
            ->perPage(25)
            ->hasEditAction(true)
            ->hasDeleteAction(true)
            ->extraField('is_image')
            ->extraField('disk')
            ->extraField('width')
            ->extraField('height')
            ->extraField('weight')
            ->extraField('title')
            ->extraField('alt')
            ->card('media-card')
            ->noRecordsFound(__('hailo::medias.no_records_found'))
            ->schema([
                MediaColumn::make('original')
                    ->label(__('hailo::medias.field_label_original'))
                    ->searchable(),
                TextColumn::make('url')
                    ->label(__('hailo::medias.field_label_url'))
                    ->searchable(),
            ]);
    }

    public function store(array $values): Model
    {
        $model = new Media();
        $model->user_id = auth()->id();
        $model->visibility = 'public';
        $model->status = true;
        $model->mimetype = $values['mimetype'];
        $model->is_image = $values['is_image'];
        $model->original = $values['original'];
        $model->disk = $values['disk'];
        $model->directory = $values['directory'];
        $model->extension = $values['extension'];
        $model->url = $values['url'];
        $model->weight = $values['weight'];
        $model->height = $values['height'];
        $model->width = $values['width'];
        $model->metadata = $values['exif'];
        $model->versions = [];

        $model->save();

        return $model;
    }

    public function update(array $values, Model $model): Model
    {
        foreach (config('hailo.languages') as $iso=>$language) {
            $model->setTranslation('title', $iso, $values['title_'.$iso]);
        }
        foreach (config('hailo.languages') as $iso=>$language) {
            $model->setTranslation('alt', $iso, $values['alt_'.$iso]);
        }
        $model->save();

        return $model;
    }

    /**
     * @throws Exception
     */
    public function destroy(int $media_id): bool
    {
        $media = Media::find($media_id);
        if ($media) {
            if(Storage::disk($media->disk)->exists($media->url)){
                if(!Storage::disk($media->disk)->delete($media->url))
                {
                    throw new \Exception(__('hailo::medias.not_deleted'));
                }
            }

            if (! $media->delete()) {
                throw new \Exception(__('hailo::medias.not_deleted'));
            }

            return true;
        }
        throw new \Exception(__('hailo::medias.not_found'));
    }

    public function upload($request): array
    {
        $uploader = new Upload();
        return $uploader->makeUploadFromFile($request->file('file'));
    }
}
