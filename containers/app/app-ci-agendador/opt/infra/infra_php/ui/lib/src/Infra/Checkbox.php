<?php

namespace TRF4\UI\Infra;


use Exception;

class Checkbox extends \TRF4\UI\Labeled\Checkbox
{

    public function __construct(?string $labelInnerHtml = null, ?string $name = null, ?string $value = null)
    {
        parent::__construct($labelInnerHtml, $name, $value);

        $this->_input->class('infraCheckbox');

        if ($this->hasLabel()) {
            $this->_label->class('infraLabelOpcional');
        }
    }

    public function render(): string
    {

        if (!$this->_input->get('name')) {
            throw new Exception("Erro ao renderizar checkbox: atributo 'name' não definido.");
        }

        $this->setIdIfNotSet();

        if ($this->hasLabel()) {
            $this->_label->for($this->getAttrId());
            $this->_label->append($this->_input);

            $result = $this->_label->render();
        } else {
            $result = $this->_input->render();
        }


        return $result;
    }
}
