<?php

abstract class AbstractInfraPaginaRendererFactory
{

    /**
     * Retorna um array de caminhos relativos de arquivos .js a serem incluídos para o renderizador
     * @return array
     */
    public abstract function getCaminhosRelativosJS();

    /**
     * Retorna um array de caminhos relativos de arquivos .css a serem inclu�dos para o renderizador
     * @return array
     */
    public abstract function getCaminhosRelativosCSS();

    /**
     * @returns string
     */
    protected abstract function getAreaPaginacaoClass();

    /**
     * @return string
     */
    protected abstract function getThOrdenacaoClass();

    /**
     * @return string
     */
    protected abstract function getTrCheckClass();

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
     * @return AbstractIPAreaPaginacao
     */
    public function createAreaPaginacao(
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
        /** @var AbstractIPAreaPaginacao $class */
        $class = $this->getAreaPaginacaoClass();
        return new $class(
            $strTipo,
            $strSelecao,
            $strCustomCallbackJs,
            $varTabIndex,
            $totalRegistros,
            $totalRegistrosPaginaAtual,
            $itensPorPagina,
            $paginaAtual0Indexed,
            $objInfraPagina
        );
    }

    /**
     * @param InfraPagina $objInfraPagina
     * @param bool $varTabIndex
     * @param string $strSelecao
     * @param string $strCampo
     * @param bool $isOrdenacaoDescAtiva
     * @param bool $isOrdenacaoAscAtiva
     * @param string $strRotulo
     * @return AbstractIPThOrdenacao
     */
    public function createThOrdenacao(
        $objInfraPagina,
        $varTabIndex,
        $strSelecao,
        $strCampo,
        $isOrdenacaoDescAtiva,
        $isOrdenacaoAscAtiva,
        $strRotulo,
        $strCustomCallbackJs = null
    ) {
        $class = $this->getThOrdenacaoClass();
        return new $class(
            $objInfraPagina,
            $varTabIndex,
            $strSelecao,
            $strCampo,
            $isOrdenacaoDescAtiva,
            $isOrdenacaoAscAtiva,
            $strRotulo,
            $strCustomCallbackJs
        );
    }

    /**
     * @param string $strId
     * @param string $strNomeSelecao
     * @param string $strAtributos
     * @param string $strTitulo
     * @param bool $isChecked
     * @param int $numItem
     * @param bool $varTabIndex
     * @param InfraPagina $objInfraPagina
     * @return AbstractTrCheck
     */
    public function createTrCheck(
        $strId,
        $strNomeSelecao,
        $strAtributos,
        $strTitulo,
        $isChecked,
        $numItem,
        $varTabIndex,
        $objInfraPagina
    ) {
        $class = $this->getTrCheckClass();
        return new $class(
            $strId,
            $strNomeSelecao,
            $strAtributos,
            $strTitulo,
            $isChecked,
            $numItem,
            $varTabIndex,
            $objInfraPagina
        );
    }


}