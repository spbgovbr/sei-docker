<?php


namespace TRF4\UI\Infra;

use TRF4\UI\Element\GenericElement;
use TRF4\UI\UI;

class FileUpload extends \TRF4\UI\Component\FileUpload
{
    /** @var GenericElement */
    public $_wrapper;

    public function __construct(string $labelInnerHtml, ?string $name = null) {
        parent::__construct($labelInnerHtml, $name);
        $this->_wrapper = UI::el('div')->class('infraInputGroup');
        if($this->hasLabel()){
            $this->_label->class('infraLabelOpcional');
        }
        $this->class('infraFile');
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
