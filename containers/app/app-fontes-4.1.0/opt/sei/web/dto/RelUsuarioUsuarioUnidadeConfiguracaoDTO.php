<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
 *
 * 28/11/2017 - criado por mga
 *
 */

require_once dirname(__FILE__).'/../SEI.php';

class RelUsuarioUsuarioUnidadeConfiguracaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinAtribuicoesZeradas');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelUsuarioUsuarioUnidadeDTO');
  }
}
?>