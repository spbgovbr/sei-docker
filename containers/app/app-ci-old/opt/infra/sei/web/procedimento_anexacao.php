<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 14/10/2013 - criado por mga
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
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  
  $strAncora = PaginaSEI::getInstance()->montarAncora($_GET['id_procedimento']);
  
  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }
  
  $strParametros .= "&id_procedimento=".$_GET['id_procedimento'];

  switch($_GET['acao']){

    case 'procedimento_anexar':
      $strTitulo = 'Anexação de Processos';
      
			$objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();			
  
      if (isset($_POST['sbmAnexar'])) {
        try{		   
          
				  $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($_GET['id_procedimento']);
				  $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($_POST['hdnIdProtocolo']);

          $objProcedimentoRN = new ProcedimentoRN();
          $objProcedimentoRN->anexar($objRelProtocoloProtocoloDTO);
          
          //PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
          
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&resultado=1'.$strParametros));
          die;

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $objPesquisaPendenciaDTO = new PesquisaPendenciaDTO();
  $objPesquisaPendenciaDTO->setDblIdProtocolo($_GET['id_procedimento']);
  $objPesquisaPendenciaDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
  $objPesquisaPendenciaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());    
  
  $objAtividadeRN = new AtividadeRN();
  $arr = $objAtividadeRN->listarPendenciasRN0754($objPesquisaPendenciaDTO);
  
  $bolPossuiPendencia = false;
  if (count($arr)>0){
    $bolPossuiPendencia = true;
  }
  
  $arrComandos = array();
  
  $bolAcaoAnexar = $bolPossuiPendencia && SessaoSEI::getInstance()->verificarPermissao('procedimento_anexar');
  $bolAcaoConsultarProcedimento = SessaoSEI::getInstance()->verificarPermissao('procedimento_consultar');
  
  //$arrComandos[] = '<button type="button" accesskey="P" name="btnPesquisar" id="btnPesquisar" onclick="pesquisar();" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
  
  if ($bolAcaoAnexar){
    $strBotaoAnexar = '<button type="submit" accesskey="A" name="sbmAnexar" id="sbmAnexar" value="Anexar" class="infraButton"><span class="infraTeclaAtalho">A</span>nexar</button>';
  }
  
	$objProcedimentoDTO = new ProcedimentoDTO();
	$objProcedimentoDTO->setDblIdProcedimento($_GET['id_procedimento']);
  
	$objProcedimentoRN = new ProcedimentoRN();
	$arrObjRelProtocoloProtocoloDTO = $objProcedimentoRN->listarAnexados($objProcedimentoDTO);			
  
  $numRegistros = count($arrObjRelProtocoloProtocoloDTO);
  
  if ($numRegistros > 0){

  	//$arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    $bolCheck = true;
    
    $bolAcaoDesanexarProcedimento = SessaoSEI::getInstance()->verificarPermissao('procedimento_desanexar');

    $strResultado = '';

    $strResultado .= '<table width="99%" class="infraTable" summary="'."Lista de Processos Anexados".'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela("Processos Anexados",$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%" style="display:none">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }

    $strResultado .= '<th class="infraTh">Processo</th>'."\n";
    $strResultado .= '<th width="20%" class="infraTh">Usuário</th>'."\n";
    $strResultado .= '<th width="20%" class="infraTh">Unidade</th>'."\n";
    $strResultado .= '<th width="20%" class="infraTh">Data/Hora</th>'."\n";
    $strResultado .= '<th width="10%" class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    
    for($i = 0;$i < $numRegistros; $i++){
    	
 	    $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';

      $strResultado .= $strCssTr;
            
      $strId = $arrObjRelProtocoloProtocoloDTO[$i]->getDblIdProtocolo2();
      
      if ($bolCheck){
        $strResultado .= '<td valign="top" style="display:none">'.PaginaSEI::getInstance()->getTrCheck($i,$strId,$strProcedimento).'</td>';
      }
      
      $strResultado .= '<td align="center"><a target="_blank" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=procedimento_visualizar&id_procedimento='.$arrObjRelProtocoloProtocoloDTO[$i]->getDblIdProtocolo2()).'" title="'.PaginaSEI::tratarHTML($arrObjRelProtocoloProtocoloDTO[$i]->getObjProtocoloDTO2()->getStrNomeTipoProcedimento()).'" class="protocoloNormal">'.$arrObjRelProtocoloProtocoloDTO[$i]->getObjProtocoloDTO2()->getStrProtocoloProcedimentoFormatado().'</a></td>';
      $strResultado .= '<td align="center"><a alt="'.$arrObjRelProtocoloProtocoloDTO[$i]->getStrNomeUsuario().'" title="'.$arrObjRelProtocoloProtocoloDTO[$i]->getStrNomeUsuario().'" class="ancoraSigla">'.$arrObjRelProtocoloProtocoloDTO[$i]->getStrSiglaUsuario().'</a></td>';
      $strResultado .= '<td align="center"><a alt="'.$arrObjRelProtocoloProtocoloDTO[$i]->getStrDescricaoUnidade().'" title="'.$arrObjRelProtocoloProtocoloDTO[$i]->getStrDescricaoUnidade().'" class="ancoraSigla">'.$arrObjRelProtocoloProtocoloDTO[$i]->getStrSiglaUnidade().'</a></td>';
      $strResultado .= '<td align="center">'.$arrObjRelProtocoloProtocoloDTO[$i]->getDthAssociacao().'</td>';
      $strResultado .= '<td align="center">';

      if($bolAcaoDesanexarProcedimento){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_desanexar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].$strParametros.'&id_procedimento_anexado='.$arrObjRelProtocoloProtocoloDTO[$i]->getDblIdProtocolo2()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::PROCESSO_DESANEXAR.'" title="Desanexar Processo" alt="Desanexar Processo" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td>';
      
      $strResultado .= '</tr>'."\n";      
    }
    $strResultado .= '</table>';
  }
  
  //$arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.$strAncora)).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
   
  $strLinkMontarArvore = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem='.$_GET['acao'].'&id_procedimento='.$_GET['id_procedimento'].'&montar_visualizacao=0');
  
	
	$strLinkAjaxProtocoloRI1023 = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=protocolo_RI1023');
	$strLinkAjaxAnexacaoVerificar = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=procedimento_anexacao_verificar');

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

#lblProtocolo {position:absolute;left:0%;top:0%;}
#txtProtocolo {position:absolute;left:0%;top:35%;width:25%;}
#btnPesquisar {position:absolute;left:26%;top:28%;width:10%;}
#lblIdentificacaoProtocolo {position:absolute;left:37%;top:00%;}
#txtIdentificacaoProtocolo {position:absolute;left:37%;top:35%;width:50%;}
#sbmAnexar {position:absolute;left:89%;top:35%;width:10%;visibility:hidden;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

var objAjaxProtocoloRI1023 = null;
var objAjaxAnexacaoVerificar = null;

function inicializar(){	
  
  //atualiza árvore para mostrar o relacionamento
  <?if (($_GET['acao_origem']=='procedimento_anexar' || $_GET['acao_origem']=='procedimento_desanexar') && $_GET['resultado']=='1') { ?>
    parent.document.getElementById('ifrArvore').src = '<?=$strLinkMontarArvore?>';
  <?}?>
    
	document.getElementById('txtProtocolo').focus();
     
  objAjaxProtocoloRI1023 = new infraAjaxComplementar('txtProtocolo','<?=$strLinkAjaxProtocoloRI1023?>');
  objAjaxProtocoloRI1023.limparCampo = false;
  objAjaxProtocoloRI1023.mostrarAviso = false;
	objAjaxProtocoloRI1023.tempoAviso = 1000;  
  
  objAjaxProtocoloRI1023.prepararExecucao = function(){
    return 'idProcedimento='+document.getElementById('txtProtocolo').value;
  };
  
  objAjaxProtocoloRI1023.processarErro = function(){
    document.getElementById('txtProtocolo').focus();
  }
  
  objAjaxProtocoloRI1023.processarResultado = function(arr){
    if (arr!=null){
      document.getElementById('hdnIdProtocolo').value = arr['IdProcedimento'];
      document.getElementById('txtProtocolo').value = arr['ProtocoloProcedimentoFormatado'];
      document.getElementById('txtIdentificacaoProtocolo').value = arr['NomeTipoProcedimento'];
      document.getElementById('sbmAnexar').style.visibility = 'visible';
      document.getElementById('sbmAnexar').focus();
    }else{
      document.getElementById('hdnIdProtocolo').value = '';
      document.getElementById('txtIdentificacaoProtocolo').value = '';
      document.getElementById('sbmAnexar').style.visibility = 'hidden';
    }
  };

  objAjaxAnexacaoVerificar = new infraAjaxComplementar(null,'<?=$strLinkAjaxAnexacaoVerificar?>');
  objAjaxAnexacaoVerificar.async = false;
  objAjaxAnexacaoVerificar.prepararExecucao = function(){
    return 'idProcedimento='+document.getElementById('hdnIdProtocolo').value;
  };
  
  objAjaxAnexacaoVerificar.processarResultado = function(arr){
    if (arr!=null){
      document.getElementById('hdnAnexacao').value = arr['Anexacao']; 
    }
  };

  if (document.getElementById('hdnIdProtocolo').value!=''){
    document.getElementById('sbmAnexar').style.visibility = 'visible';
    document.getElementById('sbmAnexar').focus();
  }

  infraEfeitoTabelas();  
}


function OnSubmitForm() {
  return validarAnexacao();
}

function validarAnexacao(){

  if (infraTrim(document.getElementById('hdnIdProtocolo').value)==''){
    alert('Pesquise o Processo Destino.');
    document.getElementById('txtProtocolo').focus();
    return false;
  }
  
  objAjaxAnexacaoVerificar.executar();
  
  var msg = 'ATENÇÃO!\n\nApós a anexação não será mais possível incluir ou alterar documentos no processo.\n\n';
  
  if (document.getElementById('hdnAnexacao').value=='N'){
    msg += "O processo a ser anexado contém documento(s) não assinado(s). Não será possível assiná-lo(s) após a anexação.\n\n";
  }

  msg += "Esta operação somente poderá ser cancelada pelo Administrador do Sistema.\n\nConfirma a anexação do processo?";
  
  return confirm(msg);
}

function pesquisar(){
  if (infraTrim(document.getElementById('txtProtocolo').value)==''){
    alert('Informe o número do processo.');
    document.getElementById('txtProtocolo').focus();
    return;
  }
  objAjaxProtocoloRI1023.executar();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmProcedimentoAnexar" method="post" onsubmit="return OnSubmitForm();"  action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>
  <label id="lblProtocolo" for="txtProtocolo" accesskey="" class="infraLabelObrigatorio">Processo:</label>
  <input type="text" id="txtProtocolo" name="txtProtocolo" class="infraText" value="<?=PaginaSEI::tratarHTML($_POST['txtProtocolo'])?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <button type="button" accesskey="P" name="btnPesquisar" id="btnPesquisar" onclick="pesquisar();" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>
  
  <label id="lblIdentificacaoProtocolo" for="txtIdentificacaoProtocolo" accesskey="" class="infraLabelObrigatorio">Tipo:</label>
  <input type="text" id="txtIdentificacaoProtocolo" name="txtIdentificacaoProtocolo" readonly="readonly" class="infraText infraReadOnly" value="<?=PaginaSEI::tratarHTML($_POST['txtIdentificacaoProtocolo'])?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  
  <?=$strBotaoAnexar?>
  
	<input type="hidden" id="hdnIdProtocolo" name="hdnIdProtocolo" value="<?=$_POST['hdnIdProtocolo']?>" />  
	<input type="hidden" id="hdnAnexacao" name="hdnAnexacao" value="<?=$_POST['hdnAnexacao']?>" />

  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>