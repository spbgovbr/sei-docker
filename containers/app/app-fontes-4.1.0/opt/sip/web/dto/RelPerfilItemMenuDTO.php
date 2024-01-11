<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 21/03/2007 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class RelPerfilItemMenuDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return "rel_perfil_item_menu";
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdPerfil', 'id_perfil');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdSistema', 'id_sistema');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdRecurso', 'id_recurso');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdMenu', 'id_menu');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdItemMenu', 'id_item_menu');


    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinPerfil');

    $this->configurarPK('IdSistema', InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdPerfil', InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdRecurso', InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdMenu', InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdItemMenu', InfraDTO::$TIPO_PK_INFORMADO);
    /*
    $this->configurarFK('IdPerfil', 'perfil', 'id_perfil');
    $this->configurarFK('IdSistema', 'perfil', 'id_sistema');
    $this->configurarFK('IdMenu', 'item_menu', 'id_menu');
    $this->configurarFK('IdItemMenu', 'item_menu', 'id_item_menu');
    */
  }
}

?>