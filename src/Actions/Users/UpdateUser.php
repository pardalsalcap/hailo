<?php

namespace Pardalsalcap\Hailo\Actions\Users;

use App\Models\User;
use Exception;
use Lorisleiva\Actions\Concerns\AsAction;
use Pardalsalcap\Hailo\Repositories\UserRepository;

class UpdateUser
{
    use AsAction;

    /**
     * @throws Exception
     */
    public function handle(array $values, User $user): void
    {
        (new UserRepository())->update($values, $user);
    }
}
