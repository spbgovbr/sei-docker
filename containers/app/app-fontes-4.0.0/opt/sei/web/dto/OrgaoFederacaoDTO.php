<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/05/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class OrgaoFederacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'orgao_federacao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdOrgaoFederacao', 'id_orgao_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdInstalacaoFederacao', 'id_instalacao_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Sigla', 'sigla');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjUnidadeFederacaoDTO');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinOrigem');

    $this->configurarPK('IdOrgaoFederacao',InfraDTO::$TIPO_PK_INFORMADO);
  }
}
