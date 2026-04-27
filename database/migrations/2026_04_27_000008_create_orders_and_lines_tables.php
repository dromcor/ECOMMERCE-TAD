<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->float('porcentaje')->nullable();
            $table->date('fecha_caducidad')->nullable();
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('direccion_id')->nullable();
            $table->unsignedBigInteger('descuento_id')->nullable();
            $table->string('estado')->default('pending');
            $table->integer('precio_total_cents')->unsigned();
            $table->timestamp('fecha')->useCurrent();
            $table->timestamps();

            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('direccion_id')->references('id')->on('addresses')->onDelete('set null');
            $table->foreign('descuento_id')->references('id')->on('discounts')->onDelete('set null');
        });

        Schema::create('order_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pedido_id');
            $table->unsignedBigInteger('producto_id');
            $table->integer('cantidad');
            $table->integer('precio_unitario_cents')->unsigned();
            $table->timestamps();

            $table->foreign('pedido_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('producto_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_lines');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('discounts');
    }
};
