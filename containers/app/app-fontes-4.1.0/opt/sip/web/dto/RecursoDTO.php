<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class RecursoDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return "recurso";
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdRecurso', 'id_recurso');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdSistema', 'id_sistema');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Caminho', 'caminho');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaSistema', 'sigla', 'sistema');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdOrgaoSistema', 'id_orgao', 'sistema');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaOrgao', 'sigla', 'orgao');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinPerfil');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjItemMenuDTO');

    $this->configurarPK('IdRecurso', InfraDTO::$TIPO_PK_SEQUENCIAL);

    $this->configurarFK('IdSistema', 'sistema', 'id_sistema');
    $this->configurarFK('IdOrgaoSistema', 'orgao', 'id_orgao');


    $this->configurarExclusaoLogica('SinAtivo', 'N');
  }
}

?>