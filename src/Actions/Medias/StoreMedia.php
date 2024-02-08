<?php

namespace Pardalsalcap\Hailo\Actions\Medias;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;
use Pardalsalcap\Hailo\Repositories\MediaRepository;

class StoreMedia
{
    use AsAction;

    /**
     * @throws Exception
     */
    public function handle(array $values): Model
    {
        return (new MediaRepository())->store($values);
    }
}
