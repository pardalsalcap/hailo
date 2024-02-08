<?php

namespace Pardalsalcap\Hailo\Actions\Medias;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;
use Pardalsalcap\Hailo\Repositories\MediaRepository;

class UpdateMedia
{
    use AsAction;

    /**
     * @throws Exception
     */
    public function handle(array $values, Model $model): Model
    {
        return (new MediaRepository())->update($values, $model);
    }
}
