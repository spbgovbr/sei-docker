<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 17/10/2019 - criado por MGA
 *
 */

require_once dirname(__FILE__) . '/Sip.php';

class MailSip {

  private static $instance = null;
  private $arrObjEmailDTO = null;

  public static function getInstance() {
    if (self::$instance == null) {
      self::$instance = new MailSip();
    }
    return self::$instance;
  }

  private function __construct() {
    $this->limpar();
  }

  public function adicionar(EmailDTO $objEmailDTO) {
    $this->arrObjEmailDTO[] = $objEmailDTO;
  }

  public function limpar() {
    $this->arrObjEmailDTO = array();
  }

  public function enviar() {
    foreach ($this->arrObjEmailDTO as $objEmailDTO) {
      try {
        InfraMail::enviarConfigurado(ConfiguracaoSip::getInstance(), $objEmailDTO->getStrDe(), $objEmailDTO->getStrPara(), null, null, $objEmailDTO->getStrAssunto(), nl2br($objEmailDTO->getStrMensagem()), 'text/html');
      } catch (Exception $e) {
        LogSip::getInstance()->gravar('Erro enviando e-mail.' . "\n\n" . InfraException::inspecionar($e));
      }
    }
    $this->limpar();
  }
}
