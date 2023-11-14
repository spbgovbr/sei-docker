<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 23/11/2018 - criado por mga
 *
 * Versão do Gerador de Código: 1.42.0
 */

try {
  require_once dirname(__FILE__) . '/Sip.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSip::getInstance()->validarLink();

  PaginaSip::getInstance()->verificarSelecao('login_selecionar');

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  if (isset($_GET['pagina_simples'])) {
    PaginaSip::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  }

  $objLoginDTO = new LoginDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch ($_GET['acao']) {
    case 'login_consultar':
      $strTitulo = 'Consultar Acesso';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objLoginDTO->retTodos(true);
      $objLoginDTO->setStrIdLogin($_GET['id_login']);
      $objLoginDTO->setNumIdSistema($_GET['id_sistema']);
      $objLoginDTO->setNumIdUsuario($_GET['id_usuario']);
      $objLoginDTO->setBolExclusaoLogica(false);

      $objLoginRN = new LoginRN();
      $objLoginDTO = $objLoginRN->consultar($objLoginDTO);
      if ($objLoginDTO === null) {
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $strItensSelStaLogin = LoginINT::montarSelectStaLogin('', 'Todos', $objLoginDTO->getStrStaLogin());
} catch (Exception $e) {
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
<?
if (0){ ?>
  <style><?}?>

    #lblLogin {
      position: absolute;
      left: 0%;
      top: 0%;
      width: 26%;
    }

    #txtLogin {
      position: absolute;
      left: 0%;
      top: 40%;
      width: 26%;
    }

    #lblStaLogin {
      position: absolute;
      left: 29%;
      top: 0%;
      width: 26%;
      visibility: hidden;
    }

    #selStaLogin {
      position: absolute;
      left: 29%;
      top: 40%;
      width: 26%;
      visibility: hidden;
    }

    #lblSistema {
      position: absolute;
      left: 0%;
      top: 0%;
      width: 84%;
    }

    #txtSistema {
      position: absolute;
      left: 0%;
      top: 40%;
      width: 84%;
    }

    #lblUsuario {
      position: absolute;
      left: 0%;
      top: 0%;
      width: 84%;
    }

    #txtUsuario {
      position: absolute;
      left: 0%;
      top: 40%;
      width: 84%;
    }

    #lblUserAgent {
      position: absolute;
      left: 0%;
      top: 0%;
      width: 84%;
    }

    #txtUserAgent {
      position: absolute;
      left: 0%;
      top: 40%;
      width: 84%;
    }

    #lblHttpClientIp {
      position: absolute;
      left: 0%;
      top: 0%;
      width: 26%;
    }

    #txtHttpClientIp {
      position: absolute;
      left: 0%;
      top: 40%;
      width: 26%;
    }

    #lblHttpXForwardedFor {
      position: absolute;
      left: 29%;
      top: 0%;
      width: 26%;
    }

    #txtHttpXForwardedFor {
      position: absolute;
      left: 29%;
      top: 40%;
      width: 26%;
    }

    #lblRemoteAddr {
      position: absolute;
      left: 59%;
      top: 0%;
      width: 25%;
    }

    #txtRemoteAddr {
      position: absolute;
      left: 59%;
      top: 40%;
      width: 25%;
    }

    <?
    if (0){ ?></style><?
} ?>
<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>
<?
if (0){ ?>
  <script type="text/javascript"><?}?>

    function inicializar() {
      infraDesabilitarCamposAreaDados();
    }

    function validarCadastro() {
      return true;
    }

    <?
    if (0){ ?></script><?
} ?>
<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmLoginCadastro" method="post" onsubmit="return OnSubmitForm();"
        action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSip::getInstance()->montarAreaValidacao();
    PaginaSip::getInstance()->abrirAreaDados('4.5em');
    ?>
    <label id="lblLogin" for="txtLogin" accesskey="" class="infraLabelObrigatorio">Data/Hora:</label>
    <input type="text" id="txtLogin" name="txtLogin" onkeypress="return infraMascaraDataHora(this, event)"
           class="infraText" value="<?=PaginaSip::tratarHTML($objLoginDTO->getDthLogin());?>"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <label id="lblStaLogin" for="selStaLogin" accesskey="" class="infraLabelObrigatorio">Situação:</label>
    <select id="selStaLogin" name="selStaLogin" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
      <?=$strItensSelStaLogin?>
    </select>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('4.5em');
    ?>
    <label id="lblSistema" for="txtSistema" accesskey="" class="infraLabelObrigatorio">Sistema:</label>
    <input type="text" id="txtSistema" name="txtSistema" class="infraText"
           value="<?=PaginaSip::tratarHTML($objLoginDTO->getStrSiglaSistema() . ' / ' . $objLoginDTO->getStrSiglaOrgaoSistema() . ' - ' . $objLoginDTO->getStrDescricaoSistema());?>"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('4.5em');
    ?>
    <label id="lblUsuario" for="txtUsuario" accesskey="" class="infraLabelObrigatorio">Usuário:</label>
    <input type="text" id="txtUsuario" name="txtUsuario" class="infraText" value="<?=PaginaSip::tratarHTML($objLoginDTO->getStrSiglaUsuario() . ' / ' . $objLoginDTO->getStrSiglaOrgaoUsuario() . ' - ' . $objLoginDTO->getStrNomeUsuario());?>"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('4.5em');
    ?>
    <label id="lblUserAgent" for="txtUserAgent" accesskey="" class="infraLabelObrigatorio">User Agent:</label>
    <input type="text" id="txtUserAgent" name="txtUserAgent" class="infraText"
           value="<?=PaginaSip::tratarHTML($objLoginDTO->getStrUserAgent());?>"
           onkeypress="return infraMascaraTexto(this,event,500);" maxlength="500"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('4.5em');
    ?>
    <label id="lblHttpClientIp" for="txtHttpClientIp" accesskey="" class="infraLabelOpcional">Http Client IP:</label>
    <input type="text" id="txtHttpClientIp" name="txtHttpClientIp" class="infraText"
           value="<?=PaginaSip::tratarHTML($objLoginDTO->getStrHttpClientIp());?>"
           onkeypress="return infraMascaraTexto(this,event,39);" maxlength="39"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <label id="lblHttpXForwardedFor" for="txtHttpXForwardedFor" accesskey="" class="infraLabelOpcional">Http X Forwarded
      For:</label>
    <input type="text" id="txtHttpXForwardedFor" name="txtHttpXForwardedFor" class="infraText"
           value="<?=PaginaSip::tratarHTML($objLoginDTO->getStrHttpXForwardedFor());?>"
           onkeypress="return infraMascaraTexto(this,event,39);" maxlength="39"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <label id="lblRemoteAddr" for="txtRemoteAddr" accesskey="" class="infraLabelOpcional">Remote Addr:</label>
    <input type="text" id="txtRemoteAddr" name="txtRemoteAddr" class="infraText"
           value="<?=PaginaSip::tratarHTML($objLoginDTO->getStrRemoteAddr());?>"
           onkeypress="return infraMascaraTexto(this,event,39);" maxlength="39"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    ?>
    <input type="hidden" id="hdnIdLogin" name="hdnIdLogin" value="<?=$objLoginDTO->getStrIdLogin();?>"/>
    <input type="hidden" id="hdnIdSistema" name="hdnIdSistema" value="<?=$objLoginDTO->getNumIdSistema();?>"/>
    <input type="hidden" id="hdnIdUsuario" name="hdnIdUsuario" value="<?=$objLoginDTO->getNumIdUsuario();?>"/>
    <?
    //PaginaSip::getInstance()->montarAreaDebug();
    //PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
