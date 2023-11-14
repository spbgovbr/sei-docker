<?php


abstract class AbstractIPThOrdenacao extends AbstractIPComponente
{
    /*public const ORDENACAO_ATIVA_NULL = 0;
    public const ORDENACAO_ATIVA_ASC = 1;
    public const ORDENACAO_ATIVA_DESC = 2;
    */
    /** @var string|null */
    protected $strCustomCallbackJS;
    /** @var InfraPagina */
    protected $objInfraPagina;
    /** @var bool */
    protected $varTabIndex;
    /** @var string */
    protected $strSelecao;
    /** @var string */
    protected $strCampo;
    /** @var bool */
    protected $isOrdenacaoDescAtiva;
    /** @var bool */
    protected $isOrdenacaoAscAtiva;
    /** @var string */
    protected $strRotulo;

    /**
     * AbstractIPThOrdenacao constructor.
     * @param InfraPagina $objInfraPagina
     * @param bool $varTabIndex
     * @param string $strSelecao
     * @param string $strCampo
     * @param bool $isOrdenacaoDescAtiva
     * @param bool $isOrdenacaoAscAtiva
     * @param string $strRotulo
     * @param string|null $strCustomCallbackJs Callback a ser executado ao invés de tradicionalmente submeter o formulário
     */
    public function __construct(
        InfraPagina $objInfraPagina,
        $varTabIndex,
        $strSelecao,
        $strCampo,
        $isOrdenacaoDescAtiva,
        $isOrdenacaoAscAtiva,
        $strRotulo,
        $strCustomCallbackJs = null
    ) {
        $this->objInfraPagina = $objInfraPagina;
        $this->varTabIndex = $varTabIndex;
        $this->strSelecao = $strSelecao;
        $this->strCampo = $strCampo;
        $this->isOrdenacaoDescAtiva = $isOrdenacaoDescAtiva;
        $this->isOrdenacaoAscAtiva = $isOrdenacaoAscAtiva;
        $this->strRotulo = $strRotulo;
        $this->strCustomCallbackJS = $strCustomCallbackJs;
    }

    protected function getOnclickAsc()
    {
        return $this->getOnclickFor(InfraDTO::$TIPO_ORDENACAO_ASC);
    }


    protected function getOnclickDesc()
    {
        return $this->getOnclickFor(InfraDTO::$TIPO_ORDENACAO_DESC);
    }

    private function getOnclickFor($tipo)
    {
        $callback = !empty($this->strCustomCallbackJS) ? $this->strCustomCallbackJS : 'null';
        return "onclick=\"infraAcaoOrdenar('$this->strCampo','$tipo','$this->strSelecao',$callback);\"";
    }

    protected function getTabIndex()
    {
        $strTabIndex = '';
        if ($this->varTabIndex === true) {
            $strTabIndex = 'tabindex="' . $this->objInfraPagina->getProxTabTabela() . '"';
        } elseif ($this->varTabIndex !== false && is_numeric($this->varTabIndex)) {
            $strTabIndex = 'tabindex="' . $this->varTabIndex . '"';
        }
        return $strTabIndex;
    }


}