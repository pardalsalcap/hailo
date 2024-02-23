<?php

namespace Pardalsalcap\Hailo\Actions\Contents;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;
use Pardalsalcap\Hailo\Models\Content;
use Pardalsalcap\Hailo\Repositories\ContentRepository;
use Pardalsalcap\Hailo\Repositories\RoleRepository;

class StoreContentMetas
{
    use AsAction;

    /**
     * @throws Exception
     */
    public function handle(Content $content, array $values): bool
    {
        return (new ContentRepository())->storeMetas($content, $values);
    }
}