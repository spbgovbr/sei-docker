<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/05/2013 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class ReplicacaoAssociacaoUsuarioUnidadeDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'StaOperacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdSistema');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdUsuario');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdUnidade');
  }
}
?>