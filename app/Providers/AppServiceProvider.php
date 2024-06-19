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
    public function boot()
    {
        \Illuminate\Support\Facades\App::setLocale('fr');

        \Illuminate\Support\Facades\Validator::replacer('required', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, __('validation.required', ['attribute' => $attribute]));
        });
    }
}
