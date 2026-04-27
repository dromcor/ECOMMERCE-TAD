<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLine extends Model
{
    use HasFactory;

    protected $fillable = ['pedido_id','producto_id','cantidad','precio_unitario_cents'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'producto_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'pedido_id');
    }
}
