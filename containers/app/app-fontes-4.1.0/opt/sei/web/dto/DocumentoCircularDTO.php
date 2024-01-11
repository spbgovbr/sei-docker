<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 26/08/2015 - criado por mga
 *
 */

require_once dirname(__FILE__).'/../SEI.php';

class DocumentoCircularDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdProcedimento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdDocumento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'ProtocoloDocumentoFormatado');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'NomeSerie');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Numero');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdBloco');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAssinado');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'NumIdDestinatario');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjParticipanteDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjDocumentoDTOEmail');
  }
}
?>