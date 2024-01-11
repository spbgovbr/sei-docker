<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/06/2010 - criado por fazenda_db
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class BaseConhecimentoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'base_conhecimento';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdBaseConhecimento',
                                   'id_base_conhecimento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdBaseConhecimentoOrigem',
                                   'id_base_conhecimento_origem');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdBaseConhecimentoAgrupador',
                                   'id_base_conhecimento_agrupador');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdDocumentoEdoc',
                                   'id_documento_edoc');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdConjuntoEstilos',
                                   'id_conjunto_estilos');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidade',
                                   'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Descricao',
                                   'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Conteudo',
                                   'conteudo');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'StaEstado',
                                   'sta_estado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'StaDocumento',
                                   'sta_documento');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
                                   'Geracao',
                                   'dth_geracao');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
                                   'Liberacao',
                                   'dth_liberacao');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUsuarioGerador',
                                   'id_usuario_gerador');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUsuarioLiberacao',
                                   'id_usuario_liberacao');
      
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
																												'SiglaUsuarioGerador',
																												'g.sigla',
																												'usuario g');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
																												'SiglaUsuarioLiberacao',
																												'l.sigla',
																												'usuario l');    
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
																												'NomeUsuarioGerador',
																												'g.nome',
																												'usuario g');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
																												'NomeUsuarioLiberacao',
																												'l.nome',
																												'usuario l');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
										                                   'SiglaUnidade',
										                                   'u.sigla',
										                                   'unidade u');
    
   	$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
					                                              'DescricaoUnidade',
					                                              'u.descricao',
					                                              'unidade u');

   	$this->adicionarAtributo(InfraDTO::$PREFIXO_DBL, 'IdDocumentoEdocBase');
   	$this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjAnexoDTO');
   	$this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelBaseConhecTipoProcedDTO');
   	$this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdBaseConhecimentoBase');  
                                              
    $this->configurarPK('IdBaseConhecimento', InfraDTO::$TIPO_PK_NATIVA );
    
		$this->configurarFK('IdUsuarioGerador', 'usuario g', 'g.id_usuario');
		$this->configurarFK('IdUsuarioLiberacao', 'usuario l', 'l.id_usuario',InfraDTO::$TIPO_FK_OPCIONAL);
		$this->configurarFK('IdUnidade', 'unidade u', 'u.id_unidade');
  }
}
?>