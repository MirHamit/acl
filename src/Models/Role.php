<?php

namespace  App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'label',
        'parent_id',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function subRoles()
    {
        return Role::select('*')->where('parent_id',$this->id)->get();
    }

    public function parentRole()
    {
        return Role::select('*')->where('id',$this->parent_id)->get()->first();
    }
}
