<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/06/2019 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class VisualizarDocumentoFederacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'DocumentoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'LinkAcesso');
  }
}
