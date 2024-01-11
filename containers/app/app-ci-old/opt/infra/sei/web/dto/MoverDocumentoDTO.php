<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/12/2013 - criado por mga
*
* Versão do Gerador de Código: 1.25.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class MoverDocumentoDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdProcedimentoOrigem');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdProcedimentoDestino');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdDocumento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Motivo');
  }
}
?>