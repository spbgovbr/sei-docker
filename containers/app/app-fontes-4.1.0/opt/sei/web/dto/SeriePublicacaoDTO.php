<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 02/12/2013 - criado por mkr@trf4.jus.br
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class SeriePublicacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'serie_publicacao';
  }

  public function montar() {

      
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdSeriePublicacao',
                                   'id_serie_publicacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdSerie',
                                   'id_serie');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                    'IdOrgao',
                                    'id_orgao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeSerie',
                                              'nome',
                                              'serie');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaOrgao',
                                              'sigla',
                                              'orgao');
        
    $this->configurarPK('IdSeriePublicacao',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdSerie', 'serie', 'id_serie');
    $this->configurarFK('IdOrgao', 'orgao', 'id_orgao');    
        
  }
}
?>