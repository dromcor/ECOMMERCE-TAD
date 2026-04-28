<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return redirect()->route('products.index');
});



if (file_exists(base_path('routes/shop.php'))) {
    require base_path('routes/shop.php');
}



Route::post('/cart/add', [CartController::class, 'add'])
    ->middleware('auth')
    ->name('cart.add');

Route::get('/cart', function () {
    return view('cart.index');
})->middleware('auth')->name('cart.index');

Route::post('/cart/line/{line}/increase', [CartController::class, 'increase'])
    ->middleware('auth')
    ->name('cart.increase');

Route::post('/cart/line/{line}/decrease', [CartController::class, 'decrease'])
    ->middleware('auth')
    ->name('cart.decrease');

Route::delete('/cart/line/{line}', [CartController::class, 'remove'])
    ->middleware('auth')
    ->name('cart.remove');



Route::post('/favorite/toggle', [FavoriteController::class, 'toggle'])
    ->middleware('auth')
    ->name('favorite.toggle');




Route::post('/checkout/create', [CheckoutController::class, 'create'])
    ->middleware('auth')
    ->name('checkout.create');

Route::get('/checkout/{order}/pay', [CheckoutController::class, 'pay'])
    ->middleware('auth')
    ->name('checkout.pay');

Route::get('/checkout/success', [CheckoutController::class, 'success'])
    ->name('checkout.success');

Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])
    ->name('checkout.cancel');



Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])
    ->name('stripe.webhook');




Route::get('/test/send-order-email', function () {
    if (! app()->environment('local')) {
        abort(403);
    }

    $order = \App\Models\Order::with('lines.product', 'user')->first();

    if (! $order) {
        return 'No hay pedidos para enviar.';
    }

    Mail::to($order->user->email ?? 'test@example.com')
        ->queue(new \App\Mail\OrderConfirmed($order));

    return 'Email encolado para el pedido #' . $order->id;
});


Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'can:admin'])
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])
            ->name('index');

        Route::resource('admins', AdminUserController::class);

        Route::resource('products', AdminProductController::class);
    });