<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StripeWebhookController;

Route::get('/', function () {
    // Redirect root to the shop products listing instead of the default Laravel welcome page
    return redirect()->route('products.index');
});

// Load shop routes
if (file_exists(base_path('routes/shop.php'))) {
    require base_path('routes/shop.php');
}

// Stripe webhook endpoint (POST)
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

// Local testing route: send an order confirmation email for order id=1
Route::get('/test/send-order-email', function () {
    if (! app()->environment('local')) {
        abort(403);
    }

    $order = \App\Models\Order::with('lines.product', 'user')->first();
    if (! $order) {
        return 'No hay pedidos para enviar.';
    }

    \Mail::to($order->user->email ?? 'test@example.com')->queue(new \App\Mail\OrderConfirmed($order));
    return 'Email encolado para el pedido #'.$order->id;
});

// Checkout routes (basic)
use App\Http\Controllers\CheckoutController;
Route::get('/checkout/{order}/pay', [CheckoutController::class, 'pay'])->name('checkout.pay');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
Route::post('/checkout/create', [CheckoutController::class, 'create'])->middleware('auth')->name('checkout.create');

use App\Http\Controllers\FavoriteController;
Route::post('/favorite/toggle', [FavoriteController::class, 'toggle'])->name('favorite.toggle');
use App\Http\Controllers\CartController;
Route::post('/cart/add', [CartController::class, 'add'])->middleware('auth')->name('cart.add');
Route::get('/cart', function () { return view('cart.index'); })->name('cart.index');

// Admin routes
use App\Http\Controllers\Admin\ProductController as AdminProductController;
Route::prefix('admin')->name('admin.')->middleware('can:admin')->group(function () {
    Route::resource('products', AdminProductController::class);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
});
