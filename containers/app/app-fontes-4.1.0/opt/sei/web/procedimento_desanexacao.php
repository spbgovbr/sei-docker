<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/10/2013 - criado por mga
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

  if (isset($_GET['id_procedimento_anexado'])){
    $strParametros .= '&id_procedimento_anexado='.$_GET['id_procedimento_anexado'];
  }
  
  $arrComandos = array();
  $bolReload = false;
  switch($_GET['acao']){
    
    case 'procedimento_desanexar':
    	
    	$strTitulo = 'Desanexar Processo';
    	
      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($_GET['id_procedimento']);
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($_GET['id_procedimento_anexado']);
      $objRelProtocoloProtocoloDTO->setStrMotivo($_POST['txaMotivo']);
    	    	    	    	
      if (isset($_POST['sbmSalvar'])){

        try{
          
          $objProcedimentoRN = new ProcedimentoRN();
          $objProcedimentoRN->desanexar($objRelProtocoloProtocoloDTO);

          if(PaginaSEI::getInstance()->getAcaoRetorno() == "procedimento_anexar"){
            header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.'&atualizar_arvore=1'));
            die;

          }else{
            $bolReload = true;
          }

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }      
      
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" id="sbmSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&acao_destino='.$_GET['acao'].$strParametros.PaginaSEI::montarAncora($_GET['id_procedimento_anexado'])).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
      
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
#lblMotivo {position:absolute;left:0%;top:0%;width:50%;}
#txaMotivo {position:absolute;left:0%;top:6%;width:90%;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<? if ($bolReload){ ?>
  parent.parent.location.href = "<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&id_procedimento='.$_GET['id_procedimento'])?>";
<?}?>
function inicializar(){
  document.getElementById('txaMotivo').focus();
  infraEfeitoTabelas();
}

function OnSubmitForm() {
  
  if (infraTrim(document.getElementById('txaMotivo').value)==''){
    alert('Motivo não informado.');
    document.getElementById('txaMotivo').focus();
    return false;
  }
  
  return true;
}
 
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmProcedimentoDesanexar" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>
  
 	<label id="lblMotivo" for="txaMotivo" class="infraLabelObrigatorio">Motivo:</label>
  <textarea id="txaMotivo" name="txaMotivo" rows="<?=PaginaSEI::getInstance()->isBolNavegadorFirefox()?'5':'6'?>" class="infraTextarea" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objRelProtocoloProtocoloDTO->getStrMotivo());?></textarea>
  
  <?
  PaginaSEI::getInstance()->fecharAreaDados();  
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
//PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>