<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 02/10/2009 - criado por fbv@trf4.gov.br
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelBlocoProtocoloDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'rel_bloco_protocolo';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdProtocolo',
                                   'id_protocolo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdBloco',
                                   'id_bloco');
                                   
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Anotacao',
                                   'anotacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'Sequencia',
                                   'sequencia');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'IdxRelBlocoProtocolo',
                                   'idx_rel_bloco_protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                                   'IdProtocoloProtocolo',
                                   'p1.id_protocolo',
                                   'protocolo p1');
                                   
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                   'ProtocoloFormatadoProtocolo',
                                   'p1.protocolo_formatado',
                                   'protocolo p1');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                   'StaProtocoloProtocolo',
                                   'p1.sta_protocolo',
                                   'protocolo p1');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                  'StaNivelAcessoGlobalProtocolo',
                                  'p1.sta_nivel_acesso_global',
                                  'protocolo p1');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidadeBloco',
                                   'id_unidade',
                                   'bloco');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                   'StaTipoBloco',
                                   'sta_tipo',
                                   'bloco');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                   'StaEstadoBloco',
                                   'sta_estado',
                                   'bloco');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                                   'IdProcedimentoDocumento',
                                   'id_procedimento',
                                   'documento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                  'ProtocoloProcedimentoFormatado',
                                  'p2.protocolo_formatado',
                                  'protocolo p2');

    /* $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAberto'); */
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ,'ProtocoloDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjAssinaturaDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'PalavrasPesquisa');
    
                                   
    $this->configurarPK('IdProtocolo',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdBloco',InfraDTO::$TIPO_PK_INFORMADO);
    
    $this->configurarFK('IdProtocolo', 'protocolo p1', 'p1.id_protocolo');
		$this->configurarFK('IdBloco', 'bloco', 'id_bloco');
		$this->configurarFK('IdProtocoloProtocolo', 'documento', 'id_documento', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdProcedimentoDocumento', 'protocolo p2', 'p2.id_protocolo');
  }
}
?>