<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DeviceAsset extends Model
{
    protected $fillable     = ['name'];

    public $incrementing    = false;

    public $_daily_backups  = null;

    protected static function boot()
    {
        parent::boot();
        static::creating(function(self $model) {
            $model->id  = Str::orderedUuid();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function device()
    {
        return $this->belongsTo(Device::class, 'device_sn', 'sn');
    }

    public function backups()
    {
        return $this->hasMany(DeviceAssetBackup::class,'asset_id');
    }

    public function scopeAgent(Builder $query)
    {
        return $query->where('type', '=', 'agent');
    }

    public function scopeShare(Builder $query)
    {
        return $query->where('type', '=', 'share');
    }



    public function getLastBackups($total = 10)
    {
        return $this->backups()->latest()->limit($total)->get();
    }

    public function getLastDailyBackups($days = 10)
    {
        $key = $this->name . $this->device_sn . $days . now()->format('Y-m-d');

        $backups = Cache::remember($key, now()->addHour(12), function() use ($days) {

            $backups        = collect();

            for($i = 1; $i <= $days; $i++) {
                $date       = now()->subDays($i);
                $backup     = $this->backups()
                    ->whereDate(DB::raw('FROM_UNIXTIME(timestamp)'), '=', $date)
                    ->orderBy('success_count','desc')
                    ->first();

                $backups->push([
                    'date'      => $date,
                    'model'     => $backup
                ]);
            }

            return $backups;
        });

        return $backups;
    }
}
