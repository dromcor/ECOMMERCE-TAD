<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'price_cents',
        'stock',
        'activo',
        'images',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'images' => 'array',
    ];

    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'category_product',
            'producto_id',
            'categoria_id'
        )->withTimestamps();
    }
}