<?php

namespace Pardalsalcap\Hailo\Actions\Profile;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;
use Pardalsalcap\Hailo\Repositories\UserRepository;

class UpdateSecurityData
{
    use AsAction;

    /**
     * @throws Exception
     */
    public function handle(array $values, Model $user): void
    {
        (new UserRepository())->updateSecurityData($values, $user);
    }
}
