<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Inserta categorías y productos orientados a una tienda de cervezas.
     */
    public function run(): void
    {
        DB::table('category_product')->delete();
        DB::table('products')->delete();
        DB::table('categories')->delete();

        $categorias = [
            [
                'nombre' => 'Lager',
                'descripcion' => 'Cervezas suaves, refrescantes y fáciles de beber.',
            ],
            [
                'nombre' => 'IPA',
                'descripcion' => 'Cervezas con más aroma, amargor y presencia de lúpulo.',
            ],
            [
                'nombre' => 'Tostada',
                'descripcion' => 'Cervezas con notas de malta, caramelo y cuerpo medio.',
            ],
            [
                'nombre' => 'Negra',
                'descripcion' => 'Cervezas oscuras, con notas de café, cacao o cereal tostado.',
            ],
            [
                'nombre' => 'Artesanal',
                'descripcion' => 'Cervezas de producción más cuidada y estilos variados.',
            ],
            [
                'nombre' => 'Sin alcohol',
                'descripcion' => 'Opciones sin alcohol o de baja graduación.',
            ],
            [
                'nombre' => 'Pack degustación',
                'descripcion' => 'Lotes pensados para probar diferentes estilos.',
            ],
        ];

        $idsCategorias = [];

        foreach ($categorias as $categoria) {
            $idsCategorias[$categoria['nombre']] = DB::table('categories')->insertGetId([
                'nombre' => $categoria['nombre'],
                'descripcion' => $categoria['descripcion'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $productos = [
            [
                'nombre' => 'Cruzcampo Especial',
                'descripcion' => 'Cerveza lager andaluza, ligera, refrescante y pensada para consumo diario. Ideal para aperitivos y comidas informales.',
                'price_cents' => 120,
                'stock' => 80,
                'categorias' => ['Lager'],
                'imagen' => 'https://images.unsplash.com/photo-1608270586620-248524c67de9?w=900',
            ],
            [
                'nombre' => 'Estrella Galicia Especial',
                'descripcion' => 'Lager española con equilibrio entre malta y amargor. Una cerveza clásica para quienes buscan un sabor reconocible.',
                'price_cents' => 140,
                'stock' => 75,
                'categorias' => ['Lager'],
                'imagen' => 'https://images.unsplash.com/photo-1518176258769-f227c798150e?w=900',
            ],
            [
                'nombre' => 'Alhambra Reserva 1925',
                'descripcion' => 'Cerveza intensa y elegante, con más cuerpo que una lager normal y un toque maltoso muy característico.',
                'price_cents' => 190,
                'stock' => 60,
                'categorias' => ['Lager', 'Tostada'],
                'imagen' => 'https://images.unsplash.com/photo-1566633806327-68e152aaf26d?w=900',
            ],
            [
                'nombre' => 'Mahou Cinco Estrellas',
                'descripcion' => 'Cerveza lager equilibrada, fácil de beber y muy reconocida en España. Buena opción para acompañar tapas.',
                'price_cents' => 130,
                'stock' => 70,
                'categorias' => ['Lager'],
                'imagen' => 'https://images.unsplash.com/photo-1600788886242-5c96aabe3757?w=900',
            ],
            [
                'nombre' => 'Voll-Damm Doble Malta',
                'descripcion' => 'Cerveza con más cuerpo e intensidad, elaborada con doble malta. Recomendada para quienes buscan un sabor más potente.',
                'price_cents' => 165,
                'stock' => 55,
                'categorias' => ['Tostada'],
                'imagen' => 'https://images.unsplash.com/photo-1608270586620-248524c67de9?w=900',
            ],
            [
                'nombre' => 'La Virgen Jamonera',
                'descripcion' => 'Cerveza artesana tipo lager, pensada para acompañar aperitivos, embutidos y platos sencillos.',
                'price_cents' => 240,
                'stock' => 35,
                'categorias' => ['Lager', 'Artesanal'],
                'imagen' => 'https://images.unsplash.com/photo-1571613316887-6f8d5cbf7ef7?w=900',
            ],
            [
                'nombre' => 'La Sagra IPA',
                'descripcion' => 'IPA artesana con aroma intenso, amargor marcado y notas cítricas. Para usuarios que quieren probar algo diferente.',
                'price_cents' => 260,
                'stock' => 30,
                'categorias' => ['IPA', 'Artesanal'],
                'imagen' => 'https://images.unsplash.com/photo-1535958636474-b021ee887b13?w=900',
            ],
            [
                'nombre' => 'BrewDog Punk IPA',
                'descripcion' => 'IPA moderna, aromática y con notas tropicales. Una opción reconocida dentro del estilo IPA.',
                'price_cents' => 290,
                'stock' => 28,
                'categorias' => ['IPA', 'Artesanal'],
                'imagen' => 'https://images.unsplash.com/photo-1567696911980-2eed69a46042?w=900',
            ],
            [
                'nombre' => 'Founders All Day IPA',
                'descripcion' => 'Session IPA de trago más ligero, con buen aroma a lúpulo pero menos pesada que otras IPA.',
                'price_cents' => 310,
                'stock' => 22,
                'categorias' => ['IPA'],
                'imagen' => 'https://images.unsplash.com/photo-1618885472179-5e474019f2a9?w=900',
            ],
            [
                'nombre' => 'Guinness Draught',
                'descripcion' => 'Cerveza negra irlandesa, cremosa y con notas tostadas. Recomendada para quienes buscan una cerveza distinta.',
                'price_cents' => 230,
                'stock' => 40,
                'categorias' => ['Negra'],
                'imagen' => 'https://images.unsplash.com/photo-1599599810769-bcde5a160d32?w=900',
            ],
            [
                'nombre' => 'Cerveza Artesana Tostada del Sur',
                'descripcion' => 'Cerveza tostada de estilo artesanal, con notas de caramelo, pan tostado y final suave.',
                'price_cents' => 250,
                'stock' => 25,
                'categorias' => ['Tostada', 'Artesanal'],
                'imagen' => 'https://images.unsplash.com/photo-1608270586620-248524c67de9?w=900',
            ],
            [
                'nombre' => 'Pack Degustación Cervecero',
                'descripcion' => 'Pack de seis cervezas variadas: lager, tostada, IPA y negra. Ideal para probar diferentes estilos.',
                'price_cents' => 1290,
                'stock' => 18,
                'categorias' => ['Pack degustación', 'Artesanal'],
                'imagen' => 'https://images.unsplash.com/photo-1518099074172-2e47ee6cfdc0?w=900',
            ],
            [
                'nombre' => 'Pack Cruzcampo + Estrella Galicia',
                'descripcion' => 'Lote básico con cervezas nacionales conocidas. Pensado para reuniones, comidas o consumo habitual.',
                'price_cents' => 890,
                'stock' => 32,
                'categorias' => ['Pack degustación', 'Lager'],
                'imagen' => 'https://images.unsplash.com/photo-1612528443702-f6741f70a049?w=900',
            ],
            [
                'nombre' => 'Estrella Galicia 0,0',
                'descripcion' => 'Cerveza sin alcohol, fresca y ligera. Buena alternativa para quienes quieren evitar el alcohol.',
                'price_cents' => 125,
                'stock' => 50,
                'categorias' => ['Sin alcohol', 'Lager'],
                'imagen' => 'https://images.unsplash.com/photo-1566633806327-68e152aaf26d?w=900',
            ],
            [
                'nombre' => 'Cerveza Artesana de Trigo',
                'descripcion' => 'Cerveza suave, turbia y aromática, con notas de cereal y final refrescante.',
                'price_cents' => 245,
                'stock' => 26,
                'categorias' => ['Artesanal'],
                'imagen' => 'https://images.unsplash.com/photo-1571613316887-6f8d5cbf7ef7?w=900',
            ],
        ];

        foreach ($productos as $producto) {
            $productoId = DB::table('products')->insertGetId([
                'nombre' => $producto['nombre'],
                'descripcion' => $producto['descripcion'],
                'price_cents' => $producto['price_cents'],
                'stock' => $producto['stock'],
                'activo' => true,
                'images' => json_encode([$producto['imagen']]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($producto['categorias'] as $nombreCategoria) {
                if (isset($idsCategorias[$nombreCategoria])) {
                    DB::table('category_product')->insert([
                        'categoria_id' => $idsCategorias[$nombreCategoria],
                        'producto_id' => $productoId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}