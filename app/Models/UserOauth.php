<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Leo108\CASServer\OAuth\Plugin;
use Leo108\CASServer\OAuth\PluginCenter;

class UserOauth extends Model
{
    protected $table = 'user_oauth';
    protected $primaryKey = 'user_id';
    protected $appends = ['plugins'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getProfileAttribute()
    {
        return json_decode($this->attributes['profile']);
    }

    public function setProfileAttribute($value)
    {
        $this->attributes['profile'] = json_encode($value);
    }

    public function setProfile($fieldName, $value)
    {
        $profile = $this->getProfileAttribute();
        if ($value) {
            $profile[$fieldName] = $value;
        } else {
            unset($profile[$fieldName]);
        }
        $this->setProfileAttribute($profile);
    }

    public function getPluginsAttribute()
    {
        $results = [];
        $plugins = app(PluginCenter::class)->getAll();
        foreach ($plugins as $key => $plugin) {
            /* @var Plugin $plugin */
            $results[$key] = [
                'name'   => $plugin->getName(),
                'logo'   => $plugin->getLogo(),
                'status' => !empty($this->attributes[$plugin->getFieldName()]),
            ];
        }

        return $results;
    }
}
