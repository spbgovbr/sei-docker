<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 11/07/2018 - criado por mga
 *
 * Versão do Gerador de Código: 1.41.0
 */

require_once dirname(__FILE__) . '/../Sip.php';

class UsuarioHistoricoDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return 'usuario_historico';
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuarioHistorico', 'id_usuario_historico');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdCodigoAcesso', 'id_codigo_acesso');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuarioOperacao', 'id_usuario_operacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Operacao', 'dth_operacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Pausa2fa', 'dth_pausa_2fa');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaOperacao', 'sta_operacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Motivo', 'motivo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdOrgaoUsuarioOperacao', 'usu_oper.id_orgao', 'usuario usu_oper');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUsuarioOperacao', 'usu_oper.sigla', 'usuario usu_oper');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeUsuarioOperacao', 'usu_oper.nome', 'usuario usu_oper');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaOrgaoUsuarioOperacao', 'org_oper.sigla', 'orgao org_oper');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoOrgaoUsuarioOperacao', 'org_oper.descricao', 'orgao org_oper');

    $this->configurarPK('IdUsuarioHistorico', InfraDTO::$TIPO_PK_SEQUENCIAL);

    $this->configurarFK('IdUsuarioOperacao', 'usuario usu_oper', 'usu_oper.id_usuario');
    $this->configurarFK('IdOrgaoUsuarioOperacao', 'orgao org_oper', 'org_oper.id_orgao');
  }
}
