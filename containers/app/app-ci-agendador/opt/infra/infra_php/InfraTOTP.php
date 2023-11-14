<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4Є REGIГO
 *
 * 28/12/2017 - criado por MGA
 *
 * @package infra_php
 */

class InfraTOTP {

  /**
   * Gera o cуdigo QRCode para autenticaзгo em 2 fatores
   * @param string $strDirTemp Diretуrio temporбrio para geraзгo do arquivo PNG do QRCode
   * @param string $strEmissor Instituiзгo e/ou sistema
   * @param string $strIdentificacao sigla e/ou nome de usuбrio
   * @param string $strSegredo chave secreta codificada em Base32 (RFC3548)
   * @param int $numDigitos nъmero de dнgitos (6 ou 8)
   * @param int $numIntervalo tamanho da janela de tempo (ignorado pelo Google Authenticator que usa o valor 30)
   * @return imagem do QRCode codificada em Base64
   */
    public static function gerar($strDirTemp, $strEmissor, $strIdentificacao, $strSegredo, $numDigitos = 6, $numIntervalo = 30){

    try {

      if ($numDigitos != 6 && $numDigitos != 8) {
        throw new InfraException('Nъmero de dнgitos invбlido para autenticaзгo de 2 fatores.');
      }

      $base32 = new Tuupola\Base32();

      //public static function create(?string $secret = null, int $period = 30, string $digest = 'sha1', int $digits = 6, int $epoch = 0)
      $objTotp = OTPHP\TOTP::create(str_replace('=', '', $base32->encode($strSegredo)), $numIntervalo, 'sha1', $numDigitos);

      $objTotp->setIssuer($strEmissor);
      $objTotp->setLabel($strIdentificacao);
      $objTotp->setIssuerIncludedAsParameter(true);

      if (InfraDebug::isBolProcessar()) {
        InfraDebug::getInstance()->gravarInfra('[InfraTOPT->gerar] '.$objTotp->getProvisioningUri());
      }

      $strArquivo = $strDirTemp.'/'.md5(time().$strEmissor.mt_rand().$strIdentificacao.mt_rand().uniqid(mt_rand(), true));

      InfraQRCode::gerar($objTotp->getProvisioningUri(), $strArquivo, 'L');

      if (($binQrCode = file_get_contents($strArquivo)) === false) {
        throw new InfraException('Erro lendo arquivo QRCode para autenticaзгo de 2 fatores.');
      }

      unlink($strArquivo);

      return base64_encode($binQrCode);

    }catch(Exception $e){
      throw new InfraException('Erro gerando cуdigo para autenticaзгo de 2 fatores.', $e);
    }
  }

  /**
   * Valida chave de 2 fatores
   * @param string $strSegredo chave secreta utilizada na geraзгo do QRCode codificada em Base32 (RFC3548)
   * @param string $strChave valor informado pelo App
   * @param int $numJanelas nъmero de janelas vбlidas (0..n)
   * @param int $numDigitos nъmero de dнgitos (6 ou 8)
   * @param int $numIntervalo tamanho da janela de tempo (ignorado pelo Google Authenticator que usa o valor 30)
   * @param long $numTimestamp tempo de referкncia para validaзгo (tempo atual se nгo informado)
   * @return true/false
   */
  public static function verificar($strSegredo, $strChave, $numJanelas = 3, $numDigitos = 6, $numIntervalo = 30, $numTimestamp=null){
    try {

      if ($numDigitos != 6 && $numDigitos != 8) {
        throw new InfraException('Nъmero de dнgitos invбlido para autenticaзгo de 2 fatores.');
      }

      $base32 = new Tuupola\Base32();

      ////public static function create(?string $secret = null, int $period = 30, string $digest = 'sha1', int $digits = 6, int $epoch = 0)
      $objTotp = OTPHP\TOTP::create(str_replace('=', '', $base32->encode($strSegredo)), $numIntervalo, 'sha1', $numDigitos);

      //public function verify(string $otp, ?int $timestamp = null, ?int $window = null)
      if ($objTotp->verify($strChave, $numTimestamp, $numJanelas)) {
        return true;
      }

    } catch (Exception $e) {
      throw new InfraException('Erro verificando chave na autenticaзгo de 2 fatores.', $e);
    }
    return false;
  }
}
?>