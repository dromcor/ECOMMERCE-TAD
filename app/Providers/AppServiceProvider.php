<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LoginResponse::class, function () {
            return new class implements LoginResponse {
                public function toResponse($request)
                {
                    if ($request->user() && $request->user()->isAdmin()) {
                        return redirect()->route('admin.index');
                    }

                    return redirect()->route('products.index');
                }
            };
        });
    }

    public function boot(): void
    {
        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });
    }
}