<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DeviceAssetBackup extends Model
{

    protected $fillable     = ['timestamp'];

    public $incrementing    = false;

    protected $casts        = [
        'local_verify_errors'   => 'array'
    ];

    protected $appends      = [
        'backup_time',
        'summary_status',
        'error_messages',
    ];

    protected $_error_messages = null;

    protected static function boot()
    {
        parent::boot();
        static::creating(function(self $model) {
            $model->id = Str::orderedUuid();
        });
    }

    public function scopeLatest(Builder $query)
    {
        return $query->orderBy('timestamp', 'desc');
    }

    public function asset()
    {
        return $this->belongsTo(DeviceAsset::class,'asset_id');
    }


    public function getSummaryStatusAttribute()
    {
        $success        = 0;

        if($this->backup_status == 'success')
            $success++;

        if($this->local_verify_status == 'success')
            $success++;

        if($this->screenshot_verify_image)
            $success++;


        if($success == 1)
            return 'half';

        if($success == 2)
            return 'half';

        if($success == 3)
            return 'success';

        return 'failed';
    }

    public function getErrorMessagesAttribute()
    {
        if($this->_error_messages === null ) {
            $errors     =   [$this->backup_status_message];

            foreach($this->local_verify_errors ?? [] as $e) {
                $errors[]   = $e['errorType'] ?? null;
            }

            $this->_error_messages =  array_filter($errors);
        }

        return $this->_error_messages;
    }

    public function getBackupTimeAttribute()
    {
        return Carbon::createFromTimestamp($this->timestamp);
    }
}
