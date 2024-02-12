<?php

namespace Pardalsalcap\Hailo\Tools;


use Intervention\Image\Laravel\Facades\Image;
use Pardalsalcap\Hailo\Models\Media;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Exception;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
class Upload
{
    protected array $disk;
    protected string $disk_name;

    protected string $directory;
    protected string $original;
    protected string $extension;
    protected string $mimetype;
    protected string $filename;
    protected string $path;
    protected bool $is_image = false;
    protected string $save_path;
    protected string $url;
    protected int $weight;
    protected int $width;
    protected int $height;
    protected array $versions = [];

    protected array $exif =[];

    public function __construct($public = true)
    {
        $this->setUpDisk('public');
        if (!$public) {
            $this->setUpDisk('private');
        }
    }

    public function setUpDisk($disk_name): void
    {
        $this->disk = config('filesystems.disks.' . $disk_name);
        $this->disk_name = $disk_name;
    }

    /**
     * @throws Exception
     */
    public function makeUploadFromFile($file, $disk_name = null): array
    {
        if (!is_null($disk_name)) {
            $this->setUpDisk($disk_name);
        }
        $this->original = $file->getClientOriginalName();
        $this->extension = File::extension($this->original);
        $this->mimetype = $file->getMimeType();

        $this->filename = $this->uniqueFilename($file->getClientOriginalName(), $this->extension);
        $this->directory = $this->getStoragePath();

        $this->path = Storage::disk($this->disk_name)->put($this->directory, $file);
        if ($this->path) {
            $this->filename = str_replace('.'.$this->extension, '', str_replace($this->directory . '/', '', $this->path));
        }
        else
        {
            throw new Exception('Error al subir el archivo');
        }

        $this->width = 0;
        $this->height = 0;
        if ($this->isImage()) {
            $image = Image::read($this->disk['root'] . '/' . $this->path);
            $this->width = $image->width();
            $this->height = $image->height();
            $this->exif = $image->exif()->toArray();
            //$image->toWebp(60)->save($this->disk['root'] . '/' . $this->directory . '/' . $this->filename . '.webp');
            //$this->versions['webp'] = $this->directory . '/' . $this->filename . '.webp';
            foreach(config('hailo.curations', []) as $class_name)
            {
                $curation = new $class_name;
                $curation_path = $curation->setup($this->disk['root'] . '/' . $this->path, $this->extension, $this->directory, $this->filename, $this->disk_name)->generate();
                $this->versions[$curation->id] = [
                    'path' => $curation_path,
                    'width' => $curation->getWidth(),
                    'height' => $curation->getHeight(),
                    'curator' => $class_name,
                ];
            }
        }

        $this->weight = $file->getSize();
        //dd($this->result());

        return $this->result();
    }

    public function makeReplaceFromFile($file, int $media_id, string $version=null): bool
    {
        $media = Media::find($media_id);

        $this->setUpDisk($media->disk);

        $this->original = $file->getClientOriginalName();
        $this->extension = File::extension($this->original);
        $this->mimetype = $file->getMimeType();
        $this->filename = $this->uniqueFilename($file->getClientOriginalName(), $this->extension);
        $this->directory = 'temp';


        $this->path = Storage::disk($this->disk_name)->putFileAs($this->directory, $file, $this->filename);
        if ($this->path) {
            $this->filename = str_replace('.'.$this->extension, '', str_replace($this->directory . '/', '', $this->path));
        }
        else
        {
            throw new Exception('Error al subir el archivo');
        }

        if (!is_null($version))
        {
            $curations = config('hailo.curations', []);
            if (isset($curations[$version]))
            {
                $curator = new $curations[$version]();
                $cropped = $curator->setup($this->disk['root'] . '/temp/' . $this->filename.'.'.$this->extension, $media->extension, $media->directory, $media->filename, $media->disk)->generate();
            }
        }
        if(Storage::disk($media->disk)->exists('/temp/'.$this->filename."." . $media->extension)){
            Storage::disk($media->disk)->delete('/temp/'.$this->filename."." . $media->extension);
        }
        return true;

        $this->width = 0;
        $this->height = 0;
        if ($this->isImage()) {
            $image = Image::read($this->disk['root'] . '/' . $this->path);
            $this->width = $image->width();
            $this->height = $image->height();
            $this->exif = $image->exif()->toArray();
            //$image->toWebp(60)->save($this->disk['root'] . '/' . $this->directory . '/' . $this->filename . '.webp');
            //$this->versions['webp'] = $this->directory . '/' . $this->filename . '.webp';
            foreach(config('hailo.curations', []) as $class_name)
            {
                $curation = new $class_name;
                $curation_path = $curation->setup($this->disk['root'] . '/' . $this->path, $this->extension, $this->directory, $this->filename, $this->disk_name)->generate();
                $this->versions[$curation->id] = [
                    'path' => $curation_path,
                    'width' => $curation->getWidth(),
                    'height' => $curation->getHeight(),
                    'curator' => $class_name,
                ];
            }
        }

        $this->weight = $file->getSize();
        //dd($this->result());

        return $this->result();
    }

    public function result (): array
    {
        return [
            'original' => $this->original,
            'extension' => $this->extension,
            'mimetype' => $this->mimetype,
            'filename' => $this->filename,
            'directory' => $this->directory,
            'path' => $this->path,
            'is_image' => $this->is_image,
            'disk' => $this->disk_name,
            'weight' => $this->weight,
            'width' => $this->width,
            'height' => $this->height,
            'url' => $this->path,
            'exif' => $this->exif,
            'versions' => $this->versions,
        ];
    }

    public function isImage(): bool
    {
        if (str_starts_with($this->mimetype, 'image')) {
            $this->is_image = true;
            return true;
        }

        $this->is_image = false;
        return false;
    }

    public function getStoragePath($date = null): string
    {
        $time = time();
        if (!is_null($date)) {
            $time = strtotime($date);
        }
        return date('Y', $time) . '/' . date('m', $time) . '/' . date('d', $time);
    }
    public function uniqueFilename(string $original, string $extension): string
    {
        return md5(time() . $original) . '.' . $extension;
    }
    public function copyFile($file, $filename, $original, $date = null): array
    {

        $date = !is_null($date) ? $date : date('Y-m-d');

        $directory = $this->getStoragePath($date);
        if (!Storage::disk($this->disk_name)->exists($directory)) {
            Storage::disk($this->disk_name)->makeDirectory($directory, 0775, true, true);
        }
        $image = false;

        $mimetype = File::mimeType($file);
        if (strpos($mimetype, 'image') !== false) {
            $image = true;
        }
        if (!$image) {
            $extension = File::extension($file);
            if (in_array($extension, ['jpg', 'gif', 'png'])) {
                $image = true;
            }
        }
        $save_path = $this->getStoragePath($date);
        //$upload_success = File::copy($file, $save_path . $filename);
        $upload_success = Storage::disk($this->disk_name)->put($save_path . '/' . $filename, File::get($file));

        $filename = $save_path . '/' . $filename;
        if ($this->disk['driver'] != 'local') {
            $filename = str_replace(env('SPACES_BUCKET') . '/', '', $filename);
            $save_path = str_replace(env('SPACES_BUCKET') . '/', '', $save_path);
        }

        return [
            'original' => $original,
            'extension' => null,
            'mimetype' => $mimetype,
            'filename' => $filename,
            'save_path' => $save_path,
            'upload_success' => $upload_success,
            'image' => $image,
            'disk' => $this->disk_name,
            'weight' => File::size($file),
        ];
    }

    public function importFileFromFolder($file_path, $file_name, $file_date, $file_title, $file_user): mixed
    {
        if (File::exists($file_path)) {
            $upload_process = $this->copyFile($file_path, $this->uniqueFilename($file_path, pathinfo($file_path)['extension']), $file_name, $file_date);

            if (is_array($upload_process) and isset($upload_process['upload_success']) and $upload_process['upload_success']) {
                $media = Media::create(
                    [
                        'type' => $upload_process['image'] ? 'img' : 'dwn',
                        'user_id' => $file_user,
                        'auth' => false,
                        'mimetype' => $upload_process['mimetype'],
                        'status' => true,
                        'title' => $file_title,
                        'disk' => $upload_process['disk'],
                        'url' => $upload_process['filename'],
                        'created_at' => $file_date,
                    ]
                );
                if ($media) {
                    return $media;
                }
            }
        }

        return false;
    }


}
