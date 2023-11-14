<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/09/2012 - criado por mga
*
* Versão do Gerador de Código: 1.33.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class NumeracaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'numeracao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdNumeracao',
                                   'id_numeracao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'Sequencial',
                                   'sequencial');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'Ano',
                                   'ano');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdSerie',
                                   'id_serie');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdOrgao',
                                   'id_orgao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidade',
                                   'id_unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeSerie',
                                              'nome',
                                              'serie');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'StaNumeracaoSerie',
                                              'sta_numeracao',
                                              'serie');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaOrgao',
                                              'sigla',
                                              'orgao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoOrgao',
                                              'descricao',
                                              'orgao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUnidade',
                                              'sigla',
                                              'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoUnidade',
                                              'descricao',
                                              'unidade');
    
    $this->configurarPK('IdNumeracao',InfraDTO::$TIPO_PK_NATIVA);

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'SequencialOriginal');
    
    $this->configurarFK('IdSerie', 'serie', 'id_serie');
    $this->configurarFK('IdOrgao', 'orgao', 'id_orgao',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade',InfraDTO::$TIPO_FK_OPCIONAL);

  }
}
?>