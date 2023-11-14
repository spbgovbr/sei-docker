<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/05/2019 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class ReceberProcessoFederacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'InstalacaoFederacaoDTORemetente');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'OrgaoFederacaoDTORemetente');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'UnidadeFederacaoDTORemetente');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'UsuarioFederacaoDTORemetente');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'ProcedimentoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'InstalacaoFederacaoDTOOrigem');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'ProcedimentoDTOOrigem');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjAcessoFederacaoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'StaTipo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Motivo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTH, 'DataHora');
  }
}
