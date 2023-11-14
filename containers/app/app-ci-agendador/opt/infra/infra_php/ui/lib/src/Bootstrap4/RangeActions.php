<?php


namespace TRF4\UI\Bootstrap4;


use TRF4\UI\Component\MultiRange;
use TRF4\UI\Element\GenericElement;
use TRF4\UI\Renderer\Bootstrap4;
use TRF4\UI\UI;

trait RangeActions
{

    /** @var GenericElement */
    public $_wrapper;

    /** @var GenericElement */
    public $_input2;

    /** @var GenericElement */
    public $_output2;

    public $_values;

    protected function buildElements(): void
    {
        $this->_input->class('custom-range');

        $this->setStepIfNotSet();

        $this->_input->dataThumbwidth(20);

        # se não houver setado valor inicial, define como padrão o valor mínimo do range
        if (!$this->get('value')) {
            $this->_input->value($this->get('min'));
        }

        $this->_values = UI::el('div')->class('rangeValues');

        //todo remover esse if, levando para a classe específica do MultiRange
        if ($this->isMulti()) {

            $val2 = ($this->getEndValue()) ?: $this->get('max');

            # cria outro campo range com o mesmo formato
            $this->_input2 = UI::el('input')->type('range')
                ->class('custom-range')
                ->name(MultiRange::get2ndRangeId($this->_input->getAttrId()))
                ->max($this->_input->get('max'))
                ->min($this->_input->get('min'))
                ->step($this->_input->get('step'))
                ->dataThumbwidth(20)
                ->value($val2);

            $this->_output2 = UI::el('output');
        }

        Bootstrap4::transformLabel($this);
    }

    public function setStepIfNotSet(): void
    {
        if (!$this->get('step')) {
            $step = $this->calculateStep();
            $this->_input->step($step);
        }
    }

    protected function calculateStepFromValue(float $value): float
    {
        $decimalDigitsCount = strlen(substr(strrchr($value, "."), 1));
        return 10 ** ($decimalDigitsCount * -1);
    }


    abstract protected function calculateStep(): float;

    protected function assembleAndPrintElements(): string
    {

        $wrapper = UI::el('div')->class('form-group');

        $this->_wrapper->innerHTML(
            $this->_input->render() .
            $this->_output->render() .
            $this->_input2 .
            $this->_output2 .
            $this->_selection->render() .
            $this->_values->render() .
            Bootstrap4::getFeedbackForInvalidValue($this)
        );

        $this->buildHintIfIsSet($this->_wrapper, "no-label");

        if ($this->_hintWrapper) {
            $this->_wrapper = $this->_hintWrapper;
        }

        $label = "";
        if ($this->hasLabel()) {
            $label = $this->_label->render();
        }

        $wrapper->innerHTML($label . $this->_wrapper);

        return $wrapper->render();
    }
}
