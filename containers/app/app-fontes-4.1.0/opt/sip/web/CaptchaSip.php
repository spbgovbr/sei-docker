<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 14/06/2006 - criado por MGA
 *
 */

require_once dirname(__FILE__) . '/Sip.php';

class CaptchaSip extends InfraCaptcha {

  private static $instance = null;

  public static function getInstance() {
    if (CaptchaSip::$instance == null) {
      CaptchaSip::$instance = new CaptchaSip(BancoSip::getInstance());
    }
    return CaptchaSip::$instance;
  }

  public function isBolGravarAcessos() {
    return true;
  }

  public function configurarCaptcha($strIdentificacao) {
    try {
      $objInfraParametro = new InfraParametro(BancoSip::getInstance());
      $tipoCaptcha = $objInfraParametro->getValor('SIP_TIPO_CAPTCHA', false);

      if ($tipoCaptcha == null) {
        $tipoCaptcha = CaptchaSip::$TIPO_INFRA;
      }

      switch ($tipoCaptcha) {
        case CaptchaSip::$TIPO_INFRA:
          $this->configurarInfra($strIdentificacao);
          break;

        case CaptchaSip::$TIPO_INFRA_V2:
          $this->configurarInfraV2($strIdentificacao);
          break;

        case CaptchaSip::$TIPO_HCAPTCHA:
          $this->configurarHCaptcha($strIdentificacao, ConfiguracaoSip::getInstance()->getValor('hCaptcha', 'ChaveSecreta'), ConfiguracaoSip::getInstance()->getValor('hCaptcha', 'ChaveSite'));
          break;

        case CaptchaSip::$TIPO_RECAPTCHA_V2:
          $this->configurarReCaptchaV2($strIdentificacao, ConfiguracaoSip::getInstance()->getValor('ReCaptchaV2', 'ChaveSecreta'), ConfiguracaoSip::getInstance()->getValor('ReCaptchaV2', 'ChaveSite'));
          break;

        case CaptchaSip::$TIPO_RECAPTCHA_V3:
          $this->configurarReCaptchaV3($strIdentificacao, ConfiguracaoSip::getInstance()->getValor('ReCaptchaV3', 'ChaveSecreta'), ConfiguracaoSip::getInstance()->getValor('ReCaptchaV3', 'ChaveSite'),
            ConfiguracaoSip::getInstance()->getValor('ReCaptchaV3', 'Score'), $_GET['acao']);
          break;

        default:
          throw new InfraException('Tipo de Captcha inválido.');
      }
    } catch (Exception $e) {
      throw new InfraException('Erro configurando captcha.', $e);
    }
  }
}

?>