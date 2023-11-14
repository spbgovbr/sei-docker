<?php

namespace TRF4\UI\Infra;

use TRF4\UI\Component\Textarea as BaseTextarea;

class Textarea extends BaseTextarea
{

    public function __construct(string $labelInnerHtml, string $name)
    {
        parent::__construct($labelInnerHtml, $name);
        $this->_wrapper->class('infraInputGroup');
        $this->_label->class('infraLabelOpcional');
    }

    public function render(): string
    {
        $label = "";
        if($this->hasLabel()){
            $this->_label->for($this->getDefaultElement()->getAttrId());
            $label = $this->_label;
        }

        $this->_wrapper->innerHTML(
            $label .
            $this->_textarea
        );

        return $this->_wrapper->render();
    }
}
