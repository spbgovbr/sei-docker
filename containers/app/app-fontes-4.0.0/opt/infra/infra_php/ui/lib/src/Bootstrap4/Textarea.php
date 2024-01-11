<?php

namespace TRF4\UI\Bootstrap4;


use TRF4\UI\Renderer\Bootstrap4;

class Textarea extends \TRF4\UI\Component\Textarea
{
    public function __construct(string $labelInnerHtml, string $name)
    {
        parent::__construct($labelInnerHtml, $name);
        $this->_textarea->class('form-control form-group');
        $this->_wrapper->class('form-group');
    }

    public function render(): string
    {

        $js = "";
        Bootstrap4::transformLabel($this);

        $label = "";
        if($this->hasLabel()){
            $label = $this->_label;
        }

        $this->buildHintIfIsSet();

        if($this->getHint()) {   
            $id = $this->getAttrId();
            $js = <<<html
            <script type="text/javascript">
                $('#$id-hint').popover();
            </script>
html;
        }

        if($this->_hintWrapper){
            $this->_textarea = $this->_hintWrapper;
        }

        $this->_wrapper->innerHTML(
            $label .
            $this->_textarea .
            Bootstrap4::getFeedbackForInvalidValue($this) .
            $js            
        );

        return $this->_wrapper->render();
    }
}
