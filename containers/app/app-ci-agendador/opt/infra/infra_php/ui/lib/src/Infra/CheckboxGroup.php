<?php

namespace TRF4\UI\Infra;


use TRF4\UI\Element\AbstractElement;
use TRF4\UI\UI;

class CheckboxGroup extends \TRF4\UI\Component\CheckboxGroup
{

    /** @var AbstractElement */
    public $_wrapper;

    public function getDefaultElement(): AbstractElement
    {
        return $this->_wrapper;
    }

    public function __construct(string $labelInnerHtml, array $options, ?string $defaultChildrenName = null)
    {
        parent::__construct($labelInnerHtml, $options, $defaultChildrenName);
        $this->_wrapper = UI::el('fieldset')->class('infraFieldset');
    }

    public function render(): string
    {
        $fieldset = $this->_wrapper;

        $label = ($this->hasLabel())? $this->getLabelText() : "";

        $legend = UI::el('legend', '&nbsp;' . $label . '&nbsp;')->class('infraLegend');

        $checkboxsHtml = '';


        foreach ($this->getOptions() as $checkbox) {
            $div = UI::el('div')->class('infraDivCheckbox');

            if (!$checkbox->get('name')) {
                $checkbox->name($this->defaultChildrenName);
            }

            $div->innerHTML($checkbox->render());

            $checkboxsHtml .= $div;
        }


        $fieldset->innerHTML($legend . $checkboxsHtml);


        return $fieldset->render();
    }
}
