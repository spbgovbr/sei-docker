<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 18/12/2019 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class ReplicarSinalizacoesFederacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'InstalacaoFederacaoDTORemetente');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'OrgaoFederacaoDTORemetente');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'UnidadeFederacaoDTORemetente');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjSinalizacaoFederacaoDTO');
  }
}
