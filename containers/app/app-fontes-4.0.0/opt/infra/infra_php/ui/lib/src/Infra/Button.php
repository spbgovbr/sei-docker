<?php

namespace TRF4\UI\Infra;

use TRF4\UI\Component\Button as BaseButton;

class Button extends BaseButton
{
    public function render(): string
    {
        $this->class('infraButton');
        return parent::render();
    }
}
