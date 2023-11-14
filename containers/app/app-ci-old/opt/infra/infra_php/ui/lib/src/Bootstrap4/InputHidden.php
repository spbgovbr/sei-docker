<?php


namespace TRF4\UI\Bootstrap4;


use TRF4\UI\Element\GenericElement;
use TRF4\UI\Renderer\Bootstrap4;
use TRF4\UI\UI;

class InputHidden extends \TRF4\UI\Component\InputHidden
{
    public function __construct(?string $name = null, ?string $value = null) {
        parent::__construct(null, $name);

        if ($value) {
            $this->value($value);
        }
    }

    protected function buildElements(): void { }

    protected function assembleAndPrintElements(): string {

        return $this->_input->render();
    }
}
