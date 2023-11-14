<?php

namespace TRF4\UI\Infra;


use TRF4\UI\Element\GenericElement;
use TRF4\UI\UI;

class DateInterval extends \TRF4\UI\Component\Date
{
    /** @var GenericElement */
    public $_wrapper;

    public function __construct(string $labelInnerHtml, string $name)
    {
        parent::__construct($labelInnerHtml, $name);
        $this->_wrapper = UI::el('div')->class('infraInputGroup');
        if($this->hasLabel()) $this->_label->class('infraLabelOpcional');
        $this->class('infraText');
    }

    public function render(): string
    {
        $withTime = $this->withTime;
        $mascara = $withTime ? 'infraMascaraDataHora' : 'infraMascaraData';

        $inputId = $this->getDefaultElement()->getAttrId();
        $startId = $inputId . 'Inicio';
        $endId = $inputId . 'Fim';

        $startDate = new Date('', $startId);
        $endDate = new Date('', $endId);

        $this->onkeypress("return $mascara(this, event);");

        $label = ""; 
        if($this->hasLabel()){
            $this->_label->for($this->_input->getAttrId());
            $label = $this->_label;     
        } 

        $startEndDate = $startDate . $endDate;

        if($this->getHint()){
            if($this->hasLabel()) {
                $hint = UI::el('div')->class('w-100')->innerHTML($label . $this->getHint());
                $label = $hint;    
            } else {
                $hint = UI::el('div')->class('input-hint-wrapper')->innerHTML(
                    $this->getHint() .
                    $startEndDate
                );        
                $startEndDate = $hint;
            }
        }

        $this->_wrapper->innerHTML(
            $label .
            $startEndDate
        );

        return $this->_wrapper;
    }
}
