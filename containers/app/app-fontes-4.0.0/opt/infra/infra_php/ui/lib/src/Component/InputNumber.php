<?php

namespace TRF4\UI\Component;


use TRF4\UI\Element\AbstractElement;
use TRF4\UI\Element\GenericElement;
use TRF4\UI\Labeled\AbstractElementWithLabel;
use TRF4\UI\UI;

/**
 * Class InputNumber
 * @package TRF4\UI
 * @method min($number)
 * @method max($number)
 */
abstract class InputNumber extends AbstractInputWithLabel
{
    use Customizable;

    /** @var GenericElement */
    public $_input;
    protected $type = 'number';
}
