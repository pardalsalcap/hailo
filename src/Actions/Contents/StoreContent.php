<?php

namespace Pardalsalcap\Hailo\Actions\Contents;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;
use Pardalsalcap\Hailo\Repositories\ContentRepository;
use Pardalsalcap\Hailo\Repositories\RoleRepository;

class StoreContent
{
    use AsAction;

    /**
     * @throws Exception
     */
    public function handle(array $values): Model
    {
        return (new ContentRepository())->store($values);
    }
}
