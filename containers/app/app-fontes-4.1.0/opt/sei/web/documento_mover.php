<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/12/2013 - criado por mga
*
* Versão do Gerador de Código: 1.13.1
*
* Versão no CVS: $Id$
*/
try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(false);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  //PaginaSEI::getInstance()->salvarCamposPost(array('selTipoProcedimento'));

  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }

  if (isset($_GET['id_procedimento'])){
    $strParametros .= '&id_procedimento='.$_GET['id_procedimento'];
  }

  if (isset($_GET['id_documento'])){
    $strParametros .= '&id_documento='.$_GET['id_documento'];
  }

  $arrComandos = array();
  
  switch($_GET['acao']){

    case 'documento_mover':
       
      $strTitulo = 'Mover Documento';
       
      $objMoverDocumentoDTO = new MoverDocumentoDTO();
      $objMoverDocumentoDTO->setDblIdProcedimentoOrigem($_GET['id_procedimento']);
      $objMoverDocumentoDTO->setDblIdProcedimentoDestino($_POST['hdnIdProcedimentoDestino']);
      $objMoverDocumentoDTO->setDblIdDocumento($_GET['id_documento']);
      $objMoverDocumentoDTO->setStrMotivo($_POST['txaMotivo']);

      if (isset($_POST['sbmMover'])){

        try{

          $objDocumentoRN = new DocumentoRN();
          $objRelProtocoloProtocoloDTO = $objDocumentoRN->mover($objMoverDocumentoDTO);

          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&resultado=1&id_procedimento='.$_GET['id_procedimento'].'&id_documento='.$_GET['id_documento'].'-'.$objRelProtocoloProtocoloDTO->getDblIdRelProtocoloProtocolo().'&arvore='.$_GET['arvore']));
          die;
          
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
        
      }

      $strBotaoMover = '<button type="submit" accesskey="M" name="sbmMover" id="sbmMover" value="Mover" class="infraButton">&nbsp;&nbsp;&nbsp;<span class="infraTeclaAtalho">M</span>over&nbsp;&nbsp;&nbsp;</button>';
      //$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&acao_destino='.$_GET['acao'].$strParametros.PaginaSEI::montarAncora($_GET['id_procedimento_anexado']))).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }
  
  $strLinkMontarArvore = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem='.$_GET['acao'].'&id_procedimento='.$_GET['id_procedimento'].'&id_documento='.$_GET['id_documento'].'&atualizar_arvore=1');
  $strLinkAjaxProtocoloRI1023 = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=protocolo_RI1023');

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

#lblProcedimentoDestino {position:absolute;left:0%;top:0%;}
#txtProcedimentoDestino {position:absolute;left:0%;top:6%;width:25%;}
#btnPesquisar {position:absolute;left:26%;top:5.7%;width:10%;}
#lblIdentificacaoProcedimentoDestino {position:absolute;left:37%;top:0%;}
#txtIdentificacaoProcedimentoDestino {position:absolute;left:37%;top:6%;width:50%;}

#lblMotivo {position:absolute;left:0%;top:16%;width:50%;}
#txaMotivo {position:absolute;left:0%;top:22%;width:87%;}

#sbmMover {position:absolute;left:0%;top:57%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
  //atualiza árvore para mostrar o relacionamento
<?if ($_GET['acao_origem']=='documento_mover' && $_GET['resultado']=='1') { ?>
  parent.parent.document.getElementById('ifrArvore').src = '<?=$strLinkMontarArvore?>';
<?}?>

var objAjaxProtocoloRI1023 = null;
function inicializar(){
  
	document.getElementById('txtProcedimentoDestino').focus();
     
  objAjaxProtocoloRI1023 = new infraAjaxComplementar('txtProcedimentoDestino','<?=$strLinkAjaxProtocoloRI1023?>');
  objAjaxProtocoloRI1023.limparCampo = false;
  objAjaxProtocoloRI1023.mostrarAviso = false;
	objAjaxProtocoloRI1023.tempoAviso = 1000;  
  
  objAjaxProtocoloRI1023.prepararExecucao = function(){
    return 'idProcedimento='+document.getElementById('txtProcedimentoDestino').value;
  };
  
  objAjaxProtocoloRI1023.processarErro = function(){
    document.getElementById('txtProcedimentoDestino').focus();
  }
  
  objAjaxProtocoloRI1023.processarResultado = function(arr){
    if (arr!=null){
      document.getElementById('hdnIdProcedimentoDestino').value = arr['IdProcedimento'];
      document.getElementById('txtProcedimentoDestino').value = arr['ProtocoloProcedimentoFormatado'];
      document.getElementById('txtIdentificacaoProcedimentoDestino').value = arr['NomeTipoProcedimento'];
      document.getElementById('txaMotivo').focus();
    }else{
      document.getElementById('hdnIdProcedimentoDestino').value = '';
      document.getElementById('txtIdentificacaoProcedimentoDestino').value = '';
      document.getElementById('txaMotivo').value = '';
    }
  };

  infraEfeitoTabelas();
}

function OnSubmitForm() {
  
  if (infraTrim(document.getElementById('hdnIdProcedimentoDestino').value)==''){
    alert('Pesquise o Processo Destino.');
    document.getElementById('txtProcedimentoDestino').focus();
    return false;
  }
  
  if (infraTrim(document.getElementById('txaMotivo').value)==''){
    alert('Motivo não informado.');
    document.getElementById('txaMotivo').focus();
    return false;
  }
  
  return true;
}
 
function pesquisar(){
  if (infraTrim(document.getElementById('txtProcedimentoDestino').value)==''){
    alert('Informe o número do processo destino.');
    document.getElementById('txtProcedimentoDestino').focus();
    return;
  }
  objAjaxProtocoloRI1023.executar();
}

 
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
if (!($_GET['acao_origem']=='documento_mover' && $_GET['resultado']=='1')) {
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmDocumentoMover" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>
  <label id="lblProcedimentoDestino" for="txtProcedimentoDestino" accesskey="" class="infraLabelObrigatorio">Processo Destino:</label>
  <input type="text" id="txtProcedimentoDestino" name="txtProcedimentoDestino" class="infraText" value="<?=PaginaSEI::tratarHTML($_POST['txtProcedimentoDestino'])?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <button type="button" accesskey="P" name="btnPesquisar" id="btnPesquisar" onclick="pesquisar();" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>
  
  <label id="lblIdentificacaoProcedimentoDestino" for="txtIdentificacaoProcedimentoDestino" accesskey="" class="infraLabelObrigatorio">Tipo:</label>
  <input type="text" id="txtIdentificacaoProcedimentoDestino" name="txtIdentificacaoProcedimentoDestino" readonly="readonly" class="infraText infraReadOnly" value="<?=PaginaSEI::tratarHTML($_POST['txtIdentificacaoProcedimentoDestino'])?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  
 	<label id="lblMotivo" for="txaMotivo" class="infraLabelObrigatorio">Motivo:</label>
  <textarea id="txaMotivo" name="txaMotivo" rows="5" class="infraTextarea" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objMoverDocumentoDTO->getStrMotivo());?></textarea>
  
  <?=$strBotaoMover?>
  
  <input type="hidden" id="hdnIdProcedimentoDestino" name="hdnIdProcedimentoDestino" value="<?=$_POST['hdnIdProcedimentoDestino']?>" />
  
  <?
  PaginaSEI::getInstance()->fecharAreaDados();  
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
//PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
}
PaginaSEI::getInstance()->fecharHtml();
?>