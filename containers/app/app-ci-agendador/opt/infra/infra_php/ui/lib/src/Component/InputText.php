<?php

namespace TRF4\UI\Component;


use TRF4\UI\Element\AbstractElement;
use TRF4\UI\Element\GenericElement;
use TRF4\UI\Labeled\AbstractElementWithLabel;
use TRF4\UI\UI;

abstract class InputText extends AbstractInputWithLabel
{
    use Customizable;

    /** @var ?string */
    protected $mask = null;
    /** @var bool */
    protected $lazyLoadMask;
    protected $type = 'text';

    /**
     * @param string $mask
     * @param bool $lazyLoadMask
     * @return $this
     */
    public function mask(string $mask, bool $lazyLoadMask = true): self
    {
        $this->mask = $mask;
        $this->lazyLoadMask = $lazyLoadMask;
        return $this;
    }
}
