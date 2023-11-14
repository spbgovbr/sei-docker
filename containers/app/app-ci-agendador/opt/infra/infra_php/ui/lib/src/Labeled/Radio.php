<?php


namespace TRF4\UI\Labeled;


use TRF4\UI\Element\AbstractElement;
use TRF4\UI\Element\GenericElement;
use TRF4\UI\UI;

abstract class Radio extends AbstractElementWithLabel
{


    /**
     * @var GenericElement
     */
    public $_input;

    public function __construct(string $labelInnerHtml, string $value, string $id)
    {
        parent::__construct($labelInnerHtml);
        $this->_input = UI::el('input');
        $this->type('radio');
        $this->value($value);
        $this->id($id);
        $this->disableIdEqualToName = true;
    }

    public function getDefaultElement(): AbstractElement
    {
        return $this->_input;
    }
}
