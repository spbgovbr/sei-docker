<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 12/11/2007 - criado por MGA
 *
 */

require_once dirname(__FILE__).'/SEI.php';

  if (!ConfiguracaoSEI::getInstance()->isSetValor('BancoReplicaSEI','Tipo')){
    die('Tipo do banco de dados do SEI réplica não configurado.');
  }

  switch(ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI','Tipo')){
    case 'MySql':
      class BancoReplicaSEI extends InfraMySqli {
        private static $instance = null;

        public static function getInstance() {
          if (self::$instance == null) {
            self::$instance = new BancoReplicaSEI();
          }
          return self::$instance;
        }

        public function getServidor() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI','Servidor');
        }

        public function getPorta() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI','Porta');
        }

        public function getBanco() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI','Banco');
        }

        public function getUsuario(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI', 'Usuario');
        }

        public function getSenha(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI', 'Senha');
        }

        public function isBolManterConexaoAberta(){
          return true;
        }

        public function isBolForcarPesquisaCaseInsensitive(){
          return !ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI', 'PesquisaCaseInsensitive', false, false);
        }

        public function isBolConsultaRetornoAssociativo(){
          return true;
        }

        public function isBolUsarPreparedStatement(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI', 'PreparedStatement', false, true);
        }
      }
      break;

    case 'SqlServer':
      class BancoReplicaSEI extends InfraSqlServer {
        private static $instance = null;

        public static function getInstance() {
          if (self::$instance == null) {
            self::$instance = new BancoReplicaSEI();
          }
          return self::$instance;
        }

        public function getServidor() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI','Servidor');
        }

        public function getPorta() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI','Porta');
        }

        public function getBanco() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI','Banco');
        }

        public function getUsuario(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI', 'Usuario');
        }

        public function getSenha(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI', 'Senha');
        }

        public function isBolManterConexaoAberta(){
          return true;
        }

        public function isBolForcarPesquisaCaseInsensitive(){
          return !ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI', 'PesquisaCaseInsensitive', false, false);
        }

        public function isBolConsultaRetornoAssociativo(){
          return true;
        }

        public function isBolUsarPreparedStatement(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI', 'PreparedStatement', false, true);
        }
      }
      break;

    case 'Oracle':
      class BancoReplicaSEI extends InfraOracle {
        private static $instance = null;

        public static function getInstance() {
          if (self::$instance == null) {
            self::$instance = new BancoReplicaSEI();
          }
          return self::$instance;
        }

        public function getServidor() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI','Servidor');
        }

        public function getPorta() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI','Porta');
        }

        public function getBanco() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI','Banco');
        }

        public function getUsuario(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI', 'Usuario');
        }

        public function getSenha(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI', 'Senha');
        }

        public function isBolManterConexaoAberta(){
          return true;
        }

        public function isBolForcarPesquisaCaseInsensitive(){
          return !ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI', 'PesquisaCaseInsensitive', false, false);
        }

        public function isBolUsarPreparedStatement(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI', 'PreparedStatement', false, true);
        }
      }
      break;

    case 'PostgreSql':
      class BancoReplicaSEI extends InfraPostgreSql {
        private static $instance = null;

        public static function getInstance() {
          if (self::$instance == null) {
            self::$instance = new BancoReplicaSEI();
          }
          return self::$instance;
        }

        public function getServidor() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI','Servidor');
        }

        public function getPorta() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI','Porta');
        }

        public function getBanco() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI','Banco');
        }

        public function getUsuario(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI', 'Usuario');
        }

        public function getSenha(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI', 'Senha');
        }

        public function isBolManterConexaoAberta(){
          return true;
        }

        public function isBolForcarPesquisaCaseInsensitive(){
          return !ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI', 'PesquisaCaseInsensitive', false, false);
        }

        public function isBolUsarPreparedStatement(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoReplicaSEI', 'PreparedStatement', false, true);
        }
      }
      break;

    default:
      die('Configuração do tipo de banco de dados do SEI réplica inválida.');
  }
?>