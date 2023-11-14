<?php

namespace TRF4\UI\Infra;


use TRF4\UI\Element\GenericElement;
use TRF4\UI\UI;

class Date extends \TRF4\UI\Component\Date
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

    public function value(?string $value)
    {
        $this->value = $value;
        return $this;
    }

    public function render(): string
    {
        $withTime = $this->withTime;
        $mascara = $withTime ? 'infraMascaraDataHora' : 'infraMascaraData';

        $this->onkeypress("return $mascara(this, event);");


        $calendarImg = <<<html
            <img title="{$this->getLabelText()}"
                alt="{$this->getLabelText()}"
                src="/infra_css/imagens/calendario.gif"
                class="infraImgNormal"
                onclick="infraCalendario('{$this->getDefaultElement()->getAttrId()}', this, $withTime);" />
html;

        $label = ""; 
        if($this->hasLabel()){
            $this->_label->for($this->_input->getAttrId());
            $label = $this->_label;     
        } 

        $this->_wrapper->innerHTML(
            $label .
            $this->_input .
            $calendarImg
        );

        return $this->_wrapper;
    }
}
