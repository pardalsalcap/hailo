<?php

namespace Pardalsalcap\Hailo\Tools\Curations;

use Pardalsalcap\Hailo\Tools\Curation;

class CmsThumbnail extends Curation
{
    public function setup($original, $extension, $directory, $filename, $disk_name): self
    {
        $this->id = 'cms';
        $this->original = $original;
        $this->width = 300;
        $this->height = 300;
        $this->extension = $extension;
        $this->directory = $directory;
        $this->filename = $filename;
        $this->disk_name = $disk_name;
        $this->format = 'webp';
        $this->mode = 'exact';

        return $this;
    }

    public static function info()
    {
        return __('hailo::medias.curation_cms_information');
    }
}
