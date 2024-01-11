<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 19/05/2006 - criado por MGA
 *
 * @package infra_php
 */

interface InfraIBanco
{
    public function getIdBanco();

    public function getIdConexao();

    public function isBolProcessandoTransacao();

    public function isBolManterConexaoAberta();

    public function abrirConexao();

    public function fecharConexao();

    public function abrirTransacao();

    public function confirmarTransacao();

    public function cancelarTransacao();

    public function consultarSql($strSql, $arrCamposBind = null);

    public function executarSql($strSql, $arrCamposBind = null);

    public function paginarSql($strSql, $numIni, $numQtd, $arrCamposBind = null);

    public function limitarSql($strSql, $numQtd, $arrCamposBind = null);

    public function formatarSelecaoDta($strTabela, $strCampo, $strAlias);

    public function formatarSelecaoDth($strTabela, $strCampo, $strAlias);

    public function formatarSelecaoStr($strTabela, $strCampo, $strAlias);

    public function formatarSelecaoBol($strTabela, $strCampo, $strAlias);

    public function formatarSelecaoNum($strTabela, $strCampo, $strAlias);

    public function formatarSelecaoDin($strTabela, $strCampo, $strAlias);

    public function formatarSelecaoDbl($strTabela, $strCampo, $strAlias);

    public function formatarSelecaoBin($strTabela, $strCampo, $strAlias);

    public function formatarGravacaoDta($dta);

    public function formatarGravacaoDth($dth);

    public function formatarGravacaoStr($str);

    public function formatarGravacaoBol($bol);

    public function formatarGravacaoNum($num);

    public function formatarGravacaoDin($din);

    public function formatarGravacaoDbl($dbl);

    public function formatarGravacaoBin($dbl);

    public function formatarLeituraDta($dta);

    public function formatarLeituraDth($dth);

    public function formatarLeituraStr($str);

    public function formatarLeituraBol($bol);

    public function formatarLeituraNum($num);

    public function formatarLeituraDin($din);

    public function formatarLeituraDbl($dbl);

    public function formatarLeituraBin($dbl);

    public function converterStr($strTabela, $strCampo);

    public function isBolForcarPesquisaCaseInsensitive();

    public function formatarPesquisaStr($strTabela, $strCampo, $strValor, $strOperador, $bolCaseInsensitive, $strBind);

    public function criarSequencialNativa($strSequencia, $numInicial);

    public function isBolValidarISO88591();
    //public function getValorSequencia($sequencia);
}

