<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['nombre','descripcion','price_cents','stock','activo','images'];

    protected $casts = [
        'images' => 'array',
        'activo' => 'boolean',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product', 'producto_id', 'categoria_id');
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites', 'producto_id', 'usuario_id');
    }
}
