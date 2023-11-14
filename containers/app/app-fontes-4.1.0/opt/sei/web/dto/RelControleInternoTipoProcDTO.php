<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 13/06/2012 - criado por mga
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelControleInternoTipoProcDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'rel_controle_interno_tipo_proc';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdTipoProcedimento',
                                   'id_tipo_procedimento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdControleInterno',
                                   'id_controle_interno');

		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeTipoProcedimento',
                                              'nome',
                                              'tipo_procedimento');    
    
    $this->configurarPK('IdTipoProcedimento',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdControleInterno',InfraDTO::$TIPO_PK_INFORMADO);
    
    $this->configurarFK('IdControleInterno', 'controle_interno', 'id_controle_interno');
    $this->configurarFK('IdTipoProcedimento', 'tipo_procedimento', 'id_tipo_procedimento', InfraDTO::$TIPO_FK_OPCIONAL);
    

  }
}
?>