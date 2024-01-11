<?php

namespace TRF4\UI\Bootstrap4;


use TRF4\UI\Renderer\Bootstrap4;
use TRF4\UI\UI;

class Radio extends \TRF4\UI\Labeled\Radio
{
    public function render(): string
    {
        $js = "";
        $this->_input->class('custom-control-input');
        Bootstrap4::transformLabel($this);

        if ($this->hasLabel()) {
            $this->_label->class('custom-control-label');
        }

        $this->buildHintIfIsSet();

        $labelResult = $this->hasLabel() ? $this->_label->render() : '';

        if($this->getHint()){
            $id = $this->getAttrId();
            $js = <<<html
            <script type="text/javascript">
                $('#$id-hint').popover();
            </script>
html;
        }

        if($this->_hintWrapper){
            $this->_input = $this->_hintWrapper;
        }

        $wrapper = UI
            ::el('div')
            ->class('custom-control custom-radio')
            ->innerHTML($this->_input . $labelResult);

        return $wrapper->render() . $js;
    }
}