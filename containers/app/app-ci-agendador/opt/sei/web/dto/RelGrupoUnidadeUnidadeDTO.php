<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/09/2014 - criado por bcu
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelGrupoUnidadeUnidadeDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'rel_grupo_unidade_unidade';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidade',
                                   'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdGrupoUnidade',
                                   'id_grupo_unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeGrupoUnidade',
                                              'nome',
                                              'grupo_unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'IdxUnidadeUnidade',
                                              'idx_unidade',
                                              'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdOrgaoUnidade','id_orgao','unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'SiglaUnidade','sigla','unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'DescricaoUnidade','descricao','unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'SinEnvioProcessoUnidade','sin_envio_processo','unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'SinEnvioProcessoOrgao','sin_envio_processo','orgao');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'PalavrasPesquisa');

    $this->configurarPK('IdUnidade',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdGrupoUnidade',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdGrupoUnidade', 'grupo_unidade', 'id_grupo_unidade');
    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade');
    $this->configurarFK('IdOrgaoUnidade', 'orgao', 'id_orgao');
  }
}
?>