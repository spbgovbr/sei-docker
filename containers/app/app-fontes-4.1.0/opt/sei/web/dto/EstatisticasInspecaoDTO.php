<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 18/06/2012 - criado por mga
*
* Versão do Gerador de Código: 1.13.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class EstatisticasInspecaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdOrgao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdUnidade');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdTipoProcedimento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdSerie');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'StaTipo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SiglaUnidade');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTH,'Abertura');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'ProtocoloFormatadoProtocolo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'Inicio');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'Fim');

    //ordenacao
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SiglaOrgao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'NomeTipoProcedimento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'NomeSerie');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'Quantidade');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'QuantidadeGerados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'QuantidadeRecebidos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'TotalTramitacao');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'OrgaosProcessosGerados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'UnidadesProcessosGerados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'TiposProcessosGerados');
    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'OrgaosDocumentos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'UnidadesDocumentos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'TiposDocumentos');
    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'OrgaosTramitacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'UnidadesTramitacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'TiposProcessosTramitacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'Movimentacao');
    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdInspecao');
  }
}
?>