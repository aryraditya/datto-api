<?php

namespace App\Jobs;

use App\Models\Device;
use App\Supports\Datto\Services\Asset;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SynchronizeDeviceAssets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Device
     */
    private $model;

    /**
     * Create a new job instance.
     *
     * @param Device $model
     */
    public function __construct(Device $model)
    {
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $asset = new Asset($this->model);
        $asset->synchronize();
    }
}
