<?php

namespace ctf0\SimpleMenu\Controllers\Admin\Traits;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;

trait SMUsers
{
    use HasRoles;

    public static function bootSMUsers()
    {
        static::addGlobalScope('relations', function (Builder $builder) {
            $builder->with(['roles', 'permissions']);
        });
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
