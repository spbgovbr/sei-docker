<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/01/2007 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class MenuDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return "menu";
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMenu', 'id_menu');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdSistema', 'id_sistema');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaSistema', 'sigla', 'sistema');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoSistema', 'descricao', 'sistema');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdOrgaoSistema', 'id_orgao', 'sistema');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaOrgaoSistema', 'sigla', 'orgao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoOrgaoSistema', 'descricao', 'orgao');

    //Usado na copia de sistemas
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjItemMenuDTO');

    $this->configurarPK('IdMenu', InfraDTO::$TIPO_PK_SEQUENCIAL);

    $this->configurarFK('IdSistema', 'sistema', 'id_sistema');
    $this->configurarFK('IdOrgaoSistema', 'orgao', 'id_orgao');
    $this->configurarExclusaoLogica('SinAtivo', 'N');
  }
}

?>