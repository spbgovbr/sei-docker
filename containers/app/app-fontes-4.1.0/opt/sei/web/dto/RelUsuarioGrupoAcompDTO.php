<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 12/09/2017 - criado por mga
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelUsuarioGrupoAcompDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'rel_usuario_grupo_acomp';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdGrupoAcompanhamento', 'id_grupo_acompanhamento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdUnidadeGrupoAcompanhamento', 'id_unidade', 'grupo_acompanhamento');

    $this->configurarPK('IdUsuario',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdGrupoAcompanhamento',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdGrupoAcompanhamento', 'grupo_acompanhamento', 'id_grupo_acompanhamento');
  }
}
?>