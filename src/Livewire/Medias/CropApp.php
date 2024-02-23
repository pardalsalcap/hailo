<?php

namespace Pardalsalcap\Hailo\Livewire\Medias;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Livewire\Component;
use Pardalsalcap\Hailo\Models\Media;
use Pardalsalcap\Hailo\Repositories\MediaRepository;

class CropApp extends Component
{
    public bool $show = false;

    public string $image = '';

    public int $media_id;

    public string $version;

    public int $width;

    public int $height;

    public string $mode;

    protected MediaRepository $repository;

    protected $listeners = [
        'initCrop' => 'initCrop',
        'cropDone' => 'endCrop',
        'addedMediaVersion' => 'close',
    ];

    public function mount(): void
    {
        $this->repository = new MediaRepository();
    }

    public function hydrate(): void
    {
        $this->repository = new MediaRepository();
    }

    public function initCrop($data): void
    {
        $media = Media::find($data['media_id']);
        $this->media_id = $media->id;
        $this->version = $data['version'];
        $this->image = $media->getUrl();
        $this->show = true;
        $version = $media->versions[$this->version];
        $curator = new $version['curator']();
        $disk = config('filesystems.disks.'.$media->disk);
        $curator->setup($disk['root'].$media->url, $media->extension, $media->directory, $media->filename, $media->disk);
        $this->width = $curator->getWidth();
        $this->height = $curator->getHeight();
        $this->mode = $curator->getMode();
        //($original, $extension, $directory, $filename, $disk_name)
    }

    public function endCrop($data)
    {
        $width = $data['width'];
        $height = $data['height'];
        $x = $data['x'];
        $y = $data['y'];

        $media = Media::find($this->media_id);
        $disk = config('filesystems.disks.'.$media->disk);
        $image = Image::read($disk['root'].'/'.$media->url);
        $image->crop($width, $height, $x, $y);
        $temp = Str::uuid();
        $temp_path = $disk['root'].'/temp/'.$temp.'.'.$media->extension;
        $image->save($temp_path);
        $version = $media->versions[$this->version];
        $curator = new $version['curator']();
        $cropped = $curator->setup($disk['root'].'/temp/'.$temp.'.'.$media->extension, $media->extension, $media->directory, $media->filename, $media->disk)->generate();

        if (Storage::disk($media->disk)->exists('/temp/'.$temp.'.'.$media->extension)) {
            Storage::disk($media->disk)->delete('/temp/'.$temp.'.'.$media->extension);
        }

        $this->close();

    }

    public function close()
    {
        $this->show = false;
        $this->dispatch('cropEnded');
    }

    public function cancel(): void
    {
        $this->show = false;
    }

    public function render(): View|Factory
    {
        return view('hailo::livewire.medias.crop', [

        ]);
    }
}
