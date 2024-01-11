<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 25/10/2011 - criado por mga
 *
 * Versão do Gerador de Código: 1.32.1
 *
 * Versão no CVS: $Id$
 */

require_once dirname(__FILE__) . '/../Sip.php';

class RelRegraAuditoriaRecursoDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return 'rel_regra_auditoria_recurso';
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdSistema', 'id_sistema');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdRecurso', 'id_recurso');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdRegraAuditoria', 'id_regra_auditoria');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeRecurso', 'nome', 'recurso');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoRegraAuditoria', 'descricao', 'regra_auditoria');

    $this->configurarPK('IdSistema', InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdRecurso', InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdRegraAuditoria', InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdSistema', 'recurso', 'id_sistema');
    $this->configurarFK('IdRecurso', 'recurso', 'id_recurso');
    $this->configurarFK('IdRegraAuditoria', 'regra_auditoria', 'id_regra_auditoria');
  }
}

?>