<?php

namespace TRF4\UI\Bootstrap4;


use Exception;
use TRF4\UI\UI;

class Checkbox extends \TRF4\UI\Labeled\Checkbox
{
    /** @var \TRF4\UI\Element\AbstractSimpleElement */
    public $_wrapper;

    public function __construct(?string $labelInnerHtml = null, ?string $name = null, ?string $value = null)
    {

        if ($labelInnerHtml === null) {
            $labelInnerHtml = '';
        }

        parent::__construct($labelInnerHtml, $name, $value);

        $this->class('custom-control-input');

        if ($this->hasLabel()) {
            $this->_label->class('custom-control-label');
        }

        $this->_wrapper = UI::el('div')->class('custom-control custom-checkbox');
    }

    public function render(): string
    {

        if (!$this->isInGroup) {
            $this->_wrapper('class', 'form-group');
        }

        if (!$this->_input->get('name')) {
            throw new Exception("Erro ao renderizar checkbox: atributo 'name' não definido.");
        }

        $this->setIdIfNotSet();

        if ($this->hasLabel()) {
            $this->_label->for($this->getDefaultElement()->getAttrId());
        }

        $js = "";

        $this->buildHintIfIsSet();

        if($this->_hintWrapper){
            $this->_input = $this->_hintWrapper;
        } 

        if($this->getHint()) {
            $id = $this->getAttrId();
            $js = <<<html
            <script>
                $('#$id-hint').popover();
            </script>
html;
        } 

        $this->_wrapper->innerHTML($this->_input . $this->_label);

        return $this->_wrapper->render() . $js;
    }
}
