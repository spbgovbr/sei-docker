<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 29/07/2016 - criado por mga
*
* Versão do Gerador de Código: 1.38.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class SerieRestricaoDTO extends InfraDTO {

  private $numTipoFkUnidade = null;

  public function __construct(){
    $this->numTipoFkUnidade = InfraDTO::$TIPO_FK_OPCIONAL;
    parent::__construct();
  }

  public function getStrNomeTabela() {
  	 return 'serie_restricao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdSerieRestricao',
                                   'id_serie_restricao');

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
                                              'SiglaOrgao',
                                              'sigla',
                                              'orgao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUnidade',
                                              'sigla',
                                              'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoUnidade',
                                              'descricao',
                                              'unidade');

    $this->configurarPK('IdSerieRestricao',InfraDTO::$TIPO_PK_NATIVA);
    $this->configurarFK('IdOrgao', 'orgao', 'id_orgao');
    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade', $this->getNumTipoFkUnidade());
  }

  public function getNumTipoFkUnidade(){
    return $this->numTipoFkUnidade;
  }

  public function setNumTipoFkUnidade($numTipoFkUnidade){
    $this->numTipoFkUnidade = $numTipoFkUnidade;
  }
}
?>