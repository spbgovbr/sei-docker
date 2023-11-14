<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Comparator extends Component
{
    /** @var string */
    public $leftSideId;
    /** @var array */
    public $tabs;

    /**
     * Create a new component instance.
     *
     * @param string $leftSideId
     * @param array $tabs
     */
    public function __construct(?string $leftSideId = '', array $tabs)
    {
        $this->tabs = $tabs;
        $this->leftSideId = $leftSideId;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.comparator');
    }
}
