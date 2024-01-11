<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.12.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelGrupoContatoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'rel_grupo_contato';
  }

  public function montar() {

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdContato',
                                   'id_contato');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdGrupoContato',
                                   'id_grupo_contato');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaContato',
                                              'sigla',
                                              'contato');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                   'NomeContato',
                                   'nome',
                                   'contato');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                    'IdxContatoContato',
                                    'idx_contato',
                                    'contato');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'PalavrasPesquisa');

    $this->configurarPK('IdContato',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdGrupoContato',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdContato','contato','id_contato');
  }
}
?>