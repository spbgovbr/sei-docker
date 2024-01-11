<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/12/2007 - criado por mga
*
* Versão do Gerador de Código: 1.12.0
*
* Versão no CVS: $Id$
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
    case 'procedimento_sobrestar':
      $strTitulo = 'Sobrestamento';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" id="sbmSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if ($_GET['acao_origem']=='arvore_visualizar'){
        $arrProtocolosOrigem = array($_GET['id_procedimento']);
      }else if ($_GET['acao_origem']=='procedimento_controlar'){
        $arrProtocolosOrigem = array_merge(PaginaSEI::getInstance()->getArrStrItensSelecionados('Gerados'),PaginaSEI::getInstance()->getArrStrItensSelecionados('Recebidos'),PaginaSEI::getInstance()->getArrStrItensSelecionados('Detalhado'));
      }else{
     	  if ($_POST['hdnIdProtocolos']!=''){
     	    $arrProtocolosOrigem = explode(',',$_POST['hdnIdProtocolos']);
     	  }
      }

      if (isset($_POST['sbmSalvar'])) {
        try{
		       
          $arrObjRelProtocoloProtocoloDTO = array(); 	
          foreach($arrProtocolosOrigem as $dblIdProtocolo){
  				  $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();			  
  				  $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($_POST['hdnIdProcedimentoDestino']);
  				  $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($dblIdProtocolo);
  				  $objRelProtocoloProtocoloDTO->setStrMotivo($_POST['txaMotivo']);
  				  $arrObjRelProtocoloProtocoloDTO[] = $objRelProtocoloProtocoloDTO;
          }

          $objProcedimentoRN = new ProcedimentoRN();
          $objProcedimentoRN->sobrestarRN1014($arrObjRelProtocoloProtocoloDTO);
          
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

  if (!isset($_POST['rdoSobrestar']) || $_POST['rdoSobrestar']=='S'){
    $strCheckSomenteSobrestar = 'checked="checked"';
    $strCheckSobrestarVincular = '';
  }else{
    $strCheckSomenteSobrestar = '';
    $strCheckSobrestarVincular = 'checked="checked"';
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

#divOptSomenteSobrestar {position:absolute;left:0%;top:65%;}
#divOptSobrestarVincular {position:absolute;left:0%;top:80%;}

#divProcedimentoDestino {display:none;}
#lblProcedimentoDestino {position:absolute;left:0%;top:5%;}
#txtProcedimentoDestino {position:absolute;left:0%;top:40%;width:25%;}
#btnPesquisar {position:absolute;left:26.5%;top:38%;width:10%;}
#lblIdentificacaoProcedimentoDestino {position:absolute;left:38%;top:5%;}
#txtIdentificacaoProcedimentoDestino {position:absolute;left:38%;top:40%;width:61%;}

#lblMotivo {position:absolute;left:0%;top:2%;}
#txaMotivo {position:absolute;left:0%;top:20%;width:99%;}
	
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>


var objAjaxProtocoloRI1023 = null;

function inicializar(){
	document.getElementById('txaMotivo').focus();

	if (document.getElementById('optSobrestarVincular').checked){
		document.getElementById('divProcedimentoDestino').style.display = 'block';
	}

  //LOCALIZAR PROTOCOLO
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
      document.getElementById('sbmSalvar').focus();
    }else{
      document.getElementById('hdnIdProcedimentoDestino').value = '';
      document.getElementById('txtIdentificacaoProcedimentoDestino').value = '';
    }
  };
}

function OnSubmitForm() {
  return validarSobrestarRI1013();
}

function validarSobrestarRI1013(){

	if (!document.getElementById('optSomenteSobrestar').checked && !document.getElementById('optSobrestarVincular').checked){
  	alert('Selecione uma opção.');
  	return false;
  }  

	if (document.getElementById('optSobrestarVincular').checked){  
	  if (infraTrim(document.getElementById('hdnIdProcedimentoDestino').value)==''){
	    alert('Informe o Processo para Vinculação.');
	    document.getElementById('txtProcedimentoDestino').focus();
	    return false;
	  }
  }

  if (infraTrim(document.getElementById('txaMotivo').value)==''){
    alert('Informe o Motivo.');
    document.getElementById('txaMotivo').focus();
    return false;
  }
  
  return true;
}

function mostrarProcedimentoDestino(){
  if (document.getElementById('optSobrestarVincular').checked){
   document.getElementById('divProcedimentoDestino').style.display = 'block';
   document.getElementById('txtProcedimentoDestino').focus();
  }else{
   document.getElementById('divProcedimentoDestino').style.display = 'none';
   document.getElementById('hdnIdProcedimentoDestino').value = '';
   document.getElementById('txtIdentificacaoProcedimentoDestino').value = '';
  }
}


function pesquisar(){
  if (infraTrim(document.getElementById('txtProcedimentoDestino').value)==''){
    alert('Informe o Processo para Vinculação.');
    document.getElementById('txtProcedimentoDestino').focus();
    return;
  }
  
  objAjaxProtocoloRI1023.executar();
}


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
    
    <div id="divOptSomenteSobrestar" class="infraDivRadio">
      <input type="radio" name="rdoSobrestar" id="optSomenteSobrestar" onclick="mostrarProcedimentoDestino();" value="S" <?=$strCheckSomenteSobrestar?> class="infraRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSomenteSobrestar" for="optSomenteSobrestar" class="infraLabelRadio">Somente sobrestar</label>
    </div>
    
    <div id="divOptSobrestarVincular" class="infraDivRadio">
      <input type="radio" name="rdoSobrestar" id="optSobrestarVincular" onclick="mostrarProcedimentoDestino();" value="V" <?=$strCheckSobrestarVincular?> class="infraRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSobrestarVincular" for="optSobrestarVincular" class="infraLabelRadio">Sobrestar vinculando a outro processo</label>
    </div>
    
  </div>

  <div id="divProcedimentoDestino" class="infraAreaDados" style="height:5em;">
    <label id="lblProcedimentoDestino" for="txtProcedimentoDestino" accesskey="" class="infraLabelObrigatorio">Processo para Vinculação:</label>
    <input type="text" id="txtProcedimentoDestino" name="txtProcedimentoDestino" class="infraText" value="<?=PaginaSEI::tratarHTML($_POST['txtProcedimentoDestino'])?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <button type="button" accesskey="P" name="btnPesquisar" id="btnPesquisar" onclick="pesquisar();" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>
    <label id="lblIdentificacaoProcedimentoDestino" for="txtIdentificacaoProcedimentoDestino" accesskey="" class="infraLabelObrigatorio">Tipo:</label>
    <input type="text" id="txtIdentificacaoProcedimentoDestino" name="txtIdentificacaoProcedimentoDestino" readonly="readonly" class="infraText infraReadOnly" value="<?=PaginaSEI::tratarHTML($_POST['txtIdentificacaoProcedimentoDestino'])?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  </div>
  
  <div id="divMotivo" class="infraAreaDados" style="height:10em;">
    <label id="lblMotivo" for="txaMotivo" accesskey="" class="infraLabelObrigatorio">Motivo:</label>
    <textarea id="txaMotivo" name="txaMotivo" class="infraTextarea"  rows="<?=PaginaSEI::getInstance()->isBolNavegadorFirefox()?'3':'4'?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($_POST['txaMotivo'])?></textarea>
  </div>
  
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