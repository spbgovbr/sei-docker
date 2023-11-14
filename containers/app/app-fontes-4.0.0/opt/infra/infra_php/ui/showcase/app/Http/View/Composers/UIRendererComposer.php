<?php

namespace App\Http\View\Composers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use TRF4\UI\Renderer\Bootstrap4;
use TRF4\UI\Renderer\Infra;

class UIRendererComposer
{
    /** @var Request */
    protected $request;

    /**
     * Create a new profile composer.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        // Dependencies automatically resolved by service container...
        $this->request = $request;
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $defaultRenderer = $this->buildKeySlug(Bootstrap4::class);

        $renderer = session('renderer') ?: $defaultRenderer;

        $view->with('renderer', $renderer);
        $view->with('renderers', $this->getRenderers());
        $view->with('template', $this->getTemplateForRenderer($renderer));
    }


    private function getRenderers(): array
    {
        $options = [];
        $children = [
            Bootstrap4::class,
            Infra::class,
        ];
        foreach ($children as $c) {
            $key = $this->buildKeySlug($c);
            $options[$key] = $this->getShortName($c);
        }
        return $options;
    }

    private function getTemplateForRenderer($renderer)
    {
        $class = str_replace('-', '\\', $renderer);
        $class = strtolower($this->getShortName($class));

        return "templates.$class-app";
    }

    private function getShortName($renderer): string
    {
        return (new \ReflectionClass($renderer))->getShortName();
    }

    private function buildKeySlug(string $c): string
    {
        return str_replace('\\', '-', $c);
    }
}
