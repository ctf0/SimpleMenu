<?php

namespace ctf0\SimpleMenu\Models\Traits;

use Spatie\Permission\Traits\HasRoles;

trait SMUsers
{
    use HasRoles;

    // Mutator for Password
    public function setPasswordAttribute($value)
    {
        if (is_null($value)) {
            $value = $this->password;
        }

        $this->attributes['password'] = app('hash')->needsRehash($value) 
        	? app('hash')->make($value) 
        	: $value;
    }
}
