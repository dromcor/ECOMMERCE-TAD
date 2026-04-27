<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->string('calle');
            $table->string('ciudad');
            $table->string('codigo_postal');
            $table->string('pais');
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pedido_id');
            $table->date('fecha_emision')->nullable();
            $table->text('datos_fiscales')->nullable();
            $table->float('total_impuestos')->nullable();
            $table->text('direccion_facturacion')->nullable();
            $table->timestamps();

            $table->foreign('pedido_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('addresses');
    }
};
