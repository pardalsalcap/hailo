<?php

namespace Pardalsalcap\Hailo\Actions\Users;

use Exception;
use Lorisleiva\Actions\Concerns\AsAction;
use Pardalsalcap\Hailo\Repositories\UserRepository;

class DestroyUser
{
    use AsAction;

    /**
     * @throws Exception
     */
    public function handle(int $user_id): bool
    {
        return (new UserRepository())->destroy($user_id);
    }
}
