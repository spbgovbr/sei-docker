<?php

namespace TRF4\UI\Infra;


class Radio extends \TRF4\UI\Labeled\Radio
{
    public function __construct(string $labelInnerHtml, string $value, string $id)
    {
        parent::__construct($labelInnerHtml, $value, $id);

        if ($this->hasLabel()) {
            $this->_label->class('infraLabelOpcional infraLabelRadio');
        }
    }

    public function render(): string
    {
        $this->_input->class('infraRadio');

        if ($this->hasLabel()) {
            $this->_label->for($this->getDefaultElement()->getAttrId());
            $result = $this->_input . $this->_label;
        } else {
            $result = $this->_input->render();
        }

        return $result;
    }
}
