<?php


namespace TRF4\UI\Component;


use TRF4\UI\Element\AbstractElement;
use TRF4\UI\Element\Component;
use TRF4\UI\Element\GenericElement;
use TRF4\UI\UI;

abstract class Table extends Component
{
    /** @var GenericElement */
    public $_table;
    /** @var array */
    protected $additionalColumns = [];
    /** @var bool */
    protected $withHeader = true;
    /** @var string|null */
    protected $title;
    /** @var array */
    protected $rows;

    public function __construct(?string $title, array $rows)
    {
        $this->_table = UI::el('table');
        $this->title = $title;
        $this->rows = $rows;
    }

    public function getDefaultElement(): AbstractElement
    {
        return $this->_table;
    }

    /**
     * Remove o header da tabela
     * @return $this
     */
    public function noHeader(): self
    {
        $this->withHeader = false;
        return $this;
    }

    /**
     * Adiciona uma coluna com um valor personalizado. �til para adicionar colunas com a��es/bot�es/etc.
     * O segundo par�metro � uma fun��o que recebe cada linha (array) no callback.
     * @param $header
     * @param callable $rowContentFn
     * @return $this
     */
    public function addColumn($header, callable $rowContentFn): self
    {
        $this->additionalColumns[$header] = $rowContentFn;
        return $this;
    }
}