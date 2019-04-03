<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable     = ['sn'];

    protected $primaryKey   = 'sn';

    public $incrementing    = false;

    public $casts  = [
        'registration_date' => 'datetime',
        'service_period' => 'datetime',
        'warranty_expire' => 'datetime',
        'last_seen'         => 'datetime:c',
    ];

    protected $appends      = [
        'storage_capacity',
//        'last_seen',
//        'registration_date',
//        'warranty_expire',
    ];

    public function storageHistories()
    {
        return $this->hasMany(DeviceStorageHistory::class, 'device_sn','sn');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assets()
    {
        return $this->hasMany(DeviceAsset::class, 'device_sn', 'sn');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function agents()
    {
        return $this->assets()->agent();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shares()
    {
        return $this->assets()->share();
    }


    public function getStorageCapacityAttribute()
    {
        return round($this->ls_used_size / ($this->ls_used_size + $this->ls_available_size) * 100);
    }

    public function getStorageTrendAttribute()
    {
        return null;
    }

    public function getHumanizeStorageUsedAttribute()
    {
        $kb     = 1000;
        $mb     = $kb * 1000;
        $gb     = $mb * 1000;
        $tb     = $gb * 1000;
        $size   = $this->offsite_storage_used;

        if($size > $tb)
            return $size / $tb . ' TB';

        if($size > $gb)
            return $size / $gb . ' GB';

        if($size > $mb)
            return $size / $mb . ' MB';

        if($size > $kb)
            return $size / $kb . 'KB';

        return $size . 'B';

    }


    public function saveStorageHistory()
    {
        $this->storageHistories()->create([
            'ls_used_size'  => $this->ls_used_size,
            'ls_used_unit'  => $this->ls_used_unit,
            'ls_available_size' => $this->ls_available_size,
            'ls_available_unit' => $this->ls_available_unit,
            'offsite_storage_used'  => $this->offsite_storage_used,
            'offsite_storage_unit'  => $this->offsite_storage_unit
        ]);
    }
}
