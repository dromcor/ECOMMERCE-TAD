<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register the test command when running in console
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Console\Commands\SendTestOrderEmail::class,
                \App\Console\Commands\TestCheckoutFlow::class,
            ]);
        }
        // Register a simple 'admin' gate used across routes and controllers
        \Illuminate\Support\Facades\Gate::define('admin', function ($user) {
            return $user && method_exists($user, 'isAdmin') && $user->isAdmin();
        });

        // Fortify setup: use simple create and authenticate callbacks
        if (class_exists(\Laravel\Fortify\Fortify::class)) {
            \Laravel\Fortify\Fortify::createUsersUsing(function () {
                return new class implements \Laravel\Fortify\Contracts\CreatesNewUsers {
                    public function create(array $input) {
                        // Validate if needed, or assume validation is done by Fortify/custom requests
                        return \App\Models\User::create([
                            'name' => $input['name'],
                            'email' => $input['email'],
                            'password' => \Illuminate\Support\Facades\Hash::make($input['password']),
                        ]);
                    }
                };
            });

            \Laravel\Fortify\Fortify::authenticateUsing(function (\Illuminate\Http\Request $request) {
                $user = \App\Models\User::where('email', $request->email)->first();
                if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
                    return $user;
                }
            });

            // Register simple view callbacks
            \Laravel\Fortify\Fortify::loginView(function () {
                return view('auth.login');
            });

            \Laravel\Fortify\Fortify::registerView(function () {
                return view('auth.register');
            });
        }

        // Migrate guest cart to user cart on login/registration
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            $user = $event->user;
            $request = request();
            $token = $request->cookie('cart_session');
            if ($token) {
                $guestCart = \App\Models\Cart::where('session_id', $token)->first();
                if ($guestCart) {
                    $userCart = \App\Models\Cart::firstOrCreate(['usuario_id' => $user->id]);
                    // move lines
                    foreach ($guestCart->lines as $line) {
                        $existing = \App\Models\CartLine::where('cart_id', $userCart->id)->where('producto_id', $line->producto_id)->first();
                        if ($existing) {
                            $existing->cantidad += $line->cantidad;
                            $existing->save();
                        } else {
                            $line->cart_id = $userCart->id;
                            $line->save();
                        }
                    }
                    $guestCart->delete();
                }
            }
        });

        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Registered::class, function ($event) {
            $user = $event->user;
            $request = request();
            $token = $request->cookie('cart_session');
            if ($token) {
                $guestCart = \App\Models\Cart::where('session_id', $token)->first();
                if ($guestCart) {
                    $userCart = \App\Models\Cart::firstOrCreate(['usuario_id' => $user->id]);
                    foreach ($guestCart->lines as $line) {
                        $existing = \App\Models\CartLine::where('cart_id', $userCart->id)->where('producto_id', $line->producto_id)->first();
                        if ($existing) {
                            $existing->cantidad += $line->cantidad;
                            $existing->save();
                        } else {
                            $line->cart_id = $userCart->id;
                            $line->save();
                        }
                    }
                    $guestCart->delete();
                }
            }
        });
    }
}
