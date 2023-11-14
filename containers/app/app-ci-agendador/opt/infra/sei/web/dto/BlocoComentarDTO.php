<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/07/2019 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class BlocoComentarDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'TextoComentario');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjBlocoDTO');
  }
}
?>