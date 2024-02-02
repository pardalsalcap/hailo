<?php

namespace Pardalsalcap\Hailo\Actions\Roles;

use Exception;
use Lorisleiva\Actions\Concerns\AsAction;
use Pardalsalcap\Hailo\Repositories\RoleRepository;

class DestroyRole
{
    use AsAction;

    /**
     * @throws Exception
     */
    public function handle(int $role_id): bool
    {
        return (new RoleRepository())->destroy($role_id);
    }
}
