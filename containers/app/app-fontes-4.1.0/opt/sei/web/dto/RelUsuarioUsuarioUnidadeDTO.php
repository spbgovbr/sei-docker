<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 12/09/2017 - criado por mga
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelUsuarioUsuarioUnidadeDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'rel_usuario_usuario_unidade';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuarioAtribuicao', 'id_usuario_atribuicao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidade', 'id_unidade');

    $this->configurarPK('IdUsuario',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdUsuarioAtribuicao',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdUnidade',InfraDTO::$TIPO_PK_INFORMADO);

  }
}
?>