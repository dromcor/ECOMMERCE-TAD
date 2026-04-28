<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'nombre',
    ];

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'role_user',
            'rol_id',
            'usuario_id'
        )->withTimestamps();
    }
}