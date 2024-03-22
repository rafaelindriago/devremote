<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('phone', fn(string $attribute, string $value) => preg_match('/^[\+][\d]{1,3}[\s][\d]{5,10}$/', $value));
    }
}
