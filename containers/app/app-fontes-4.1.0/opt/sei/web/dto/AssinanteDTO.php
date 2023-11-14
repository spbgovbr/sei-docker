<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 13/10/2009 - criado por mga
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AssinanteDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'assinante';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdAssinante',
                                   'id_assinante');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                    'IdOrgao',
                                    'id_orgao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'CargoFuncao',
                                   'cargo_funcao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaOrgao', 'sigla', 'orgao');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoOrgao', 'descricao', 'orgao');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdUnidade');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelAssinanteUnidadeDTO');
                                   
    $this->configurarPK('IdAssinante',InfraDTO::$TIPO_PK_NATIVA);
    $this->configurarFK('IdOrgao', 'orgao', 'id_orgao');
  }
}
?>