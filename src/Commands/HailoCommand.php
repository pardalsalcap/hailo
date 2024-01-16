<?php

namespace Pardalsalcap\Hailo\Commands;

use Illuminate\Console\Command;

class HailoCommand extends Command
{
    public $signature = 'hailo';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
