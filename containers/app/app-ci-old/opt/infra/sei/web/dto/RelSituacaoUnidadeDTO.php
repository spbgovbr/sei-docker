<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/03/2015 - criado por mga
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelSituacaoUnidadeDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'rel_situacao_unidade';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidade',
                                   'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdSituacao',
                                   'id_situacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'SiglaUnidade',
        'sigla',
        'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'DescricaoUnidade',
        'descricao',
        'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'SinAtivoSituacao',
        'sin_ativo',
        'situacao');

    $this->configurarPK('IdUnidade',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdSituacao',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade');
    $this->configurarFK('IdSituacao', 'situacao', 'id_situacao');
  }
}
?>