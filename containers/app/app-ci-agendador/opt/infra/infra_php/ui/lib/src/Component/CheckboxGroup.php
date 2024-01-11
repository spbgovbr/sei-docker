<?php

namespace TRF4\UI\Component;

use Exception;
use TRF4\UI\Labeled\AbstractElementWithLabel;
use TRF4\UI\Labeled\Checkbox;

abstract class CheckboxGroup extends AbstractElementWithLabel
{
    /** @var Checkbox[] */
    public $options;

    protected $defaultChildrenName = null;


    /**
     * As opções podem ser enviadas em duas formas: array de arrays ou array de Checkboxes
     * O primeiro método tem o seguinte formato: ['label', 'value', (opcional) 'id']
     * Ex.:
     * UI::checkboxGroup('my_group', [
     *      ['check_1', 'value1'],
     *      ['check_2' 'value2']
     * ]
     *
     * Nesse caso, o "name" será herdado do group, ou seja: cada checkbox terá o NAME=my_group
     *
     * A ID, se não especificada, será no formato NAME_VALUE. Ex.: check_1_value1, check_2_value2.
     *
     * CheckboxGroup constructor.
     * @param string $labelInnerHtml
     * @param array[]|Checkbox[] $options
     * @param string|null $defaultChildrenName
     * @throws Exception
     * @see Checkbox::fromArray
     */
    public function __construct(string $labelInnerHtml, array $options, ?string $defaultChildrenName = null)
    {
        parent::__construct($labelInnerHtml);
        $this->setOptions($options);

        $this->defaultChildrenName = $defaultChildrenName;
    }

    /**
     * @return Checkbox[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array[]|Checkbox[] $options
     * @throws Exception
     */
    private function setOptions(array $options): self
    {
        if (empty($options)) {
            throw new Exception('É necessário haver pelo menos uma opção no construtor de CheckboxGroup.');
        }

        foreach ($options as $checkbox) {
            if (is_array($checkbox)) {
                $this->options[] = Checkbox::fromArray($checkbox);
            } elseif ($checkbox instanceof Checkbox) {
                $this->options[] = $checkbox;
            } else {
                $class = Checkbox::class;
                throw new Exception("Opção inválida: deve ser uma instância de $class ou um array");
            }
        }

        return $this;

    }
}
