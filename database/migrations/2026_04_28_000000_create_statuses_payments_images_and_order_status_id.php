<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Order statuses
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('nombre')->unique();
            $table->timestamps();
        });

        // Payments table to store payment attempts/records
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pedido_id');
            $table->string('metodo')->nullable();
            $table->integer('amount_cents')->unsigned()->nullable();
            $table->string('status')->nullable();
            $table->string('provider_ref')->nullable();
            $table->json('raw')->nullable();
            $table->timestamps();

            $table->foreign('pedido_id')->references('id')->on('orders')->onDelete('cascade');
        });

        // Product images
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id');
            $table->string('path');
            $table->string('alt')->nullable();
            $table->integer('orden')->default(0);
            $table->timestamps();

            $table->foreign('producto_id')->references('id')->on('products')->onDelete('cascade');
        });

        // Add status_id to orders (nullable, keep existing `estado` for compatibility)
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'status_id')) {
                $table->smallInteger('status_id')->unsigned()->nullable()->after('descuento_id');
                $table->foreign('status_id')->references('id')->on('order_statuses')->onDelete('set null');
            }
        });

        // Seed basic statuses
        if (app()->runningInConsole()) {
            DB::table('order_statuses')->insert([
                ['nombre' => 'pending', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'paid', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'shipped', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'delivered', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'cancelled', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'status_id')) {
                $table->dropForeign(['status_id']);
                $table->dropColumn('status_id');
            }
        });

        Schema::dropIfExists('product_images');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_statuses');
    }
};
