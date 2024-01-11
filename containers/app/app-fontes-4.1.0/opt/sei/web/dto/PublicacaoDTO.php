<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 25/11/2008 - criado por mga
*
* Versão do Gerador de Código: 1.25.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class PublicacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'publicacao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdPublicacao',
                                   'id_publicacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdDocumento',
                                   'id_documento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidade',
                                   'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdAtividade',
                                   'id_atividade');
                                   
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUsuario',
                                   'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
                                   'Agendamento',
                                   'dth_agendamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA,
                                   'Disponibilizacao',
                                   'dta_disponibilizacao');
                                   
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA,
                                   'Publicacao',
                                   'dta_publicacao');
                                   
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'StaMotivo',
                                   'sta_motivo');
 
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdVeiculoIO',
                                   'id_veiculo_io');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdSecaoIO',
                                   'id_secao_io');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA,
                                   'PublicacaoIO',
                                   'dta_publicacao_io');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'PaginaIO',
                                   'pagina_io');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Resumo',
                                   'resumo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 
                                  'Numero',
                                  'numero');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                  'IdVeiculoPublicacao',
                                  'id_veiculo_publicacao');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUnidadeResponsavelDocumento',
                                              'sigla',
                                              'unidade');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoUnidadeResponsavelDocumento',
                                              'descricao',
                                              'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdUnidadeResponsavelDocumento',
                                              'id_unidade_responsavel',
                                              'documento');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdOrgaoUnidadeResponsavelDocumento',
                                              'id_orgao',
                                              'unidade');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaOrgaoUnidadeResponsavelDocumento',
                                              'sigla',
                                              'orgao');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoOrgaoUnidadeResponsavelDocumento',
                                              'descricao',
                                              'orgao');
        
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                                              'IdDocumentoDocumento',
                                              'id_documento',
                                              'documento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'ConteudoDocumento',
                                              'conteudo',
                                              'documento_conteudo');

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

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                          'IdUnidadeGeradoraProtocolo',
                                          'd.id_unidade_geradora',
                                          'protocolo d');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                          'SiglaUnidadeGeradoraProtocolo',
                                          'uni_ger.sigla',
                                          'unidade uni_ger');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                        'DescricaoUnidadeGeradoraProtocolo',
                                        'uni_ger.descricao',
                                        'unidade uni_ger');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'ProtocoloFormatadoProtocolo',
                                              'd.protocolo_formatado',
                                              'protocolo d');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'ProtocoloFormatadoPesquisaProtocolo',
                                              'd.protocolo_formatado_pesquisa',
                                              'protocolo d');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                                              'IdProtocoloAgrupadorProtocolo',
                                              'd.id_protocolo_agrupador',
                                              'protocolo d');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                                              'IdProtocoloProtocolo',
                                              'd.id_protocolo',
                                              'protocolo d');
        
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA,
                                              'GeracaoProtocolo',
                                              'd.dta_geracao',
                                              'protocolo d');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'StaNivelAcessoLocalProtocolo',
                                              'd.sta_nivel_acesso_local',
                                              'protocolo d');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'StaTipoVeiculoPublicacao',
                                              'sta_tipo',
                                              'veiculo_publicacao');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeVeiculoPublicacao',
                                              'nome',
                                              'veiculo_publicacao');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoVeiculoPublicacao',
                                              'descricao',
                                              'veiculo_publicacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaVeiculoImprensaNacional',
                                              'sigla',
                                              'veiculo_imprensa_nacional');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoVeiculoImprensaNacional',
                                              'descricao',
                                              'veiculo_imprensa_nacional');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeSecaoImprensaNacional',
                                              'nome',
                                              'secao_imprensa_nacional');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'ProtocoloProcedimentoFormatado',
                                              'p.protocolo_formatado',
                                              'protocolo p');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdPublicacaoLegado');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'EfeitoPublicacaoLegado');
    
    //$this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'DescricaoVeiculo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'DescricaoMotivo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'StaEstado');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'DocumentoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'TextoInformativo');
            
    $this->configurarPK('IdPublicacao', InfraDTO::$TIPO_PK_NATIVA );
    
    $this->configurarFK('IdDocumento','documento','id_documento');
    $this->configurarFK('IdDocumentoDocumento','protocolo d','d.id_protocolo');
    $this->configurarFK('IdUnidadeGeradoraProtocolo', 'unidade uni_ger', 'uni_ger.id_unidade');
    $this->configurarFK('IdDocumentoDocumento','documento_conteudo','id_documento',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdProcedimentoDocumento','protocolo p','p.id_protocolo');
    $this->configurarFK('IdUnidadeResponsavelDocumento','unidade','id_unidade');
    $this->configurarFK('IdOrgaoUnidadeResponsavelDocumento','orgao','id_orgao');
    $this->configurarFK('IdSerieDocumento','serie','id_serie');
    $this->configurarFK('IdVeiculoPublicacao','veiculo_publicacao','id_veiculo_publicacao');
    $this->configurarFK('IdVeiculoIO','veiculo_imprensa_nacional','id_veiculo_imprensa_nacional',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdSecaoIO','secao_imprensa_nacional','id_secao_imprensa_nacional',InfraDTO::$TIPO_FK_OPCIONAL);

  }
}
?>