<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/11/2011 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class ReplicacaoRegraAuditoriaDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'StaOperacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdRegraAuditoria');
  }
}

?>