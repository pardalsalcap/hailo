<?php

namespace Pardalsalcap\Hailo\Actions\Medias;

use Exception;
use Lorisleiva\Actions\Concerns\AsAction;
use Pardalsalcap\Hailo\Repositories\MediaRepository;
use Pardalsalcap\Hailo\Repositories\RoleRepository;

class DestroyMedia
{
    use AsAction;

    /**
     * @throws Exception
     */
    public function handle(int $media_id): bool
    {
        return (new MediaRepository())->destroy($media_id);
    }
}
