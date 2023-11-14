<?

/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 02/05/2016 - criado por mga@trf4.jus.br
 *
 */

try {
  require_once dirname(__FILE__).'/../../../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }

  if(isset($_GET['id_procedimento'])){
    $strParametros .= '&id_procedimento='.$_GET['id_procedimento'];
  }

  if(isset($_GET['id_documento'])){
    $strParametros .= '&id_documento='.$_GET['id_documento'];
  }

  $arrComandos = array();

  $strLinkRetorno = null;

  switch($_GET['acao']){
    
    case 'md_abc_documento_processar':

      $strTitulo = 'Formulário Documento';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

      //Algumas variáveis de sessão disponíveis:
      //SessaoSEI::getInstance()->getNumIdUsuario()
      //SessaoSEI::getInstance()->getStrSiglaUsuario()
      //SessaoSEI::getInstance()->getStrNomeUsuario()
      //SessaoSEI::getInstance()->getNumIdOrgaoUsuario()
      //SessaoSEI::getInstance()->getStrSiglaOrgaoUsuario()
      //SessaoSEI::getInstance()->getStrDescricaoOrgaoUsuario()
      //SessaoSEI::getInstance()->getNumIdUnidadeAtual()
      //SessaoSEI::getInstance()->getStrSiglaUnidadeAtual()
      //SessaoSEI::getInstance()->getStrDescricaoUnidadeAtual()

      $strCampo1 = null;
      if (isset($_POST['txtCampo1'])){
        $strCampo1 = $_POST['txtCampo1'];
      }

      $strCampo2 = null;
      if (isset($_POST['txtCampo2'])){
        $strCampo2 = $_POST['txtCampo2'];
      }

      if (isset($_POST['sbmSalvar'])) {
        try{


          InfraDebug::getInstance()->gravar('SALVAR: CAMPO1='.$strCampo1.' CAMPO2='.$strCampo2);

          $strLinkRetorno = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem='.$_GET['acao'].'&id_procedimento='.$_GET['id_procedimento'].'&id_documento='.$_GET['id_documento'].'&montar_visualizacao=1');

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>

#lblCampo1 {position:absolute;left:0%;top:0%;width:50%;}
#txtCampo1 {position:absolute;left:0%;top:6%;width:50%;}

#lblCampo2 {position:absolute;left:0%;top:16%;width:50%;}
#txtCampo2 {position:absolute;left:0%;top:22%;width:50%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){

  <?if ($strLinkRetorno!=null){?>
  parent.document.getElementById('ifrArvore').src = '<?=$strLinkRetorno?>';
  return;
  <?}?>  

  document.getElementById('txtCampo1').focus();
}

function validarFormulario() {

  if (infraTrim(document.getElementById('txtCampo1').value)=='') {
    alert('Informe o Campo 1.');
    document.getElementById('txtCampo1').focus();
    return false;
  }

  return true;
}

function OnSubmitForm() {
  return validarFormulario();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmFormularioArvore" method="post" onsubmit="return OnSubmitForm();" action="<?=PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros))?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>

  <label id="lblCampo1" for="txtCampo1" accesskey="1" class="infraLabelObrigatorio">Campo <span class="infraTeclaAtalho">1</span>:</label>
  <input type="text" id="txtCampo1" name="txtCampo1" class="infraText" value="<?=PaginaSEI::tratarHTML($strCampo1)?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblCampo2" for="txtCampo2" accesskey="2" class="infraLabelOpcional">Campo <span class="infraTeclaAtalho">2</span>:</label>
  <input type="text" id="txtCampo2" name="txtCampo2" class="infraText" value="<?=PaginaSEI::tratarHTML($strCampo2)?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>