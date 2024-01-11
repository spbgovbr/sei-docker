<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/05/2014 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class ReplicacaoPermissaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'StaOperacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdSistema');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdUsuario');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdUnidade');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdPerfil');
  }
}

?>