<?php

namespace App;

use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_users','user_id', 'company_id')
            ->using(CompanyUser::class)
            ->withTimestamps();
    }
}
