<?php

namespace ctf0\SimpleMenu\Controllers\Admin\Traits;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;

trait Users
{
    use HasRoles;

    public static function bootUsers()
    {
        static::addGlobalScope('relations', function (Builder $builder) {
            $builder->with(['roles', 'permissions']);
        });
    }

    // Accessor for Avatar
    public function getAvatarAttribute($value)
    {
        return null != $value ?: 'https://www.svgrepo.com/show/13656/user.svg';
    }

    // Mutator for Password
    public function setPasswordAttribute($value)
    {
        if (is_null($value)) {
            $value = $this->password;
        }

        $this->attributes['password'] = app('hash')->needsRehash($value) ? bcrypt($value) : $value;
    }
}
