<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/05/2010 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class ParametrosEDocDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ,'DocumentoDTOSolicitacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ,'ParametrosEditorDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ConteudoAtributosModelo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ConteudoInicialSecoes');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'TextoPadrao');
  }
}
?>