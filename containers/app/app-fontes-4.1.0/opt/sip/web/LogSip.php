<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 14/06/2006 - criado por MGA
 *
 */

require_once dirname(__FILE__) . '/Sip.php';

class LogSip extends InfraLog {

  private static $instance = null;

  public static function getInstance() {
    if (LogSip::$instance == null) {
      LogSip::$instance = new LogSip(BancoSip::getInstance());
    }
    return LogSip::$instance;
  }

  public function getNumTipoPK() {
    return InfraDTO::$TIPO_PK_NATIVA;
  }

  public function isBolTratarTipos() {
    return true;
  }
}

?>