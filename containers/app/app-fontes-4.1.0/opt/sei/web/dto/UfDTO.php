<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/12/2007 - criado por mga
*
* Versão do Gerador de Código: 1.12.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class UfDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'uf';
  }

  public function montar() {

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUf',       'id_uf');
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Sigla',      'sigla');
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome',       'nome');
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'CodigoIbge', 'codigo_ibge');
     $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdPais',     'id_pais');
  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'Pais', 'nome', 'pais');
  	 
     $this->configurarPK('IdUf',InfraDTO::$TIPO_PK_NATIVA);
     $this->configurarFK('IdPais', 'pais', 'id_pais',InfraDTO::$TIPO_FK_OPCIONAL);
  }
}
?>