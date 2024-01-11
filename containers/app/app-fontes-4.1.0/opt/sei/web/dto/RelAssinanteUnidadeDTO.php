<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/11/2009 - criado por mga
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelAssinanteUnidadeDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'rel_assinante_unidade';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidade',
                                   'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdAssinante',
                                   'id_assinante');


    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                            'IdOrgaoUnidade',
                                            'id_orgao',
                                            'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'SiglaUnidade',
                                             'sigla',
                                             'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'DescricaoUnidade',
                                             'descricao',
                                             'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                            'CargoFuncaoAssinante',
                                            'cargo_funcao',
                                            'assinante');

    $this->configurarPK('IdUnidade',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdAssinante',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade');
    $this->configurarFK('IdAssinante', 'assinante', 'id_assinante');
  }
}
?>