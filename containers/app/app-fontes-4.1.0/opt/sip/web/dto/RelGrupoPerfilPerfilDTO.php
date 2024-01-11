<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 15/07/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../Sip.php';

class RelGrupoPerfilPerfilDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return 'rel_grupo_perfil_perfil';
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdGrupoPerfil', 'id_grupo_perfil');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdSistema', 'id_sistema');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdPerfil', 'id_perfil');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeGrupoPerfil', 'nome', 'grupo_perfil');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinAtivoGrupoPerfil', 'sin_ativo', 'grupo_perfil');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SinAtivoPerfil', 'sin_ativo', 'perfil');

    $this->configurarPK('IdGrupoPerfil', InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdSistema', InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdPerfil', InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdGrupoPerfil', 'grupo_perfil', 'id_grupo_perfil');
    $this->configurarFK('IdPerfil', 'perfil', 'id_perfil');
  }
}
