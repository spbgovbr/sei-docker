<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 25/10/2011 - criado por mga
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../Sip.php';

class RegraAuditoriaDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'regra_auditoria';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdRegraAuditoria',
                                   'id_regra_auditoria');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Descricao',
                                   'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdSistema',
                                   'id_sistema');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinAtivo',
                                   'sin_ativo');
    
  	$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdOrgaoSistema',
                                              'id_orgao',
                                              'sistema');

  	$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaSistema',
                                              'sigla',
                                              'sistema');
  	
  	$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaOrgaoSistema',
                                              'org_sis.sigla',
                                              'orgao org_sis');
  	
  	$this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjRelRegraAuditoriaRecursoDTO');
  	 
    $this->configurarPK('IdRegraAuditoria',InfraDTO::$TIPO_PK_SEQUENCIAL);
    
    $this->configurarFK('IdSistema', 'sistema', 'id_sistema');
    $this->configurarFK('IdOrgaoSistema', 'orgao org_sis', 'org_sis.id_orgao');
    
    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
?>