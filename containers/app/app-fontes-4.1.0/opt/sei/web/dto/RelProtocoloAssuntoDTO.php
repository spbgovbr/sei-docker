<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 12/02/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.13.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelProtocoloAssuntoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'rel_protocolo_assunto';
  }

  public function montar() {

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdProtocolo',
                                   'id_protocolo');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdAssuntoProxy',
                                   'id_assunto_proxy');
                                   
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidade',
                                   'id_unidade');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'Sequencia',
                                   'sequencia');
                                   

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                'IdProtocoloProcedimento',
                                'id_protocolo_procedimento');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'StaProtocoloProtocolo',
                                              'sta_protocolo',
                                              'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                      'ProtocoloFormatadoProtocolo',
                                      'protocolo_formatado',
                                      'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                      'StaProtocolo',
                                      'sta_protocolo',
                                      'protocolo');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA,
                                              'GeracaoProtocolo',
                                              'dta_geracao',
                                              'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdAssunto',
                                              'id_assunto',
                                              'assunto_proxy');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'CodigoEstruturadoAssunto',
                                              'codigo_estruturado',
                                              'assunto');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoAssunto',
                                              'descricao',
                                              'assunto');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'StaDestinacaoAssunto',
                                              'sta_destinacao',
                                              'assunto');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'ObservacoesAssunto',
                                              'observacao',
                                              'assunto');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'PrazoCorrenteAssunto',
                                              'prazo_corrente',
                                              'assunto');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'PrazoIntermediarioAssunto',
                                              'prazo_intermediario',
                                              'assunto');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUnidade',
                                              'sigla',
                                              'unidade');


     $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'TipoProtocolo');

    $this->configurarPK('IdProtocolo',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdAssuntoProxy',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdProtocolo', 'protocolo', 'id_protocolo');
    $this->configurarFK('IdAssuntoProxy', 'assunto_proxy', 'id_assunto_proxy');
    $this->configurarFK('IdAssunto', 'assunto', 'id_assunto');
    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade');
  }
}
?>