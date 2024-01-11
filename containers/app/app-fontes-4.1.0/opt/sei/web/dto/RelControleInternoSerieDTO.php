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

class RelControleInternoSerieDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'rel_controle_interno_serie';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdSerie',
                                   'id_serie');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdControleInterno',
                                   'id_controle_interno');
    
		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeSerie',
                                              'nome',
                                              'serie');    
    

    $this->configurarPK('IdSerie',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdControleInterno',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdControleInterno', 'controle_interno', 'id_controle_interno');
    $this->configurarFK('IdSerie', 'serie', 'id_serie',InfraDTO::$TIPO_FK_OPCIONAL);
    
  }
}
?>