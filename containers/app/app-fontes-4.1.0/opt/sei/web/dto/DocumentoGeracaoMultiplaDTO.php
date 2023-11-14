<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 10/02/2015 - criado por mga
 *
 */

require_once dirname(__FILE__).'/../SEI.php';

class DocumentoGeracaoMultiplaDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'DblIdProcedimento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdSerie');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdTextoPadraoInterno');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'ProtocoloFormatadoDocumentoBase');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'StaNivelAcessoLocal');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdHipoteseLegal');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'StaGrauSigilo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdBloco');
  }
}
?>