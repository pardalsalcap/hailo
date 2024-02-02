<?php

namespace Pardalsalcap\Hailo\Actions\Roles;

use Exception;
use Lorisleiva\Actions\Concerns\AsAction;
use Pardalsalcap\Hailo\Repositories\RoleRepository;
use Spatie\Permission\Models\Role;

class UpdateRole
{
    use AsAction;

    /**
     * @throws Exception
     */
    public function handle(array $values, Role $user): void
    {
        (new RoleRepository())->update($values, $user);
    }
}
