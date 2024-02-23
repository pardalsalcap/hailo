<?php

namespace Pardalsalcap\Hailo\Actions\Contents;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;
use Pardalsalcap\Hailo\Models\Content;
use Pardalsalcap\Hailo\Repositories\ContentRepository;

class UpdateContent
{
    use AsAction;

    /**
     * @throws Exception
     */
    public function handle(array $values, Content $content): Model
    {
        return (new ContentRepository())->update($values, $content);
    }
}
