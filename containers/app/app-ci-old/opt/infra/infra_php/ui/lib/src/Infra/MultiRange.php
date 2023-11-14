<?php


namespace TRF4\UI\Infra;


use TRF4\UI\Element\GenericElement;
use TRF4\UI\UI;

class MultiRange extends \TRF4\UI\Component\MultiRange
{
    /** @var GenericElement */
    public $_wrapper;

    public function __construct(string $labelInnerHtml, ?string $name = null, float $v1, float $v2 ) {
        parent::__construct($labelInnerHtml, $name, $v1, $v2);
        $this->_wrapper = UI::el('div')->class('infraInputGroup');
        if($this->hasLabel()){
            $this->_label->class('infraLabelOpcional');
        }
        $this->class('infraRange');
    }


    protected function buildElements(): void {
        $label = ""; 
        if($this->hasLabel()){
            $this->_label->for($this->_input->getAttrId());
            $label = $this->_label;     
        } 

        $this->_wrapper->innerHTML(
            $label .
            $this->_input 
        );
    }

    protected function assembleAndPrintElements(): string {
        return $this->_wrapper->render();

    }
}
