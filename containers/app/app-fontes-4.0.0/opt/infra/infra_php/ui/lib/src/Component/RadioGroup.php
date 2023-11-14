<?php

namespace TRF4\UI\Component;

use Exception;
use TRF4\UI\Labeled\AbstractElementWithLabel;
use TRF4\UI\Labeled\Radio;

abstract class RadioGroup extends AbstractElementWithLabel
{
    /** @var array[]|Radio[] */
    public $options;
    /**
     * @var string
     */
    protected $defaultChildrenName;

    /**
     * RadioGroup constructor.
     * @param string $labelInnerHtml
     * @param string $name
     * @param array[]|Radio[] $options
     * @throws Exception
     */
    public function __construct(string $labelInnerHtml, string $name, array $options)
    {
        parent::__construct($labelInnerHtml);
        $this->validateOptions($options);
        $this->options = $options;
        $this->defaultChildrenName = $name;
    }

    public function getOptions()
    {
        return $this->options;
    }

    private function validateOptions(array $options)
    {
        if (count($options) === 0) {
            throw new Exception('É necessário haver pelo menos uma opção no construtor de RadioGroup.');
        }

        foreach ($options as $option) {
            $this->validateOption($option);
        }
    }

    private function validateOption($option)
    {
        $erro = false;

        if (is_array($option)) {
            if (count($option) < 3) {
                $erro = true;
            }
        } elseif (!$option instanceof Radio) {
            $erro = true;
        }

        if ($erro) {
            $class = Radio::class;
            throw new Exception("Opção inválida: deve ser uma instância de $class ou um array com 3 valores (label, value, id).");
        }
    }
}
