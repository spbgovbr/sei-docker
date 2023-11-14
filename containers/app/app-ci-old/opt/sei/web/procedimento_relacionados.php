<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/01/2008 - criado por marcio_db
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

    case 'procedimento_relacionar':
      $strTitulo = 'Relacionamentos do Processo';
      
			$objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();			
  
      if (isset($_POST['sbmAdicionar'])) {
        try{		   
          
				  $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($_POST['hdnIdProtocolo']);
				  $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($_GET['id_procedimento']);
				  $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_RELACIONADO);
				  $objRelProtocoloProtocoloDTO->setStrMotivo(null);

          $objProcedimentoRN = new ProcedimentoRN();
          $objProcedimentoRN->relacionarProcedimentoRN1020($objRelProtocoloProtocoloDTO);
          
          //PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
          
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&resultado=1'.$strParametros.PaginaSEI::getInstance()->montarAncora($strId)));
          die;

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;
      
    case 'procedimento_excluir_relacionamento':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjRelProtocoloProtocoloDTO = array();
        foreach($arrStrIds as $strId){
          $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
          $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($strId);
          $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($_GET['id_procedimento']);
          $arrObjRelProtocoloProtocoloDTO[] = $objRelProtocoloProtocoloDTO;
        }
        
        if (count($arrObjRelProtocoloProtocoloDTO)!=1){
          throw new InfraException('Quantidade de relacionamentos para remoção inválida.');
        }
        
        $objProcedimentoRN = new ProcedimentoRN();
        $objProcedimentoRN->removerRelacionamentoProcedimentoRN1021($arrObjRelProtocoloProtocoloDTO[0]);
        
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&resultado=1'.$strParametros.PaginaSEI::getInstance()->montarAncora(implode(',',PaginaSEI::getInstance()->getArrStrItensSelecionados()))));
        die;

      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
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
  
  $bolAcaoRelacionar = $bolPossuiPendencia && SessaoSEI::getInstance()->verificarPermissao('procedimento_relacionar');
  $bolAcaoConsultarProcedimento = SessaoSEI::getInstance()->verificarPermissao('procedimento_consultar');
  
  //$arrComandos[] = '<button type="button" accesskey="P" name="btnPesquisar" id="btnPesquisar" onclick="pesquisar();" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
  
  if ($bolAcaoRelacionar){
    $strBotaoAdicionar = '<button type="submit" accesskey="A" name="sbmAdicionar" id="sbmAdicionar" value="Adicionar" class="infraButton"><span class="infraTeclaAtalho">A</span>dicionar</button>';
  }
  
	$objProcedimentoDTO = new ProcedimentoDTO();
	$objProcedimentoDTO->setDblIdProcedimento($_GET['id_procedimento']);
  
	$objProcedimentoRN = new ProcedimentoRN();
	$arrObjRelProtocoloProtocoloDTO = $objProcedimentoRN->listarRelacionados($objProcedimentoDTO);			
  
  $numRegistros = count($arrObjRelProtocoloProtocoloDTO);
  
  if ($numRegistros > 0){

  	//$arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    $bolCheck = true;
    
    $bolAcaoExcluirRelacionamento = SessaoSEI::getInstance()->verificarPermissao('procedimento_excluir_relacionamento');

		if ($bolAcaoExcluirRelacionamento){
			$strLinkRemover = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_excluir_relacionamento&acao_origem='.$_GET['acao'].$strParametros);
		}

    $strResultado = '';

    $strResultado .= '<table width="99%" class="infraTable" summary="'."Lista de Processos Relacionados".'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela("Processos Relacionados",$numRegistros).'</caption>';
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

      if ($arrObjRelProtocoloProtocoloDTO[$i]->getObjProtocoloDTO1()!=null){
        $objProcedimentoDTO = $arrObjRelProtocoloProtocoloDTO[$i]->getObjProtocoloDTO1();
      }else{
        $objProcedimentoDTO = $arrObjRelProtocoloProtocoloDTO[$i]->getObjProtocoloDTO2();
      }
      
      //$objProcedimentoDTO = $arrObjProcedimentoDTO[$i];
      
      $strId = $objProcedimentoDTO->getDblIdProcedimento();
      
      if ($bolCheck){
        $strResultado .= '<td valign="top" style="display:none">'.PaginaSEI::getInstance()->getTrCheck($i,$strId,$strProcedimento).'</td>';
      }
      
  	  $strClassRelacionamento = '';
  	  if ($objProcedimentoDTO->getStrSinAberto()=='S'){
  	    $strClassRelacionamento = 'protocoloAberto';
  	  }else{
  	    $strClassRelacionamento = 'protocoloFechado';
  	  }
      $strResultado .= '<td align="center"><a target="_blank" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=procedimento_visualizar&id_procedimento='.$objProcedimentoDTO->getDblIdProcedimento()).'" title="'.PaginaSEI::tratarHTML($objProcedimentoDTO->getStrNomeTipoProcedimento()).'" class="'.$strClassRelacionamento.'">'.$objProcedimentoDTO->getStrProtocoloProcedimentoFormatado().'</a></td>';
      $strResultado .= '<td align="center"><a alt="'.$arrObjRelProtocoloProtocoloDTO[$i]->getStrNomeUsuario().'" title="'.$arrObjRelProtocoloProtocoloDTO[$i]->getStrNomeUsuario().'" class="ancoraSigla">'.$arrObjRelProtocoloProtocoloDTO[$i]->getStrSiglaUsuario().'</a></td>';
      $strResultado .= '<td align="center"><a alt="'.$arrObjRelProtocoloProtocoloDTO[$i]->getStrDescricaoUnidade().'" title="'.$arrObjRelProtocoloProtocoloDTO[$i]->getStrDescricaoUnidade().'" class="ancoraSigla">'.$arrObjRelProtocoloProtocoloDTO[$i]->getStrSiglaUnidade().'</a></td>';
      $strResultado .= '<td align="center">'.$arrObjRelProtocoloProtocoloDTO[$i]->getDthAssociacao().'</td>';
      $strResultado .= '<td align="center">';

      if($bolAcaoExcluirRelacionamento && $arrObjRelProtocoloProtocoloDTO[$i]->getNumIdUnidade()==SessaoSEI::getInstance()->getNumIdUnidadeAtual()){
        $strResultado .= '<a href="#ID-'.$strId.'"  onclick="acaoRemover(\''.$strId.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::PROCESSO_REMOVER_RELACIONAMENTO.'" title="Remover Relacionamento" alt="Remover Relacionamento" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td>';
      
      $strResultado .= '</tr>'."\n";      
    }
    $strResultado .= '</table>';
  }
  
  //$arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.$strAncora)).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
   
  $strLinkMontarArvore = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem='.$_GET['acao'].'&id_procedimento='.$_GET['id_procedimento'].'&montar_visualizacao=0');
  
	
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

#lblProtocolo {position:absolute;left:0%;top:0%;}
#txtProtocolo {position:absolute;left:0%;top:35%;width:25%;}
#btnPesquisar {position:absolute;left:26%;top:28%;width:10%;}
#lblIdentificacaoProtocolo {position:absolute;left:37%;top:00%;}
#txtIdentificacaoProtocolo {position:absolute;left:37%;top:35%;width:50%;}
#sbmAdicionar {position:absolute;left:89%;top:35%;width:10%;visibility:hidden;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

var objAjaxProtocoloRI1023 = null;

function inicializar(){	
  
  //atualiza árvore para mostrar o relacionamento
  <?if (($_GET['acao_origem']=='procedimento_relacionar' || $_GET['acao_origem']=='procedimento_excluir_relacionamento') && $_GET['resultado']=='1') { ?>
    parent.document.getElementById('ifrArvore').src = '<?=$strLinkMontarArvore?>';
  <?}?>
    
	document.getElementById('txtProtocolo').focus();
     
  objAjaxProtocoloRI1023 = new infraAjaxComplementar('txtProtocolo','<?=$strLinkAjaxProtocoloRI1023?>');
  objAjaxProtocoloRI1023.limparCampo = false;
  objAjaxProtocoloRI1023.mostrarAviso = false;
	objAjaxProtocoloRI1023.tempoAviso = 1000;  
  
  objAjaxProtocoloRI1023.prepararExecucao = function(){
    return '&idProcedimento='+document.getElementById('txtProtocolo').value;
  };
  
  objAjaxProtocoloRI1023.processarErro = function(){
    document.getElementById('txtProtocolo').focus();
  }
  
  objAjaxProtocoloRI1023.processarResultado = function(arr){
    if (arr!=null){
      document.getElementById('hdnIdProtocolo').value = arr['IdProcedimento'];
      document.getElementById('txtProtocolo').value = arr['ProtocoloProcedimentoFormatado'];
      document.getElementById('txtIdentificacaoProtocolo').value = arr['NomeTipoProcedimento'];
      document.getElementById('sbmAdicionar').style.visibility = 'visible';
      document.getElementById('sbmAdicionar').focus();
    }else{
      document.getElementById('hdnIdProtocolo').value = '';
      document.getElementById('txtIdentificacaoProtocolo').value = '';
      document.getElementById('sbmAdicionar').style.visibility = 'hidden';
    }
  };

  if (document.getElementById('hdnIdProtocolo').value!=''){
    document.getElementById('sbmAdicionar').style.visibility = 'visible';
    document.getElementById('sbmAdicionar').focus();
  }

  infraEfeitoTabelas();  
}


<?if ($bolAcaoExcluirRelacionamento){?>
function acaoRemover(id){
  if (confirm("Confirma exclusão do relacionamento?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmProcedimentoListarRelacionamentos').action='<?=$strLinkRemover?>';
    document.getElementById('frmProcedimentoListarRelacionamentos').submit();
  }
}
<?}?>

function OnSubmitForm() {
  return validarRelacionamentoProcedimento();
}

function validarRelacionamentoProcedimento(){

  if (infraTrim(document.getElementById('hdnIdProtocolo').value)==''){
    alert('Pesquise o Processo Destino.');
    document.getElementById('txtProtocolo').focus();
    return false;
  }
  
	
  return true;
}

function pesquisar(){
  if (infraTrim(document.getElementById('txtProtocolo').value)==''){
    alert('Informe número do processo.');
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
<form id="frmProcedimentoListarRelacionamentos" method="post" onsubmit="return OnSubmitForm();"  action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>
  <label id="lblProtocolo" for="txtProtocolo" accesskey="" class="infraLabelObrigatorio">Processo Destino:</label>
  <input type="text" id="txtProtocolo" name="txtProtocolo" class="infraText" value="<?=PaginaSEI::tratarHTML($_POST['txtProtocolo'])?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <button type="button" accesskey="P" name="btnPesquisar" id="btnPesquisar" onclick="pesquisar();" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>
  
  <label id="lblIdentificacaoProtocolo" for="txtIdentificacaoProtocolo" accesskey="" class="infraLabelObrigatorio">Tipo:</label>
  <input type="text" id="txtIdentificacaoProtocolo" name="txtIdentificacaoProtocolo" readonly="readonly" class="infraText infraReadOnly" value="<?=PaginaSEI::tratarHTML($_POST['txtIdentificacaoProtocolo'])?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  
  <?=$strBotaoAdicionar?>
  
	<input type="hidden" id="hdnIdProtocolo" name="hdnIdProtocolo" value="<?=$_POST['hdnIdProtocolo']?>" />  

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