<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/03/2020 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class PesquisaDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'pesquisa';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdPesquisa', 'id_pesquisa');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidade', 'id_unidade');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjCampoPesquisaDTO');

    $this->configurarPK('IdPesquisa',InfraDTO::$TIPO_PK_NATIVA);

  }
}
