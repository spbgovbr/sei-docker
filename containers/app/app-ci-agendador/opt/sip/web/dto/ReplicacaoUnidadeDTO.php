<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class ReplicacaoUnidadeDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'StaOperacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdHierarquia');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdSistema');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdUnidade');
  }
}
?>