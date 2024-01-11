<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 31/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.13.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class SolicitacaoArquivamentoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {  
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdUnidade');
  	$this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjProtocoloDTO');
  }
}
?>