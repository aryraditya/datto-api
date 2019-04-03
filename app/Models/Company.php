<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    public function users()
    {
        return $this->belongsToMany(User::class, 'company_users', 'company_id', 'user_id')
            ->withTimestamps()
            ->using(CompanyUser::class);
    }

    public function devices()
    {
        return $this->belongsToMany(Device::class, 'company_devices', 'company_id', 'device_sn')
            ->withTimestamps()
            ->using(CompanyDevice::class);
    }
}
