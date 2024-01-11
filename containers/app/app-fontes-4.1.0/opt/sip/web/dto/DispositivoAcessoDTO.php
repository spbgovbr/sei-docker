<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 13/09/2019 - criado por mga
 *
 * Versão do Gerador de Código: 1.42.0
 */

require_once dirname(__FILE__) . '/../Sip.php';

class DispositivoAcessoDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return 'dispositivo_acesso';
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdDispositivoAcesso', 'id_dispositivo_acesso');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdCodigoAcesso', 'id_codigo_acesso');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Liberacao', 'dth_liberacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'ChaveDispositivo', 'chave_dispositivo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'ChaveAcesso', 'chave_acesso');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'UserAgent', 'user_agent');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Acesso', 'dth_acesso');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IpAcesso', 'ip_acesso');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdUsuarioCodigoAcesso', 'id_usuario', 'codigo_acesso');

    $this->configurarPK('IdDispositivoAcesso', InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarExclusaoLogica('SinAtivo', 'N');

    $this->configurarFK('IdCodigoAcesso', 'codigo_acesso', 'id_codigo_acesso');
  }
}
