<?php


namespace TRF4\UI\Component;


use TRF4\UI\Element;
use TRF4\UI\Element\AbstractElement;
use TRF4\UI\Labeled\AbstractElementWithLabel;
use TRF4\UI\UI;

abstract class Textarea extends AbstractElementWithLabel
{
    /** @var Element\GenericElement */
    public $_textarea;
    /** @var Element\GenericElement */
    public $_wrapper;

    public function __construct(string $labelInnerHtml, string $name)
    {
        parent::__construct($labelInnerHtml);
        $this->_textarea = UI::el('textarea');
        $this->_wrapper = UI::el('div');
        $this->name($name);
    }

    public function getDefaultElement(): AbstractElement
    {
        return $this->_textarea;
    }
}
