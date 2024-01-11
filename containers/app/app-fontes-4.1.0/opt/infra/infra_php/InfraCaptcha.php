<?php

/*CREATE TABLE infra_captcha (
  identificacao         varchar(50)  NOT NULL ,
  dia                   int  NOT NULL ,
  mes                   int  NOT NULL ,
  ano                   int  NOT NULL ,
  acertos               bigint  NOT NULL ,
  erros                 bigint  NOT NULL
);

ALTER TABLE infra_captcha	ADD CONSTRAINT  pk_infra_captcha PRIMARY KEY (identificacao  ASC,dia  ASC,mes  ASC,ano  ASC);*/

abstract class InfraCaptcha
{
    private $numTipo = null;
    private $strIdentificacao = null;
    private $strIdentificacaoFormatada = null;
    private $arrResposta = null;

    private $strInfraPesquisa = null;
    private $strInfraCodigo = null;
    private $bolInfraAudio = null;

    private $strHCaptchaSiteKey = null;
    private $strHCaptchaSecret = null;

    private $strReCaptchaV2SiteKey = null;
    private $strReCaptchaV2Secret = null;

    private $strReCaptchaV3SiteKey = null;
    private $strReCaptchaV3Secret = null;
    private $dblReCaptchaV3Score = null;
    private $strReCaptchaV3Action = null;

    public static $TIPO_INFRA = 1;
    public static $TIPO_HCAPTCHA = 2;
    public static $TIPO_RECAPTCHA_V2 = 3;
    public static $TIPO_RECAPTCHA_V3 = 4;
    public static $TIPO_INFRA_V2 = 5;

    public function __construct(InfraIBanco $objInfraIBanco)
    {
        BancoInfra::setObjInfraIBanco($objInfraIBanco);
    }

    public function isBolGravarAcessos()
    {
        return false;
    }

    public function getNumTipo()
    {
        return $this->numTipo;
    }

    public function getArrResposta()
    {
        return $this->arrResposta;
    }

    public function configurarInfra($strIdentificacao, $bolExibirAudio = false)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraCaptcha->configurarInfra]');
        }

        $this->numTipo = self::$TIPO_INFRA;
        $this->strIdentificacao = $strIdentificacao;

        if (isset($_SESSION['INFRA_CAPTCHA'])) {
            $this->strInfraPesquisa = $_SESSION['INFRA_CAPTCHA'];
        } else {
            $this->strInfraPesquisa = '';
        }
        $this->strInfraCodigo = $_SESSION['INFRA_CAPTCHA_CODIGO'] = self::obterCodigo();
        $this->bolInfraAudio = $bolExibirAudio;
        $_SESSION['INFRA_CAPTCHA'] = self::gerar($this->strInfraCodigo);
    }

    public function configurarInfraV2($strIdentificacao, $bolExibirAudio = true)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraCaptcha->configurarInfraV2]');
        }

        $this->numTipo = self::$TIPO_INFRA_V2;
        $this->strIdentificacao = $strIdentificacao;
        $this->strIdentificacaoFormatada = str_replace(' ', '_', strtoupper(InfraString::excluirAcentos($this->strIdentificacao)));

        if (isset($_SESSION['INFRA_CAPTCHA_V2_'.$this->strIdentificacaoFormatada])) {
            $this->strInfraPesquisa = $_SESSION['INFRA_CAPTCHA_V2_'.$this->strIdentificacaoFormatada];
        } else {
            $this->strInfraPesquisa = '';
        }
        $this->strInfraCodigo = $_SESSION['INFRA_CAPTCHA_CODIGO_'.$this->strIdentificacaoFormatada] = self::obterCodigoV2();
        $this->bolInfraAudio = $bolExibirAudio;
        $_SESSION['INFRA_CAPTCHA_V2_'.$this->strIdentificacaoFormatada] = self::gerarV2($this->strInfraCodigo);
    }

    public function configurarHCaptcha($strIdentificacao, $strSecret, $strSiteKey)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraCaptcha->configurarHCaptcha]');
        }

        $this->numTipo = self::$TIPO_HCAPTCHA;
        $this->strIdentificacao = $strIdentificacao;
        $this->strHCaptchaSecret = $strSecret;
        $this->strHCaptchaSiteKey = $strSiteKey;
    }

    public function configurarReCaptchaV2($strIdentificacao, $strSecret, $strSiteKey)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraCaptcha->configurarReCaptchaV2]');
        }

        $this->numTipo = self::$TIPO_RECAPTCHA_V2;
        $this->strIdentificacao = $strIdentificacao;
        $this->strReCaptchaV2Secret = $strSecret;
        $this->strReCaptchaV2SiteKey = $strSiteKey;
    }


    public function configurarReCaptchaV3($strIdentificacao, $strSecret, $strSiteKey, $dblScore, $strAction)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraCaptcha->configurarReCaptchaV3]');
        }

        $this->numTipo = self::$TIPO_RECAPTCHA_V3;
        $this->strIdentificacao = $strIdentificacao;
        $this->strReCaptchaV3Secret = $strSecret;
        $this->strReCaptchaV3SiteKey = $strSiteKey;
        $this->dblReCaptchaV3Score = $dblScore;
        $this->strReCaptchaV3Action = $strAction;
    }

    public function montarStyle()
    {
        if (($this->numTipo == self::$TIPO_INFRA) || ($this->numTipo == self::$TIPO_INFRA_V2)) {
            echo '<style type="text/css">
          #divInfraCaptcha {margin:.3em 0;}
          #lblInfraCaptcha {margin-right:.5em;}
          #lblInfraCaptcha img {vertical-align:bottom;}
          #txtInfraCaptcha {vertical-align:top;font-size:1.8em;text-align:center;max-width:130px;margin-right:3px;padding:3px;height:' . ($this->numTipo == self::$TIPO_INFRA ? '45' : '50') . 'px;}
          </style>';
        } elseif ($this->numTipo == self::$TIPO_HCAPTCHA || $this->numTipo == self::$TIPO_RECAPTCHA_V2 || $this->numTipo == self::$TIPO_RECAPTCHA_V3) {
            echo '<style type="text/css">
          #divInfraCaptcha {margin:.3em 0;}
          </style>';
        }
    }

    public function montarJavascript()
    {
        if ($this->numTipo == self::$TIPO_HCAPTCHA) {
            echo '<script src="https://hcaptcha.com/1/api.js" async defer></script>' . "\n";
        } elseif ($this->numTipo == self::$TIPO_RECAPTCHA_V2) {
            echo ' <script src="https://www.google.com/recaptcha/api.js" async defer></script>' . "\n";
        } elseif ($this->numTipo == self::$TIPO_RECAPTCHA_V3) {
            echo '<script src="https://www.google.com/recaptcha/api.js?render=' . $this->strReCaptchaV3SiteKey . '"></script>' . "\n";
        } elseif ($this->numTipo == self::$TIPO_INFRA_V2) {
            echo '<script type="text/javascript" charset="iso-8859-1" >
          var infraCaptchaHttpRequest;
          function infraGerarNovoCaptcha(url) {
            if (window.XMLHttpRequest) {
              infraCaptchaHttpRequest = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
              try {
                infraCaptchaHttpRequest = new ActiveXObject("Msxml2.XMLHTTP");
              }
              catch (e) {
                try {
                  infraCaptchaHttpRequest = new ActiveXObject("Microsoft.XMLHTTP");
                }
                catch (e) {}
              }
            }
            if (!infraCaptchaHttpRequest) {
              alert(\'Não foi possível fazer a chamada AJAX para geração de um novo captcha. Tente novamente por favor.\');
              return false;
            }
            infraCaptchaHttpRequest.onreadystatechange = infraAtualizarImagemCaptcha;
            infraCaptchaHttpRequest.open(\'GET\', INFRA_PATH_JS + url);
            infraCaptchaHttpRequest.send();
          }
          function infraAtualizarImagemCaptcha() {
            if (infraCaptchaHttpRequest.readyState === 4) {
              if (infraCaptchaHttpRequest.status === 200) {
                document.getElementById(\'imgCaptcha\').src = infraCaptchaHttpRequest.responseText;
              } else {
                alert(\'Houve um erro durante a requisição da um novo captcha. Tente novamente por favor.\');
              }
            }
          }
          </script>';
        }
    }

    public function verificar()
    {
        $ret = false;

        try {
            if (($this->numTipo == self::$TIPO_INFRA) || ($this->numTipo == self::$TIPO_INFRA_V2)) {
                if (isset($_POST['txtInfraCaptcha']) && !empty($_POST['txtInfraCaptcha'])) {
                    $this->arrResposta = array();
                    $ret = strtoupper($_POST['txtInfraCaptcha']) == strtoupper($this->strInfraPesquisa);
                }
            } elseif ($this->numTipo == self::$TIPO_HCAPTCHA) {
                if (isset($_POST['h-captcha-response']) && !empty($_POST['h-captcha-response'])) {
                    $data = array(
                        'secret' => $this->strHCaptchaSecret,
                        'response' => $_POST['h-captcha-response']
                    );

                    $verify = curl_init();
                    curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
                    curl_setopt($verify, CURLOPT_POST, true);
                    curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
                    curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
                    $this->arrResposta = json_decode(curl_exec($verify), true);

                    //{
                    //   "success": true|false,     // is the passcode valid, and does it meet security criteria you specified, e.g. sitekey?
                    //   "challenge_ts": timestamp, // timestamp of the challenge (ISO format yyyy-MM-dd'T'HH:mm:ssZZ)
                    //   "hostname": string,        // the hostname of the site where the challenge was solved
                    //   "credit": true|false,      // optional: whether the response will be credited
                    //   "error-codes": [...]       // optional: any error codes
                    //   "score": float,            // ENTERPRISE feature: a score denoting malicious activity.
                    //   "score_reason": [...]      // ENTERPRISE feature: reason(s) for score. See BotStop.com for details.
                    //}

                    if (InfraDebug::isBolProcessar()) {
                        InfraDebug::getInstance()->gravarInfra(
                            '[InfraCaptcha->verificar] ' . "\n" . print_r($this->arrResposta, true)
                        );
                    }

                    if ($this->arrResposta['success']) {
                        $ret = true;
                    }
                }
            } elseif ($this->numTipo == self::$TIPO_RECAPTCHA_V2) {
                if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
                    $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode(
                            $this->strReCaptchaV2Secret
                        ) . '&response=' . urlencode($_POST['g-recaptcha-response']);

                    $this->arrResposta = json_decode(file_get_contents($url), true);

                    if (InfraDebug::isBolProcessar()) {
                        InfraDebug::getInstance()->gravarInfra(
                            '[InfraCaptcha->verificar] ' . "\n" . print_r($this->arrResposta, true)
                        );
                    }

                    if ($this->arrResposta['success']) {
                        $ret = true;
                    }
                }
            } elseif ($this->numTipo == self::$TIPO_RECAPTCHA_V3) {
                //USE THE RECAPTCHA PHP CLIENT LIBRARY FOR VALIDATION
                $recaptcha = new ReCaptcha\ReCaptcha($this->strReCaptchaV3Secret);
                $response = $recaptcha->setExpectedAction($this->strReCaptchaV3Action)
                    ->setScoreThreshold($this->dblReCaptchaV3Score)
                    ->verify($_POST['hdnInfraRecaptcha']);

                $this->arrResposta = array();
                $this->arrResposta['success'] = $response->isSuccess();
                $this->arrResposta['hostname'] = $response->getHostname();
                $this->arrResposta['challenge_ts'] = $response->getChallengeTs();
                $this->arrResposta['apk_package_name'] = $response->getApkPackageName();
                $this->arrResposta['action'] = $response->getAction();
                $this->arrResposta['score'] = $response->getScore();
                $this->arrResposta['error-codes'] = $response->getErrorCodes();

                if (InfraDebug::isBolProcessar()) {
                    InfraDebug::getInstance()->gravarInfra(
                        '[InfraCaptcha->verificar] ' . "\n" . print_r($this->arrResposta, true)
                    );
                }

                if ($this->arrResposta['success']) {
                    $ret = true;
                }
            }

            if ($this->isBolGravarAcessos()) {
                $objInfraCaptchaDTO = new InfraCaptchaDTO();
                $objInfraCaptchaDTO->setStrIdentificacao($this->strIdentificacao);
                if ($ret) {
                    $objInfraCaptchaDTO->setDblAcertos(1);
                    $objInfraCaptchaDTO->setDblErros(0);
                } else {
                    $objInfraCaptchaDTO->setDblAcertos(0);
                    $objInfraCaptchaDTO->setDblErros(1);
                }
                $objInfraCaptchaRN = new InfraCaptchaRN();
                $objInfraCaptchaRN->registrar($objInfraCaptchaDTO);
            }
        }catch(Exception $e){
            throw new InfraException('Erro verificando código de confirmação.', $e);
        }

        return $ret;
    }

    public function validarOnSubmit($strIdForm)
    {
        if ($this->numTipo == self::$TIPO_INFRA) {
            echo "if (infraTrim(document.getElementById('txtInfraCaptcha').value)=='') {
          alert('Informe o código de confirmação.');
          document.getElementById('txtInfraCaptcha').focus();
          return false; 
          }else{
          document.getElementById('hdnInfraCaptcha').value='1';
          return true;
          }";
        } elseif ($this->numTipo == self::$TIPO_INFRA_V2) {
            echo "if (infraTrim(document.getElementById('txtInfraCaptcha').value)=='') {
          alert('Informe o código de confirmação.');
          document.getElementById('txtInfraCaptcha').focus();
          return false; 
          }else{
          document.getElementById('hdnInfraCaptcha').value='1';
          return true;
          }";
        } elseif ($this->numTipo == self::$TIPO_HCAPTCHA) {
            echo 'var hRet = document.getElementsByName(\'h-captcha-response\');
          if (hRet.length == 0 || hRet[0].value == \'\'){
          alert(\'Marque a opção "Sou humano".\');
          return false;
          }else{
          document.getElementById(\'hdnInfraCaptcha\').value=\'1\';
          return true;
          }';
        } elseif ($this->numTipo == self::$TIPO_RECAPTCHA_V2) {
            echo 'var gRet = document.getElementsByName(\'g-recaptcha-response\');
          if (gRet.length == 0 || gRet[0].value == \'\'){
          alert(\'Marque a opção "Não sou um robô".\');
          return false;
          }else{
          document.getElementById(\'hdnInfraCaptcha\').value=\'1\';
          return true;
          }';
        } elseif ($this->numTipo == self::$TIPO_RECAPTCHA_V3) {
            echo "grecaptcha.ready(function() {
          grecaptcha.execute('" . $this->strReCaptchaV3SiteKey . "', {action: '" . $this->strReCaptchaV3Action . "'}).then(
          function(token) {
          document.getElementById('hdnInfraRecaptcha').value=token;
          document.getElementById('hdnInfraCaptcha').value='1';
          document.getElementById('" . $strIdForm . "').onsubmit = null;
          document.getElementById('" . $strIdForm . "').submit();
          });
          });
          return false;";
        }
    }

    public function montarHtml($numTabulacao = null)
    {
        if ($this->numTipo == self::$TIPO_INFRA) {
            $strTab = ($numTabulacao !== null) ? 'tabindex="' . $numTabulacao . '"' : '';

            echo '
<div id="divInfraCaptcha" class="infraAreaDados" style="height:5em;">
  <label id="lblInfraCaptcha" for="txtInfraCaptcha" class="infraLabelObrigatorio">
  <img src="data:image/png;base64,' . base64_encode(self::gerarImagem($this->strInfraCodigo)) . '" title="Informe o código de confirmação" /></label>
';

            if ($this->bolInfraAudio) {
                echo '
  <audio id="infraAudioCaptchaMedia"><source id="infraSrcAudioCaptcha" src="/infra_js/infra_gerar_audio_captcha.php"></audio>
  <img id="infraImgAudioCaptcha" title="Ouvir a narração das letras do código de confirmação" onclick="infraGerarAudioCaptcha(\'infraAudioCaptchaMedia\', \'infraSrcAudioCaptcha\')" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAOxAAADsQBlSsOGwAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAHdSURBVEiJ7ZO/axRBGIafb3ME9C6Chdiodxe1kAgp1EJCCtNYWVkoZheEHIIp40lQC1diQLMbBSGgoBC4pFH8AwS1EMFGUFSwiXpBGwki6G40JDufxXGydx73I3dW+lbzzby8z8w3M/Bff0tp2z/fO+wfaOSzWgnN2n6uPBY4YoQbGdu7uWv46ra2AVnbzymM/J4QuSXwBaQ/kq7ZrD3dv27AH+FAsXBmdln0mCoPFZKI+juPT29vGVArvKzPhbPh4u7goog+UiUVJcyFlgD1wtO2P7XV8ZK4rgl+hlfK7ep1ru2P+xLxImP7z+K11oELDG5ELgH5pXtukLS9+yCnIhMdBp43dYJGUmUg7VzfA2Alup8CiCUVl90WoBRg9gIYvn4sUdnSUUBZa+EmAUAqO9s2wLD2BqA72VX6bMpS5wDCk8XC+FuAaHVlEEBVX8UtFa+oOJc/GK/rPVOBxysbeiYB+kZnUuH35aMoGMs8qPLVVzWkehO4rpVdSE0oDCG8LBbyp+PLDVv0YS5/W+BOrbW+0ZlU5l3PZYUhEYKEiSarPU3dQS1I2vFGwm8/7qJ6SIRAjDW2MD/+aV2AWhBRyQGbQV+sinXy/fzY62azmlLG8c7tOOHv62jov6lfApmmZg2LDDcAAAAASUVORK5CYII=" ' . $strTab . '/>
';
            }

            echo '
  <input type="text" id="txtInfraCaptcha" name="txtInfraCaptcha" class="infraText" value="" maxlength="4" ' . $strTab . '/>
  <input type="hidden" id="hdnInfraCaptcha" name="hdnInfraCaptcha" value="0" />
</div>';
        } elseif ($this->numTipo == self::$TIPO_INFRA_V2) {
            $strTab = ($numTabulacao !== null) ? 'tabindex="' . $numTabulacao . '"' : '';

            $strChave = hash(
                'sha256',
                'b9e97b3c17266a68c19682f2c96ca' . date("H-Y-d-m") . 'c723e70345d6a5af253cf30a4500f886696'
            );

            echo '
<div id="divInfraCaptcha" class="infraAreaDados" style="height:5em;">
  <div style="float:left;">
    <label id="lblInfraCaptcha" for="txtInfraCaptcha" class="infraLabelObrigatorio">
    <img id="imgCaptcha" src="data:image/png;base64,' . base64_encode(self::gerarImagemV2($this->strInfraCodigo)) . '" title="Informe o código de confirmação" /></label>
  </div>
  <div style="width:28px;float:left;">
    <img id="infraImgRecarregarCaptcha" title="Regerar as letras do código de confirmação" onclick="infraGerarNovoCaptcha(\'/infra_gerar_captcha.php?r=s&c=' . $strChave . '&i='.$this->strIdentificacaoFormatada.'\');" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0ibWRpLXJlbG9hZCIgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiB2aWV3Qm94PSIwIDAgMjQgMjQiPjxwYXRoIGQ9Ik0yIDEyQzIgMTYuOTcgNi4wMyAyMSAxMSAyMUMxMy4zOSAyMSAxNS42OCAyMC4wNiAxNy40IDE4LjRMMTUuOSAxNi45QzE0LjYzIDE4LjI1IDEyLjg2IDE5IDExIDE5QzQuNzYgMTkgMS42NCAxMS40NiA2LjA1IDcuMDVDMTAuNDYgMi42NCAxOCA1Ljc3IDE4IDEySDE1TDE5IDE2SDE5LjFMMjMgMTJIMjBDMjAgNy4wMyAxNS45NyAzIDExIDNDNi4wMyAzIDIgNy4wMyAyIDEyWiIgLz48L3N2Zz4=" ' . $strTab . '/>';
            if ($this->bolInfraAudio) {
                echo '    
    <audio id="infraAudioCaptchaMedia"><source id="infraSrcAudioCaptcha" src="/infra_js/infra_gerar_audio_captcha.php?v=' . $this->numTipo . '&i='.$this->strIdentificacaoFormatada.'"></audio>
    <img id="infraImgAudioCaptcha" title="Ouvir a narração das letras do código de confirmação" onclick="infraGerarAudioCaptcha(\'infraAudioCaptchaMedia\', \'infraSrcAudioCaptcha\', \'' . $this->numTipo . '\', \''.$this->strIdentificacaoFormatada.'\')" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0ibWRpLXZvbHVtZS1oaWdoIiB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCI+PHBhdGggZD0iTTE0LDMuMjNWNS4yOUMxNi44OSw2LjE1IDE5LDguODMgMTksMTJDMTksMTUuMTcgMTYuODksMTcuODQgMTQsMTguN1YyMC43N0MxOCwxOS44NiAyMSwxNi4yOCAyMSwxMkMyMSw3LjcyIDE4LDQuMTQgMTQsMy4yM00xNi41LDEyQzE2LjUsMTAuMjMgMTUuNSw4LjcxIDE0LDcuOTdWMTZDMTUuNSwxNS4yOSAxNi41LDEzLjc2IDE2LjUsMTJNMyw5VjE1SDdMMTIsMjBWNEw3LDlIM1oiIC8+PC9zdmc+" ' . $strTab . '/>
';
            }
            echo '
  </div>
  <div style="float:left;">
    <input type="text" id="txtInfraCaptcha" name="txtInfraCaptcha" class="infraText" value="" maxlength="6" ' . $strTab . '/>
    <input type="hidden" id="hdnInfraCaptcha" name="hdnInfraCaptcha" value="0" />
  </div>
</div>';
        } elseif ($this->numTipo == self::$TIPO_HCAPTCHA) {
            $strTab = ($numTabulacao !== null) ? 'data-tabindex="' . $numTabulacao . '"' : '';
            echo '
<div id="divInfraCaptcha" class="infraAreaDados">
  <div class="h-captcha" data-sitekey="' . $this->strHCaptchaSiteKey . '" ' . $strTab . '></div>
  <input type="hidden" id="hdnInfraCaptcha" name="hdnInfraCaptcha" value="0" />
</div>
';
        } elseif ($this->numTipo == self::$TIPO_RECAPTCHA_V2) {
            $strTab = ($numTabulacao !== null) ? 'tabindex="' . $numTabulacao . '"' : '';
            echo '
<div id="divInfraCaptcha" class="infraAreaDados">
  <div class="g-recaptcha" data-sitekey="' . $this->strReCaptchaV2SiteKey . '" ' . $strTab . '></div> 
  <input type="hidden" id="hdnInfraCaptcha" name="hdnInfraCaptcha" value="0" />
</div>';
        } elseif ($this->numTipo == self::$TIPO_RECAPTCHA_V3) {
            echo '
<div id="divInfraCaptcha" class="infraAreaDados" style="style:display:none;">
  <div class="g-recaptcha"></div>
  <input type="hidden" id="hdnInfraRecaptcha" name="hdnInfraRecaptcha" value="" />
  <input type="hidden" id="hdnInfraCaptcha" name="hdnInfraCaptcha" value="0" />
</div>';
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public static function obterCodigo()
    {
        $arrRand = array(
            array(48, 57) //números
        ,
            array(97, 122) //letras maiúsculas
        ,
            array(65, 90) //letras minúsculas
        );

        $strCodeToRandom = '';
        $r = mt_rand(0, 2);
        $strCodeToRandom .= mt_rand($arrRand[$r][0], $arrRand[$r][1]);

        $strCodeToRandom .= '-';

        $r = mt_rand(0, 2);
        $strCodeToRandom .= mt_rand($arrRand[$r][0], $arrRand[$r][1]);

        return $strCodeToRandom;
    }

    public static function obterCodigoV2()
    {
        $arrItens = array(
            array(1, 2, 3, 4, 5, 7, 8, 9),
            array(
                'A',
                'B',
                'C',
                'D',
                'E',
                'F',
                'G',
                'H',
                'I',
                'J',
                'K',
                'L',
                'M',
                'N',
                'P',
                'R',
                'S',
                'T',
                'U',
                'V',
                'W',
                'X',
                'Y',
                'Z'
            )
        );

        $strCodeToRandom = '';

        for ($i = 0; $i < 6; $i++) {
            $numArrayItens = mt_rand(0, 1);
            $arrChaveAleatoria = array_rand($arrItens[$numArrayItens]);
            $strCodeToRandom .= ord($arrItens[$numArrayItens][$arrChaveAleatoria]);

            if ($i < 5) {
                $strCodeToRandom .= '-';
            }
        }

        return $strCodeToRandom;
    }

    public static function gerar($strCodigo)
    {
        $strCaptcha = null;

        if (trim($strCodigo) != '') {
            $MENOR_COD_CAPTCHA = 48;
            $MAIOR_COD_CAPTCHA = 122;
            $arrCodNaoExistentes = array(58, 59, 60, 61, 62, 63, 64, 91, 92, 93, 94, 95, 96);
            $arrCodigoParaGeracaoCaptcha = explode('-', $strCodigo);

            $strCaptcha = chr($arrCodigoParaGeracaoCaptcha[0]) . chr($arrCodigoParaGeracaoCaptcha[1]);

            sort($arrCodigoParaGeracaoCaptcha);

            $media = round(($arrCodigoParaGeracaoCaptcha[1] - $arrCodigoParaGeracaoCaptcha[0]) / 2);

            if (in_array(
                    $arrCodigoParaGeracaoCaptcha[0] + $media,
                    $arrCodNaoExistentes
                ) || $arrCodigoParaGeracaoCaptcha[0] + $media > $MAIOR_COD_CAPTCHA) {
                $strCaptcha .= chr($arrCodigoParaGeracaoCaptcha[0]);
            } else {
                $strCaptcha .= chr($arrCodigoParaGeracaoCaptcha[0] + $media);
            }

            if (in_array(
                    $arrCodigoParaGeracaoCaptcha[1] - $media,
                    $arrCodNaoExistentes
                ) || $arrCodigoParaGeracaoCaptcha[1] - $media < $MENOR_COD_CAPTCHA) {
                $strCaptcha .= chr($arrCodigoParaGeracaoCaptcha[1]);
            } else {
                $strCaptcha .= chr($arrCodigoParaGeracaoCaptcha[1] - $media);
            }
        }

        return $strCaptcha;
    }

    public static function gerarV2($strCodigo)
    {
        $arrItens = array(
            array(1, 2, 3, 4, 5, 7, 8, 9),
            array(
                'A',
                'B',
                'C',
                'D',
                'E',
                'F',
                'G',
                'H',
                'I',
                'J',
                'K',
                'L',
                'M',
                'N',
                'P',
                'R',
                'S',
                'T',
                'U',
                'V',
                'W',
                'X',
                'Y',
                'Z'
            )
        );
        $strCaptcha = null;

        if (trim($strCodigo) != '') {
            $arrCodigoParaGeracaoCaptcha = explode('-', $strCodigo);

            $numMedia = round(($arrCodigoParaGeracaoCaptcha[2] - $arrCodigoParaGeracaoCaptcha[0]) / 2);

            for ($i = 0; $i < InfraArray::contar($arrCodigoParaGeracaoCaptcha); $i++) {
                if ((!in_array($arrCodigoParaGeracaoCaptcha[$i] + $numMedia, $arrItens[0]) && (!in_array(
                        $arrCodigoParaGeracaoCaptcha[$i] + $numMedia,
                        $arrItens[1]
                    )))) {
                    $strCaptcha .= chr($arrCodigoParaGeracaoCaptcha[$i]);
                } else {
                    $strCaptcha .= chr($arrCodigoParaGeracaoCaptcha[$i] + $numMedia);
                }
            }
        }

        return $strCaptcha;
    }

    public static function montarLabel($strCodigo, $strIdLabel = 'lblCaptcha')
    {
        return '<label id="' . $strIdLabel . '" class="infraLabelObrigatorio"><img src="/infra_js/infra_gerar_captcha.php?codetorandom=' . $strCodigo . '" alt="Não foi possível carregar imagem de confirmação" /></label>';
    }

    public static function montarAudio(
        $strCodigo,
        $strIdAudioObject = 'audioCaptchaMedia',
        $strIdSrcAudio = 'srcAudioCaptcha',
        $strIdImgAudio = 'imgAudioCaptcha',
        $strImgAudio = '/infra_css/imagens/audio.gif'
    ) {
        return '<audio id="' . $strIdAudioObject . '"><source id="' . $strIdSrcAudio . '" src="/infra_js/infra_gerar_audio_captcha.php?codetorandom=' . $strCodigo . '"></audio><img id=' . $strIdImgAudio . ' alt="Ouvir a narração das letras" title="Ouvir a narração das letras" onclick="infraGerarAudioCaptcha(\'' . $strIdAudioObject . '\', \'' . $strIdSrcAudio . '\', \'' . $strCodigo . '\')" src="' . $strImgAudio . '">';
    }

    public static function gerarImagem($strCodigo)
    {
        $strFonte = __DIR__ . '/captcha/century.ttf';
        $strCaptcha = self::gerar($strCodigo);
        $objImagem = ImageCreateFromPNG(__DIR__ . "/captcha/imagens_fundo/bg" . mt_rand(1, 13) . ".png");

        $numTamanho = mt_rand(16, 18);
        $numAngulo = mt_rand(-5, 5);
        $numTamanhoTexto = imagettfbbox($numTamanho, $numAngulo, $strFonte, $strCaptcha);
        $numLargura = abs($numTamanhoTexto[2] - $numTamanhoTexto[0]);
        $numAltura = abs($numTamanhoTexto[5] - $numTamanhoTexto[3]);
        ImageTTFText(
            $objImagem,
            $numTamanho,
            $numAngulo,
            floor((imagesx($objImagem) / 2) - ($numLargura / 2) + (mt_rand(-20, 20))),
            floor((imagesy($objImagem)) - ($numAltura / 2)),
            ImageColorAllocate($objImagem, mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100)),
            $strFonte,
            $strCaptcha[0] . ' ' . $strCaptcha[1] . ' ' . $strCaptcha[2] . ' ' . $strCaptcha[3]
        );

        ob_start();
        ImagePNG($objImagem);
        $img = ob_get_clean();
        ImageDestroy($objImagem);
        return $img;
    }

    public static function gerarImagemV2($strCodigo)
    {
        $strFonte = __DIR__ . '/captcha/comicz.ttf';
        $strCaptcha = self::gerarV2($strCodigo);
        $objImagem = ImageCreateFromPNG(__DIR__ . "/captcha/imagens_fundo/bg_branco.png");

        imagesetthickness($objImagem, 2);

        for ($i = 0; $i < 500; $i++) {
            imagesetpixel($objImagem, mt_rand(0, 180), mt_rand(0, 50), ImageColorAllocate($objImagem, 0, 0, 0));
        }

        for ($i = 0; $i < 16; $i++) {
            imagefilledellipse(
                $objImagem,
                mt_rand(0, 180),
                mt_rand(0, 50),
                mt_rand(2, 4),
                mt_rand(2, 4),
                ImageColorAllocate($objImagem, 0, 0, 0)
            );
        }

        /*for ($i=0; $i<4; $i++) {
          imagearc($objImagem, rand(0,180), rand(0,50), 20, 20, rand(0,90), rand(100,250), ImageColorAllocate($objImagem, 0, 0, 0));
        }*/

        for ($i = 0; $i < 3; $i++) {
            imageline(
                $objImagem,
                mt_rand(0, 180),
                mt_rand(0, 50),
                mt_rand(0, 180),
                mt_rand(0, 50),
                ImageColorAllocate($objImagem, 0, 0, 0)
            );
        }

        $numDeslocamentoX = 15;
        for ($i = 0; $i < 6; $i++) {
            ImageTTFText(
                $objImagem,
                mt_rand(18, 22),
                mt_rand(-30, 30),
                $numDeslocamentoX,
                mt_rand(25, 35),
                ImageColorAllocate($objImagem, 0, 0, 0),
                $strFonte,
                $strCaptcha[$i]
            );
            $numDeslocamentoX += 25;
        }

        ob_start();
        ImagePNG($objImagem);
        $img = ob_get_clean();
        ImageDestroy($objImagem);
        return $img;
    }

}

