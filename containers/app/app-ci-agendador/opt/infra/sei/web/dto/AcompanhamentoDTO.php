<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/11/2010 - criado por jonatas_db
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AcompanhamentoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'acompanhamento';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdAcompanhamento',
                                   'id_acompanhamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidade',
                                   'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdGrupoAcompanhamento',
                                   'id_grupo_acompanhamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdProtocolo',
                                   'id_protocolo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUsuario',
                                   'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
                                   'Alteracao',
                                   'dth_alteracao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Observacao',
                                   'observacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'IdxAcompanhamento',
                                  'idx_acompanhamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'TipoVisualizacao',
                                   'tipo_visualizacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             						'SiglaUsuario',
                                             						'sigla',
                                             						'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             						'NomeUsuario',
                                             						'nome',
                                             						'usuario');
        
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
										                                   'SiglaUnidade',
										                                   'sigla',
										                                   'unidade');
    
   	$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
					                                              'DescricaoUnidade',
					                                              'descricao',
					                                              'unidade');
   	
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
																												'NomeGrupo',
																												'nome',
																												'grupo_acompanhamento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
                                              					'IdProtocoloProtocolo',
                                              					'id_protocolo',
                                              					'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              					'ProtocoloFormatado',
                                              					'protocolo_formatado',
                                              					'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                                        'StaNivelAcessoGlobalProtocolo',
                                                        'sta_nivel_acesso_global',
                                                        'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                                        'IdTipoProcedimentoProcedimento',
                                                        'id_tipo_procedimento',
                                                        'procedimento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                                        'NomeTipoProcedimento',
                                                        'nome',
                                                        'tipo_procedimento');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ,'ProcedimentoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'PalavrasPesquisa');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAlterados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAbertos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinFechados');

    /*
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ,'AnotacaoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjRetornoProgramadoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ,'RelProcedSituacaoUnidadeDTO');
    */

    $this->configurarPK('IdAcompanhamento', InfraDTO::$TIPO_PK_NATIVA);
    
		$this->configurarFK('IdUnidade', 'unidade', 'id_unidade');
		$this->configurarFK('IdGrupoAcompanhamento', 'grupo_acompanhamento', 'id_grupo_acompanhamento', InfraDTO::$TIPO_FK_OPCIONAL);
		$this->configurarFK('IdUsuario','usuario','id_usuario');
    $this->configurarFK('IdProtocolo', 'protocolo', 'id_protocolo');
    $this->configurarFK('IdProtocoloProtocolo', 'procedimento', 'id_procedimento');
    $this->configurarFK('IdTipoProcedimentoProcedimento', 'tipo_procedimento', 'id_tipo_procedimento');
  }
}
?>