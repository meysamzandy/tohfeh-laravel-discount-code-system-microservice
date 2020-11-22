<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extendImplicit('exclude_if_not_null', function($attribute, $value, $parameters, $validator) {
            dd($attribute, $value, $parameters, $validator);
            (new \Illuminate\Validation\Validator)->validateExcludeIf($attribute, $value, $parameters);
            if($value !== null) return true; // this line checks for 'empty' strings
            return $value === false;
        });
    }
}
