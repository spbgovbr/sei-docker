<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 12/11/2007 - criado por MGA
 *
 */

require_once dirname(__FILE__).'/SEI.php';

  if (!ConfiguracaoSEI::getInstance()->isSetValor('BancoSEI','Tipo')){
    die('Tipo do banco de dados do SEI não configurado.');
  }

  switch(ConfiguracaoSEI::getInstance()->getValor('BancoSEI','Tipo')){
    case 'MySql':
      class BancoSEI extends InfraMySqli {
        private static $instance = null;
        private static $bolScript = false;
        private $strUsuario = null;
        private $strSenha = null;

        public static function getInstance() {
          if (self::$instance == null) {
            self::$instance = new BancoSEI();
          }
          return self::$instance;
        }

        public function setBolScript($bolScript){
          self::$bolScript = $bolScript;
        }

        public function getServidor() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoSEI','Servidor');
        }

        public function getPorta() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoSEI','Porta');
        }

        public function getBanco() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoSEI','Banco');
        }

        public function getUsuario(){
          if ($this->strUsuario != null) {
            return $this->strUsuario;
          }else{
            return ConfiguracaoSEI::getInstance()->getValor('BancoSEI', 'Usuario');
          }
        }

        public function getSenha(){
          if ($this->strSenha != null) {
            return $this->strSenha;
          }else{
            return ConfiguracaoSEI::getInstance()->getValor('BancoSEI', 'Senha');
          }
        }
        public function setUsuario(string $strUsuario){
          $this->strUsuario = $strUsuario;
        }

        public function setSenha(string $strSenha){
          $this->strSenha = $strSenha;
        }

        public function isBolManterConexaoAberta(){
          return true;
        }

        public function isBolForcarPesquisaCaseInsensitive(){
          return !ConfiguracaoSEI::getInstance()->getValor('BancoSEI', 'PesquisaCaseInsensitive', false, false);
        }

        public function isBolConsultaRetornoAssociativo(){
          return true;
        }

        public function isBolUsarPreparedStatement(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoSEI', 'PreparedStatement', false, true);
        }
      }
      break;

    case 'SqlServer':
      class BancoSEI extends InfraSqlServer {
        private static $instance = null;
        private static $bolScript = false;
        private $strUsuario = null;
        private $strSenha = null;

        public static function getInstance() {
          if (self::$instance == null) {
            self::$instance = new BancoSEI();
          }
          return self::$instance;
        }

        public function setBolScript($bolScript){
          self::$bolScript = $bolScript;
        }

        public function getServidor() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoSEI','Servidor');
        }

        public function getPorta() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoSEI','Porta');
        }

        public function getBanco() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoSEI','Banco');
        }

        public function getUsuario(){
          if ($this->strUsuario != null) {
            return $this->strUsuario;
          }else{
            return ConfiguracaoSEI::getInstance()->getValor('BancoSEI', 'Usuario');
          }
        }

        public function getSenha(){
          if ($this->strSenha != null) {
            return $this->strSenha;
          }else{
            return ConfiguracaoSEI::getInstance()->getValor('BancoSEI', 'Senha');
          }
        }
        public function setUsuario(string $strUsuario){
          $this->strUsuario = $strUsuario;
        }

        public function setSenha(string $strSenha){
          $this->strSenha = $strSenha;
        }

        public function isBolManterConexaoAberta(){
          return true;
        }

        public function isBolForcarPesquisaCaseInsensitive(){
          return !ConfiguracaoSEI::getInstance()->getValor('BancoSEI', 'PesquisaCaseInsensitive', false, false);
        }

        public function isBolConsultaRetornoAssociativo(){
          return true;
        }

        public function isBolUsarPreparedStatement(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoSEI', 'PreparedStatement', false, true);
        }
      }
      break;

    case 'Oracle':
      class BancoSEI extends InfraOracle {
        private static $instance = null;
        private static $bolScript = false;
        private $strUsuario = null;
        private $strSenha = null;

        public static function getInstance() {
          if (self::$instance == null) {
            self::$instance = new BancoSEI();
          }
          return self::$instance;
        }

        public function setBolScript($bolScript){
          self::$bolScript = $bolScript;
        }

        public function getServidor() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoSEI','Servidor');
        }

        public function getPorta() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoSEI','Porta');
        }

        public function getBanco() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoSEI','Banco');
        }

        public function getUsuario(){
          if ($this->strUsuario != null) {
            return $this->strUsuario;
          }else{
            return ConfiguracaoSEI::getInstance()->getValor('BancoSEI', 'Usuario');
          }
        }

        public function getSenha(){
          if ($this->strSenha != null) {
            return $this->strSenha;
          }else{
            return ConfiguracaoSEI::getInstance()->getValor('BancoSEI', 'Senha');
          }
        }
        public function setUsuario(string $strUsuario){
          $this->strUsuario = $strUsuario;
        }

        public function setSenha(string $strSenha){
          $this->strSenha = $strSenha;
        }

        public function isBolManterConexaoAberta(){
          return true;
        }

        public function isBolForcarPesquisaCaseInsensitive(){
          return !ConfiguracaoSEI::getInstance()->getValor('BancoSEI', 'PesquisaCaseInsensitive', false, false);
        }

        public function isBolUsarPreparedStatement(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoSEI', 'PreparedStatement', false, true);
        }
      }
      break;

    case 'PostgreSql':
      class BancoSEI extends InfraPostgreSql {
        private static $instance = null;
        private static $bolScript = false;
        private $strUsuario = null;
        private $strSenha = null;

        public static function getInstance() {
          if (self::$instance == null) {
            self::$instance = new BancoSEI();
          }
          return self::$instance;
        }

        public function setBolScript($bolScript){
          self::$bolScript = $bolScript;
        }

        public function getServidor() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoSEI','Servidor');
        }

        public function getPorta() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoSEI','Porta');
        }

        public function getBanco() {
          return ConfiguracaoSEI::getInstance()->getValor('BancoSEI','Banco');
        }

        public function getUsuario(){
          if ($this->strUsuario != null) {
            return $this->strUsuario;
          }else{
            return ConfiguracaoSEI::getInstance()->getValor('BancoSEI', 'Usuario');
          }
        }

        public function getSenha(){
          if ($this->strSenha != null) {
            return $this->strSenha;
          }else{
            return ConfiguracaoSEI::getInstance()->getValor('BancoSEI', 'Senha');
          }
        }
        public function setUsuario(string $strUsuario){
          $this->strUsuario = $strUsuario;
        }

        public function setSenha(string $strSenha){
          $this->strSenha = $strSenha;
        }

        public function isBolManterConexaoAberta(){
          return true;
        }

        public function isBolForcarPesquisaCaseInsensitive(){
          return !ConfiguracaoSEI::getInstance()->getValor('BancoSEI', 'PesquisaCaseInsensitive', false, false);
        }

        public function isBolUsarPreparedStatement(){
          return ConfiguracaoSEI::getInstance()->getValor('BancoSEI', 'PreparedStatement', false, true);
        }
      }
      break;

    default:
      die('Configuração do tipo de banco de dados do SEI inválida.');
  }
?>