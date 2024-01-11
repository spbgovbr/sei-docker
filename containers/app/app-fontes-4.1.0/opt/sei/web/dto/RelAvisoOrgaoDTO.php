<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/06/2021 - criado por mgb29
*
* Versão do Gerador de Código: 1.43.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelAvisoOrgaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'rel_aviso_orgao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdAviso', 'id_aviso');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdOrgao', 'id_orgao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'SiglaOrgao',
      'sigla',
      'orgao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'DescricaoOrgao',
      'descricao',
      'orgao');

    $this->configurarPK('IdAviso',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdOrgao',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdOrgao','orgao','id_orgao');
  }
}
