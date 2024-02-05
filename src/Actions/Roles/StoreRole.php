<?php

namespace Pardalsalcap\Hailo\Actions\Roles;

use Exception;
use Lorisleiva\Actions\Concerns\AsAction;
use Pardalsalcap\Hailo\Repositories\RoleRepository;

class StoreRole
{
    use AsAction;

    /**
     * @throws Exception
     */
    public function handle(array $values): void
    {
        (new RoleRepository())->store($values);
    }
}
