<?php

namespace App;

use App\Models\UserOauth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Leo108\CAS\Contracts\Models\UserModel;

class User extends Authenticatable implements UserModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'real_name',
        'enabled',
        'admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function oauth()
    {
        return $this->hasOne(UserOauth::class);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return $this
     */
    public function getEloquentModel()
    {
        return $this;
    }

    /**
     * @return array
     */
    public function getCASAttributes()
    {
        return [
            'email'         => $this->email,
            'real_name'     => $this->real_name,
            'oauth_profile' => json_encode($this->oauth->profile),
        ];
    }
}
