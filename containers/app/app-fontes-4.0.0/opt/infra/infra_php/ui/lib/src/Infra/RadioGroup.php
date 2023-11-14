<?php

namespace TRF4\UI\Infra;


use TRF4\UI\Element\AbstractElement;
use TRF4\UI\Element\AbstractSimpleElement;
use TRF4\UI\UI;

class RadioGroup extends \TRF4\UI\Component\RadioGroup
{


    /**
     * @var AbstractSimpleElement
     */
    public $_wrapper;

    public function __construct(string $labelInnerHtml, string $name, array $options)
    {
        parent::__construct($labelInnerHtml, $name, $options);
        $this->_wrapper = UI::el('fieldset')->class('infraFieldset');

    }

    public function render(): string
    {
        $fieldset = $this->_wrapper;

        $legend = UI::el('legend', '&nbsp;' . $this->getLabelText() . '&nbsp;')->class('infraLegend');

        $radiosHtml = '';

        foreach ($this->getOptions() as $radio) {
            $div = UI::el('div')->class('infraDivRadio');

            if (is_array($radio)) {
                $radio = UI::radio(...$radio);
            }

            $radio->name($this->defaultChildrenName);

            $div->innerHTML($radio->render());

            $radiosHtml .= $div;
        }


        $fieldset->innerHTML($legend . $radiosHtml);


        return $fieldset->render();
    }

    public function getDefaultElement(): AbstractElement
    {
        return $this->_wrapper;
    }
}
