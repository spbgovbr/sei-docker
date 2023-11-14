<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 31/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.13.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class ProtocoloDTO extends InfraDTO {

  private $numTipoFkProcedimento = null;
  private $numTipoFkDocumento = null;
  private $numTipoFkParticipante = null;
  
  public function __construct(){
    $this->numTipoFkProcedimento = InfraDTO::$TIPO_FK_OPCIONAL;
    $this->numTipoFkDocumento = InfraDTO::$TIPO_FK_OPCIONAL;
    $this->numTipoFkParticipante = InfraDTO::$TIPO_FK_OBRIGATORIA;
    parent::__construct();
  }
  
  public function getStrNomeTabela() {
  	 return 'protocolo';
  }

  public function montar() {  

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdProtocolo',
                                   'id_protocolo');

     $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'IdProtocoloFederacao',
                                   'id_protocolo_federacao');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdProtocoloAgrupador',
                                   'id_protocolo_agrupador');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'ProtocoloFormatado',
                                   'protocolo_formatado');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'ProtocoloFormatadoPesquisa',
                                   'protocolo_formatado_pesquisa');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'ProtocoloFormatadoPesqInv',
                                   'protocolo_formatado_pesq_inv');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Descricao',
                                   'descricao');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'StaProtocolo',
                                   'sta_protocolo');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'StaEstado',
                                   'sta_estado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'SinEliminado',
                                  'sin_eliminado');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'StaNivelAcessoGlobal',
                                   'sta_nivel_acesso_global');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'StaNivelAcessoLocal',
                                   'sta_nivel_acesso_local');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                            	     'StaNivelAcessoOriginal',
                            	     'sta_nivel_acesso_original');
  	 
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidadeGeradora',
                                   'id_unidade_geradora');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUsuarioGerador',
                                   'id_usuario_gerador');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA,
                                   'Geracao',
                                   'dta_geracao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA,
                                    'Inclusao',
                                    'dta_inclusao');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'CodigoBarras',
                                   'codigo_barras');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                            	     'IdHipoteseLegal',
                            	     'id_hipotese_legal');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                            	     'StaGrauSigilo',
                            	     'sta_grau_sigilo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUnidadeGeradora',
                                              'uni_ger.sigla',
                                              'unidade uni_ger');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoUnidadeGeradora',
                                              'uni_ger.descricao',
                                              'unidade uni_ger');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdOrgaoUnidadeGeradora',
                                              'uni_ger.id_orgao',
                                              'unidade uni_ger');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SinOuvidoriaUnidadeGeradora',
                                              'uni_ger.sin_ouvidoria',
                                              'unidade uni_ger');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaOrgaoUnidadeGeradora',
                                              'sigla',
                                              'orgao');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUsuarioGerador',
                                              'usu_ger.sigla',
                                              'usuario usu_ger');
                                              
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeUsuarioGerador',
                                              'usu_ger.nome',
                                              'usuario usu_ger');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                                              'IdDocumentoDocumento',
                                              'id_documento',
                                              'documento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                                              'IdProcedimentoDocumento',
                                              'id_procedimento',
                                              'documento');
                                              
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdSerieDocumento',
                                              'id_serie',
                                              'documento');
                                              
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeSerieDocumento',
                                              'nome',
                                              'serie');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NumeroDocumento',
                                              'numero',
                                              'documento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeArvoreDocumento',
                                              'nome_arvore',
                                              'documento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DIN,
                                              'ValorDocumento',
                                              'din_valor',
                                              'documento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                                              'IdDocumentoEdocDocumento',
                                              'id_documento_edoc',
                                              'documento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'StaDocumentoDocumento',
                                              'sta_documento',
                                              'documento');
        
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdTipoFormularioDocumento',
                                              'id_tipo_formulario',
                                              'documento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdTipoConferenciaDocumento',
                                              'id_tipo_conferencia',
                                              'documento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SinArquivamentoDocumento',
                                              'sin_arquivamento',
                                              'documento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SinBloqueadoDocumento',
                                              'sin_bloqueado',
                                              'documento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'ConteudoDocumento',
                                              'conteudo',
                                              'documento_conteudo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdTipoProcedimentoProcedimento',
                                              'p.id_tipo_procedimento',
                                              'procedimento p');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdPlanoTrabalhoProcedimento',
                                              'p.id_plano_trabalho',
                                              'procedimento p');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomePlanoTrabalhoProcedimento',
                                              'nome',
                                              'plano_trabalho');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA,
                                              'ConclusaoProcedimento',
                                              'p.dta_conclusao',
                                              'procedimento p');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                                              'IdProcedimento',
                                              'p.id_procedimento',
                                              'procedimento p');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SinInicialAtividade',
                                              'sin_inicial',
                                              'atividade');
                                              
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdUnidadeAtividade',
                                              'id_unidade',
                                              'atividade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdTarefaAtividade',
                                              'id_tarefa',
                                              'atividade');
                                              
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTH,
                                              'ConclusaoAtividade',
                                              'dth_conclusao',
                                              'atividade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoObservacao',
                                              'descricao',
                                              'observacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdUnidadeObservacao',
                                              'id_unidade',
                                              'observacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdTipoProcedimentoDocumento',
                                              'pd.id_tipo_procedimento',
                                              'procedimento pd');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                                              'IdProcedimentoDocumentoProcedimento',
                                              'pd.id_procedimento',
                                              'procedimento pd');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'ProtocoloFormatadoProcedimentoDocumento',
                                              'ppd.protocolo_formatado',
                                              'protocolo ppd');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'ProtocoloFormatadoPesquisaProcedimentoDocumento',
                                              'ppd.protocolo_formatado_pesquisa',
                                              'protocolo ppd');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeTipoProcedimentoProcedimento',
                                              'tpp.nome',
                                              'tipo_procedimento tpp');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SinOuvidoriaTipoProcedimentoProcedimento',
                                              'tpp.sin_ouvidoria',
                                              'tipo_procedimento tpp');
                                            
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeTipoProcedimentoDocumento',
                                              'tpd.nome',
                                              'tipo_procedimento tpd');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdContatoParticipante',
                                              'id_contato',
                                              'participante');
                                              
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'StaParticipacaoParticipante',
                                              'sta_participacao',
                                              'participante');

	  $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdProcedimento');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjParticipanteDTO');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjRelProtocoloAtributoDTO');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjObservacaoDTO');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjAnexoDTO');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjRelProtocoloAssuntoDTO');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjRelProtocoloProtocoloDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ,'AnotacaoDTO');
    		
		//$this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ,'PublicacaoDTO');
		
		$this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAberto');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAssinado');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinPublicado');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'CodigoAcesso');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAcessoAssinaturaBloco');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAcessoRascunhoBloco');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinDisponibilizadoParaOutraUnidade');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinCredencialProcesso');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinCredencialAssinatura');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinLancarAndamento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinUnidadeGeradoraProtocolo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'AcessoModulos');

	  $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjLocalizadorDTO');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinPesquisaFederacao');
	  
   	$this->configurarPK('IdProtocolo',InfraDTO::$TIPO_PK_INFORMADO);
	      
    $this->configurarFK('IdProtocolo', 'procedimento p', 'p.id_procedimento', $this->getNumTipoFkProcedimento());
    $this->configurarFK('IdTipoProcedimentoProcedimento', 'tipo_procedimento tpp', 'tpp.id_tipo_procedimento');
    $this->configurarFK('IdProtocolo', 'documento', 'id_documento', $this->getNumTipoFkDocumento());
    $this->configurarFK('IdProtocolo', 'participante', 'id_protocolo', $this->getNumTipoFkParticipante());
    $this->configurarFK('IdProtocolo', 'atividade', 'id_protocolo');
    $this->configurarFK('IdDocumentoDocumento', 'documento_conteudo', 'id_documento',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdSerieDocumento', 'serie', 'id_serie');
    $this->configurarFK('IdUnidadeGeradora', 'unidade uni_ger', 'uni_ger.id_unidade');
    $this->configurarFK('IdOrgaoUnidadeGeradora', 'orgao', 'id_orgao');
    $this->configurarFK('IdUsuarioGerador', 'usuario usu_ger', 'usu_ger.id_usuario');
    $this->configurarFK('IdProtocolo', 'observacao', 'id_protocolo');
    $this->configurarFK('IdProcedimentoDocumento', 'procedimento pd', 'pd.id_procedimento');
    $this->configurarFK('IdTipoProcedimentoDocumento', 'tipo_procedimento tpd', 'tpd.id_tipo_procedimento');
    $this->configurarFK('IdProcedimentoDocumentoProcedimento', 'protocolo ppd', 'ppd.id_protocolo');
    $this->configurarFK('IdProtocolo', 'arquivamento', 'id_protocolo', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdPlanoTrabalhoProcedimento', 'plano_trabalho', 'id_plano_trabalho', InfraDTO::$TIPO_FK_OPCIONAL);
  }
  
  public function getNumTipoFkProcedimento(){
    return $this->numTipoFkProcedimento;
  }
  
  public function setNumTipoFkProcedimento($numTipoFkProcedimento){
    $this->numTipoFkProcedimento = $numTipoFkProcedimento;
  }

  public function getNumTipoFkDocumento(){
    return $this->numTipoFkDocumento;
  }
  
  public function setNumTipoFkDocumento($numTipoFkDocumento){
    $this->numTipoFkDocumento = $numTipoFkDocumento;
  }

  public function getNumTipoFkParticipante(){
    return $this->numTipoFkParticipante;
  }
  
  public function setNumTipoFkParticipante($numTipoFkParticipante){
    $this->numTipoFkParticipante = $numTipoFkParticipante;
  }
  
}
?>