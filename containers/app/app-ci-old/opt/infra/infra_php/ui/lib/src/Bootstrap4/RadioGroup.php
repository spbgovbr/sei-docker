<?php

namespace TRF4\UI\Bootstrap4;

use TRF4\UI\Element\AbstractElement;
use TRF4\UI\Element\AbstractSimpleElement;
use TRF4\UI\Renderer\Bootstrap4;
use TRF4\UI\UI;

class RadioGroup extends \TRF4\UI\Component\RadioGroup
{


    /**
     * @var AbstractSimpleElement
     */
    public $_wrapper;

    public $_label;

    public function __construct(string $labelInnerHtml, string $name, array $options)
    {
        parent::__construct($labelInnerHtml, $name, $options);
        $this->_wrapper = Bootstrap4::formGroup();
    }

    public function render(): string
    {
        $radiosHtml = '';
        $js = "";

        if($this->hasLabel()) {
            $this->_label = UI::el('span', $this->getLabelText());
        }

        foreach ($this->getOptions() as $radio) {
            if (is_array($radio)) {
                $radio = UI::radio(...$radio);
            }
            $radio->name($this->defaultChildrenName);

            $radiosHtml .= $radio->render();
        }

        $this->buildHintIfIsSet($radiosHtml);

        if($this->_hintWrapper){
            $radiosHtml = $this->_hintWrapper;
        }

        $label = ($this->hasLabel())? $this->_label->render() : "";

        if($this->getHint()) {
            $id = $this->defaultChildrenName;
            $js = <<<html
            <script>
                $('#$id-hint').popover();
            </script>
html;
        }

        $this->_wrapper->innerHTML($label . $radiosHtml);

        return $this->_wrapper->render() . $js;
    }


    public function getDefaultElement(): AbstractElement
    {
        return $this->_wrapper;
    }
}
