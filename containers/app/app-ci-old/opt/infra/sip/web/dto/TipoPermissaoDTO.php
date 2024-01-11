<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class TipoPermissaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return "tipo_permissao";
  }

  public function montar() {

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdTipoPermissao',
                                   'id_tipo_permissao');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Descricao',
                                   'descricao');

    $this->configurarPK('IdTipoPermissao',InfraDTO::$TIPO_PK_SEQUENCIAL);

  }
}
?>