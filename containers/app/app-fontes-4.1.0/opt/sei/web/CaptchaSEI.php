<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 03/05/2021 - criado por MGA
 *
 */

require_once dirname(__FILE__).'/SEI.php';

class CaptchaSEI extends InfraCaptcha {

  private static $instance = null;

  public static function getInstance()
  {
    if (CaptchaSEI::$instance == null) {
      CaptchaSEI::$instance = new CaptchaSEI(BancoSEI::getInstance());
    }

    return CaptchaSEI::$instance;
  }

  public function isBolGravarAcessos(){
    return true;
  }

  public function setObjInfraIBanco($objInfraIBanco){
    BancoInfra::setObjInfraIBanco($objInfraIBanco);
  }

  public function configurarCaptcha($strIdentificacao){
    try {

      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
      $tipoCaptcha = $objInfraParametro->getValor('SEI_TIPO_CAPTCHA', false);

      if ($tipoCaptcha == null) {
        $tipoCaptcha = CaptchaSEI::$TIPO_INFRA_V2;
      }

      switch ($tipoCaptcha) {
        case CaptchaSEI::$TIPO_INFRA:
          $this->configurarInfra($strIdentificacao,true);
          break;

        case CaptchaSEI::$TIPO_INFRA_V2:
          $this->configurarInfraV2($strIdentificacao);
          break;

        case CaptchaSEI::$TIPO_HCAPTCHA:
          $this->configurarHCaptcha(
              $strIdentificacao,
              ConfiguracaoSEI::getInstance()->getValor('hCaptcha', 'ChaveSecreta'),
              ConfiguracaoSEI::getInstance()->getValor('hCaptcha', 'ChaveSite'));
          break;

        case CaptchaSEI::$TIPO_RECAPTCHA_V2:
          $this->configurarReCaptchaV2(
              $strIdentificacao,
              ConfiguracaoSEI::getInstance()->getValor('ReCaptchaV2', 'ChaveSecreta'),
              ConfiguracaoSEI::getInstance()->getValor('ReCaptchaV2', 'ChaveSite'));
          break;

        case CaptchaSEI::$TIPO_RECAPTCHA_V3:
          $this->configurarReCaptchaV3(
              $strIdentificacao,
              ConfiguracaoSEI::getInstance()->getValor('ReCaptchaV3', 'ChaveSecreta'),
              ConfiguracaoSEI::getInstance()->getValor('ReCaptchaV3', 'ChaveSite'),
              ConfiguracaoSEI::getInstance()->getValor('ReCaptchaV3', 'Score'),
              $_GET['acao']);
          break;

        default:
          throw new InfraException('Tipo de Captcha inválido.');
      }

    }catch(Exception $e){
      throw new InfraException('Erro configurando captcha.', $e);
    }
  }

}
?>