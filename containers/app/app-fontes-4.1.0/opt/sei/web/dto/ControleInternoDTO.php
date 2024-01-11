<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/01/2011 - criado por jonatas_db
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class ControleInternoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'controle_interno';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdControleInterno',
                                   'id_controle_interno');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Descricao',
                                   'descricao');

    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdOrgaoControlado',
                                              'id_orgao',
                                              'rel_controle_interno_orgao');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdUnidadeControle',
                                              'id_unidade',
                                              'rel_controle_interno_unidade');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdSerieControlada',
                                              'id_serie',
                                              'rel_controle_interno_serie');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdTipoProcedimentoControlado',
                                              'id_tipo_procedimento',
                                              'rel_controle_interno_tipo_proc');
    
		$this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelControleInternoOrgao');    
		$this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelControleInternoUnidade');    
		$this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelControleInternoTipoProc');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelControleInternoSerie');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL, 'IdProcedimento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'StaNivelAcessoGlobal');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdOrgao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdUnidade');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdTipoProcedimento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdTipoProcedimentoAnterior');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdSerie');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdSerieAnterior');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'IdProcessos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'IdProtocolos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'StaOperacao');

    $this->configurarPK('IdControleInterno',InfraDTO::$TIPO_PK_NATIVA);
    
		$this->configurarFK('IdControleInterno', 'rel_controle_interno_orgao', 'id_controle_interno');
		$this->configurarFK('IdControleInterno', 'rel_controle_interno_unidade', 'id_controle_interno');
		$this->configurarFK('IdControleInterno', 'rel_controle_interno_serie', 'id_controle_interno',InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_WHERE);
		$this->configurarFK('IdControleInterno', 'rel_controle_interno_tipo_proc', 'id_controle_interno',InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_WHERE);
  }
}
?>