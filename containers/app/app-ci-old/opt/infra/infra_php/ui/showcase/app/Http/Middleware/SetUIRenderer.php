<?php


namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use TRF4\UI\Config;
use TRF4\UI\Renderer\Bootstrap4;

class SetUIRenderer
{
    public function handle(Request $request, Closure $next)
    {
        $defaultRenderer = Bootstrap4::class;
        $requestRenderer = str_replace('-', '\\', $request->get('renderer'));

        if (class_exists($requestRenderer)) {
            $rendererClass = $requestRenderer;
        } else {
            if ($requestRenderer) {
                echo "Renderer do tipo '$requestRenderer' não existe; usando default($defaultRenderer)"; //todo ver se tem como gerar um alerta
            }

            $rendererClass = $defaultRenderer;
        }
        $renderer = new $rendererClass();
        Config::setRenderer($renderer);
        return $next($request);
    }

}
