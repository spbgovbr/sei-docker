<?
  /**
  * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
  * 03/07/2019 - criado por cle@trf4.jus.br
  * Versão do Gerador de Código: 1.42.0
  */

  require_once dirname(__FILE__).'/../../Infra.php';

  class InfraSessaoRestDTO extends InfraDTO {

    public function getStrNomeTabela() {
       return 'infra_sessao_rest';
    }

    public function montar() {

      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdInfraSessaoRest', 'id_infra_sessao_rest');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SiglaUsuario', 'sigla_usuario');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdOrgao', 'id_orgao');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SiglaOrgao', 'sigla_orgao');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Login', 'dth_login');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Acesso', 'dth_acesso');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Logout', 'dth_logout');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'UserAgent', 'user_agent');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'HttpClientIp', 'http_client_ip');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'HttpXForwardedFor', 'http_x_forwarded_for');
      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'RemoteAddr', 'remote_addr');

      $this->configurarPK('IdInfraSessaoRest',InfraDTO::$TIPO_PK_INFORMADO);

    }
  }
