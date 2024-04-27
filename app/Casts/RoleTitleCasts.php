<?php

namespace App\Casts;

use App\Models\Role;

class RoleTitleCast
{
    public function get($model, $key, $value, $attributes)
    {
        if ($value === null) {
            return null;
        }

        $role = Role::find($value);

        return $role ? $role->title : null;
    }

    public function set($model, $key, $value, $attributes)
    {
        return $value;
    }
}
