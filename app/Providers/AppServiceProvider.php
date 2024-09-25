<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

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
        //
        // $importantVariable = DB::table('settings')->where('key', 'important_variable')->value('value');

        // // Bind it to the service container
        // app()->singleton('importantVariable', function () use ($importantVariable) {
        //     return $importantVariable;
        // });
        
        // DB::listen(function ($query) {
        //     \Log::info(
        //         $query->sql,
        //         $query->bindings,
        //         $query->time
        //     );
        // });
    }
}
