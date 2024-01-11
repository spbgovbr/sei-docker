<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/08/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelUsuarioGrupoBlocoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'rel_usuario_grupo_bloco';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdGrupoBloco', 'id_grupo_bloco');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdUnidadeGrupoBloco', 'id_unidade', 'grupo_bloco');

    $this->configurarPK('IdGrupoBloco',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdUsuario',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdGrupoBloco', 'grupo_bloco', 'id_grupo_bloco');
  }
}
