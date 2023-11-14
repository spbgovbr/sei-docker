<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 30/11/2015 - criado por mga
*
* Versão do Gerador de Código: 1.36.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class MapeamentoAssuntoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'mapeamento_assunto';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdAssuntoOrigem',
                                   'id_assunto_origem');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdAssuntoDestino',
                                   'id_assunto_destino');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdAssuntoAssuntoOrigem',
                                              'o.id_assunto',
                                              'assunto o');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdTabelaAssuntosAssuntoOrigem',
                                              'o.id_tabela_assuntos',
                                              'assunto o');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'CodigoEstruturadoAssuntoOrigem',
                                              'o.codigo_estruturado',
                                              'assunto o');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoAssuntoOrigem',
                                              'o.descricao',
                                              'assunto o');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SinAtivoAssuntoOrigem',
                                              'o.sin_ativo',
                                              'assunto o');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdTabelaAssuntosAssuntoDestino',
                                              'd.id_tabela_assuntos',
                                              'assunto d');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'CodigoEstruturadoAssuntoDestino',
                                             'd.codigo_estruturado',
                                             'assunto d');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoAssuntoDestino',
                                              'd.descricao',
                                              'assunto d');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SinAtivoAssuntoDestino',
                                              'd.sin_ativo',
                                              'assunto d');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'PalavrasPesquisa');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinAssuntosNaoMapeados');

    $this->configurarPK('IdAssuntoOrigem',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdAssuntoDestino',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdAssuntoOrigem', 'assunto o', 'o.id_assunto');
    $this->configurarFK('IdAssuntoDestino', 'assunto d', 'd.id_assunto');
  }
}
?>