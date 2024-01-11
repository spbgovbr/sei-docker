<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/11/2015 - criado por mga
*
* Versão do Gerador de Código: 1.35.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class DocumentoConteudoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'documento_conteudo';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdDocumento',
                                   'id_documento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Conteudo',
                                   'conteudo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'ConteudoAssinatura',
                                   'conteudo_assinatura');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'CrcAssinatura',
                                   'crc_assinatura');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'QrCodeAssinatura',
                                   'qr_code_assinatura');

    $this->configurarPK('IdDocumento',InfraDTO::$TIPO_PK_INFORMADO);

  }
}
?>