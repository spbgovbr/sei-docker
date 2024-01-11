<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/05/2013 - criado por mga
*
* Versão do Gerador de Código: 1.33.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AuditoriaProtocoloDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'auditoria_protocolo';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdAuditoriaProtocolo',
                                   'id_auditoria_protocolo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdProtocolo',
                                   'id_protocolo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUsuario',
                                   'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdAnexo',
                                   'id_anexo');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'Versao',
                                   'versao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA,
                                   'Auditoria',
                                   'dta_auditoria');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Recurso');
    
    $this->configurarPK('IdAuditoriaProtocolo',InfraDTO::$TIPO_PK_NATIVA);

  }
}
?>