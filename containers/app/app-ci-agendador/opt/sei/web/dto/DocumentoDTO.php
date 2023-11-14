<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 30/07/2008 - criado por mga
*
* Versão do Gerador de Código: 1.21.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class DocumentoDTO extends InfraDTO {

  private $numFiltroFkDocumentoConteudo = null;

  public function __construct(){
    $this->numFiltroFkDocumentoConteudo = InfraDTO::$FILTRO_FK_ON;
    parent::__construct();
  }

  public function getStrNomeTabela() {
  	 return 'documento';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdDocumento',
                                   'id_documento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdDocumentoEdoc',
                                   'id_documento_edoc');
                                   
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdProcedimento',
                                   'id_procedimento');
                                   
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdSerie',
                                   'id_serie');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                  'IdTipoFormulario',
                                  'id_tipo_formulario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidadeResponsavel',
                                   'id_unidade_responsavel');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdConjuntoEstilos',
                                   'id_conjunto_estilos');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                  'IdTipoConferencia',
                                  'id_tipo_conferencia');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'SinArquivamento',
                                  'sin_arquivamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Numero',
                                   'numero');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                    'SinBloqueado',
                                    'sin_bloqueado');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'StaDocumento',
                                   'sta_documento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'NomeArvore',
                                   'nome_arvore');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Numero',
                                   'numero');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'Conteudo',
                                              'conteudo',
                                              'documento_conteudo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'ConteudoAssinatura',
                                              'conteudo_assinatura',
                                              'documento_conteudo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'CrcAssinatura',
                                              'crc_assinatura',
                                              'documento_conteudo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'QrCodeAssinatura',
                                              'qr_code_assinatura',
                                              'documento_conteudo');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                                              'IdProtocoloProtocolo',
                                              'd.id_protocolo',
                                              'protocolo d');
                                   
  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'ProtocoloDocumentoFormatado',
                                              'd.protocolo_formatado',
                                              'protocolo d');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'IdProtocoloFederacaoProtocolo',
                                              'd.id_protocolo_federacao',
                                              'protocolo d');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'CodigoBarrasDocumento',
                                              'd.codigo_barras',
                                              'protocolo d');
  	 
		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'ProtocoloDocumentoFormatadoPesquisa',
                                              'd.protocolo_formatado_pesquisa',
                                              'protocolo d');
  	 
  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                                              'IdProtocoloAgrupadorProtocolo',
                                              'd.id_protocolo_agrupador',
                                              'protocolo d');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'StaProtocoloProtocolo',
                                              'd.sta_protocolo',
                                              'protocolo d');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA,
                                              'GeracaoProtocolo',
                                              'd.dta_geracao',
                                              'protocolo d');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA,
                                              'InclusaoProtocolo',
                                              'd.dta_inclusao',
                                              'protocolo d');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdUnidadeGeradoraProtocolo',
                                              'd.id_unidade_geradora',
                                              'protocolo d');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdUsuarioGeradorProtocolo',
                                              'd.id_usuario_gerador',
                                              'protocolo d');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'StaNivelAcessoLocalProtocolo',
                                              'd.sta_nivel_acesso_local',
                                              'protocolo d');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                        	     'IdHipoteseLegalProtocolo',
                                        	     'd.id_hipotese_legal',
                                        	     'protocolo d');
  	 
  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                        	     'StaGrauSigiloProtocolo',
                                        	     'd.sta_grau_sigilo',
                                        	     'protocolo d');
     
  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'StaNivelAcessoGlobalProtocolo',
                                              'd.sta_nivel_acesso_global',
                                              'protocolo d');
                                              
     $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoProtocolo',
                                              'd.descricao',
                                              'protocolo d');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeSerie',
                                              'nome',
                                              'serie');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdModeloSerie',
                                              'id_modelo',
                                              'serie');
  	 
  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdModeloEdocSerie',
                                              'id_modelo_edoc',
                                              'serie');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SinAssinaturaPublicacaoSerie',
                                              'sin_assinatura_publicacao',
                                              'serie');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SinDestinatarioSerie',
                                              'sin_destinatario',
                                              'serie');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUnidadeGeradoraProtocolo',
                                              'uni_ger.sigla',
                                              'unidade uni_ger');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoUnidadeGeradoraProtocolo',
                                              'uni_ger.descricao',
                                              'unidade uni_ger');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdOrgaoUnidadeGeradoraProtocolo',
                                              'uni_ger.id_orgao',
                                              'unidade uni_ger');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaOrgaoUnidadeGeradoraProtocolo',
                                              'org_ger.sigla',
                                              'orgao org_ger');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoOrgaoUnidadeGeradoraProtocolo',
                                              'org_ger.descricao',
                                              'orgao org_ger');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUnidadeResponsavel',
                                              'uni_resp.sigla',
                                              'unidade uni_resp');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoUnidadeResponsavel',
                                              'uni_resp.descricao',
                                              'unidade uni_resp');
                                              
     $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdOrgaoUnidadeResponsavel',
                                              'uni_resp.id_orgao',
                                              'unidade uni_resp');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                                              'IdProtocoloProcedimento',
                                              'p.id_protocolo',
                                              'protocolo p');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'EspecificacaoProcedimento',
                                              'p.descricao',
                                              'protocolo p');

  	$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'ProtocoloProcedimentoFormatado',
                                              'p.protocolo_formatado',
                                              'protocolo p');

  	$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'CodigoBarrasProcedimento',
                                              'p.codigo_barras',
                                              'protocolo p');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'StaEstadoProcedimento',
                                              'p.sta_estado',
                                              'protocolo p');

	  $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'StaEstadoProtocolo',
                                              'd.sta_estado',
                                              'protocolo d');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdTipoProcedimentoProcedimento',
                                              'id_tipo_procedimento',
                                              'procedimento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeTipoProcedimentoProcedimento',
                                              'nome',
                                              'tipo_procedimento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeHipoteseLegal',
                                              'nome',
                                              'hipotese_legal');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'BaseLegalHipoteseLegal',
                                              'base_legal',
                                              'hipotese_legal');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoTipoConferencia',
                                              'descricao',
                                              'tipo_conferencia');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ,'ProtocoloDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ,'AnexoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAssinado');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAssinadoPeloUsuarioAtual');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAssinadoPelaUnidadeAtual');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAssinadoPorOutraUnidade');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinPublicavel');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinPublicacaoAgendada');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinPublicado');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinCircular');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdTextoPadraoEdoc');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdTextoPadraoInterno');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'ProtocoloDocumentoTextoBase');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdDocumentoEdocBase');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdDocumentoBase');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdDocumentoTextoBase');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjAtividadeDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ,'PublicacaoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjAssinaturaDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'Versao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'CodigoVerificador');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjUnidadeDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'CodigoAcesso');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ,'ArquivamentoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'MotivoCancelamento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'SecaoConteudo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdBloco');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ,'InfraSessao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'LinkDownload');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinValidarXss');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinPdf');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinZip');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinPdfEscalaCinza');

    $this->configurarPK('IdDocumento',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdSerie', 'serie', 'id_serie');
    $this->configurarFK('IdDocumento', 'protocolo d', 'd.id_protocolo');
    $this->configurarFK('IdDocumento', 'documento_conteudo', 'id_documento', InfraDTO::$TIPO_FK_OPCIONAL,$this->getNumFiltroFkDocumentoConteudo());
    $this->configurarFK('IdUnidadeGeradoraProtocolo', 'unidade uni_ger', 'uni_ger.id_unidade');
    $this->configurarFK('IdOrgaoUnidadeGeradoraProtocolo', 'orgao org_ger', 'org_ger.id_orgao');
    $this->configurarFK('IdUnidadeResponsavel', 'unidade uni_resp', 'uni_resp.id_unidade');
    $this->configurarFK('IdTipoConferencia','tipo_conferencia','id_tipo_conferencia', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdProcedimento', 'protocolo p', 'p.id_protocolo');
    $this->configurarFK('IdProtocoloProcedimento', 'procedimento', 'id_procedimento');
    $this->configurarFK('IdTipoProcedimentoProcedimento', 'tipo_procedimento', 'id_tipo_procedimento');
    $this->configurarFK('IdHipoteseLegalProtocolo', 'hipotese_legal', 'id_hipotese_legal', InfraDTO::$TIPO_FK_OPCIONAL);

  }

  /**
   * @return int|null
   */
  public function getNumFiltroFkDocumentoConteudo()
  {
    return $this->numFiltroFkDocumentoConteudo;
  }

  /**
   * @param int|null $numFiltroFkDocumentoConteudo
   */
  public function setNumFiltroFkDocumentoConteudo($numFiltroFkDocumentoConteudo)
  {
    $this->numFiltroFkDocumentoConteudo = $numFiltroFkDocumentoConteudo;
  }

}  
?>