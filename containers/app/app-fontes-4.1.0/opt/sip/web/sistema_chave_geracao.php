<?

/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 18/10/2019 - criado por mga
 *
 */
try {
  require_once dirname(__FILE__) . '/Sip.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(false);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSip::getInstance()->validarLink();

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  PaginaSip::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  $arrComandos = array();

  switch ($_GET['acao']) {
    case 'sistema_gerar_chave_acesso':

      $strTitulo = 'Geração de Chave de Acesso';

      $objSistemaDTO = new SistemaDTO();
      $objSistemaDTO->setNumIdSistema($_GET['id_sistema']);

      $objSistemaRN = new SistemaRN();
      $objSistemaDTO = $objSistemaRN->gerarChaveAcesso($objSistemaDTO);

      //$arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" id="sbmSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      //$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&acao_destino='.$_GET['acao'].$strParametros)).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
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
  #lblAviso {color:red;font-size:14px;}
  #lblChaveAcesso {}
  #txtChaveAcesso {width:80%;}
  #btnCopiar {}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

  function inicializar(){
  var imgChaveAcesso = window.parent.document.getElementById('imgChaveAcesso<?=$_GET['id_sistema']?>');
  if (imgChaveAcesso != null){
  imgChaveAcesso.src = '<?=PaginaSip::getInstance()->getDiretorioSvgLocal()?>/chave_laranja.svg';
  }
  }

  function OnSubmitForm() {
  return true;
  }

  function copiar() {
  var copyText = document.getElementById("txtChaveAcesso");
  copyText.select();
  document.execCommand("copy");
  }

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmSistemaGerarChave" method="post" onsubmit="return OnSubmitForm();"
        action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    //PaginaSip::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSip::getInstance()->montarAreaValidacao();
    PaginaSip::getInstance()->abrirAreaDados('10em');
    ?>

    <label id="lblAviso" class="infraLabelOpcional">Uma nova chave de acesso foi gerada e estará disponível para cópia
      somente neste momento.</label>
    <br><br>
    <label id="lblChaveAcesso" for="txtChaveAcesso" class="infraLabelOpcional">Chave:&nbsp;</label>
    <input type="text" id="txtChaveAcesso" name="txtChaveAcesso" readonly="readonly" class="infraText infraReadOnly"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"
           value="<?=PaginaSip::tratarHTML($objSistemaDTO->getStrChaveCompleta());?>"></input>&nbsp;
    <button type="button" id="btnCopiar" onclick="copiar()" value="Copiar" class="infraButton"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">Copiar
    </button>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    //PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
//PaginaSip::getInstance()->montarAreaDebug();
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>