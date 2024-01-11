<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 02/12/2010 - criado por jonatas_db
*
* Versão do Gerador de Código: 1.12.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class ContatoSubstituirDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {

  	$this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdContato');
  	$this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjContato');  	
                                              
  }
}
?>