<?php

namespace App\Supports\Datto\Services;

use App\Models\DeviceAsset;
use App\Supports\Datto\Datto;
use App\Supports\Datto\Service;
use App\Supports\Datto\Services\Device;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Asset extends Service {
    /**
     * @var \App\Models\Device
     */
    protected $_device;

    public function __construct($sn, Datto $datto = null)
    {
        parent::__construct($datto);

        if($sn instanceof \App\Models\Device) {
            $this->_device  = $sn;
            $this->sn       = $this->_device->sn;
        } else {
            $this->sn       = $sn;
            $this->_device  = \App\Models\Device::find($sn);
        }

    }

    /**
     * @return \App\Models\Device
     */
    public function device() : \App\Models\Device
    {
        return $this->_device;
    }

    public function get()
    {
        return $this->datto->http()->get("bcdr/device/$this->sn/asset");
    }

    public function synchronize($transaction = true)
    {
        if($transaction)
            DB::beginTransaction();

        $assets   = $this->get();

        $this->save($assets);

        if($transaction)
            DB::commit();
    }

    public function save($assets)
    {
        if(!$assets)
            return false;

        /** @var \App\Models\Device $device */
        $device     = $this->device();

        if(!$device)
            return false;

        foreach ($assets as $asset) {
            $model      = $device->assets()->firstOrNew([
                'name'    => $asset->name,
            ]);

            $model->volume          = $asset->volume;
            $model->local_ip        = $asset->localIp;
            $model->os              = $asset->os;
            $model->protected_volume_count  = $asset->protectedVolumesCount;
            $model->unprotected_volume_count= $asset->unprotectedVolumesCount;
            $model->agent_version   = $asset->agentVersion;
            $model->is_paused       = $asset->isPaused;
            $model->is_archived     = $asset->isArchived;
            $model->latest_offsite  = $asset->latestOffsite;
            $model->local_snapshot  = $asset->localSnapshots;
            $model->fqdn            = $asset->fqdn;
            $model->type            = $asset->type;

            $model->save();

            $this->saveBackups($model, $asset->backups ?? []);
        }
    }

    protected function saveBackups(DeviceAsset $asset, $backups)
    {
        foreach($backups as $backup) {
            $model      = $asset->backups()->firstOrNew([
                'timestamp' => Carbon::parse($backup->timestamp)->timestamp
            ]);

            $successCount                   = 0;

            $model->backup_status           = $backup->backup->status ?? null;
            $model->backup_status_message   = $backup->backup->errorMessage ?? null;
            $model->local_verify_status     = $backup->localVerification->status ?? null;
            $model->local_verify_errors     = $backup->localVerification->errors ?? [];
            $model->advanced_verify_status  = $backup->advancedVerification->screenshotVerification->status ?? null;
            $model->screenshot_verify_image = $backup->advancedVerification->screenshotVerification->image ?? null;

            if($model->backup_status == 'success')
                $successCount++;
            if($model->local_verify_status == 'success')
                $successCount++;
            if($model->screenshot_verify_image)
                $successCount++;

            $model->success_count           = $successCount;
            $model->save();

        }
    }
}