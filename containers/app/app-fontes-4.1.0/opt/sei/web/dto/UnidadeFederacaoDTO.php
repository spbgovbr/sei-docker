<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/06/2019 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class UnidadeFederacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'unidade_federacao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdUnidadeFederacao', 'id_unidade_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdInstalacaoFederacao', 'id_instalacao_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Sigla', 'sigla');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaInstalacaoFederacao', 'sigla', 'instalacao_federacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoInstalacaoFederacao', 'descricao', 'instalacao_federacao');

    $this->configurarPK('IdUnidadeFederacao',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdInstalacaoFederacao', 'instalacao_federacao', 'id_instalacao_federacao');
  }
}
