<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class RelHierarquiaUnidadeDTO extends InfraDTO {

  // function __destruct() {
  //     parent::__destruct();
  //}

  public function getStrNomeTabela() {
    return "rel_hierarquia_unidade";
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidade', 'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdHierarquia', 'id_hierarquia');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdHierarquiaPai', 'id_hierarquia_pai');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidadePai', 'id_unidade_pai');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'DataInicio', 'dta_inicio');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'DataFim', 'dta_fim');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUnidade', 'sigla', 'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoUnidade', 'descricao', 'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'IdOrigemUnidade', 'id_origem', 'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdOrgaoUnidade', 'id_orgao', 'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaOrgaoUnidade', 'sigla', 'orgao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoOrgaoUnidade', 'descricao', 'orgao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinGlobalUnidade', 'sin_global', 'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinAtivoUnidade', 'sin_ativo', 'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeHierarquia', 'nome', 'hierarquia');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Ramificacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'UnidadesSuperiores');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'UnidadesInferiores');


    $this->configurarPK('IdUnidade', InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdHierarquia', InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade');
    $this->configurarFK('IdHierarquia', 'hierarquia', 'id_hierarquia');
    $this->configurarFK('IdOrgaoUnidade', 'orgao', 'id_orgao');
    $this->configurarExclusaoLogica('SinAtivo', 'N');
  }
}

?>