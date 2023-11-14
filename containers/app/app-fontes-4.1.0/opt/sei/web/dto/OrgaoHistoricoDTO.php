<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/07/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class OrgaoHistoricoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'orgao_historico';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdOrgaoHistorico', 'id_orgao_historico');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdOrgao', 'id_orgao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Sigla', 'sigla');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'Inicio', 'dta_inicio');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'Fim', 'dta_fim');

    $this->configurarPK('IdOrgaoHistorico',InfraDTO::$TIPO_PK_NATIVA);

    $this->adicionarAtributo(InfraDTO::$PREFIXO_BOL,'OrigemSIP');


  }
}
