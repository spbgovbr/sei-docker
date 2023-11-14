<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 21/09/2022 - criado por cas84
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__).'/../SEI.php';

class RelOrgaoPesquisaDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return 'rel_orgao_pesquisa';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdOrgao1', 'id_orgao_1');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdOrgao2', 'id_orgao_2');

    $this->configurarPK('IdOrgao1',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdOrgao2',InfraDTO::$TIPO_PK_INFORMADO);

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'SiglaOrgao1',
      'o1.sigla',
      'orgao o1');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'DescricaoOrgao1',
      'o1.descricao',
      'orgao o1');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'SiglaOrgao2',
      'o2.sigla',
      'orgao o2');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'DescricaoOrgao2',
      'o2.descricao',
      'orgao o2');


    $this->configurarFK('IdOrgao1','orgao o1','o1.id_orgao');
    $this->configurarFK('IdOrgao2','orgao o2','o2.id_orgao');

  }
}
