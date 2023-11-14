<?php

namespace TRF4\UI\Component;


use TRF4\UI\Element\AbstractElement;
use TRF4\UI\Element\GenericElement;
use TRF4\UI\Labeled\AbstractElementWithLabel;
use TRF4\UI\UI;

/**
 * Class Range
 * @package TRF4\UI\Component
 * @method Range step(float $value)
 */
abstract class Range extends AbstractElementWithLabel
{
    use Customizable;

    /** @var GenericElement */
    public $_input;
    /** @var GenericElement */
    public $_output;
    /** @var GenericElement */
    public $_selection;
    /** @var bool */
    private $isMultiple = false;

    /**
     * InputRange constructor.
     * @param string|null $labelInnerHtml
     * @param string $name
     * @param int $v1
     * @param int $v2
     */
    public function __construct(?string $labelInnerHtml, string $name, float $v1, float $v2)
    {
        parent::__construct($labelInnerHtml);

        $this->_input = UI::el('input')->type('range');
        $this->_output = UI::el('output');
        $this->_selection = UI::el('div');

        $min = min($v1, $v2);
        $max = max($v1, $v2);

        $this->min($min);
        $this->max($max);

        $this->name($name);
        $this->_output;
    }

    public function multi(): self
    {
        $this->isMultiple = true;
        return $this;
    }

    /**
     * @return AbstractElement
     * @internal
     */
    public function getDefaultElement(): AbstractElement
    {
        return $this->_input;
    }

    protected function isMulti(): bool
    {
        return $this->isMultiple;
    }


}
