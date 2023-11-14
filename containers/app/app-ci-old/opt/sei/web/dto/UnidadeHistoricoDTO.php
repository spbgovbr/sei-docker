<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/07/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class UnidadeHistoricoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'unidade_historico';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidadeHistorico', 'id_unidade_historico');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdOrgao', 'id_orgao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidade', 'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Sigla', 'sigla');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'Inicio', 'dta_inicio');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'Fim', 'dta_fim');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaOrgao', 'sigla', 'orgao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoOrgao', 'descricao', 'orgao');

    $this->configurarPK('IdUnidadeHistorico',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdOrgao', 'orgao', 'id_orgao');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_BOL,'OrigemSIP');

  }
}
