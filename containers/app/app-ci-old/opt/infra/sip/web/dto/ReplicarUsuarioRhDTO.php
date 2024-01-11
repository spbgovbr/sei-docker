<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 29/11/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class ReplicarUsuarioRhDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'StaOperacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdOrgao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'IdOrigem');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Sigla');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Nome');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'NomeSocial');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'Cpf');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Email');
  }
}
?>