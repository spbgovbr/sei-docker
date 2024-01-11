<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/12/2007 - criado por mga
*
* Versão do Gerador de Código: 1.12.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class NivelAcessoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
 
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,
                                   'StaNivel');

  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,
                                   'Descricao');
  }
}
?>