<?php

namespace MirHamit\ACL\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
