<?php

namespace Pardalsalcap\Hailo\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Pardalsalcap\Hailo\Actions\Medias\RenameMedia;
use Pardalsalcap\Hailo\Forms\Fields\TextInput;
use Pardalsalcap\Hailo\Forms\Form;
use Pardalsalcap\Hailo\Forms\Section;
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
                    ->help(__('hailo::medias.field_help_title'))
                    ->required(),
                TextInput::make('alt')
                    ->label(__('hailo::medias.field_label_alt'))
                    ->help(__('hailo::medias.field_help_alt'))
                    ->placeholder(__('hailo::medias.field_placeholder_alt'))
                    ->translatable()
                    ->required(),
                Section::make('config')
                    ->title(__('hailo::medias.section_title_config'))
                    ->schema([
                        TextInput::make('filename')
                            ->label(__('hailo::medias.field_label_filename'))
                            ->help(__('hailo::medias.field_help_filename'))
                            ->placeholder(__('hailo::medias.field_placeholder_filename'))
                            ->rules(['alpha_dash:ascii', 'lowercase', 'required']),
                    ]),

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
            ->extraField('versions')
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
        $model->filename = $values['filename'];
        $model->extension = $values['extension'];
        $model->url = $values['url'];
        $model->weight = $values['weight'];
        $model->height = $values['height'];
        $model->width = $values['width'];
        $model->metadata = $values['exif'];
        $model->versions = $values['versions'];

        $model->save();

        return $model;
    }

    public function update(array $values, Model $model): Model
    {
        foreach (config('hailo.languages') as $iso => $language) {
            $model->setTranslation('title', $iso, $values['title_' . $iso]);
        }
        foreach (config('hailo.languages') as $iso => $language) {
            $model->setTranslation('alt', $iso, $values['alt_' . $iso]);
        }

        if ($model->filename <> $values['filename']) {

            $model = RenameMedia::run($model, $values['filename']);
        }

        $model->save();

        return $model;
    }

    public function rename(Media $media, string $new_filename): Media
    {
        $new_url = $media->directory . '/' . $new_filename . '.' . $media->extension;
        $new_versions = [];
        // Check if a file with the new name already exists
        if (Storage::disk($media->disk)->exists($new_url)) {
            throw new \Exception(__('hailo::medias.file_exists'));
        }
        // Check if the new URL would be unique
        if (Media::where('url', $new_url)->where('id', '<>', $media->id)->exists()) {
            throw new \Exception(__('hailo::medias.url_exists'));
        }
        // Rename the file
        if (!Storage::disk($media->disk)->move($media->url, $new_url)) {
            throw new \Exception(__('hailo::medias.not_renamed'));
        }
        $media->url = $new_url;
        // Rename the versions
        if ($media->versions) {
            foreach ($media->versions as $key=>$version) {
                $new_version = $version;
                $new_version['path']=str_replace($media->filename, $new_filename, $version['path']);
                if (!Storage::disk($media->disk)->move($version['path'], $new_version['path'])) {
                    throw new \Exception(__('hailo::medias.not_renamed'));
                }
                $new_versions[$key] = $new_version;
            }
            $media->versions = $new_versions;
        }

        // Update the model
        $media->filename = $new_filename;

        return $media;
    }

    /**
     * @throws Exception
     */
    public function destroy(int $media_id): bool
    {
        $media = Media::find($media_id);
        if ($media) {
            if (Storage::disk($media->disk)->exists($media->url)) {
                if (!Storage::disk($media->disk)->delete($media->url)) {
                    throw new \Exception(__('hailo::medias.not_deleted'));
                }
            }

            if (!empty($media->versions)) {
                foreach ($media->versions as $version) {
                    $version_path = $version['path'];
                    if (Storage::disk($media->disk)->exists($version_path)) {
                        if (!Storage::disk($media->disk)->delete($version_path)) {
                            throw new \Exception(__('hailo::medias.not_deleted'));
                        }
                    }
                }
            }

            if (!$media->delete()) {
                throw new \Exception(__('hailo::medias.not_deleted'));
            }

            return true;
        }
        throw new \Exception(__('hailo::medias.not_found'));
    }

    public function upload($request): array
    {
        $uploader = new Upload();
        $action = $request->get('action');
        $media_id = $request->get('media_id');
        $version = $request->get('version');
        if ($action == 'replace')
        {
            return $uploader->makeReplaceFromFile($request->file('file'), $media_id, $version);
        }
        return $uploader->makeUploadFromFile($request->file('file'));
    }
}
