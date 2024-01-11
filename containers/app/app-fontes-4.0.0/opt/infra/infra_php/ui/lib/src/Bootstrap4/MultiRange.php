<?php


namespace TRF4\UI\Bootstrap4;


use TRF4\UI\Element\GenericElement;
use TRF4\UI\Renderer\Bootstrap4;
use TRF4\UI\UI;

class MultiRange extends \TRF4\UI\Component\MultiRange
{
    use RangeActions;

    public function __construct(string $labelInnerHtml, string $name, float $v1, float $v2)
    {
        parent::__construct($labelInnerHtml, $name, $v1, $v2);

        $this->_wrapper = UI::el('div')->class('range-slider');

        $this->_wrapper->class('multi-range');

        $this->_wrapper->class('range-control d-flex');

        $this->_selection->class('progress-bar progress-bar-striped progress-bar-animated');

        $this->_selection->class('multi-slider-selection');
    }

    protected function calculateStep(): float
    {
        if ($this->get('value')) {
            $step = $this->calculateStepFromValue($this->get('value'));
        } elseif ($this->getEndValue()) {
            $step = $this->calculateStepFromValue($this->get('value'));
        } else {
            $step = 1;
        }

        return $step;
    }
}
