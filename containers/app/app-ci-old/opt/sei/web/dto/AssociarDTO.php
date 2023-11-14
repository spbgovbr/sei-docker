<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/04/2011 - criado por mga
*
* Versão do Gerador de Código: 1.23.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AssociarDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL, 'IdProcedimento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdUnidade');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdUsuario');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'StaNivelAcessoGlobal');
  }
}
?>