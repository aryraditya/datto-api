<?php

namespace App\Console\Commands\App\Device;

use Illuminate\Console\Command;

class Sync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:device:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize Devices';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        (new \App\Supports\Datto\Services\Device())->synchronize();
    }
}
