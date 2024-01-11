<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 03/02/2015 - criado por LGI_SJPR
 *
 * @package infra_php
 */

class InfraBcrypt {

  private $rounds;
  private $randomState;

  public function __construct($rounds = 12) {
    if(CRYPT_BLOWFISH != 1) {
      throw new InfraException("O bcrypt não é suportado nesta instalação. Veja http://php.net/crypt");
    }

    $this->rounds = $rounds;
  }

  /**
   * Criptografar uma string
   *
   * @param  string  $entrada A string/senha a ser criptografada
   * @return string
   */
  public function hash($entrada) {
    $hash = crypt($entrada, $this->getSalt());

    if(strlen($hash) > 13)
      return $hash;

    return false;
  }


  /**
   * Validar uma string criptografada, compara a senha que está sendo fornecida pelo usuário (normal), com a senha que está armazenada no banco (criptografada)
   *
   * @param  string $entrada A string/senha sem criptografia
   * @param  string $hashExistente   O hash da string/senha que está armazenado no banco
   *
   * @return boolean
   */
  public function verificar($entrada, $hashExistente) {
    $hash = crypt($entrada, $hashExistente);

    return $hash === $hashExistente;
  }

  private function getSalt() {
    $salt = sprintf('$2a$%02d$', $this->rounds);

    $bytes = $this->getBytesAleatorios(16);

    $salt .= $this->codificarBytes($bytes);

    return $salt;
  }



  private function getBytesAleatorios($cont) {
    $bytes = '';

    if(function_exists('openssl_random_pseudo_bytes') &&
        (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')) { // OpenSSL é lento no Windows
      $bytes = openssl_random_pseudo_bytes($cont);
    }

    if($bytes === '' && is_readable('/dev/urandom') &&
        ($hRand = @fopen('/dev/urandom', 'rb')) !== FALSE) {
      $bytes = fread($hRand, $cont);
      fclose($hRand);
    }

    if(strlen($bytes) < $cont) {
      $bytes = '';

      if($this->randomState === null) {
        $this->randomState = microtime();
        if(function_exists('getmypid')) {
          $this->randomState .= getmypid();
        }
      }

      for($i = 0; $i < $cont; $i += 16) {
        $this->randomState = md5(microtime() . $this->randomState);

        if (PHP_VERSION >= '5') {
          $bytes .= md5($this->randomState, true);
        } else {
          $bytes .= pack('H*', md5($this->randomState));
        }
      }

      $bytes = substr($bytes, 0, $cont);
    }

    return $bytes;
  }

  private function codificarBytes($entrada) {
    //O código abaixo é do Framework PHP Password Hashing
    $itoa64 = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    $saida = '';
    $i = 0;
    do {
      $c1 = ord($entrada[$i++]);
      $saida .= $itoa64[$c1 >> 2];
      $c1 = ($c1 & 0x03) << 4;
      if ($i >= 16) {
        $saida .= $itoa64[$c1];
        break;
      }

      $c2 = ord($entrada[$i++]);
      $c1 |= $c2 >> 4;
      $saida .= $itoa64[$c1];
      $c1 = ($c2 & 0x0f) << 2;

      $c2 = ord($entrada[$i++]);
      $c1 |= $c2 >> 6;
      $saida .= $itoa64[$c1];
      $saida .= $itoa64[$c2 & 0x3f];
    } while (1);

    return $saida;
  }
}
?>