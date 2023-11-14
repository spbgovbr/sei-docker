<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/10/2019 - criado por mga
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class EmailDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'De');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Para');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Assunto');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Mensagem');
  }
}
?>