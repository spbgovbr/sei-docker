<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/11/2015 - criado por mga
*
* Versão do Gerador de Código: 1.36.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AssuntoProxyDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'assunto_proxy';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdAssuntoProxy',
                                   'id_assunto_proxy');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdAssunto',
                                   'id_assunto');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                   'IdTabelaAssuntosAssunto',
                                   'id_tabela_assuntos',
                                   'assunto');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                   'CodigoEstruturadoAssunto',
                                   'codigo_estruturado',
                                   'assunto');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                   'DescricaoAssunto',
                                   'descricao',
                                   'assunto');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                    'IdxAssuntoAssunto',
                                    'idx_assunto',
                                    'assunto');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                   'SinAtivoAssunto',
                                   'sin_ativo',
                                   'assunto');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'PalavrasPesquisa');

    $this->configurarPK('IdAssuntoProxy',InfraDTO::$TIPO_PK_NATIVA);
    $this->configurarFK('IdAssunto', 'assunto', 'id_assunto');
  }
}
?>