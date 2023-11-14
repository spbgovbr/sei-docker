<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/07/2019 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class ReplicarAcessosFederacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'InstalacaoFederacaoDTORemetente');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'OrgaoFederacaoDTORemetente');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'UnidadeFederacaoDTORemetente');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'ProcedimentoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjAcessoFederacaoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjInstalacaoFederacaoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjOrgaoFederacaoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjUnidadeFederacaoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjUsuarioFederacaoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjProtocoloFederacaoDTO');
  }
}
