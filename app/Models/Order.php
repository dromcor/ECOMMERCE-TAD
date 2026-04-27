<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['usuario_id','direccion_id','descuento_id','estado','precio_total_cents','fecha'];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function lines()
    {
        return $this->hasMany(OrderLine::class, 'pedido_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
