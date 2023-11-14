<?php

namespace TRF4\UI\Bootstrap4;

use TRF4\UI\Component\Button as BaseButton;

class Button extends BaseButton
{

    public function render(): string
    {

        $this->class('btn btn-sm');

        if (!$this->isPrimary()) {
            $this->class('btn-outline-primary');
        } else {
            $this->class('btn-primary');
        }

        return parent::render();
    }
}
