<?php


namespace TRF4\UI\Bootstrap4;


use TRF4\UI\Element\GenericElement;
use TRF4\UI\Renderer\Bootstrap4;
use TRF4\UI\UI;

class InputText extends \TRF4\UI\Component\InputText
{

    /** @var GenericElement */
    public $_wrapper;

    public function __construct(?string $labelInnerHtml = null, ?string $name = null)
    {
        parent::__construct($labelInnerHtml, $name);

        $this->_wrapper = UI::el('div')->class('form-group');
    }

    protected function buildElements(): void
    {
        $this->_input->class('form-control form-control-sm');

        Bootstrap4::transformLabel($this);
    }

    protected function assembleAndPrintElements(): string
    {
        $js = "";
        $inputId = $this->getAttrId();

        if ($this->mask) {
            $lazyLoadMask = json_encode($this->lazyLoadMask);

            $js .= "(function(){
                IMask(document.getElementById('$inputId'), {
                    mask: '".$this->mask."',
                    lazy: $lazyLoadMask
                });
            })();";
            $this->scripts[] = $js;
        }

        $this->buildHintIfIsSet();

        $input = $this->_input;
        if($this->_hintWrapper){
            $input = $this->_hintWrapper;
        }

        $label = "";
        if($this->hasLabel()){
            $label = $this->_label->render();
        }

        $this->_wrapper->innerHTML(
            $label .
            $input->render() .
            Bootstrap4::getFeedbackForInvalidValue($this)
        );

        return $this->_wrapper->render();
    }

}
