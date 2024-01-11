<?php


abstract class AbstractTrCheck extends AbstractIPComponente
{

    protected $strId;
    protected $strNomeSelecao;
    protected $strAtributos;
    protected $strTitulo;
    protected $isChecked;
    protected $numItem;
    protected $hasTabIndex;
    /** @var $objInfraPagina InfraPagina */
    protected $objInfraPagina;

    public function __construct($strId,
                                $strNomeSelecao,
                                $strAtributos,
                                $strTitulo,
                                $isChecked,
                                $numItem,
                                $hasTabIndex,
                                $objInfraPagina
    )
    {
        $this->strId = $strId;
        $this->strNomeSelecao = $strNomeSelecao;
        $this->strAtributos = $strAtributos;
        $this->strTitulo = $strTitulo;
        $this->isChecked = $isChecked;
        $this->numItem = $numItem;
        $this->objInfraPagina = $objInfraPagina;
    }
}