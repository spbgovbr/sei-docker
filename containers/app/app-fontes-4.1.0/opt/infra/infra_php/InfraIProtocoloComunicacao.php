<?php

/**
 * Interface a ser implementada por classes que implementem um protocolo.
 *
 * criado em 08/04/2014 - bmy@trf4.gov.br
 * alterado em
 *
 * Observações:
 *
 */
interface InfraIProtocoloComunicacao
{

    public function abrirConexao($bolConexaoSegura = true, $strIdConexao = null);

    public function mostrarDiretorioLocal();

    public function mostrarTamanhoArquivo($strArquivoRemoto);

    public function listarArquivos($strDiretorio);

    public function listarArquivosDetalhes($strDiretorio);

    public function enviarArquivo($strArquivoLocal, $strArquivoRemoto);

    public function receberArquivo($strArquivoLocal, $strArquivoRemoto);

    public function apagarArquivo($strArquivoRemoto);

    public function criarDiretorio($strDiretorioRemoto);

    public function apagarDiretorio($strDiretorioRemoto);

    public function executarComando($strComando);

    public function mostrarTipoSistemaRemoto();

    public function fecharConexao();

    public function getServidor($strIdConexao);

    public function getPorta($strIdConexao);

    public function getUsuario($strIdConexao);

    public function getSenha($strIdConexao);
}

