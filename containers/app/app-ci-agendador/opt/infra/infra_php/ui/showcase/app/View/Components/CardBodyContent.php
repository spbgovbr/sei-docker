<?php

namespace App\View\Components;

use App\Http\View\DocsHelper\TestClass;
use Illuminate\View\Component;

class CardBodyContent extends Component
{
    /** @var TestClass */
    public $testClass;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(TestClass $testClass)
    {
        $this->testClass = $testClass;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.card-body-content');
    }
}
