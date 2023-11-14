<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 02/05/2011 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class ConcederCredencialDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
     $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdProcedimento');  	
     $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdUsuario');
     $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdUnidade');
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'AtividadesOrigem');
  }
}
?>