<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/12/2007 - criado por marcio_db
* 15/06/2018 - cjy - ícone de acompanhamento no controle de processos
*
* Versão do Gerador de Código: 1.12.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class PesquisaPendenciaDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdProtocolo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdUsuario');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdUnidade');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinInicial');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinNaoVisualizados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAlterados');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'StaEstadoProcedimento');
    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'StaTipoAtribuicao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdUsuarioAtribuicao');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdMarcador');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdTipoProcedimento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdAcompanhamento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'StaTipoControlePrazo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'StaTipoRetornoProgramado');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinMontandoArvore');

   	$this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAnotacoes');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinObservacoes');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinSituacoes');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinMarcadores');
   	$this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinInteressados');
   	$this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinRetornoProgramado');
   	$this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinCredenciais');
   	$this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAcompanhamentos');
   	$this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinControlePrazo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinLinhaDireta');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinHoje');
   	$this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdDocumento');

   	//ordenacao
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'Processos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Nome');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ,'AtividadeDTOOrdenacao');
  }
}
?>