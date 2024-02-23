<?php

namespace Pardalsalcap\Hailo\Tools\Curations;

use Pardalsalcap\Hailo\Tools\Curation;

class MaxResolution extends Curation
{
    public function setup($original, $extension, $directory, $filename, $disk_name): self
    {
        $this->id = 'max';
        $this->original = $original;
        $this->width = 2000;
        $this->height = 2000;
        $this->extension = $extension;
        $this->directory = $directory;
        $this->filename = $filename;
        $this->disk_name = $disk_name;
        $this->format = 'webp';
        $this->mode = 'proportional';

        return $this;
    }

    public static function info()
    {
        return __('hailo::medias.curation_max_information');
    }
}
