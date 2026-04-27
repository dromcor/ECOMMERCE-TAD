<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartLine extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id','producto_id','cantidad','price_snapshot_cents'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'producto_id');
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
