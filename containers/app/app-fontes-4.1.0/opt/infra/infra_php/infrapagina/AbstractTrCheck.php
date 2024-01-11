<?php


abstract class AbstractTrCheck extends AbstractIPComponente
{

    protected $strId;
    protected $strNomeSelecao;
    protected $strAtributos;
    protected $strTitulo;
    protected $isChecked;
    protected $numItem;
    protected $varTabIndex;
    /** @var $objInfraPagina InfraPagina */
    protected $objInfraPagina;

    public function __construct(
        $strId,
        $strNomeSelecao,
        $strAtributos,
        $strTitulo,
        $isChecked,
        $numItem,
        $varTabIndex,
        $objInfraPagina
    ) {
        $this->strId = $strId;
        $this->strNomeSelecao = $strNomeSelecao;
        $this->strAtributos = $strAtributos;
        $this->strTitulo = $strTitulo;
        $this->isChecked = $isChecked;
        $this->numItem = $numItem;
        $this->varTabIndex = $varTabIndex;
        $this->objInfraPagina = $objInfraPagina;
    }


    protected function getLink()
    {
        $strId = $this->strId;
        $strNomeSelecao = $this->strNomeSelecao;
        $aId = "ID-$strId";
        return "<a id=\"lnk{$strNomeSelecao}$aId\" name=\"$aId\"></a>";
    }
}