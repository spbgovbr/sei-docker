<?php


abstract class AbstractIPAreaPaginacao extends AbstractIPComponente
{
    /** @var string */
    protected $strTipo;
    /** @var string */
    protected $strSelecao;
    /** @var string */
    protected $strCustomCallbackJs;
    /** @var bool */
    protected $varTabIndex;
    /** @var int */
    protected $totalRegistros;
    /** @var int */
    protected $totalRegistrosPaginaAtual;
    /** @var int */
    protected $itensPorPagina;
    /** @var int */
    protected $paginaAtual0Indexed;
    /** @var InfraPagina */
    protected $objInfraPagina;
    /** @var int */
    protected $numPaginas;
    /** @var bool */
    protected $isUltimaPagina;
    /** @var int */
    protected $numUltimaPagina;
    /** @var bool */
    protected $isPrimeiraPagina;


    /**
     * @param string $strTipo Se é inferior ou superior. Afeta apenas as IDs geradas
     * @param string $strSelecao Identificador dos campos
     * @param string $strCustomCallbackJs
     * @param bool $varTabIndex
     * @param int $totalRegistros
     * @param int $totalRegistrosPaginaAtual
     * @param int $itensPorPagina
     * @param int $paginaAtual0Indexed
     * @param InfraPagina $objInfraPagina
     */
    public function __construct(
        $strTipo,
        $strSelecao,
        $strCustomCallbackJs,
        $varTabIndex,
        $totalRegistros,
        $totalRegistrosPaginaAtual,
        $itensPorPagina,
        $paginaAtual0Indexed,
        $objInfraPagina
    ) {
        $this->strTipo = $strTipo;
        $this->strSelecao = $strSelecao;
        $this->strCustomCallbackJs = $strCustomCallbackJs;
        $this->varTabIndex = $varTabIndex;
        $this->totalRegistros = $totalRegistros;
        $this->totalRegistrosPaginaAtual = $totalRegistrosPaginaAtual;
        $this->itensPorPagina = $itensPorPagina;
        $this->paginaAtual0Indexed = $paginaAtual0Indexed;
        $this->objInfraPagina = $objInfraPagina;
        $this->numPaginas = ceil($totalRegistros / $itensPorPagina);
        $this->isUltimaPagina = ($paginaAtual0Indexed == ($this->numPaginas - 1));
        $this->numUltimaPagina = ($this->numPaginas - 1);
        $this->isPrimeiraPagina = $paginaAtual0Indexed === 0;
    }

    protected function hasMaisDeUmaPagina()
    {
        return $this->totalRegistros > $this->totalRegistrosPaginaAtual;
    }

    protected function criarJsOnclick($jsTipo, $jsPag)
    {
        return "infraAcaoPaginar('$jsTipo',$jsPag,'$this->strSelecao', $this->strCustomCallbackJs);";
    }
}