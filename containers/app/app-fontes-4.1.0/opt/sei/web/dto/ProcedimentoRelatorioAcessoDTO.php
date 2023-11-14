<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 31/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.13.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class ProcedimentoRelatorioAcessoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'Dias');                                              
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdUnidade');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SiglaUnidade');                                              
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'DescricaoUnidade');                                              
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdOrgao');    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'ProtocoloFormatadoProtocolo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTH,'Abertura');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdProtocolo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'IdentificacaoProtocolo');  
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinProcessosNaoRecebidos'); 
  }
}
?>