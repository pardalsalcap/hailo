<?php

namespace Pardalsalcap\Hailo\Actions\Contents;

use Exception;
use Lorisleiva\Actions\Concerns\AsAction;
use Pardalsalcap\Hailo\Repositories\ContentRepository;
use Pardalsalcap\Hailo\Repositories\RoleRepository;

class DestroyContent
{
    use AsAction;

    /**
     * @throws Exception
     */
    public function handle(int $content_id): bool
    {
        return (new ContentRepository())->destroy($content_id);
    }
}
