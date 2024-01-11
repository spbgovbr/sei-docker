<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/04/2014 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class ReabrirProcessoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL, 'IdProcedimento');
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdUnidade');
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdUsuario');
  }
}
?>