<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 28/08/2018 - criado por MGA
 *
 */

require_once dirname(__FILE__).'/SEI.php';

  if (!ConfiguracaoSEI::getInstance()->isSetValor('BancoAuditoriaSEI','Tipo')){
    die('Tipo do banco de dados de auditoria do SEI não configurado.');
  }

  switch(ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI','Tipo')){
    case 'MySql':
      class BancoAuditoriaSEI extends InfraMySqli {
        private static $instance = null;

        public static function getInstance() {
          if (self::$instance == null) {
            self::$instance = new BancoAuditoriaSEI();
          }
          return self::$instance;
        }

        public function getServidor() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI','Servidor');
        }

        public function getPorta() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI','Porta');
        }

        public function getBanco() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI','Banco');
        }

        public function getUsuario(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI','Usuario');
        }

        public function getSenha(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI','Senha');
        }

        public function isBolManterConexaoAberta(){
          return true;
        }

        public function isBolForcarPesquisaCaseInsensitive(){
          return !ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI', 'PesquisaCaseInsensitive', false, false);
        }

        public function isBolConsultaRetornoAssociativo(){
          return true;
        }

        public function isBolUsarPreparedStatement(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI', 'PreparedStatement', false, true);
        }
      }
      break;

    case 'SqlServer':
      class BancoAuditoriaSEI extends InfraSqlServer {
        private static $instance = null;

        public static function getInstance() {
          if (self::$instance == null) {
            self::$instance = new BancoAuditoriaSEI();
          }
          return self::$instance;
        }

        public function getServidor() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI','Servidor');
        }

        public function getPorta() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI','Porta');
        }

        public function getBanco() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI','Banco');
        }

        public function getUsuario(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI','Usuario');
        }

        public function getSenha(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI','Senha');
        }

        public function isBolManterConexaoAberta(){
          return true;
        }

        public function isBolForcarPesquisaCaseInsensitive(){
          return !ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI', 'PesquisaCaseInsensitive', false, false);
        }

        public function isBolConsultaRetornoAssociativo(){
          return true;
        }

        public function isBolUsarPreparedStatement(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI', 'PreparedStatement', false, true);
        }
      }
      break;

    case 'Oracle':
      class BancoAuditoriaSEI extends InfraOracle {
        private static $instance = null;

        public static function getInstance() {
          if (self::$instance == null) {
            self::$instance = new BancoAuditoriaSEI();
          }
          return self::$instance;
        }

        public function getServidor() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI','Servidor');
        }

        public function getPorta() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI','Porta');
        }

        public function getBanco() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI','Banco');
        }

        public function getUsuario(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI','Usuario');
        }

        public function getSenha(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI','Senha');
        }

        public function isBolManterConexaoAberta(){
          return true;
        }

        public function isBolForcarPesquisaCaseInsensitive(){
          return !ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI', 'PesquisaCaseInsensitive', false, false);
        }

        public function isBolUsarPreparedStatement(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI', 'PreparedStatement', false, true);
        }
      }
      break;

    case 'PostgreSql':
      class BancoAuditoriaSEI extends InfraPostgreSql {
        private static $instance = null;

        public static function getInstance() {
          if (self::$instance == null) {
            self::$instance = new BancoAuditoriaSEI();
          }
          return self::$instance;
        }

        public function getServidor() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI','Servidor');
        }

        public function getPorta() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI','Porta');
        }

        public function getBanco() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI','Banco');
        }

        public function getUsuario(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI', 'Usuario');
        }

        public function getSenha(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI', 'Senha');
        }

        public function isBolManterConexaoAberta(){
          return true;
        }

        public function isBolForcarPesquisaCaseInsensitive(){
          return !ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI', 'PesquisaCaseInsensitive', false, false);
        }

        public function isBolUsarPreparedStatement(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoAuditoriaSEI', 'PreparedStatement', false, true);
        }
      }
      break;

    default:
      die('Configuração do tipo de banco de dados de auditoria do SEI inválida.');
  }
?>