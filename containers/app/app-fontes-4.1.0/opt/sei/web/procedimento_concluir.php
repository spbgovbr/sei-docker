<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////
 
  SessaoSEI::getInstance()->validarLink();

  //PaginaSEI::getInstance()->verificarSelecao('rel_protocolo_protocolo_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  
  $arrComandos = array();
  
  $strParametros = '';
  
  if (isset($_GET['id_procedimento'])){
    $strParametros .= "&id_procedimento=".$_GET['id_procedimento'];
  }
  
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }

  switch($_GET['acao']){
    case 'procedimento_concluir':
      $strTitulo = 'Conclusão de Processo';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" id="sbmSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

      $objConcluirProcessoDTO = new ConcluirProcessoDTO();

      if ($_GET['acao_origem']=='arvore_visualizar'){
        $arrProtocolosOrigem = array($_GET['id_procedimento']);
      }else if ($_GET['acao_origem']=='procedimento_controlar'){
        $arrProtocolosOrigem = array_merge(PaginaSEI::getInstance()->getArrStrItensSelecionados('Gerados'),PaginaSEI::getInstance()->getArrStrItensSelecionados('Recebidos'),PaginaSEI::getInstance()->getArrStrItensSelecionados('Detalhado'));
      }else{
     	  if ($_POST['hdnIdProtocolos']!=''){
     	    $arrProtocolosOrigem = explode(',',$_POST['hdnIdProtocolos']);
     	  }
      }

      $arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::montarAncora($arrProtocolosOrigem)).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objConcluirProcessoDTO->setDblIdProcedimento($arrProtocolosOrigem);
      $objConcluirProcessoDTO->setDtaPrazoReaberturaProgramada($_POST['txtPrazoReaberturaProgramada']);
      $objConcluirProcessoDTO->setNumDiasReaberturaProgramada($_POST['txtDiasReaberturaProgramada']);
      $objConcluirProcessoDTO->setStrSinDiasUteisReaberturaProgramada(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinDiasUteisReaberturaProgramada']));

      if (isset($_POST['sbmSalvar'])) {
        try{

          $objProcedimentoRN = new ProcedimentoRN();
          $objProcedimentoRN->concluir($objConcluirProcessoDTO);
          
          PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&atualizar_arvore=1'.$strParametros));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  if (!isset($_POST['rdoConcluir']) || $_POST['rdoConcluir']=='S'){
    $strCheckSomenteConcluir = 'checked="checked"';
    $strCheckConcluirAgendar = '';
  }else{
    $strCheckSomenteConcluir = '';
    $strCheckConcluirAgendar = 'checked="checked"';
  }

  $strItensSelProcedimentos = ProcedimentoINT::conjuntoCompletoFormatadoRI0903($arrProtocolosOrigem);
	$strLinkAjaxProtocoloRI1023 = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=protocolo_RI1023');

	$strIdProtocolos = implode(',',$arrProtocolosOrigem);
	
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

#lblProcedimentos {position:absolute;left:0%;top:0%;}
#selProcedimentos {position:absolute;left:0%;top:12%;width:99%;}

#divOptSomenteConcluir {position:absolute;left:0%;top:65%;}
#divOptConcluirAgendar {position:absolute;left:0%;top:80%;}

<?=SeiINT::montarCssEscolhaDataCertaDiasUteis('ReaberturaProgramada');?>

#divReaberturaProgramada {display:none;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>


function inicializar(){
  mostrarDataProgramada();
  configurarReaberturaProgramada();
  document.getElementById('sbmSalvar').focus();
}

function OnSubmitForm() {
  return validarConcluir();
}

function validarConcluir(){

  if (!document.getElementById('optSomenteConcluir').checked && !document.getElementById('optConcluirAgendar').checked){
    alert('Selecione uma opção.');
    return false;
  }

	if (document.getElementById('optConcluirAgendar').checked){

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
  }

  return true;
}

function mostrarDataProgramada(){
  if (document.getElementById('optConcluirAgendar').checked){
   document.getElementById('divReaberturaProgramada').style.display = 'block';
  }else{
   document.getElementById('divReaberturaProgramada').style.display = 'none';
  }
}

<?=SeiINT::montarJavascriptEscolhaDataCertaDiasUteis('ReaberturaProgramada')?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmDesentranharDocumento" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
?>
  <div id="divGeral" class="infraAreaDados" style="height:15em;">
   	<label id="lblProcedimentos" for="selProcedimentos" class="infraLabelObrigatorio">Processos:</label>
    <select id="selProcedimentos" name="selProcedimentos" size="4" class="infraSelect" multiple="multiple" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelProcedimentos?>
    </select>
    
    <div id="divOptSomenteConcluir" class="infraDivRadio">
      <input type="radio" name="rdoConcluir" id="optSomenteConcluir" onclick="mostrarDataProgramada();" value="S" <?=$strCheckSomenteConcluir?> class="infraRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSomenteConcluir" for="optSomenteConcluir" class="infraLabelRadio">Somente concluir</label>
    </div>
    
    <div id="divOptConcluirAgendar" class="infraDivRadio">
      <input type="radio" name="rdoConcluir" id="optConcluirAgendar" onclick="mostrarDataProgramada();" value="V" <?=$strCheckConcluirAgendar?> class="infraRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblConcluirAgendar" for="optConcluirAgendar" class="infraLabelRadio">Concluir e agendar reabertura na unidade</label>
    </div>
    
  </div>

  <?=SeiINT::montarHtmlEscolhaDataCertaDiasUteis('ReaberturaProgramada','Reabertura Programada', $objConcluirProcessoDTO->getStrSinDiasUteisReaberturaProgramada())?>

  <input type="hidden" id="hdnIdProtocolos" name="hdnIdProtocolos" value="<?=$strIdProtocolos;?>" />
	<input type="hidden" id="hdnIdProcedimentoDestino" name="hdnIdProcedimentoDestino" value="<?=$_POST['hdnIdProcedimentoDestino']?>" />  
	
  <?
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>