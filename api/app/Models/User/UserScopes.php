<?php 

namespace App\Models\User;

trait UserScopes {

    public function scopeActive($query)
    {
        return $query->where('active',true);
    }

}