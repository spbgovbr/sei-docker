<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class CoordenadorUnidadeDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return "coordenador_unidade";
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdSistema', 'id_sistema');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidade', 'id_unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdOrgaoSistema', 'id_orgao', 'sistema');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaSistema', 'sigla', 'sistema');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoSistema', 'descricao', 'sistema');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaOrgaoSistema', 'a.sigla', 'orgao a');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoOrgaoSistema', 'a.descricao', 'orgao a');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdOrgaoUsuario', 'id_orgao', 'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUsuario', 'sigla', 'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeUsuario', 'nome', 'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaOrgaoUsuario', 'b.sigla', 'orgao b');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoOrgaoUsuario', 'b.descricao', 'orgao b');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdOrgaoUnidade', 'id_orgao', 'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUnidade', 'sigla', 'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoUnidade', 'descricao', 'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinGlobalUnidade', 'sin_global', 'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaOrgaoUnidade', 'c.sigla', 'orgao c');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoOrgaoUnidade', 'c.descricao', 'orgao c');


    $this->configurarPK('IdSistema', InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdUsuario', InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdUnidade', InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdSistema', 'sistema', 'id_sistema');
    $this->configurarFK('IdUsuario', 'usuario', 'id_usuario');
    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade');

    $this->configurarFK('IdOrgaoSistema', 'orgao a', 'a.id_orgao');
    $this->configurarFK('IdOrgaoUsuario', 'orgao b', 'b.id_orgao');
    $this->configurarFK('IdOrgaoUnidade', 'orgao c', 'c.id_orgao');
  }
}

?>