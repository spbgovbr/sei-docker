<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 25/03/2011 - criado por mga
*
* Versão do Gerador de Código: 1.13.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AssuntoAtualizacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'CodigoEstruturadoAnterior');
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'CodigoEstruturadoAtual');
  	 
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdAssuntoAnterior');
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdAssuntoAtual');
  }
}
?>
