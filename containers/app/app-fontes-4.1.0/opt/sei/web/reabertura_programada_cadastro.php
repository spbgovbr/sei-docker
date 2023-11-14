<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 */

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();
  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore', 'pagina_simples', 'id_procedimento', 'id_protocolo','id_reabertura_programada'));

  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  if (isset($_GET['pagina_simples'])){
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  }

  $strDesabilitar = '';
  $bolExiste = false;
  $arrComandos = array();

  switch($_GET['acao']){
    case 'reabertura_programada_registrar':
      $strTitulo = 'Reabertura Programada';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmRegistrarReabertura" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

      $objReaberturaProgramadaRN = new ReaberturaProgramadaRN();

      $objReaberturaProgramadaDTO = new ReaberturaProgramadaDTO();

      $numIdReaberturaProgramada = null;
      if ($_GET['id_reabertura_programada']!='') {
        $numIdReaberturaProgramada = $_GET['id_reabertura_programada'];
      }

      $arrIdProtocolo = null;
      if (isset($_GET['id_protocolo'])) {
        $arrIdProtocolo = array($_GET['id_protocolo']);
      }else if (isset($_GET['id_procedimento'])) {
        $arrIdProtocolo = array($_GET['id_procedimento']);
      }else if ($_GET['acao_origem']=='procedimento_controlar'){
        $arrItensControleProcesso = array_merge(PaginaSEI::getInstance()->getArrStrItensSelecionados('Gerados'), PaginaSEI::getInstance()->getArrStrItensSelecionados('Recebidos'), PaginaSEI::getInstance()->getArrStrItensSelecionados('Detalhado'));
        $arrIdProtocolo = $arrItensControleProcesso;
      }else{
        $arrIdProtocolo = explode(',',$_POST['hdnIdProtocolo']);
      }

      if ($_GET['id_reabertura_programada']!='') {
        $strAncora = $_GET['id_reabertura_programada'];
      } else if ($_GET['id_protocolo']!=''){
        $strAncora = $_GET['id_protocolo'];
      }else{
        $strAncora = $arrIdProtocolo;
      }

      //if (!PaginaSEI::getInstance()->isBolArvore()) {
      //  $arrComandos[] = '<button type="button" accesskey="V" name="btnVoltar" id="btnVoltar" value="Voltar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . PaginaSEI::getInstance()->montarAncora($strAncora) . '\';" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>';
      //}else{
        $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::montarAncora($strAncora)).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
      //}

      $objReaberturaProgramadaDTO->setNumIdReaberturaProgramada($numIdReaberturaProgramada);
      $objReaberturaProgramadaDTO->setStrSiglaUsuario($_POST['txtSiglaUsuario']);
      $objReaberturaProgramadaDTO->setStrNomeUsuario($_POST['txtNomeUsuario']);
      $objReaberturaProgramadaDTO->setDtaProgramada($_POST['txtDtaProgramada']);
      $objReaberturaProgramadaDTO->setDtaPrazo($_POST['txtPrazoReaberturaProgramada']);
      $objReaberturaProgramadaDTO->setNumDias($_POST['txtDiasReaberturaProgramada']);
      $objReaberturaProgramadaDTO->setStrSinDiasUteis(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinDiasUteisReaberturaProgramada']));
      $objReaberturaProgramadaDTO->setDblIdProtocolo($arrIdProtocolo);

      if (isset($_POST['sbmRegistrarReabertura'])) {
        try{

          $ret = $objReaberturaProgramadaRN->registrar($objReaberturaProgramadaDTO);

          if (PaginaSEI::getInstance()->getAcaoRetorno()=='reabertura_programada_gerenciar' && $ret!=null){
            $strAncora = $ret->getNumIdReaberturaProgramada();
          }

          //PaginaSEI::getInstance()->setStrMensagem('Anotação registrada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::montarAncora($strAncora)));
          die;

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
  /* #frmReaberturaCadastro {max-width: 1200px;} */

  #divUsuario {max-width:1200px;<?=(!$bolExiste || count($arrIdProtocolo)>1)?'display:none;':''?>}
  #lblSiglaUsuario {position:absolute;left:0%;top:0%;width:20%;}
  #txtSiglaUsuario {position:absolute;left:0%;top:40%;width:20%;}

  #lblNomeUsuario {position:absolute;left:21%;top:0%;width:48%;}
  #txtNomeUsuario {position:absolute;left:21%;top:40%;width:48%;}

  #lblDtaProgramada {position:absolute;left:70%;top:0%;width:20%;}
  #txtDtaProgramada {position:absolute;left:70%;top:40%;width:20%;}

  <?=SeiINT::montarCssEscolhaDataCertaDiasUteis('ReaberturaProgramada');?>

  #fldPrazoReaberturaProgramada {border:0;}
  #fldPrazoReaberturaProgramada legend {display:none;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
  document.getElementById('optDataCertaReaberturaProgramada').focus();
  configurarReaberturaProgramada();
}

function OnSubmitForm() {

  if (!document.getElementById('optDataCertaReaberturaProgramada').checked && !document.getElementById('optDiasReaberturaProgramada').checked){
    alert('Selecione uma opção.');
    return false;
  }

  if (document.getElementById('optDataCertaReaberturaProgramada').checked){
    if (infraTrim(document.getElementById('txtPrazoReaberturaProgramada').value)==''){
      alert('Informe a data de reabertura.');
      document.getElementById('txtPrazoReaberturaProgramada').focus();
      return false;
    }
  }else{
    if (infraTrim(document.getElementById('txtDiasReaberturaProgramada').value)==''){
      alert('Informe o prazo em dias para reabertura.');
      document.getElementById('txtDiasReaberturaProgramada').focus();
      return false;
    }
  }

  return true;
}

<?=SeiINT::montarJavascriptEscolhaDataCertaDiasUteis('ReaberturaProgramada')?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmReaberturaCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    ?>

    <?=SeiINT::montarHtmlEscolhaDataCertaDiasUteis('ReaberturaProgramada','Reabertura Programada', $objReaberturaProgramadaDTO->getStrSinDiasUteis())?>

    <input type="hidden" id="hdnIdProtocolo" name="hdnIdProtocolo" value="<?=implode(',',$arrIdProtocolo);?>" />

    <?
    PaginaSEI::getInstance()->montarAreaDebug();
    //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>