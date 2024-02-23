<?php

namespace Pardalsalcap\Hailo\Tools;

use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;

class Curation
{
    public string $id;

    protected string $original;

    protected string $extension;

    protected int $width;

    protected int $height;

    protected string $format;

    protected string $directory;

    protected string $filename;

    protected string $mode;

    protected string $disk_name;

    public function generate()
    {
        $image = Image::read($this->original);
        if ($this->mode === 'proportional') {
            $image->scaleDown($this->width, $this->height);
        } elseif ($this->mode === 'cover') {
            $image->cover($this->width, $this->height);
        }

        $disk = config('filesystems.disks.'.$this->disk_name);
        $path = $disk['root'].'/'.$this->directory.'/'.$this->id;
        if (! File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
        if ($this->format === 'webp') {
            $image->toWebp(60)->save($path.'/'.$this->filename.'.webp');

            return $this->directory.'/'.$this->id.'/'.$this->filename.'.webp';
        }

        return null;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getMode()
    {
        return $this->mode;
    }
}
