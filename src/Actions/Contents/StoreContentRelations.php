<?php

namespace Pardalsalcap\Hailo\Actions\Contents;

use Exception;
use Lorisleiva\Actions\Concerns\AsAction;
use Pardalsalcap\Hailo\Models\Content;
use Pardalsalcap\Hailo\Repositories\ContentRepository;

class StoreContentRelations
{
    use AsAction;

    /**
     * @throws Exception
     */
    public function handle(Content $content, array $relations, array $values): bool
    {
        return (new ContentRepository())->storeRelations($content, $relations, $values);
    }
}
