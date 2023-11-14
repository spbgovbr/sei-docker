<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 29/11/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class OrgaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return "orgao";
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdOrgao', 'id_orgao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Sigla', 'sigla');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Ordem', 'ordem');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAutenticar', 'sin_autenticar');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelOrgaoAutenticacaoDTO');

    $this->configurarPK('IdOrgao', InfraDTO::$TIPO_PK_SEQUENCIAL);

    $this->configurarExclusaoLogica('SinAtivo', 'N');
  }
}

?>