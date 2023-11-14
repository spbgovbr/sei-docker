<?php

namespace TRF4\UI\Infra;

class Select extends \TRF4\UI\Labeled\Select
{

    protected function buildElements(): void
    {
        $this->class('infraSelect');
        $this->_wrapper->class('infraInputGroup');
        $this->_select->innerHTML = $this->optionsToHtml();
        $this->_label
            ->class('infraLabelOpcional')
            ->for($this->_select->getAttrId());
    }

    protected function assembleAndPrintElements(): string
    {
        $label = "";
        if($this->hasLabel()){
            $label = $this->_label;
        }

        $this->_wrapper->innerHTML(
            $this->_label .
            $this->_select
        );

        return $this->_wrapper->render();
    }
}
