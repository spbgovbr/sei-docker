<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 08/04/2013 - criado por MGA
 *
 */

require_once dirname(__FILE__) . '/Sip.php';

class CacheSip extends InfraCache {

  private static $instance = null;

  public static function getInstance() {
    if (self::$instance == null) {
      self::$instance = new CacheSip();
    }
    return self::$instance;
  }

  public function getStrServidor() {
    return ConfiguracaoSip::getInstance()->getValor('CacheSip', 'Servidor');
  }

  public function getNumPorta() {
    return ConfiguracaoSip::getInstance()->getValor('CacheSip', 'Porta');
  }

  public function getNumTimeout() {
    return ConfiguracaoSip::getInstance()->getValor('CacheSip', 'Timeout', false, 1);
  }

  public function getNumTempo() {
    return ConfiguracaoSip::getInstance()->getValor('CacheSip', 'Tempo', false, 3600);
  }

  public function getObjInfraSessao() {
    return SessaoSip::getInstance();
  }

  public function getObjInfraLog() {
    return LogSip::getInstance();
  }
}

?>