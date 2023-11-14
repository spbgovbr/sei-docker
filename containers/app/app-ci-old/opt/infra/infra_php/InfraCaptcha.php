<?
class InfraCaptcha {

  private function __construct(){
    
  }

  public static function obterCodigo(){
    $arrRand = array(
        array(48,57) //números
        ,array(97,122) //letras maiúsculas
        ,array(65,90) //letras minúsculas
    );

    $strCodeToRandom = '';
    $r = rand(0,2);
    $strCodeToRandom .= rand($arrRand[$r][0],$arrRand[$r][1]);

    $strCodeToRandom .= '-';

    $r = rand(0,2);
    $strCodeToRandom .= rand($arrRand[$r][0],$arrRand[$r][1]);

    return $strCodeToRandom;
  }
  
  public static function gerar($strCodigo){
    $strCaptcha = null;

    if (trim($strCodigo)!='') {

      $MENOR_COD_CAPTCHA = 48;
      $MAIOR_COD_CAPTCHA = 122;
      $arrCodNaoExistentes = array(58, 59, 60, 61, 62, 63, 64, 91, 92, 93, 94, 95, 96);
      $arrCodigoParaGeracaoCaptcha = explode('-', $strCodigo);

      $strCaptcha = chr($arrCodigoParaGeracaoCaptcha[0]).chr($arrCodigoParaGeracaoCaptcha[1]);

      sort($arrCodigoParaGeracaoCaptcha);

      $media = round(($arrCodigoParaGeracaoCaptcha[1] - $arrCodigoParaGeracaoCaptcha[0]) / 2);

      if (in_array($arrCodigoParaGeracaoCaptcha[0] + $media, $arrCodNaoExistentes) || $arrCodigoParaGeracaoCaptcha[0] + $media > $MAIOR_COD_CAPTCHA) {
        $strCaptcha .= chr($arrCodigoParaGeracaoCaptcha[0]);
      } else {
        $strCaptcha .= chr($arrCodigoParaGeracaoCaptcha[0] + $media);
      }

      if (in_array($arrCodigoParaGeracaoCaptcha[1] - $media, $arrCodNaoExistentes) || $arrCodigoParaGeracaoCaptcha[1] - $media < $MENOR_COD_CAPTCHA) {
        $strCaptcha .= chr($arrCodigoParaGeracaoCaptcha[1]);
      } else {
        $strCaptcha .= chr($arrCodigoParaGeracaoCaptcha[1] - $media);
      }
    }
    return $strCaptcha;
  }

  public static function montarLabel($strCodigo, $strIdLabel = 'lblCaptcha'){
    return '<label id="'.$strIdLabel.'" class="infraLabelObrigatorio"><img src="/infra_js/infra_gerar_captcha.php?codetorandom='.$strCodigo.'" alt="Não foi possível carregar imagem de confirmação" /></label>';
  }

  public static function montarAudio($strCodigo, $strIdAudioObject = 'audioCaptchaMedia', $strIdSrcAudio = 'srcAudioCaptcha', $strIdImgAudio = 'imgAudioCaptcha', $strImgAudio = '/infra_css/imagens/audio.gif'){
    return '<audio id="'.$strIdAudioObject.'"><source id="'.$strIdSrcAudio.'" src="/infra_js/infra_gerar_audio_captcha.php?codetorandom='.$strCodigo.'"></audio><img id='.$strIdImgAudio.' alt="Ouvir a narração das letras" title="Ouvir a narração das letras" onclick="infraGerarAudioCaptcha(\''.$strIdAudioObject.'\', \''.$strIdSrcAudio.'\', \''.$strCodigo.'\')" src="'.$strImgAudio.'">';
  }

  public static function gerarImagem($strCodigo){
    $strFonte = dirname(__FILE__).'/captcha/century.ttf';
    $strCaptcha = self::gerar($strCodigo);
    $objImagem = ImageCreateFromPNG(dirname(__FILE__)."/captcha/imagens_fundo/bg".rand(1, 13).".png");

    $numTamanho = rand(16, 18);
    $numAngulo = rand(-5, 5);
    $numTamanhoTexto = imagettfbbox($numTamanho, $numAngulo, $strFonte, $strCaptcha);
    $numLargura = abs($numTamanhoTexto[2]-$numTamanhoTexto[0]);
    $numAltura = abs($numTamanhoTexto[5]-$numTamanhoTexto[3]);
    ImageTTFText($objImagem, $numTamanho, $numAngulo,
        (imagesx($objImagem)/2) - ($numLargura/2) + (rand(-20, 20)),
        (imagesy($objImagem))-($numAltura/2),
        ImageColorAllocate($objImagem, rand(0, 100), rand(0, 100), rand(0, 100)),
        $strFonte, $strCaptcha[0].' '.$strCaptcha[1].' '.$strCaptcha[2].' '.$strCaptcha[3]);

    ob_start();
    ImagePNG($objImagem);
    $img = ob_get_clean();
    ImageDestroy($objImagem);
    return $img;
  }
}
?>