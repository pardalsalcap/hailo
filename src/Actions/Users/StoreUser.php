<?php

namespace Pardalsalcap\Hailo\Actions\Users;

use Exception;
use Lorisleiva\Actions\Concerns\AsAction;
use Pardalsalcap\Hailo\Repositories\UserRepository;

class StoreUser
{
    use AsAction;

    /**
     * @throws Exception
     */
    public function handle(array $values): void
    {
        (new UserRepository())->store($values);
    }
}
