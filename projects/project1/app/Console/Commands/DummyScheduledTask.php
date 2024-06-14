<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DummyScheduledTask extends Command
{
    protected $signature = 'dummy:scheduled-task';

    protected $description = 'Dummy scheduled task for testing';

    public function handle()
    {
        Log::info('Dummy scheduled task executed successfully!');
        $this->info('Dummy scheduled task executed successfully!');
    }
}
