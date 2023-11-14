<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 30/11/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class AdministradorSistemaDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return "administrador_sistema";
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdSistema', 'id_sistema');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUsuario', 'sigla', 'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeUsuario', 'nome', 'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaSistema', 'sigla', 'sistema');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdOrgaoUsuario', 'id_orgao', 'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaOrgaoUsuario', 'a.sigla', 'orgao a');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoOrgaoUsuario', 'a.descricao', 'orgao a');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdOrgaoSistema', 'id_orgao', 'sistema');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaOrgaoSistema', 'b.sigla', 'orgao b');


    $this->configurarPK('IdUsuario', InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdSistema', InfraDTO::$TIPO_PK_INFORMADO);


    $this->configurarFK('IdUsuario', 'usuario', 'id_usuario');
    $this->configurarFK('IdSistema', 'sistema', 'id_sistema');

    $this->configurarFK('IdOrgaoUsuario', 'orgao a', 'a.id_orgao');
    $this->configurarFK('IdOrgaoSistema', 'orgao b', 'b.id_orgao');
  }
}

?>