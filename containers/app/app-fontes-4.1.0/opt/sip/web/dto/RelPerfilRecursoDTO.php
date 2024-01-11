<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class RelPerfilRecursoDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return "rel_perfil_recurso";
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdPerfil', 'id_perfil');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdRecurso', 'id_recurso');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdSistema', 'id_sistema');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeRecurso', 'nome', 'recurso');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoRecurso', 'descricao', 'recurso');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinAtivoRecurso', 'sin_ativo', 'recurso');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomePerfil', 'nome', 'perfil');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinAtivoPerfil', 'sin_ativo', 'perfil');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinPerfil');

    $this->configurarPK('IdPerfil', InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdRecurso', InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdSistema', InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdPerfil', 'perfil', 'id_perfil');
    $this->configurarFK('IdSistema', 'perfil', 'id_sistema');
    $this->configurarFK('IdRecurso', 'recurso', 'id_recurso');
    $this->configurarFK('IdSistema', 'recurso', 'id_sistema');
  }
}

?>