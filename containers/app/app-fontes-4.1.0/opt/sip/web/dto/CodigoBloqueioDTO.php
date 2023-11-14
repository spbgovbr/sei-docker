<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 14/10/2019 - criado por mga
 *
 * Versão do Gerador de Código: 1.42.0
 */

require_once dirname(__FILE__) . '/../Sip.php';

class CodigoBloqueioDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return 'codigo_bloqueio';
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdCodigoBloqueio', 'id_codigo_bloqueio');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdCodigoAcesso', 'id_codigo_acesso');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'ChaveBloqueio', 'chave_bloqueio');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Envio', 'dth_envio');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Bloqueio', 'dth_bloqueio');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->configurarPK('IdCodigoBloqueio', InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarExclusaoLogica('SinAtivo', 'N');
  }
}
