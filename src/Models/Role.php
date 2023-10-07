<?php

namespace MirHamit\ACL\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table = 'roles';

    protected $fillable = [
        'name',
        'slug',
        'label',
        'parent_id',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id');
    }

}
