<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/12/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelGrupoFedOrgaoFedDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'rel_grupo_fed_orgao_fed';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdGrupoFederacao', 'id_grupo_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdOrgaoFederacao', 'id_orgao_federacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaOrgaoFederacao', 'sigla', 'orgao_federacao');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoOrgaoFederacao', 'descricao', 'orgao_federacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'IdInstalacaoFederacao', 'id_instalacao_federacao', 'orgao_federacao');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaInstalacaoFederacao', 'sigla', 'instalacao_federacao');

    $this->configurarPK('IdGrupoFederacao',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdOrgaoFederacao',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdOrgaoFederacao', 'orgao_federacao', 'id_orgao_federacao');
    $this->configurarFK('IdInstalacaoFederacao', 'instalacao_federacao', 'id_instalacao_federacao');

  }
}
