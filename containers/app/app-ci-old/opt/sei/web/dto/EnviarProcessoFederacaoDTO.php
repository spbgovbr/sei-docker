<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 13/06/2019 - criado por mga
 *
 */

require_once dirname(__FILE__).'/../SEI.php';

class EnviarProcessoFederacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL, 'IdProcedimento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Senha');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Motivo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'StaTipo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjInstalacaoFederacaoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjOrgaoFederacaoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjUnidadeFederacaoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjAcessoFederacaoDTO');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'ProtocoloFederacaoDTO');
  }
}
?>