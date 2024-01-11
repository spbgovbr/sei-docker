<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/03/2020 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class CampoPesquisaDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'campo_pesquisa';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdCampoPesquisa', 'id_campo_pesquisa');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Chave', 'chave');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Valor', 'valor');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdPesquisa', 'id_pesquisa');

    $this->configurarPK('IdCampoPesquisa',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdPesquisa', 'pesquisa', 'id_pesquisa',InfraDTO::$TIPO_FK_OPCIONAL);
  }
}
