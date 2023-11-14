<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        /*Blade::directive('includeRaw', function (string $path, ?string $alias = null) {
            return Blade::include($path, $alias);
        });*/
        \Blade::directive('includeRaw', function ($expression) {
            //todo
            return "<?echo 1;?>";
            /*//return "<?php $var = view('$expression')->render(); ?>";
            // ... do whatever you need to do to correctly set $templateName
            return "<?php echo \$__env->make( '$expression' , array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>";*/
        });
    }
}
