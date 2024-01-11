<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 29/08/2018 - criado por MGA
 *
 */

require_once dirname(__FILE__) . '/Sip.php';

if (!ConfiguracaoSip::getInstance()->isSetValor('BancoAuditoriaSip', 'Tipo')) {
  die('Tipo do banco de dados de auditoria do SIP não configurado.');
}

switch (ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'Tipo')) {
  case 'MySql':
    class BancoAuditoriaSip extends InfraMySqli {
      private static $instance = null;

      public static function getInstance() {
        if (self::$instance == null) {
          self::$instance = new BancoAuditoriaSip();
        }
        return self::$instance;
      }

      public function getServidor() {
        return ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'Servidor');
      }

      public function getPorta() {
        return ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'Porta');
      }

      public function getBanco() {
        return ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'Banco');
      }

      public function getUsuario() {
        return ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'Usuario');
      }

      public function getSenha() {
        return ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'Senha');
      }

      public function isBolManterConexaoAberta() {
        return true;
      }

      public function isBolForcarPesquisaCaseInsensitive() {
        return !ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'PesquisaCaseInsensitive', false, false);
      }

      public function isBolConsultaRetornoAssociativo() {
        return true;
      }

      public function isBolUsarPreparedStatement() {
        return ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'PreparedStatement', false, true);
      }
    }

    break;

  case 'SqlServer':
    class BancoAuditoriaSip extends InfraSqlServer {
      private static $instance = null;

      public static function getInstance() {
        if (self::$instance == null) {
          self::$instance = new BancoAuditoriaSip();
        }
        return self::$instance;
      }

      public function getServidor() {
        return ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'Servidor');
      }

      public function getPorta() {
        return ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'Porta');
      }

      public function getBanco() {
        return ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'Banco');
      }

      public function getUsuario() {
        return ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'Usuario');
      }

      public function getSenha() {
        return ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'Senha');
      }

      public function isBolManterConexaoAberta() {
        return true;
      }

      public function isBolForcarPesquisaCaseInsensitive() {
        return !ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'PesquisaCaseInsensitive', false, false);
      }

      public function isBolConsultaRetornoAssociativo() {
        return true;
      }

      public function isBolUsarPreparedStatement() {
        return ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'PreparedStatement', false, true);
      }
    }

    break;

  case 'Oracle':
    class BancoAuditoriaSip extends InfraOracle {
      private static $instance = null;

      public static function getInstance() {
        if (self::$instance == null) {
          self::$instance = new BancoAuditoriaSip();
        }
        return self::$instance;
      }

      public function getServidor() {
        return ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'Servidor');
      }

      public function getPorta() {
        return ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'Porta');
      }

      public function getBanco() {
        return ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'Banco');
      }

      public function getUsuario() {
        return ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'Usuario');
      }

      public function getSenha() {
        return ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'Senha');
      }

      public function isBolManterConexaoAberta() {
        return true;
      }

      public function isBolForcarPesquisaCaseInsensitive() {
        return !ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'PesquisaCaseInsensitive', false, false);
      }

      public function isBolUsarPreparedStatement() {
        return ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'PreparedStatement', false, true);
      }
    }

    break;

  case 'PostgreSql':
    class BancoAuditoriaSip extends InfraPostgreSql {
      private static $instance = null;

      public static function getInstance() {
        if (self::$instance == null) {
          self::$instance = new BancoAuditoriaSip();
        }
        return self::$instance;
      }

      public static function setBanco($objInfraIBanco) {
        self::$instance = $objInfraIBanco;
      }

      public function getServidor() {
        return ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'Servidor');
      }

      public function getPorta() {
        return ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'Porta');
      }

      public function getBanco() {
        return ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'Banco');
      }

      public function getUsuario() {
        return ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'Usuario');
      }

      public function getSenha() {
        return ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'Senha');
      }

      public function isBolManterConexaoAberta() {
        return true;
      }

      public function isBolForcarPesquisaCaseInsensitive() {
        return !ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'PesquisaCaseInsensitive', false, false);
      }

      public function isBolUsarPreparedStatement() {
        return ConfiguracaoSip::getInstance()->getValor('BancoAuditoriaSip', 'PreparedStatement', false, true);
      }
    }

    break;

  default:
    die('Configuração do tipo de banco de dados de auditoria do SIP inválida.');
}
?>