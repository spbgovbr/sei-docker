<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/11/2011 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id: secao_modelo_cadastro.php 10280 2015-08-27 13:32:34Z mga $
*/

try {
  require_once dirname(__FILE__).'/../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->verificarSelecao('secao_modelo_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $strParametros = '';
  if(isset($_GET['id_modelo'])){
    $strParametros .= '&id_modelo='.$_GET['id_modelo'];
  }  

  //PaginaSEI::getInstance()->salvarCamposPost(array('selModelo'));

  $objSecaoModeloDTO = new SecaoModeloDTO();

  $strDesabilitar = '';
  
  
  //$strItensSelEstilos =  EstiloINT::montarSelectNome(null,null,null);
  $arrComandos = array();

  switch($_GET['acao']){
    case 'secao_modelo_cadastrar':
      $strTitulo = 'Nova Seção';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarSecaoModelo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objSecaoModeloDTO->setNumIdSecaoModelo(null);
      $numIdModelo = $_GET['id_modelo'];
      if ($numIdModelo!==''){
        $objSecaoModeloDTO->setNumIdModelo($numIdModelo);
      }else{
        $objSecaoModeloDTO->setNumIdModelo(null);
      }
        
      $objSecaoModeloDTO->setStrNome($_POST['txtNome']);
      $objSecaoModeloDTO->setStrConteudo($_POST['txaConteudo']);
      $objSecaoModeloDTO->setNumOrdem($_POST['txtOrdem']);
      $objSecaoModeloDTO->setStrSinSomenteLeitura(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinSomenteLeitura']));
      $objSecaoModeloDTO->setStrSinAssinatura(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinAssinatura']));
      $objSecaoModeloDTO->setStrSinPrincipal(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPrincipal']));
      $objSecaoModeloDTO->setStrSinDinamica(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinDinamica']));
      $objSecaoModeloDTO->setStrSinCabecalho(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinCabecalho']));
      $objSecaoModeloDTO->setStrSinRodape(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinRodape']));
      $objSecaoModeloDTO->setStrSinHtml(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinHtml']));
      $objSecaoModeloDTO->setStrSinAtivo('S');
      
      $arrEstilos=PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnEstilosMultiplaObjeto']);
      $arrObjRelSecaoModeloEstiloDTO = array();
      foreach($arrEstilos as $numIdEstilo){
      	$objRelSecaoModeloEstiloDTO = new RelSecaoModeloEstiloDTO();
      	$objRelSecaoModeloEstiloDTO->setNumIdEstilo($numIdEstilo);
      	if ($_POST['selEstiloPadrao']==$numIdEstilo) {
      	  $objRelSecaoModeloEstiloDTO->setStrSinPadrao('S');
      	} else {
      		$objRelSecaoModeloEstiloDTO->setStrSinPadrao('N');
      	}
      	$arrObjRelSecaoModeloEstiloDTO[] = $objRelSecaoModeloEstiloDTO; 
      } 
      
      $objSecaoModeloDTO->setArrObjRelSecaoModeloEstiloDTO($arrObjRelSecaoModeloEstiloDTO);
      
      if (isset($_POST['sbmCadastrarSecaoModelo'])) {
        try{
          $objSecaoModeloRN = new SecaoModeloRN();
          $objSecaoModeloDTO = $objSecaoModeloRN->cadastrar($objSecaoModeloDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Seção "'.$objSecaoModeloDTO->getNumOrdem().'" cadastrada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.'&id_secao_modelo='.$objSecaoModeloDTO->getNumIdSecaoModelo().PaginaSEI::getInstance()->montarAncora($objSecaoModeloDTO->getNumIdSecaoModelo())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'secao_modelo_alterar':
      $strTitulo = 'Alterar Seção';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarSecaoModelo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_secao_modelo'])){
        $objSecaoModeloDTO->setNumIdSecaoModelo($_GET['id_secao_modelo']);
        $objSecaoModeloDTO->retTodos();
        $objSecaoModeloRN = new SecaoModeloRN();
        $objSecaoModeloDTO = $objSecaoModeloRN->consultar($objSecaoModeloDTO);
        if ($objSecaoModeloDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objSecaoModeloDTO->setNumIdSecaoModelo($_POST['hdnIdSecaoModelo']);
        $objSecaoModeloDTO->setNumIdModelo($_GET['id_modelo']);
        $objSecaoModeloDTO->setStrNome($_POST['txtNome']);
        $objSecaoModeloDTO->setStrConteudo($_POST['txaConteudo']);
        $objSecaoModeloDTO->setNumOrdem($_POST['txtOrdem']);
        $objSecaoModeloDTO->setStrSinSomenteLeitura(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinSomenteLeitura']));
        $objSecaoModeloDTO->setStrSinAssinatura(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinAssinatura']));
        $objSecaoModeloDTO->setStrSinPrincipal(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPrincipal']));
        $objSecaoModeloDTO->setStrSinDinamica(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinDinamica']));
        $objSecaoModeloDTO->setStrSinCabecalho(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinCabecalho']));
        $objSecaoModeloDTO->setStrSinRodape(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinRodape']));
        $objSecaoModeloDTO->setStrSinHtml(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinHtml']));
        
        $arrEstilos=PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnEstilosMultiplaObjeto']);
        $arrObjRelSecaoModeloEstiloDTO = array();
        foreach($arrEstilos as $numIdEstilo){
      	  $objRelSecaoModeloEstiloDTO = new RelSecaoModeloEstiloDTO();
      	  $objRelSecaoModeloEstiloDTO->setNumIdEstilo($numIdEstilo);
          if ($_POST['selEstiloPadrao']==$numIdEstilo) {
      	    $objRelSecaoModeloEstiloDTO->setStrSinPadrao('S');
      	  } else {
      		  $objRelSecaoModeloEstiloDTO->setStrSinPadrao('N');
      	  }
      	  $arrObjRelSecaoModeloEstiloDTO[] = $objRelSecaoModeloEstiloDTO;       
        }       
        $objSecaoModeloDTO->setArrObjRelSecaoModeloEstiloDTO($arrObjRelSecaoModeloEstiloDTO);
      }
      
      
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::getInstance()->montarAncora($objSecaoModeloDTO->getNumIdSecaoModelo())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarSecaoModelo'])) {
        try{
          $objSecaoModeloRN = new SecaoModeloRN();
          $objSecaoModeloRN->alterar($objSecaoModeloDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Seção "'.$objSecaoModeloDTO->getNumOrdem().'" alterada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::getInstance()->montarAncora($objSecaoModeloDTO->getNumIdSecaoModelo())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'secao_modelo_consultar':
      $strTitulo = 'Consultar Seção';
    //  DebugSEI::getinstance()->gravar("**".$strParametros);
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::getInstance()->montarAncora($_GET['id_secao_modelo'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objSecaoModeloDTO->setNumIdSecaoModelo($_GET['id_secao_modelo']);
      $objSecaoModeloDTO->setBolExclusaoLogica(false);
      $objSecaoModeloDTO->retTodos();
      $objSecaoModeloRN = new SecaoModeloRN();
      $objSecaoModeloDTO = $objSecaoModeloRN->consultar($objSecaoModeloDTO);
      if ($objSecaoModeloDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }
  $objModeloDTO = new ModeloDTO();
  $objModeloDTO->setBolExclusaoLogica(false);
  $objModeloDTO->retStrNome();
  $objModeloDTO->setNumIdModelo($_GET['id_modelo']);

  $objModeloRN = new ModeloRN();
  $objModeloDTO = $objModeloRN->consultar($objModeloDTO);
  $strModelo = $objModeloDTO->getStrNome();

  $idEstiloPadrao=null;
  if (isset($_GET['id_secao_modelo'])){
    $objRelSecaoModeloEstiloDTO = new RelSecaoModeloEstiloDTO();
    $objRelSecaoModeloEstiloDTO->retNumIdEstilo();
    $objRelSecaoModeloEstiloDTO->setNumIdSecaoModelo($_GET['id_secao_modelo']);
    $objRelSecaoModeloEstiloDTO->setStrSinPadrao('S');
    $objRelSecaoModeloEstiloRN = new RelSecaoModeloEstiloRN();
    $objRelSecaoModeloEstiloDTO=$objRelSecaoModeloEstiloRN->consultar($objRelSecaoModeloEstiloDTO);
    if ($objRelSecaoModeloEstiloDTO) {
    	$idEstiloPadrao=$objRelSecaoModeloEstiloDTO->getNumIdEstilo();
    }
  }
  $objEditorRN=new EditorRN();
  $objEditorDTO=new EditorDTO();

  $objEditorDTO->setStrNomeCampo('txaConteudo');
  if ($_GET['acao']=='secao_modelo_consultar') {
    $objEditorDTO->setStrSinSomenteLeitura('S');
  } else {
    $objEditorDTO->setStrSinSomenteLeitura('N');
  }
  $objEditorDTO->setNumTamanhoEditor(220);
  $objEditorDTO->setStrSinLinkSei('S');
  $retEditor = $objEditorRN->montarSimples($objEditorDTO);

  $strLinkAjaxProtocoloLinkEditor = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=protocolo_link_editor');

  $strItensSelEstilosMultipla = RelSecaoModeloEstiloINT::montarSelectNomeEstilo(null,null,$idEstiloPadrao,$objSecaoModeloDTO->getNumIdSecaoModelo());
  $strItensSelEstilos = RelSecaoModeloEstiloINT::montarSelectNomeEstilo('null','&nbsp;',$idEstiloPadrao,$objSecaoModeloDTO->getNumIdSecaoModelo());
  $strLinkEstilosMultiplaObjeto = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=estilo_selecionar&tipo_selecao=2&id_object=objLupaEstilosMultipla'.$strParametros);
  
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
#lblModelo {position:absolute;left:0%;top:0%;width:50%;}
#txtModelo {position:absolute;left:0%;top:12%;width:50%;}

#lblNome {position:absolute;left:0%;top:32%;width:25%;}
#txtNome {position:absolute;left:0%;top:44%;width:25%;}

#lblOrdem {position:absolute;left:26%;top:32%;width:5%;}
#txtOrdem {position:absolute;left:26%;top:44%;width:5%;}

#divOpcoes {position:absolute;left:0%;top:67%;width:99%;}
#divOpcoes div {float:left;display:inline;padding-right:1em;}

#lblConteudo {position:absolute;left:0%;top:86%;width:90%;}

#txaConteudo {width:99%;font-family: Courier, Courier New, monospace;}
//#ancAjuda {position:absolute;left:91%;top:28.5%;}

#lblEstilosMultiplaObjeto {position:absolute;left:0%;top:4%;width:50%;}
#selEstilosMultiplaObjeto {position:absolute;left:0%;top:16%;width:50%;}
#imgLupaEstilosMultiplaObjeto {position:absolute;left:51%;top:16%;}
#imgExcluirEstilosMultiplaObjeto {position:absolute;left:51%;top:30%;}

#lblEstiloPadrao {position:absolute;left:0%;top:62%;width:50%;}
#selEstiloPadrao {position:absolute;left:0%;top:74%;width:50%;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script>
var objLupaEstilosMultipla = null;
var reClasses=new RegExp('(<p[^>]*)class="([^"]*)"([^>]*>)','g');
function inicializar(){
  ativarDesativarCampos();
  if ('<?=$_GET['acao']?>'=='secao_modelo_cadastrar'){
    document.getElementById('txtNome').focus();
  } else if ('<?=$_GET['acao']?>'=='secao_modelo_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
       
  }
  objLupaEstilosMultipla = new infraLupaSelect('selEstilosMultiplaObjeto','hdnEstilosMultiplaObjeto','<?=$strLinkEstilosMultiplaObjeto?>');
	objLupaEstilosMultipla.processarSelecao = function(itens){
	  return true;
	};
	
	objLupaEstilosMultipla.processarRemocao = function(itens){
	  var optSelEstiloPadrao = document.getElementById('selEstiloPadrao').options;    
	  for(var i=0;i < optSelEstiloPadrao.length;i++){
	    for(var j=0;j < itens.length;j++) {
	      if (optSelEstiloPadrao[i].value==itens[j].value) {
	        optSelEstiloPadrao[i]=null;
	      }
	    } 	    
	  }
    setTimeout(function(){desativarEditor();ativarEditor();},0);
	  return true;
	};
	objLupaEstilosMultipla.finalizarSelecao = function(){
   
    var idEstiloPadrao = document.getElementById('selEstiloPadrao').value;
    var optMulti = document.getElementById('selEstilosMultiplaObjeto').options;
    infraSelectLimpar(document.getElementById('selEstiloPadrao'));

    infraSelectAdicionarOption(document.getElementById('selEstiloPadrao'),' ','null');

	  for(var i=0;i < optMulti.length;i++){
	   opt = infraSelectAdicionarOption(document.getElementById('selEstiloPadrao'),optMulti[i].text,optMulti[i].value);
	   if (opt.value == idEstiloPadrao){
	     opt.selected = true;
	   }	    
	  }
    desativarEditor();
    ativarEditor();

	};
  infraEfeitoTabelas();
  if (document.getElementById('chkSinHtml').checked) ativarEditor();

  }

objAjax = new infraAjaxComplementar(null, '<?=$strLinkAjaxProtocoloLinkEditor?>');
objAjax.limparCampo = false;
objAjax.mostrarAviso = false;
objAjax.tempoAviso = 1000;
objAjax.async = false;

objAjax.prepararExecucao = function () {
  window._idProtocolo = '';
  window._protocoloFormatado = '';
  return 'idProtocoloDigitado=' + window._procedimento + "&idProcedimento=<?=$_GET["id_procedimento"];?>&idDocumento=<?=$_GET["id_documento"];?>";
};

objAjax.processarResultado = function (arr) {
  if (arr!=null) {
    window._idProtocolo = arr['IdProtocolo'];
    window._protocoloFormatado = arr['ProtocoloFormatado'];
  }
};

var _procedimento = '';
var _idProtocolo = '';
var _protocoloFormatado = '';


function ativarDesativarCampos(){

  document.getElementById('txaConteudo').disabled=false;
  document.getElementById('chkSinPrincipal').disabled = false;
  document.getElementById('chkSinAssinatura').disabled = false;
  document.getElementById('chkSinDinamica').disabled = false;
  document.getElementById('chkSinCabecalho').disabled = false;
  document.getElementById('chkSinRodape').disabled = false;
  document.getElementById('chkSinHtml').disabled = false;
  document.getElementById('chkSinSomenteLeitura').disabled = false;
  document.getElementById('selEstilosMultiplaObjeto').disabled=false;
  document.getElementById('imgLupaEstilosMultiplaObjeto').disabled=false;
  document.getElementById('imgExcluirEstilosMultiplaObjeto').disabled=false;
  document.getElementById('selEstiloPadrao').disabled=false;
  
  if (document.getElementById('chkSinPrincipal').checked){
    
    document.getElementById('txaConteudo').disabled=false;
    document.getElementById('chkSinAssinatura').checked = false;
    document.getElementById('chkSinAssinatura').disabled = true;
    //document.getElementById('chkSinSomenteLeitura').checked = false;
    document.getElementById('chkSinSomenteLeitura').disabled = false;
    document.getElementById('selEstilosMultiplaObjeto').disabled=false;
    document.getElementById('imgLupaEstilosMultiplaObjeto').disabled=false;
    document.getElementById('imgExcluirEstilosMultiplaObjeto').disabled=false;
    //document.getElementById('selEstiloPadrao').disabled=false;
      
  }else if (document.getElementById('chkSinAssinatura').checked){
  
    document.getElementById('txaConteudo').disabled=true;
    document.getElementById('chkSinPrincipal').checked = false;
    document.getElementById('chkSinPrincipal').disabled = true;
    document.getElementById('chkSinSomenteLeitura').checked = true;
    document.getElementById('chkSinSomenteLeitura').disabled = true;
    document.getElementById('selEstilosMultiplaObjeto').disabled = true;
    document.getElementById('imgLupaEstilosMultiplaObjeto').disabled = true;
    document.getElementById('imgExcluirEstilosMultiplaObjeto').disabled = true;
    document.getElementById('selEstiloPadrao').disabled=true;
    
  }else if (document.getElementById('chkSinDinamica').checked){

    document.getElementById('chkSinSomenteLeitura').checked = true;
    document.getElementById('chkSinSomenteLeitura').disabled = true;

  }else if (document.getElementById('chkSinSomenteLeitura').checked){
  
    document.getElementById('txaConteudo').disabled=false;
    //document.getElementById('chkSinPrincipal').checked = false;
    document.getElementById('chkSinPrincipal').disabled = false;
    //document.getElementById('chkSinAssinatura').checked = false;
    document.getElementById('chkSinAssinatura').disabled = false;
    document.getElementById('selEstilosMultiplaObjeto').disabled = false;
    document.getElementById('imgLupaEstilosMultiplaObjeto').disabled = false;
    document.getElementById('imgExcluirEstilosMultiplaObjeto').disabled = false;
    document.getElementById('selEstiloPadrao').disabled=false;
  }
}
function validarCadastro() {
 
  if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('Informe o Nome.');
    document.getElementById('txtNome').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtOrdem').value)=='') {
    alert('Informe a Ordem.');
    document.getElementById('txtOrdem').focus();
    return false;
  }
  if (document.getElementById('chkSinAssinatura').checked) {
    document.getElementById('txaConteudo').value='';
    document.getElementById('chkSinSomenteLeitura').checked=true;
    document.getElementById('selEstilosMultiplaObjeto').selectedIndex=0;
    return true;       
  }
  /*
  if (document.getElementById('selEstilosMultiplaObjeto').options.length==0) {
    alert('Selecione pelo menos um Estilo.');
    document.getElementById('selEstilosMultiplaObjeto').focus();
    return false;
  }
  if (!infraSelectSelecionado('selEstiloPadrao')) {
    alert('Selecione o Estilo Padrão.');
    document.getElementById('selEstiloPadrao').focus();
    return false;
  }
  */
  return true;
}

function OnSubmitForm() {
  CKEDITOR.instances.txaConteudo.updateElement();
  return validarCadastro();
}

function exibirAjuda(){
  infraAbrirJanela('<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=ajuda_variaveis_secao_modelo')?>','janelaAjudaVariaveisModelo',800,600,'location=0,status=1,resizable=1,scrollbars=1',false);
}

function ativarEditor() {
  var selecoes=[];
  $('#selEstilosMultiplaObjeto option').each(function(){selecoes.push($(this).text());});
  var regexp=new RegExp("^(p)\.("+selecoes.join("|")+")$","i");
  var txa=$('#txaConteudo');


  txa.val(txa.val().replace(reClasses,function(m,p1,p2,p3,o,s){
    if(selecoes.indexOf(p2)==-1)
      return p1+p3;
    return m;
  }));

<?=substr($retEditor->getStrEditores(),0,-3).',"stylesheetParser_validSelectors":regexp});'?>
}

function desativarEditor(){
  CKEDITOR.instances.txaConteudo.updateElement();
  CKEDITOR.instances.txaConteudo.destroy();
}

function checkHtml() {
  if (document.getElementById('chkSinHtml').checked) {
    if (!CKEDITOR.instances.txaConteudo) ativarEditor();
  } else {
    if (CKEDITOR.instances.txaConteudo) desativarEditor();
  }
  ativarDesativarCampos();
}
//</script>
<?


PaginaSEI::getInstance()->fecharJavaScript();
echo $retEditor->getStrInicializacao();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmSecaoModeloCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('15em');
?>
  <label id="lblModelo" for="txtModelo" class="infraLabelObrigatorio">Modelo:</label>
  <input type="text" id="txtModelo" name="txtModelo" readonly="readonly" class="infraText infraReadOnly" value=" <?=PaginaSEI::tratarHTML($strModelo)?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  
  <label id="lblNome" for="txtNome" accesskey="n" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objSecaoModeloDTO->getStrNome())?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblOrdem" for="txtOrdem" accesskey="o" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">O</span>rdem:</label>
  <input type="text" id="txtOrdem" name="txtOrdem" onkeypress="return infraMascaraNumero(this, event)" class="infraText" value="<?=PaginaSEI::tratarHTML($objSecaoModeloDTO->getNumOrdem())?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <div id="divOpcoes">

    <div id="divSinCabecalho" class="infraDivCheckbox">
      <input type="checkbox" id="chkSinCabecalho" name="chkSinCabecalho" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objSecaoModeloDTO->getStrSinCabecalho())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" onclick="ativarDesativarCampos()"/>
      <label id="lblSinCabecalho" for="chkSinCabecalho" class="infraLabelCheckbox">Cabeçalho</label>
    </div>

    <div id="divSinRodape" class="infraDivCheckbox">
      <input type="checkbox" id="chkSinRodape" name="chkSinRodape" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objSecaoModeloDTO->getStrSinRodape())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" onclick="ativarDesativarCampos()"/>
      <label id="lblSinRodape" for="chkSinRodape" class="infraLabelCheckbox">Rodapé</label>
    </div>

    <div id="divSinPrincipal" class="infraDivCheckbox">
      <input type="checkbox" id="chkSinPrincipal" name="chkSinPrincipal" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objSecaoModeloDTO->getStrSinPrincipal())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" onclick="ativarDesativarCampos()"/>
      <label id="lblSinPrincipal" for="chkSinPrincipal" class="infraLabelCheckbox">Principal</label>
    </div>

    <div id="divSinAssinatura" class="infraDivCheckbox">
      <input type="checkbox" id="chkSinAssinatura" name="chkSinAssinatura" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objSecaoModeloDTO->getStrSinAssinatura())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" onclick="ativarDesativarCampos()"/>
      <label id="lblSinAssinatura" for="chkSinAssinatura" class="infraLabelCheckbox">Assinatura</label>
    </div>

    <div id="divSinSomenteLeitura" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinSomenteLeitura" name="chkSinSomenteLeitura" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objSecaoModeloDTO->getStrSinSomenteLeitura())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" onclick="ativarDesativarCampos()"/>
    <label id="lblSinSomenteLeitura" for="chkSinSomenteLeitura" class="infraLabelCheckbox">Somente Leitura</label>
    </div>

    <div id="divSinDinamica" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinDinamica" name="chkSinDinamica" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objSecaoModeloDTO->getStrSinDinamica())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" onclick="ativarDesativarCampos()"/>
    <label id="lblSinDinamica" for="chkSinDinamica" class="infraLabelCheckbox">Dinâmica</label>
    </div>

    <div id="divSinHtml" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinHtml" name="chkSinHtml" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objSecaoModeloDTO->getStrSinHtml())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" onclick="checkHtml()"/>
    <label id="lblSinHtml" for="chkSinHtml" class="infraLabelCheckbox">Conteúdo Inicial HTML</label>
    </div>
  </div> 
  
  <label id="lblConteudo" for="txaConteudo" accesskey="t" class="infraLabelOpcional">Con<span class="infraTeclaAtalho">t</span>eúdo:</label>
<?
  PaginaSEI::getInstance()->fecharAreaDados();

?>
  <div id="divComandos" style="margin-left:0px;"></div>
  <table style="width: 100%">
    <td style="width: 90%">
      <div id="divEditores" style="overflow: auto;">
      <textarea id="txaConteudo" name="txaConteudo" rows="10"  class="infraTextarea" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objSecaoModeloDTO->getStrConteudo())?></textarea>
      </div>
    </td>
    <td style="vertical-align: top;"> <a href="javascript:void(0)" id="ancAjuda" onclick="exibirAjuda();" title="Ajuda" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><img src="<?=PaginaSEI::getInstance()->getIconeAjuda()?>" class="infraImg"/></a>
    </td>
  </table>
<?
  PaginaSEI::getInstance()->abrirAreaDados('15em');
?>

  <input type="hidden" id="hdnIdSecaoModelo" name="hdnIdSecaoModelo" value="<?=$objSecaoModeloDTO->getNumIdSecaoModelo();?>" />
  <label id="lblEstilosMultiplaObjeto" for="selEstilosMultiplaObjeto" accesskey="" class="infraLabelOpcional">Estilos:</label>
  <select id="selEstilosMultiplaObjeto" name="selEstilosMultiplaObjeto" size="4" class="infraSelect" multiple="multiple" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
  <?=$strItensSelEstilosMultipla; ?>
  </select>
  <img id="imgLupaEstilosMultiplaObjeto" onclick="objLupaEstilosMultipla.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Estilos" title="Selecionar Estilos" class="infraImgNormal" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <img id="imgExcluirEstilosMultiplaObjeto" onclick="objLupaEstilosMultipla.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Estilos" title="Remover Estilos" class="infraImgNormal" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
  
  <input type="hidden" id="hdnEstilosMultiplaObjeto" name="hdnEstilosMultiplaObjeto" value="<?=$_POST['hdnEstilosMultiplaObjeto']?>" />
   
  <label id="lblEstiloPadrao" for="selEstiloPadrao" accesskey="" class="infraLabelOpcional">Estilo Padrão:</label>
  <select id="selEstiloPadrao" name="selEstiloPadrao" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelEstilos?>
  </select>
  <input type="hidden" id="hdnIdEstiloPadrao" name="hdnIdEstiloPadrao" value="" />
  
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>