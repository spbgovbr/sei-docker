<?php


namespace TRF4\UI\Component;


use TRF4\UI\Element\AbstractElement;
use TRF4\UI\Element\Component;
use TRF4\UI\Element\GenericElement;
use TRF4\UI\UI;

abstract class IconButton extends Component
{
    /** @var GenericElement */
    public $_a;
    const TYPE_SEARCH = 1;

    public function __construct(?string $title)
    {
        $this->_a = UI::el('a');

        if ($title) {
            $this->_a->title($title);
        }
    }

    public function getDefaultElement(): AbstractElement
    {
        return $this->_a;
    }

    public function search(): self
    {
        $this->type = self::TYPE_SEARCH;
        return $this;
    }
}