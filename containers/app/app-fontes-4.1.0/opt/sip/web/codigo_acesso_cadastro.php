<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 26/06/2018 - criado por mga
 *
 * Versão do Gerador de Código: 1.41.0
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

  SessaoSip::getInstance()->setArrParametrosRepasseLink(array('pagina_simples', 'id_usuario'));

  PaginaSip::getInstance()->verificarSelecao('codigo_acesso_selecionar');

  if (isset($_GET['pagina_simples'])) {
    PaginaSip::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  }

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  PaginaSip::getInstance()->salvarCamposPost(array('selUsuario', 'selSistema'));

  $objCodigoAcessoDTO = new CodigoAcessoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch ($_GET['acao']) {
    case 'codigo_acesso_consultar':
      $strTitulo = 'Habilitação de Autenticação em 2 Fatores';
      //$arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSip::getInstance()->montarAncora($_GET['id_codigo_acesso'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

      if (!isset($_GET['pagina_simples'])) {
        $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      }

      $objCodigoAcessoDTO->setStrIdCodigoAcesso($_GET['id_codigo_acesso']);
      $objCodigoAcessoDTO->setBolExclusaoLogica(false);
      $objCodigoAcessoDTO->retStrIdCodigoAcesso();
      $objCodigoAcessoDTO->retStrSiglaUsuario();
      $objCodigoAcessoDTO->retStrNomeUsuario();
      $objCodigoAcessoDTO->retStrEmail();
      $objCodigoAcessoDTO->retStrSiglaSistema();
      $objCodigoAcessoDTO->retStrSiglaUsuarioDesativacao();
      $objCodigoAcessoDTO->retStrSiglaOrgaoUsuarioDesativacao();
      $objCodigoAcessoDTO->retDthGeracao();
      $objCodigoAcessoDTO->retDthAtivacao();
      $objCodigoAcessoDTO->retDthAcesso();
      $objCodigoAcessoDTO->retDthDesativacao();

      $objCodigoAcessoRN = new CodigoAcessoRN();
      $objCodigoAcessoDTO = $objCodigoAcessoRN->consultar($objCodigoAcessoDTO);
      if ($objCodigoAcessoDTO === null) {
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  if ($objCodigoAcessoDTO->getDthDesativacao() != null) {
    $strDesativacao = 'Desativada por ' . $objCodigoAcessoDTO->getStrSiglaUsuarioDesativacao() . ' / ' . $objCodigoAcessoDTO->getStrSiglaOrgaoUsuarioDesativacao() . ' em ' . $objCodigoAcessoDTO->getDthDesativacao() . '.';
  }

  $objDispositivoAcessoDTO = new DispositivoAcessoDTO();
  $objDispositivoAcessoDTO->setBolExclusaoLogica(false);
  $objDispositivoAcessoDTO->retStrUserAgent();
  $objDispositivoAcessoDTO->retDthAcesso();
  $objDispositivoAcessoDTO->retDthLiberacao();
  $objDispositivoAcessoDTO->retStrIpAcesso();
  $objDispositivoAcessoDTO->retStrSinAtivo();
  $objDispositivoAcessoDTO->setStrIdCodigoAcesso($objCodigoAcessoDTO->getStrIdCodigoAcesso());
  $objDispositivoAcessoDTO->setOrdDthAcesso(InfraDTO::$TIPO_ORDENACAO_DESC);

  $objDispositivoAcessoRN = new DispositivoAcessoRN();
  $arrObjDispositivoAcessoDTO = $objDispositivoAcessoRN->listar($objDispositivoAcessoDTO);

  $numRegistros = count($arrObjDispositivoAcessoDTO);

  $strResultado = '';

  if ($numRegistros) {
    $objInfraParametro = new InfraParametro(BancoSip::getInstance());
    $numDiasValidadeDispositivo = $objInfraParametro->getValor('SIP_2_FATORES_TEMPO_DIAS_VALIDADE_DISPOSITIVO');

    $strResultado .= '<table width="99%" class="infraTable" summary="Tabela de Dispositivos Acessados">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSip::getInstance()->gerarCaptionTabela("Dispositivos Acessados", $numRegistros) . '</caption>';
    $strResultado .= '<th class="infraTh">Identificação</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="15%">Liberação</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="15%">Último Acesso</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="15%">IP</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="15%">Situação</th>' . "\n";
    $strResultado .= '</tr>' . "\n";
    $strCssTr = '';
    for ($i = 0; $i < $numRegistros; $i++) {
      $bolExpirado = (InfraData::compararDatas(InfraData::calcularData($numDiasValidadeDispositivo, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ATRAS), $arrObjDispositivoAcessoDTO[$i]->getDthAcesso()) < 0);

      $strSituacao = 'Normal';
      if ($arrObjDispositivoAcessoDTO[$i]->getStrSinAtivo() == 'N') {
        $strSituacao = 'Cancelado';
      } else {
        if ($bolExpirado) {
          $strSituacao = 'Expirado';
        } else {
          if ($arrObjDispositivoAcessoDTO[$i]->getDthLiberacao() != null) {
            $strSituacao = 'Liberado';
          }
        }
      }


      if ($bolExpirado || $arrObjDispositivoAcessoDTO[$i]->getStrSinAtivo() == 'N') {
        $strResultado .= '<tr class="trVermelha">';
      } else {
        if (($i + 2) % 2) {
          $strResultado .= '<tr class="infraTrEscura">';
        } else {
          $strResultado .= '<tr class="infraTrClara">';
        }
      }

      $strResultado .= $strCssTr;
      $strResultado .= '<td>' . PaginaSip::tratarHTML($arrObjDispositivoAcessoDTO[$i]->getStrUserAgent()) . '</td>';
      $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjDispositivoAcessoDTO[$i]->getDthLiberacao()) . '</td>';
      $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjDispositivoAcessoDTO[$i]->getDthAcesso()) . '</td>';
      $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjDispositivoAcessoDTO[$i]->getStrIpAcesso()) . '</td>';
      $strResultado .= '<td align="center">' . $strSituacao . '</td>';
      $strResultado .= '</td></tr>' . "\n";
    }
    $strResultado .= '</table>';
  }
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

    #lblSiglaSistema {
      position: absolute;
      left: 0%;
      top: 0%;
      width: 30%;
    }

    #txtSiglaSistema {
      position: absolute;
      left: 0%;
      top: 40%;
      width: 30%;
    }

    #lblSiglaUsuario {
      position: absolute;
      left: 0%;
      top: 0%;
      width: 30%;
    }

    #txtSiglaUsuario {
      position: absolute;
      left: 0%;
      top: 40%;
      width: 30%;
    }

    #lblNomeUsuario {
      position: absolute;
      left: 0%;
      top: 0%;
      width: 73%;
    }

    #txtNomeUsuario {
      position: absolute;
      left: 0%;
      top: 40%;
      width: 73%;
    }

    #lblEmail {
      position: absolute;
      left: 0%;
      top: 0%;
      width: 48%;
    }

    #txtEmail {
      position: absolute;
      left: 0%;
      top: 40%;
      width: 48%;
    }

    #lblGeracao {
      position: absolute;
      left: 0%;
      top: 0%;
      width: 23%;
    }

    #txtGeracao {
      position: absolute;
      left: 0%;
      top: 40%;
      width: 23%;
    }

    #lblAtivacao {
      position: absolute;
      left: 25%;
      top: 0%;
      width: 23%;
    }

    #txtAtivacao {
      position: absolute;
      left: 25%;
      top: 40%;
      width: 23%;
    }

    #lblAcesso {
      position: absolute;
      left: 50%;
      top: 0%;
      width: 23%;
    }

    #txtAcesso {
      position: absolute;
      left: 50%;
      top: 40%;
      width: 23%;
    }

    #lblDesativacao {
      position: absolute;
      left: 0%;
      top: 40%;
      width: 60%;
      color: white;
      background-color: red;
      text-align: center;
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

    function OnSubmitForm() {
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
  <form id="frmCodigoAcessoCadastro" method="post" onsubmit="return OnSubmitForm();"
        action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSip::getInstance()->montarAreaValidacao();
    PaginaSip::getInstance()->abrirAreaDados('4.5em');
    ?>
    <label id="lblSiglaSistema" for="txtSiglaSistema" accesskey="" class="infraLabelObrigatorio">Sistema:</label>
    <input type="text" id="txtSiglaSistema" name="txtSiglaSistema" class="infraText"
           value="<?=PaginaSip::tratarHTML($objCodigoAcessoDTO->getStrSiglaSistema());?>"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('4.5em');
    ?>
    <label id="lblSiglaUsuario" for="txtSiglaUsuario" accesskey="" class="infraLabelObrigatorio">Sigla do
      Usuário:</label>
    <input type="text" id="txtSiglaUsuario" name="txtSiglaUsuario" class="infraText"
           value="<?=PaginaSip::tratarHTML($objCodigoAcessoDTO->getStrSiglaUsuario());?>"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('4.5em');
    ?>
    <label id="lblNomeUsuario" for="txtNomeUsuario" accesskey="" class="infraLabelObrigatorio">Nome do Usuário:</label>
    <input type="text" id="txtNomeUsuario" name="txtNomeUsuario" class="infraText"
           value="<?=PaginaSip::tratarHTML($objCodigoAcessoDTO->getStrNomeUsuario());?>"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('4.5em');
    ?>
    <label id="lblEmail" for="txtEmail" accesskey="" class="infraLabelObrigatorio">E-mail:</label>
    <input type="text" id="txtEmail" name="txtEmail" class="infraText"
           value="<?=PaginaSip::tratarHTML($objCodigoAcessoDTO->getStrEmail());?>"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('4.5em');
    ?>
    <label id="lblGeracao" for="txtGeracao" accesskey="" class="infraLabelObrigatorio">Geração:</label>
    <input type="text" id="txtGeracao" name="txtGeracao" class="infraText"
           value="<?=PaginaSip::tratarHTML($objCodigoAcessoDTO->getDthGeracao());?>"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <label id="lblAtivacao" for="txtAtivacao" accesskey="" class="infraLabelOpcional">Ativação:</label>
    <input type="text" id="txtAtivacao" name="txtAtivacao" class="infraText"
           value="<?=PaginaSip::tratarHTML($objCodigoAcessoDTO->getDthAtivacao());?>"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <label id="lblAcesso" for="txtAcesso" accesskey="" class="infraLabelOpcional">Último Acesso:</label>
    <input type="text" id="txtAcesso" name="txtAcesso" class="infraText"
           value="<?=PaginaSip::tratarHTML($objCodigoAcessoDTO->getDthAcesso());?>"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <?
    PaginaSip::getInstance()->fecharAreaDados();

    if ($strDesativacao != '') {
      PaginaSip::getInstance()->abrirAreaDados('4.5em');
      ?>
      <label id="lblDesativacao" class="infraLabelObrigatorio"><?=$strDesativacao?></label>
      <?
      PaginaSip::getInstance()->fecharAreaDados();
    }

    if ($numRegistros) {
      PaginaSip::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    } else {
      echo '<label class="infraLabelObrigatorio">Nenhum dispositivo acessado.</label>';
    }

    ?>
    <input type="hidden" id="hdnIdCodigoAcesso" name="hdnIdCodigoAcesso"
           value="<?=$objCodigoAcessoDTO->getStrIdCodigoAcesso();?>"/>
    <?
    //PaginaSip::getInstance()->montarAreaDebug();
    //PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
