<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 01/07/2008 - criado por fbv
*
* Versão do Gerador de Código: 1.19.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class SerieDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'serie';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdSerie',
                                   'id_serie');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdGrupoSerie',
                                   'id_grupo_serie');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'StaNumeracao',
                                   'sta_numeracao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
													    		'StaAplicabilidade',
													    		'sta_aplicabilidade');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdModeloEdoc',
                                   'id_modelo_edoc');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdModelo',
                                   'id_modelo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdTipoFormulario',
                                   'id_tipo_formulario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Nome',
                                   'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Descricao',
                                   'descricao');
   
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinInteressado',
                                   'sin_interessado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinDestinatario',
                                   'sin_destinatario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'SinValorMonetario',
                                  'sin_valor_monetario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinAssinaturaPublicacao',
                                   'sin_assinatura_publicacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinInterno',
                                   'sin_interno');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinUsuarioExterno',
                                   'sin_usuario_externo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinAtivo',
                                   'sin_ativo');
                                   
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeGrupoSerie',
                                              'nome',
                                              'grupo_serie');
    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'DescricaoModeloEdoc');                                              
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjRelUnidadeSerieUnidadeDTO');
    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjRelSerieAssuntoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjSerieRestricaoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjRelSerieVeiculoPublicacaoDTO');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinSomenteUtilizados');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdAssunto');

    $this->configurarPK('IdSerie',InfraDTO::$TIPO_PK_NATIVA );

    $this->configurarFK('IdGrupoSerie', 'grupo_serie', 'id_grupo_serie');
    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
?>