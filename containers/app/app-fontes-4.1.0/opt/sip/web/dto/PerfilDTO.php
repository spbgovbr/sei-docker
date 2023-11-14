<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class PerfilDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return "perfil";
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdPerfil', 'id_perfil');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdSistema', 'id_sistema');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinCoordenado', 'sin_coordenado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Sin2Fatores', 'sin_2_fatores');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaSistema', 'sigla', 'sistema');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoSistema', 'descricao', 'sistema');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdOrgaoSistema', 'id_orgao', 'sistema');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaOrgaoSistema', 'sigla', 'orgao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoOrgaoSistema', 'descricao', 'orgao');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelPerfilRecursoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelPerfilItemMenuDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelGrupoPerfilPerfilDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdGrupoPerfil'); //pesquisa

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinVisualizarProprios');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinVisualizarDescricao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'NomeRecurso');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinCoordenadoPeloUsuario');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinCoordenadoPorAlgumUsuario');


    $this->configurarPK('IdPerfil', InfraDTO::$TIPO_PK_SEQUENCIAL);

    $this->configurarFK('IdSistema', 'sistema', 'id_sistema');
    $this->configurarFK('IdOrgaoSistema', 'orgao', 'id_orgao');

    $this->configurarExclusaoLogica('SinAtivo', 'N');
  }
}

?>