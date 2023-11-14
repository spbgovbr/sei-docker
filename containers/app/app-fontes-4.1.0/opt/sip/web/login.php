<?

try {
  require_once dirname(__FILE__) . '/Sip.php';

  session_start();

  /////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  /////////////////////////////////////////////////////////////////////////////

  foreach ($_POST as $item) {
    if (is_array($item)) {
      die('Link inválido.');
    }
  }

  foreach ($_GET as $item) {
    if (is_array($item)) {
      die('Link inválido.');
    }
  }

  $strParametrosAction = '';
  foreach ($_GET as $key => $item) {
    if (in_array($key, array('sigla_sistema', 'sigla_orgao_sistema', 'modulo_sistema', 'menu_sistema', 'infra_url'))) {
      if ($item != '') {
        if (($key != 'infra_url' && preg_match("/[^0-9a-zA-Z\-_]/", $item)) || ($key == 'infra_url' && (strlen($item) > 1024 || !InfraUtil::isBolBase64(urldecode($item))))) {
          //LogSip::getInstance()->gravar('Link de login inválido: '.print_r($_GET, true));
          die('Link inválido.');
        }
        $strParametrosAction .= (($strParametrosAction != '') ? '&' : '') . $key . '=' . $item;
      }
    } else {
      if ($key == 'acao' && !is_numeric($item)) {
        die('Link inválido.');
      }
    }
  }

  LoginINT::inicializarSession();


  $objInfraException = new InfraException();
  $objInfraParametro = new InfraParametro(BancoSip::getInstance());

  $strSiglaSistema = $_GET['sigla_sistema'];
  $strSiglaOrgaoSistema = $_GET['sigla_orgao_sistema'];

  $numLoginSemCaptcha = ConfiguracaoSip::getInstance()->getValor('Sip', 'NumLoginSemCaptcha', false, 3);

  $objSistemaDTO = LoginINT::obterSistema($strSiglaSistema, $strSiglaOrgaoSistema);

  PaginaLogin::getInstance()->setObjSistemaDTO($objSistemaDTO);

  $numIdOrgao = null;

  $strChaveCookieUsuario = 'SIP_U_' . $strSiglaOrgaoSistema . '_' . $strSiglaSistema;
  $strChaveCookieCodigoAcesso = 'SIP_AC_' . $strSiglaOrgaoSistema . '_' . $strSiglaSistema;
  $strChaveCookieDispositivo = 'SIP_DI_' . $strSiglaOrgaoSistema . '_' . $strSiglaSistema;

  if (count($_POST) == 0) {
    if (isset($_COOKIE[$strChaveCookieUsuario]) && $_COOKIE[$strChaveCookieUsuario] != '' && $_COOKIE[$strChaveCookieUsuario] != 'deleted') {
      $numIdOrgao = $_COOKIE[$strChaveCookieUsuario];
    }
  } else {
    if (isset($_POST['selOrgao'])) {
      $numIdOrgao = $_POST['selOrgao'];
    }
  }

  if (!is_numeric($numIdOrgao)) {
    $numIdOrgao = null;
  }


  $bolDadosUsuario = true;
  $bolCodigoAcesso = false;
  $bol2Fatores = ($objSistemaDTO->getStrSta2Fatores() == SistemaRN::$T2E_OPCIONAL || $objSistemaDTO->getStrSta2Fatores() == SistemaRN::$T2E_OBRIGATORIA);
  $bolAviso2Fatores = false;
  $bolInstrucoes2Fatores = false;
  $bolConfigurar2Fatores = false;
  $bolAvisoExclusaoContaApp = false;
  $numDispositivosCancelados = 0;

  $numAcao = LoginINT::$ACAO_DADOS_USUARIO;
  $numAcaoSubmit = LoginINT::$ACAO_LOGAR_SENHA;
  if (isset($_POST['hdnAcao']) && trim($_POST['hdnAcao']) != '') {
    $numAcao = $_POST['hdnAcao'];
  } else {
    if (isset($_GET['acao']) && trim($_GET['acao'] != '')) {
      $numAcao = $_GET['acao'];
    }
  }

  if ($numAcao == LoginINT::$ACAO_LOGAR_SENHA || $numAcao == LoginINT::$ACAO_LOGAR_CONFIGURAR_2_FATORES) {
    $strIdentificacaoCaptcha = 'Login Senha';
  } else {
    $strIdentificacaoCaptcha = 'Login Código 2FA';
  }

  CaptchaSip::getInstance()->configurarCaptcha($strIdentificacaoCaptcha);

  switch ($numAcao) {
    case LoginINT::$ACAO_LOGAR_SENHA:
    case LoginINT::$ACAO_LOGAR_CONFIGURAR_2_FATORES:

      if (isset($_POST['txtUsuario'])) {
        try {
          if ($_SESSION['SIP_NUM_FALHA_LOGIN'] >= $numLoginSemCaptcha && !CaptchaSip::getInstance()->verificar()) {
            $objInfraException->lancarValidacao('Desafio não foi resolvido.');
          }

          $objLoginDTO = new LoginDTO();
          $objLoginDTO->setStrSiglaOrgaoSistema($objSistemaDTO->getStrSiglaOrgao());
          $objLoginDTO->setStrSiglaSistema($objSistemaDTO->getStrSigla());
          $objLoginDTO->setNumIdOrgaoUsuario($_POST['selOrgao']);
          $objLoginDTO->setStrSiglaUsuario($_POST['txtUsuario']);
          $objLoginDTO->setStrSenhaUsuario($_POST['pwdSenha']);

          $objLoginRN = new LoginRN();
          $objLoginRN->autenticar($objLoginDTO);

          LoginINT::limparSession();

          $numIdUsuario = $objLoginDTO->getNumIdUsuarioEmulador() != null ? $objLoginDTO->getNumIdUsuarioEmulador() : $objLoginDTO->getNumIdUsuario();

          $_SESSION['SIP_ID_USUARIO'] = $numIdUsuario;

          if ($bol2Fatores && InfraData::compararDataHorasSimples(InfraData::getStrDataHoraAtual(), $objLoginDTO->getDthPausa2faUsuario()) > 0) {
            if ($numAcao == LoginINT::$ACAO_LOGAR_CONFIGURAR_2_FATORES) {
              header('Location: login.php?' . $strParametrosAction . '&acao=' . LoginINT::$ACAO_INSTRUCOES_2_FATORES);
              die;
            }

            $bol2Fatores = false;
          }

          if ($bol2Fatores) {
            $objCodigoAcessoDTO = new CodigoAcessoDTO();
            $objCodigoAcessoDTO->retStrIdCodigoAcesso();
            $objCodigoAcessoDTO->setNumIdUsuario($objLoginDTO->getNumIdUsuario());
            $objCodigoAcessoDTO->setNumIdSistema($objSistemaDTO->getNumIdSistema());

            $objCodigoAcessoRN = new CodigoAcessoRN();
            $objCodigoAcessoDTO = $objCodigoAcessoRN->consultar($objCodigoAcessoDTO);

            if ($objCodigoAcessoDTO == null) {
              if ($numAcao == LoginINT::$ACAO_LOGAR_CONFIGURAR_2_FATORES) {
                header('Location: login.php?' . $strParametrosAction . '&acao=' . LoginINT::$ACAO_INSTRUCOES_2_FATORES);
                die;
              }

              if ($objSistemaDTO->getStrSta2Fatores() == SistemaRN::$T2E_OBRIGATORIA) {
                LoginINT::limparSession();
                $objInfraException->lancarValidacao('Este sistema requer que a autenticação em 2 fatores esteja ativada para realização do login.');
              }
            } else {
              if ($objLoginDTO->getNumIdUsuarioEmulador() != null) {
                LoginINT::limparSession();
                $objInfraException->lancarValidacao('Não é possível realizar a emulação porque a autenticação em 2 fatores está ativada para este usuário no sistema.');
              }

              $_SESSION['SIP_ID_CODIGO_ACESSO_VALIDACAO'] = $objCodigoAcessoDTO->getStrIdCodigoAcesso();
              $_SESSION['SIP_2_FATORES'] = 1;

              $bolDadosUsuario = false;
              $bolCodigoAcesso = true;

              if ($numAcao == LoginINT::$ACAO_LOGAR_SENHA) {
                $numAcaoSubmit = LoginINT::$ACAO_VALIDAR_CODIGO;
              } else {
                header('Location: login.php?' . $strParametrosAction . '&acao=' . LoginINT::$ACAO_AVISAR_2_FATORES);
                die;
              }

              $objLoginDTO->setStrIdCodigoAcesso($objCodigoAcessoDTO->getStrIdCodigoAcesso());

              if (isset($_COOKIE[$strChaveCookieCodigoAcesso])) {
                $objCodigoAcessoDTO->setStrChaveAcessoExterna($_COOKIE[$strChaveCookieCodigoAcesso]);

                if (($objDispositivoAcessoDTO = $objCodigoAcessoRN->verificarDispositivo($objCodigoAcessoDTO)) != null) {
                  $objLoginDTO->setStrIdDispositivoAcesso($objDispositivoAcessoDTO->getStrIdDispositivoAcesso());
                  $_SESSION['SIP_ID_CODIGO_ACESSO_CONFIRMADO'] = $_SESSION['SIP_ID_CODIGO_ACESSO_VALIDACAO'];

                  setcookie($strChaveCookieCodigoAcesso, $objCodigoAcessoDTO->getStrChaveAcessoExterna(), time() + 60 * 60 * 24 * 365, '/');
                  setcookie($strChaveCookieDispositivo, $objCodigoAcessoDTO->getStrChaveDispositivoExterna(), time() + 60 * 60 * 24 * 365, '/');
                }
              }
            }
          }

          if ($_SESSION['SIP_ID_CODIGO_ACESSO_VALIDACAO'] == $_SESSION['SIP_ID_CODIGO_ACESSO_CONFIRMADO']) {
            $objLoginRN = new LoginRN();
            LoginINT::redirecionarSistema($objLoginRN->cadastrar($objLoginDTO));
          }
        } catch (Exception $e) {
          if (strpos($e->__toString(), InfraLDAP::$MSG_USUARIO_SENHA_INVALIDA) !== false) {
            $_SESSION['SIP_NUM_FALHA_LOGIN'] = $_SESSION['SIP_NUM_FALHA_LOGIN'] + 1;
          }
          PaginaLogin::getInstance()->processarExcecao($e);
        }
      }
      break;

    case LoginINT::$ACAO_VALIDAR_CODIGO:

      if ($_SESSION['SIP_ID_USUARIO'] != 0 && $_SESSION['SIP_ID_CODIGO_ACESSO_VALIDACAO'] != 0) {
        try {
          $bolDadosUsuario = false;
          $bolCodigoAcesso = true;
          $numAcaoSubmit = LoginINT::$ACAO_VALIDAR_CODIGO;

          if ($_SESSION['SIP_NUM_FALHA_LOGIN'] >= $numLoginSemCaptcha && !CaptchaSip::getInstance()->verificar()) {
            $objInfraException->lancarValidacao('Desafio não foi resolvido.');
          }

          $objCodigoAcessoDTO = new CodigoAcessoDTO();
          $objCodigoAcessoDTO->setStrIdCodigoAcesso($_SESSION['SIP_ID_CODIGO_ACESSO_VALIDACAO']);
          $objCodigoAcessoDTO->setNumIdUsuario($_SESSION['SIP_ID_USUARIO']);
          $objCodigoAcessoDTO->setNumIdSistema($objSistemaDTO->getNumIdSistema());
          $objCodigoAcessoDTO->setStrCodigoExterno($_POST['txtCodigoAcesso']);

          if (isset($_COOKIE[$strChaveCookieDispositivo])) {
            $objCodigoAcessoDTO->setStrChaveDispositivoExterna($_COOKIE[$strChaveCookieDispositivo]);
          } else {
            $objCodigoAcessoDTO->setStrChaveDispositivoExterna(null);
          }

          $strSinLiberar = isset($_POST['chkLiberar']) ? PaginaLogin::getInstance()->getCheckbox($_POST['chkLiberar']) : 'N';
          $objCodigoAcessoDTO->setStrSinLiberarDispositivo($strSinLiberar);

          $objCodigoAcessoRN = new CodigoAcessoRN();

          if ($objCodigoAcessoRN->validar($objCodigoAcessoDTO)) {
            $_SESSION['SIP_NUM_FALHA_LOGIN'] = 0;

            $_SESSION['SIP_ID_CODIGO_ACESSO_CONFIRMADO'] = $_SESSION['SIP_ID_CODIGO_ACESSO_VALIDACAO'];

            if ($objCodigoAcessoDTO->getStrChaveAcessoExterna() != null) {
              setcookie($strChaveCookieCodigoAcesso, $objCodigoAcessoDTO->getStrChaveAcessoExterna(), time() + 60 * 60 * 24 * 365, '/');
            } else {
              setcookie($strChaveCookieCodigoAcesso, '', time() - 3600, '/');
            }

            if ($objCodigoAcessoDTO->getStrChaveDispositivoExterna() != null) {
              setcookie($strChaveCookieDispositivo, $objCodigoAcessoDTO->getStrChaveDispositivoExterna(), time() + 60 * 60 * 24 * 365, '/');
            } else {
              setcookie($strChaveCookieDispositivo, '', time() - 3600, '/');
            }

            $objLoginDTO = new LoginDTO();
            $objLoginDTO->setStrSiglaOrgaoSistema($objSistemaDTO->getStrSiglaOrgao());
            $objLoginDTO->setStrSiglaSistema($objSistemaDTO->getStrSigla());
            $objLoginDTO->setNumIdOrgaoUsuario($objCodigoAcessoDTO->getNumIdOrgaoUsuario());
            $objLoginDTO->setStrSiglaUsuario($objCodigoAcessoDTO->getStrSiglaUsuario());
            $objLoginDTO->setNumIdUsuario($objCodigoAcessoDTO->getNumIdUsuario());
            $objLoginDTO->setStrIdCodigoAcesso($objCodigoAcessoDTO->getStrIdCodigoAcesso());
            $objLoginDTO->setStrIdDispositivoAcesso($objCodigoAcessoDTO->getStrIdDispositivoAcesso());

            $objLoginRN = new LoginRN();
            $objLoginDTO = $objLoginRN->cadastrar($objLoginDTO);
            LoginINT::redirecionarSistema($objLoginDTO);
          }
        } catch (Exception $e) {
          if (strpos($e->__toString(), CodigoAcessoRN::$MSG_CODIGO_NAO_RECONHECIDO) !== false || strpos($e->__toString(), CodigoAcessoRN::$MSG_CODIGO_INVALIDO) !== false) {
            $_SESSION['SIP_NUM_FALHA_LOGIN'] = $_SESSION['SIP_NUM_FALHA_LOGIN'] + 1;
          }
          PaginaLogin::getInstance()->processarExcecao($e);
        }
      }
      break;

    case LoginINT::$ACAO_AVISAR_2_FATORES:
      $bolDadosUsuario = false;
      $bolAviso2Fatores = true;
      break;

    case LoginINT::$ACAO_INSTRUCOES_2_FATORES:
      $bolDadosUsuario = false;
      $bolInstrucoes2Fatores = true;

      $objCodigoAcessoDTO = new CodigoAcessoDTO();
      $objCodigoAcessoDTO->setBolExclusaoLogica(false);
      $objCodigoAcessoDTO->setNumMaxRegistrosRetorno(1);
      $objCodigoAcessoDTO->retStrIdCodigoAcesso();
      $objCodigoAcessoDTO->setNumIdSistema($objSistemaDTO->getNumIdSistema());
      $objCodigoAcessoDTO->setNumIdUsuario($_SESSION['SIP_ID_USUARIO']);

      $objCodigoAcessoRN = new CodigoAcessoRN();
      if ($objCodigoAcessoRN->consultar($objCodigoAcessoDTO) != null) {
        $bolAvisoExclusaoContaApp = true;
      }

      break;

    case LoginINT::$ACAO_ATIVAR_2_FATORES:

      if (LoginINT::verificarLogin() && $_SESSION['SIP_ID_CODIGO_ACESSO_CONFIGURACAO'] != 0 && isset($_POST['txtEmail'])) {
        try {
          $objCodigoAcessoDTO = new CodigoAcessoDTO();
          $objCodigoAcessoDTO->setStrIdCodigoAcesso($_SESSION['SIP_ID_CODIGO_ACESSO_CONFIGURACAO']);
          $objCodigoAcessoDTO->setNumIdUsuario($_SESSION['SIP_ID_USUARIO']);
          $objCodigoAcessoDTO->setStrEmail($_POST['txtEmail']);

          $objCodigoAcessoRN = new CodigoAcessoRN();
          $objCodigoAcessoRN->enviarAtivacao($objCodigoAcessoDTO);
          LoginINT::adicionarMensagemEnvioLink($objCodigoAcessoDTO->getStrEmail());
        } catch (Exception $e) {
          PaginaLogin::getInstance()->processarExcecao($e);
        }
      }
      break;

    case LoginINT::$ACAO_DESATIVAR_2_FATORES:

      if ($_SESSION['SIP_ID_USUARIO'] != 0 && $_SESSION['SIP_ID_CODIGO_ACESSO_VALIDACAO'] != 0) {
        try {
          $objCodigoAcessoDTO = new CodigoAcessoDTO();
          $objCodigoAcessoDTO->setStrIdCodigoAcesso($_SESSION['SIP_ID_CODIGO_ACESSO_VALIDACAO']);
          $objCodigoAcessoDTO->setNumIdUsuario($_SESSION['SIP_ID_USUARIO']);
          $objCodigoAcessoDTO->setNumIdUsuarioDesativacao($_SESSION['SIP_ID_USUARIO']);

          $objCodigoAcessoRN = new CodigoAcessoRN();
          $objCodigoAcessoRN->enviarDesativacao($objCodigoAcessoDTO);
          LoginINT::adicionarMensagemEnvioLink($objCodigoAcessoDTO->getStrEmail());
        } catch (Exception $e) {
          PaginaLogin::getInstance()->processarExcecao($e);
        }
      }
      break;

    case LoginINT::$ACAO_CANCELAR_LIBERACOES_2_FATORES:

      $bolDadosUsuario = false;
      $bolAviso2Fatores = true;

      if ($_SESSION['SIP_ID_USUARIO'] != 0 && $_SESSION['SIP_ID_CODIGO_ACESSO_VALIDACAO'] != 0) {
        try {
          $objDispositivoAcessoDTO = new DispositivoAcessoDTO();
          $objDispositivoAcessoDTO->retStrIdDispositivoAcesso();
          $objDispositivoAcessoDTO->setNumIdUsuarioCodigoAcesso($_SESSION['SIP_ID_USUARIO']);

          $objDispositivoAcessoRN = new DispositivoAcessoRN();
          $arrObjDispositivoAcessoDTO = $objDispositivoAcessoRN->listar($objDispositivoAcessoDTO);

          $objDispositivoAcessoRN->desativar($arrObjDispositivoAcessoDTO);

          $numDispositivosCancelados = count($arrObjDispositivoAcessoDTO);
        } catch (Exception $e) {
          PaginaLogin::getInstance()->processarExcecao($e);
        }
      }
      break;


    case LoginINT::$ACAO_GERAR_2_FATORES:

      if (LoginINT::verificarLogin()) {
        $bolConfigurar2Fatores = true;
        $bolDadosUsuario = false;

        $objCodigoAcessoDTO = new CodigoAcessoDTO();
        $objCodigoAcessoDTO->setNumIdSistema($objSistemaDTO->getNumIdSistema());
        $objCodigoAcessoDTO->setStrSiglaSistema($objSistemaDTO->getStrSigla());
        $objCodigoAcessoDTO->setStrSiglaOrgaoSistema($objSistemaDTO->getStrSiglaOrgao());
        $objCodigoAcessoDTO->setNumIdUsuario($_SESSION['SIP_ID_USUARIO']);

        $objCodigoAcessoRN = new CodigoAcessoRN();
        $objCodigoAcessoRN->gerar($objCodigoAcessoDTO);

        $strQrCode = $objCodigoAcessoDTO->getStrQrCode();
        $strChaveDigitavel = $objCodigoAcessoDTO->getStrChaveDigitavel();

        $_SESSION['SIP_ID_CODIGO_ACESSO_CONFIGURACAO'] = $objCodigoAcessoDTO->getStrIdCodigoAcesso();
      }

      break;

    default:
      LoginINT::limparSession(false);
      break;
  }

  $bolCaptcha = false;
  if ($_SESSION['SIP_NUM_FALHA_LOGIN'] >= $numLoginSemCaptcha) {
    $bolCaptcha = true;
  }

  $objOrgaoDTO = new OrgaoDTO();
  $objOrgaoDTO->retNumIdOrgao();
  $objOrgaoDTO->retStrSigla();
  $objOrgaoDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);
  $objOrgaoDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);


  $objOrgaoRN = new OrgaoRN();
  $arrObjOrgaoDTO = $objOrgaoRN->listar($objOrgaoDTO);

  $numOrgaos = count($arrObjOrgaoDTO);

  if ($numOrgaos == 1) {
    $numIdOrgao = $arrObjOrgaoDTO[0]->getNumIdOrgao();
  }

  if ($bolDadosUsuario) {
    $strItensSelOrgao = InfraINT::montarSelectArrInfraDTO('null', '&nbsp;', $numIdOrgao, $arrObjOrgaoDTO, 'IdOrgao', 'Sigla');
  }

  //Teste com apenas um órgão
  //$numOrgaos = 1;

  if ($numOrgaos <= 1) {
    //se tem apenas um nao mostra lista de orgaos
    $strDisplayOrgao = 'display:none !important;';
  } else {
    $strDisplayOrgao = '';
  }

  $strJSSufixos = '';
  if ($bolConfigurar2Fatores) {
    $strSufixos = $objInfraParametro->getValor('SIP_2_FATORES_SUFIXOS_EMAIL_NAO_PERMTIDOS');
    if ($strSufixos != null) {
      $arrSufixos = explode(',', $strSufixos);
      foreach ($arrSufixos as $strSufixo) {
        $strSufixo = strtolower(trim($strSufixo));
        if ($strSufixo != '') {
          if ($strJSSufixos != '') {
            $strJSSufixos .= ',';
          }
          $strJSSufixos .= '\'' . $strSufixo . '\'';
        }
      }
    }
  }
} catch (Exception $e) {
  LoginINT::limparSession(false);
  PaginaLogin::getInstance()->processarExcecao($e);
}

PaginaLogin::getInstance()->montarDocType();
PaginaLogin::getInstance()->abrirHtml();
PaginaLogin::getInstance()->abrirHead();
PaginaLogin::getInstance()->montarMeta();
PaginaLogin::getInstance()->montarTitle($strSiglaSistema . ' / ' . $strSiglaOrgaoSistema);
PaginaLogin::getInstance()->montarStyle();
CaptchaSip::getInstance()->montarStyle();
PaginaLogin::getInstance()->abrirStyle();
?>

  @media screen and (max-width: 991.98px)  {
  .divInfraAreaTela{
  height:84vh;
  max-height:84vh;
  min-height:84vh;
  }
  }

  body{
  background-color: #f0f0f0;
  }

  .divInfraAreaTelaD{
  padding-left: 0px !important;
  }

<?
if ($objSistemaDTO->getStrLogo() != null) { ?>
  #divLogo {
  overflow:hidden;
  margin:auto;
  width:150px;
  height:150px;
  background-image: url("data:image/png;base64,<?=$objSistemaDTO->getStrLogo()?>");
  background-position: center center;
  background-repeat: no-repeat;
  border-radius: 8px;
  }
  <?
} ?>

  #divIdentificacaoSistema h3 {font-size:2rem;}

  #divIntroducao2FA p,
  #divIntroducao2FA a,
  #divValidacao2FA p
  {
  font-size:1.2em;
  }

  a.linkLogin{
  font-size:12px;
  color:#0099e5;
  }
  a.linkLogin:hover{
  color:#006699;
  }
  a.linkLogin:focus{
  outline:1px dotted #0099e5;
  }
  #divLiberar{
  flex-wrap: nowrap;
  }
  #chkLiberar{
  margin-right:5px;
  }
  #lblLiberar{
  font-size:.75rem;
  margin-left:5px;
  color:#6c757d;
  }

  #lblChaveDigitavel {color:#205d8c;font-size:.75rem;}
  #lblChaveDigitavel:hover {cursor:pointer;}

  #lblInfraCaptcha img {width:100px;height:50px;}
  #txtInfraCaptcha {max-width:100px;}

  @media screen and (min-width: 1366px) {
  #lblInfraCaptcha img {width:130px;}
  #txtInfraCaptcha {max-width:130px;}
  }

<?
PaginaLogin::getInstance()->fecharStyle();
PaginaLogin::getInstance()->montarJavaScript();
CaptchaSip::getInstance()->montarJavascript();
PaginaLogin::getInstance()->abrirJavaScript();
if (0){?> <script> <?} ?>

    $(document).ready(function () {
      new MaskedPassword(document.getElementById("pwdSenha"), '\u25CF', true, 'input-group');
    });


    function inicializar() {
      <? if ($bolDadosUsuario){ ?>
      if (document.getElementById('txtUsuario').value == '') {
        self.setTimeout('document.getElementById(\'txtUsuario\').focus()', 500);
      } else {
        self.setTimeout('document.getElementById(\'pwdSenha\').focus()', 500);
      }
      <?} else {if ($bolCodigoAcesso) { ?>
      infraDesabilitarAutoCompleteTxt(document.getElementById('txtCodigoAcesso'));
      self.setTimeout('document.getElementById(\'txtCodigoAcesso\').focus()', 500);
      <?}else {if ($bolConfigurar2Fatores){?>
      self.setTimeout('document.getElementById(\'txtEmail\').focus()', 500);
      <?}else {if ($numAcao == LoginINT::$ACAO_CANCELAR_LIBERACOES_2_FATORES){?>
      <? if ($numDispositivosCancelados == 0){ ?>
      alert('Nenhum dispositivo encontrado para cancelamento.');
      <? } else {if ($numDispositivosCancelados == 1){ ?>
      alert('Foi cancelado um dispositivo.');
      <? }else{ ?>
      alert('Foram cancelados <?=$numDispositivosCancelados?> dispositivos.');
      <? }} ?>
      <?}}}}?>
    }

    function validarCampos() {

      <? if ($bolDadosUsuario){ ?>

      if (infraTrim(document.getElementById('txtUsuario').value) == '') {
        alert('Informe o Usuário.');
        document.getElementById('txtUsuario').focus();
        return false;
      }

      if (infraTrim(document.getElementById('pwdSenha').value) == '') {
        alert('Informe a Senha.');
        document.getElementById('pwdSenha').focus();
        return false;
      }

      if (document.getElementById('selOrgao').value == 'null') {
        alert('Escolha um Órgão.');
        document.getElementById('selOrgao').focus();
        return false;
      }

      infraCriarCookie('<?=$strChaveCookieUsuario?>', document.getElementById('selOrgao').value, 3650);

      <?}?>

      <? if ($bolCodigoAcesso){ ?>
      if (infraTrim(document.getElementById('txtCodigoAcesso').value) == '') {
        alert('Informe o Código de Acesso.');
        document.getElementById('txtCodigoAcesso').focus();
        return false;
      }

      <?}?>

      <? if ($bolConfigurar2Fatores){ ?>

      var email = infraTrim(document.getElementById('txtEmail').value).toLowerCase();
      ;

      if (email == '') {
        alert('E-mail não informado.');
        document.getElementById('txtEmail').focus();
        return false;
      }

      if (!infraValidarEmail(email)) {
        alert('E-mail inválido.');
        document.getElementById('txtEmail').focus();
        return false;
      }

      var arrSufixos = [<?=$strJSSufixos?>];
      if (arrSufixos.length) {
        for (i = 0; i < arrSufixos.length; i++) {
          if (email.endsWith(arrSufixos[i])) {
            alert('Não são permitidos endereços de e-mail com o sufixo "' + arrSufixos[i] + '".');
            return false;
          }
        }
      }
      <? } ?>

      <?
      if ($_SESSION['SIP_NUM_FALHA_LOGIN'] >= $numLoginSemCaptcha) {
        CaptchaSip::getInstance()->validarOnSubmit('frmLogin');
      }
      ?>

      return true;
    }


    function acaoLogin(acao) {

      document.getElementById('hdnAcao').value = acao;

      if (acao == <?=LoginINT::$ACAO_LOGAR_SENHA?> || acao == <?=LoginINT::$ACAO_VALIDAR_CODIGO?>) {
        if (validarCampos()){

          if (document.getElementById('sbmAcessar')!=null) {
            document.getElementById('sbmAcessar').disabled = true;
          }

          if (document.getElementById('sbmValidar')!=null) {
            document.getElementById('sbmValidar').disabled = true;
          }

          return true;
        }
        return false;
      }

      if ((acao == <?=LoginINT::$ACAO_LOGAR_CONFIGURAR_2_FATORES?> || acao == <?=LoginINT::$ACAO_ATIVAR_2_FATORES?>) && !validarCampos()) {
        return false;
      }

      <? if ($bolAvisoExclusaoContaApp){ ?>
      if (acao == <?=LoginINT::$ACAO_GERAR_2_FATORES?>) {
        alert('ATENÇÃO:\n\nSe a sua conta no sistema já estiver registrada no aplicativo certifique-se de excluí-la antes da leitura do código QR.');
      }
      <? } ?>

      if (acao == <?=LoginINT::$ACAO_DESATIVAR_2_FATORES?>) {
        if (!confirm('Confirma envio de e-mail com link para desativação da autenticação em 2 fatores?')) {
          return false;
        }
      }

      <? if ($bolConfigurar2Fatores){ ?>
      if (!confirm('Se você já efetuou a leitura do código QR com o aplicativo clique OK para continuar.')) {
        return false;
      }
      <?}?>

      <? if ($bolAviso2Fatores){ ?>
      if (acao == <?=LoginINT::$ACAO_CANCELAR_LIBERACOES_2_FATORES?>) {
        if (!confirm('Confirma cancelamento de todos os dispositivos que possuem liberação de uso do 2FA?')) {
          return false;
        }
      }
      <?}?>

      document.getElementById('frmLogin').submit();
      return true;
    }

    <? if ($bolConfigurar2Fatores){ ?>

    function copiarChaveConfiguracao() {
      var obj = document.getElementById('lblChaveDigitavel');
      if (obj != null) {
        var str = obj.innerText

        function listener(e) {
          e.clipboardData.setData("text/plain", str);
          e.preventDefault();
        }

        if (window.clipboardData) {
          window.clipboardData.setData("Text", str);
        } else {
          if (navigator.clipboard) {
            navigator.clipboard.writeText(str);
          } else {
            document.addEventListener("copy", listener);
            document.execCommand("copy");
            document.removeEventListener("copy", listener);
          }
        }
        alert('Chave de configuração copiada para a área de transferência.');
      }
    }
    <? } ?>

    <? if (0){ ?></script><? }
PaginaLogin::getInstance()->fecharJavaScript();
PaginaLogin::getInstance()->fecharHead();
PaginaLogin::getInstance()->abrirBody('', 'onload="inicializar();"');
?>
  <form id="frmLogin" name="frmLogin" class="h-100" action="login.php?<?=$strParametrosAction?>" method="post" onsubmit="return acaoLogin(<?=$numAcaoSubmit?>);">
    <div class="d-flex justify-content-center align-items-center h-100">
      <div id="area-cards-login" class="align-self-center col-xs-9 col-sm-8 col-md-6 col-lg-5 col-xl-4"
           style="max-width:500px">
        <div class="card" style="border-radius: .5em">
          <div class="card-body mb-2">
            <div class="row justify-content-center align-items-center">
              <div style="width:82%;">

                <div id="divIdentificacaoSistema" class="text-center mb-3">
                  <?
                  if ($objSistemaDTO->getStrLogo() == null) { ?>
                    <h3 style="padding: 30px 0;"><?=PaginaLogin::tratarHTML($strSiglaSistema . ((isset($_GET['modulo_sistema']) && $_GET['modulo_sistema'] != '') ? ' / ' . $_GET['modulo_sistema'] : ''))?></h3>
                    <?
                  } else { ?>
                    <div id="divLogo"></div>
                    <?
                  } ?>
                </div>

                <?
                if ($bolDadosUsuario) { ?>

                  <div id="divUsuario" class="input-group mb-3 d-flex">
                    <span class="input-group-prepend">
                      <span class="input-group-text"><img src="svg/usuario.svg"/></span>
                    </span>
                    <input type="text" autofocus="" id="txtUsuario" name="txtUsuario" placeholder="Usuário"
                           class="form-control" value="<?=PaginaLogin::tratarHTML($_POST['txtUsuario'])?>"
                           tabindex="<?=PaginaLogin::getInstance()->getProxTabDados()?>" maxlength="100">
                  </div>

                  <div id="divSenha" class="mb-3 d-flex">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><img src="svg/cadeado.svg"></span>
                    </span>
                    <input type="password" autofocus="" id="pwdSenha" name="pwdSenha" placeholder="Senha"
                           class="form-control" tabindex="<?=PaginaLogin::getInstance()->getProxTabDados()?>"
                           value="" autocomplete="off">
                  </div>

                  <div id="divOrgao" class="input-group mb-3 d-flex" style="<?=$strDisplayOrgao?>">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><img src="svg/orgao.svg"></span>
                    </span>
                    <select id="selOrgao" name="selOrgao" class="form-control"
                            tabindex="<?=PaginaLogin::getInstance()->getProxTabDados()?>">
                      <?=$strItensSelOrgao?>
                    </select>
                  </div>

                  <?
                  $numTabCaptcha = PaginaLogin::getInstance()->getProxTabDados();
                  ?>

                  <div class="md-form d-flex media">
                    <button type="submit" id="sbmAcessar" name="sbmAcessar"
                            class="btn text-white  flex-grow-1 infraCorBarraSuperior" style="border: none;"
                            accesskey="e" tabindex="<?=PaginaLogin::getInstance()->getProxTabDados()?>">
                      ACESSAR
                    </button>
                  </div>

                  <?
                  if ($bol2Fatores) { ?>
                    <div class="text-right mt-1">
                      <a href="#" class="pl-1 linkLogin" title="Adicione maior segurança no seu acesso ao sistema."
                         alt="Adicione maior segurança no seu acesso ao sistema."
                         onclick="acaoLogin(<?=LoginINT::$ACAO_LOGAR_CONFIGURAR_2_FATORES?>)"
                         tabindex="<?=PaginaLogin::getInstance()->getProxTabDados()?>">Autenticação em dois
                        fatores</a>
                    </div>
                    <?
                  } ?>

                  <?
                  if ($bolCaptcha) { ?>
                    <div class="pt-3 d-flex justify-content-center">
                      <?
                      CaptchaSip::getInstance()->montarHtml($numTabCaptcha); ?>
                    </div>
                    <?
                  } ?>

                  <?
                } else {
                  if ($bolCodigoAcesso) { ?>

                    <div id="divValidacao2FA">
                      <p class="text-justify text-secondary">Informe o código de 6 números gerado pelo aplicativo de
                        autenticação em 2 fatores:</p>
                    </div>

                    <div id="divCodigoAcesso" class="input-group mb-3 d-flex">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><img src="svg/cadeado.svg"></span>
                    </span>
                      <input type="text" autofocus="" id="txtCodigoAcesso" name="txtCodigoAcesso"
                             placeholder="Código de Acesso" class="form-control"
                             onkeypress="return infraMascaraNumero(this,event)" value=""
                             tabindex="<?=PaginaLogin::getInstance()->getProxTabDados()?>" maxlength="6">
                    </div>


                    <div class="d-flex flex-md-row flex-column mb-3">
                      <div id="divLiberar" class="input-group flex-grow-1 pb-md-0 pb-2 ">
                        <input type="checkbox" id="chkLiberar"
                               name="chkLiberar" <?=($strSinLiberar == 'S' ? 'checked="checked"' : '')?>
                               class="infraCheckbox"
                               tabindex="<?=PaginaLogin::getInstance()->getProxTabDados()?>"/>
                        <label id="lblLiberar" for="chkLiberar" class="infraLabelCheckbox">Não usar 2FA neste
                          dispositivo e navegador</label>
                      </div>
                    </div>

                    <?
                    $numTabCaptcha = PaginaLogin::getInstance()->getProxTabDados();
                    ?>

                    <div class="d-flex flex-md-row flex-column mb-3">
                      <a target="_blank"
                         href="controlador_externo.php?acao=instrucoes_2fa&sigla_sistema=<?=$strSiglaSistema?>&sigla_orgao_sistema=<?=$strSiglaOrgaoSistema?>"
                         tabindex="<?=PaginaLogin::getInstance()->getProxTabDados()?>" class="linkLogin">Instruções</a>
                    </div>

                    <div class="d-flex flex-md-row flex-column">
                      <div class="d-flex pb-md-0 pb-2 mr-md-1 mr-0  w-md-50 w-100">
                        <button type="submit" id="sbmValidar" name="sbmValidar" value="Validar" class="btn text-white w-100 infraCorBarraSuperior  "
                                style="border: none;" accesskey="p"
                                tabindex="<?=PaginaLogin::getInstance()->getProxTabDados()?>">
                          Validar
                        </button>
                      </div>
                      <div class="d-flex  mr-ml-1 ml-0  w-md-50 w-100">
                        <button type="button" class="btn text-white w-100 infraCorBarraSuperior"
                                onclick="acaoLogin(<?=LoginINT::$ACAO_DESATIVAR_2_FATORES?>)"
                                value="Desativar 2FA"
                                tabindex="<?=PaginaLogin::getInstance()->getProxTabDados()?>"
                                style="border: none;" accesskey="d">
                          Desativar 2FA
                        </button>
                      </div>
                    </div>

                    <?
                    if ($bolCaptcha) { ?>
                      <div class="pt-3 d-flex justify-content-center">
                        <?
                        CaptchaSip::getInstance()->montarHtml($numTabCaptcha); ?>
                      </div>
                      <?
                    } ?>

                    <?
                  } else {
                    if ($bolAviso2Fatores) { ?>

                      <div>
                        <p class="text-justify text-secondary">A autenticação em 2 fatores já está ativada para
                          sua conta no sistema.</p>
                      </div>

                      <div class="d-flex flex-md-row flex-column">
                        <div class="d-flex pb-md-1 pb-2 mr-0 w-100">
                          <button type="button" class="btn text-white w-100 infraCorBarraSuperior"
                                  onclick="acaoLogin(<?=LoginINT::$ACAO_CANCELAR_LIBERACOES_2_FATORES?>)"
                                  value="Cancelar Liberados 2FA"
                                  tabindex="<?=PaginaLogin::getInstance()->getProxTabDados()?>"
                                  style="border: none;" accesskey="d">
                            Cancelar Dispositivos Liberados
                          </button>
                        </div>
                      </div>

                      <div class="d-flex flex-md-row flex-column">
                        <div class="d-flex pb-md-1 pb-2 mr-0 w-100">
                          <button type="button" class="btn text-white w-100 infraCorBarraSuperior"
                                  onclick="acaoLogin(<?=LoginINT::$ACAO_DESATIVAR_2_FATORES?>)"
                                  value="Desativar 2FA"
                                  tabindex="<?=PaginaLogin::getInstance()->getProxTabDados()?>"
                                  style="border: none;" accesskey="d">
                            Desativar 2FA
                          </button>
                        </div>
                      </div>

                      <div class="d-flex flex-md-row flex-column">
                        <div class="d-flex pb-md-1 pb-2 mr-0 w-100">
                          <button type="button" class="btn text-white w-100 infraCorBarraSuperior"
                                  onclick="location.href='login.php?sigla_sistema=<?=PaginaLogin::tratarHTML($objSistemaDTO->getStrSigla())?>&sigla_orgao_sistema=<?=PaginaLogin::tratarHTML($objSistemaDTO->getStrSiglaOrgao())?>';"
                                  value="Voltar"
                                  tabindex="<?=PaginaLogin::getInstance()->getProxTabDados()?>"
                                  style="border: none;" accesskey="v">
                            Voltar
                          </button>
                        </div>
                      </div>


                      <?
                    } else {
                      if ($bolInstrucoes2Fatores) { ?>

                        <div id="divIntroducao2FA">
                          <p class="text-justify text-secondary">A autenticação em 2 fatores é um recurso para
                            adicionar maior segurança no seu acesso ao sistema. Ao ativá-la, qualquer tentativa
                            de login em dispositivos não liberados irá requerer também um código numérico gerado
                            por um aplicativo.</p>
                          <p class="text-justify  text-secondary">Antes de prosseguir, leia as instruções <a
                              class="linkLogin" target="_blank"
                              href="controlador_externo.php?acao=instrucoes_2fa&sigla_sistema=<?=$strSiglaSistema?>&sigla_orgao_sistema=<?=$strSiglaOrgaoSistema?>"
                              tabindex="<?=PaginaLogin::getInstance()->getProxTabDados()?>">aqui</a>.</p>
                        </div>

                        <div class="d-flex flex-md-row flex-column">
                          <div class="d-flex pb-md-0 pb-2 mr-md-1 mr-0 w-md-50 w-100">
                            <button type="button" onclick="acaoLogin(<?=LoginINT::$ACAO_GERAR_2_FATORES?>)"
                                    value="Prosseguir" class="btn text-white w-100 infraCorBarraSuperior"
                                    style="border: none;" accesskey="p"
                                    tabindex="<?=PaginaLogin::getInstance()->getProxTabDados()?>">
                              Prosseguir
                            </button>
                          </div>

                          <div class="d-flex  mr-ml-1 ml-0 w-md-50 w-100">
                            <button type="button" class="btn text-white w-100 infraCorBarraSuperior"
                                    onclick="location.href='login.php?sigla_sistema=<?=PaginaLogin::tratarHTML($objSistemaDTO->getStrSigla())?>&sigla_orgao_sistema=<?=PaginaLogin::tratarHTML($objSistemaDTO->getStrSiglaOrgao())?>';"
                                    value="Cancelar"
                                    tabindex="<?=PaginaLogin::getInstance()->getProxTabDados()?>"
                                    style="border: none;" accesskey="c">
                              Cancelar
                            </button>
                          </div>
                        </div>

                        <?
                      } else {
                        if ($bolConfigurar2Fatores) { ?>

                          <div class="mb-3 w-100" style="position: relative;">
                            <img alt="Autenticação de 2 Fatores" title="Autenticacao de 2 Fatores"
                                 src="data:image/png;base64,<?=$strQrCode?>" class="mx-auto d-block"
                                 onclick="alert('Código digit');">
                          </div>

                          <div class="mb-3 d-flex flex-row">
                            <div class="p-1"><img src="svg/2fa_chave.svg" title="Copiar Chave de Configuração"
                                                  onclick="copiarChaveConfiguracao()"/></div>
                            <div class="d-flex"><label id="lblChaveDigitavel"
                                                       onclick="copiarChaveConfiguracao()"
                                                       class="infraLabelOpcional"><?=$strChaveDigitavel?></label>
                            </div>
                          </div>

                          <div class="mb-3 w-100" style="position: relative;">
                            <a class="linkLogin" style="position: absolute;right: 0;bottom: 4px;"
                               target="_blank"
                               href="controlador_externo.php?acao=instrucoes_2fa&sigla_sistema=<?=$strSiglaSistema?>&sigla_orgao_sistema=<?=$strSiglaOrgaoSistema?>"
                               tabindex="<?=PaginaLogin::getInstance()->getProxTabDados()?>">Instruções</a>
                          </div>

                          <div id="divCodigoAcesso" class="input-group mb-3 d-flex">
                  <span class="input-group-prepend">
                      <span class="input-group-text"><img src="svg/email.svg"></span>
                  </span>
                            <input type="text" autofocus="" id="txtEmail" name="txtEmail"
                                   placeholder="E-mail pessoal" class="form-control"
                                   value="<?=PaginaSip::tratarHTML($_POST['txtEmail']);?>"
                                   tabindex="<?=PaginaLogin::getInstance()->getProxTabDados()?>">
                          </div>

                          <div class="d-flex flex-md-row flex-column">
                            <div class="d-flex pb-md-0 pb-2 mr-md-1 mr-0  w-md-50 w-100">
                              <button type="button" value="Enviar"
                                      onclick="acaoLogin(<?=LoginINT::$ACAO_ATIVAR_2_FATORES?>)"
                                      class="btn text-white w-100  infraCorBarraSuperior"
                                      style="border: none;" accesskey="e"
                                      tabindex="<?=PaginaLogin::getInstance()->getProxTabDados()?>">
                                Enviar
                              </button>
                            </div>
                            <div class="d-flex  mr-ml-1 ml-0  w-md-50 w-100">
                              <button type="button" class="btn text-white w-100 infraCorBarraSuperior"
                                      onclick="location.href='login.php?sigla_sistema=<?=PaginaLogin::tratarHTML($objSistemaDTO->getStrSigla())?>&sigla_orgao_sistema=<?=PaginaLogin::tratarHTML($objSistemaDTO->getStrSiglaOrgao())?>';"
                                      value="Cancelar"
                                      tabindex="<?=PaginaLogin::getInstance()->getProxTabDados()?>"
                                      style="border: none;" accesskey="c">
                                Cancelar
                              </button>
                            </div>
                          </div>
                          <?
                        }
                      }
                    }
                  }
                } ?>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <input type="hidden" id="hdnAcao" name="hdnAcao" value="<?=LoginINT::$ACAO_DADOS_USUARIO?>"/>
  </form>
<?
PaginaLogin::getInstance()->montarAreaDebug();
PaginaLogin::getInstance()->fecharBody();
PaginaLogin::getInstance()->fecharHtml();
?>