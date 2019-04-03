<?php


namespace App\Supports\Datto\Services;


use App\Jobs\SynchronizeDeviceAssets;
use App\Supports\Datto\Datto;
use App\Supports\Datto\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Device extends Service
{

    public function get($page = 1, $showHiddenDevices = true, $showChildResellerDevice = true)
    {
        $response = $this->datto->http()->get('bcdr/device', [
            '_page'                     => $page,
            'showHiddenDevices'         => (int) $showHiddenDevices,
            'showChidResellerDevices'   => (int) $showChildResellerDevice
        ]);

        return $response;
    }

    public function synchronize($page = 1, $transaction = true)
    {
        $page       = $page < 1 ? 1 : $page;

        if($transaction)
            DB::beginTransaction();

        $response   = $this->get($page);
        $devices    = $response->items ?? [];
        $totalPage  = $response->pagination->totalPages ?? 1;

        $models     = $this->saveMany($devices);

        if ($totalPage > $page) {
            $this->synchronize($page + 1, false);
        }

        if($transaction)
            DB::commit();

        $models->each(function( $model) {
           SynchronizeDeviceAssets::dispatch($model)->onQueue('asset');
        });
    }


    protected function saveMany($devices = [])
    {
        $models = collect();

        foreach($devices as $device) {
            $models->push($this->save($device));
        }

        return $models;
    }

    protected function save($device)
    {
        /** @var \App\Models\Device $model */
        $model  = \App\Models\Device::firstOrNew([
            'sn' => $device->serialNumber
        ]);

        $lastSeen               = Carbon::parse($device->lastSeenDate);
        $registrationDate       = Carbon::parse($device->registrationDate);
        $servicePeriod          = Carbon::parse($device->servicePeriod);
        $warrantyExpire         = Carbon::parse($device->warrantyExpire);

        $model->name            = $device->name;
        $model->model           = $device->model;
        $model->last_seen_ts    = $lastSeen->timestamp;
        $model->last_seen_tz    = $lastSeen->timezoneName;
        $model->hidden          = $device->hidden ? 1 : 0;
        $model->active_ticket   = $device->activeTickets;
        $model->service_plan    = $device->servicePlan;
        $model->registration_date_ts    = $registrationDate->timestamp;
        $model->registration_date_tz    = $registrationDate->timezoneName;
        $model->service_period_ts       = $servicePeriod->timestamp;
        $model->service_period_tz       = $servicePeriod->timezoneName;
        $model->warranty_expire_ts      = $warrantyExpire->timestamp;
        $model->warranty_expire_tz      = $warrantyExpire->timezoneName;
        $model->ls_used_size    = $device->localStorageUsed->size;
        $model->ls_used_unit    = $device->localStorageUsed->units;
        $model->ls_available_size = $device->localStorageAvailable->size;
        $model->ls_available_unit = $device->localStorageAvailable->units;
        $model->offsite_storage_used    = $device->offsiteStorageUsed->size;
        $model->offsite_storage_unit    = $device->offsiteStorageUsed->unit;
        $model->internal_ip     = $device->internalIP;
        $model->agent_count     = $device->agentCount;
        $model->share_count     = $device->shareCount;
        $model->alert_count     = $device->alertCount;

        $model->synced_at         = now();

        $model->save();

        $model->saveStorageHistory();

        return $model;
    }
}