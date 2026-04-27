<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['usuario_id','session_id'];

    public function lines()
    {
        return $this->hasMany(CartLine::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
