<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/10/2013 - criado por mga
*
* Versão do Gerador de Código: 1.12.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class GrauSigiloDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
 
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,
                                   'StaGrau');

  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,
                                   'Descricao');
  }
}
?>