<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 16/09/2008 - criado por fbv
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class EmailDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL, 'IdProtocolo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'De');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Para');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'CC');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'CCO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Assunto');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Mensagem');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjAnexoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'Anexos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ArquivosUpload');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'IdDocumentosProcesso');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinCCO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'IdDocumentosCirculares');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL, 'IdDocumentoBaseCircular');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdTextoPadraoInterno');
  }
}
?>