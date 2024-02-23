<?php

namespace Pardalsalcap\Hailo\Actions\Medias;

use Exception;
use Lorisleiva\Actions\Concerns\AsAction;
use Pardalsalcap\Hailo\Models\Media;
use Pardalsalcap\Hailo\Repositories\MediaRepository;

class RenameMedia
{
    use AsAction;

    /**
     * @throws Exception
     */
    public function handle(Media $media, string $filename): Media
    {
        return (new MediaRepository())->rename($media, $filename);
    }
}
