<?php


namespace App\Providers;

use App\Http\View\Composers\UIRendererComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppTemplateServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        // Using class based composers...
        View::composer(
            '*', UIRendererComposer::class
        );
    }
}
