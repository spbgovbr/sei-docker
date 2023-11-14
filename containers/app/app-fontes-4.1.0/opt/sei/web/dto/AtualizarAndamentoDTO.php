<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/11/2010 - criado por mga
*
* Versão do Gerador de Código: 1.25.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AtualizarAndamentoDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return null;
  }

  public function montar() {
  	$this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjProtocoloDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjAtividadeDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Descricao');
  }
}
?>