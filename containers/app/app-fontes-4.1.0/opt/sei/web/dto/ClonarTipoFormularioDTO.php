<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/08/2015 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class ClonarTipoFormularioDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
     $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdTipoFormularioOrigem');
		 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'NomeDestino');
  }
}
?>