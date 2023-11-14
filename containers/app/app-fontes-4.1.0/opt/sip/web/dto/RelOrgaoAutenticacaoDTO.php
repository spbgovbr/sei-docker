<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 12/06/2014 - criado por mga
 *
 * Versão do Gerador de Código: 1.33.1
 *
 * Versão no CVS: $Id$
 */

require_once dirname(__FILE__) . '/../Sip.php';

class RelOrgaoAutenticacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return 'rel_orgao_autenticacao';
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdOrgao', 'id_orgao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdServidorAutenticacao', 'id_servidor_autenticacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Sequencia', 'sequencia');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeServidorAutenticacao', 'nome', 'servidor_autenticacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'EnderecoServidorAutenticacao', 'endereco', 'servidor_autenticacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'StaTipoServidorAutenticacao', 'sta_tipo', 'servidor_autenticacao');

    $this->configurarPK('IdOrgao', InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdServidorAutenticacao', InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdServidorAutenticacao', 'servidor_autenticacao', 'id_servidor_autenticacao');
  }
}

?>