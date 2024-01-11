<?php

namespace TRF4\UI\Infra;

use TRF4\UI\Element\AbstractElement;
use TRF4\UI\UI;

class Alert extends \TRF4\UI\Component\Alert
{
    /** @var AbstractElement */
    public $_div;

    public function __construct(string $innerHTML)
    {
        parent::__construct($innerHTML);
        $this->_div = UI::el('div', $this->innerHTML)
            ->role('alert')
            ->class("alert");
    }

    public function render(): string
    {
        return $this->_div->render();
    }

    public function getDefaultElement(): AbstractElement
    {
        return $this->_div;
    }
}
