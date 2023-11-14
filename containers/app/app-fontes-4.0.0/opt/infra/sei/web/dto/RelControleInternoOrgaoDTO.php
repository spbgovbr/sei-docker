<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 14/01/2011 - criado por jonatas_db
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelControleInternoOrgaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'rel_controle_interno_orgao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdControleInterno',
                                   'id_controle_interno');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdOrgao',
                                   'id_orgao');
    
		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaOrgao',
                                              'sigla',
                                              'orgao');        

    $this->configurarPK('IdControleInterno',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdOrgao',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdControleInterno', 'controle_interno', 'id_controle_interno');
		$this->configurarFK('IdOrgao', 'orgao', 'id_orgao',InfraDTO::$TIPO_FK_OPCIONAL);    
  }
}
?>