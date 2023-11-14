<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/01/2007 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class ClonarPerfilDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
     $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdOrgaoSistema');
		 $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdSistema');
     $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdPerfilOrigem');
		 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'PerfilDestino');
		 
		 $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'PerfilDTO');
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjCoordenadorPerfilDTO');
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelPerfilRecursoDTO');
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelPerfilItemMenuDTO');
  }
}
?>