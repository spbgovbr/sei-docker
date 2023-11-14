<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/10/2010 - criado por Alexandre_db
*
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

    $strParametros = '';
    if(isset($_GET['arvore'])){
        PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
        $strParametros .= '&arvore='.$_GET['arvore'];
    }

    if (isset($_GET['id_procedimento'])){
        $strParametros .= "&id_procedimento=".$_GET['id_procedimento'];
    }

    if (isset($_GET['id_documento'])){
        $strParametros .= "&id_documento=".$_GET['id_documento'];
    }

    if (isset($_GET['id_documento_edoc'])){
        $strParametros .= "&id_documento_edoc=".$_GET['id_documento_edoc'];
    }

    $arrComandos = array();
    
    $strMostrarDocumentos = 'visibility:hidden;';
    $strMostrarOpcaoRelacionados = 'visibility:hidden;';
    $strCheckRelacionados = '';
    
    $bolDuplicacaoOK = false;
    
    switch($_GET['acao']){

        case 'procedimento_duplicar':

          $strTitulo = 'Duplicar Processo';

          $strIdInteressado = $_POST['hdnIdInteressado'];
          $strNomeInteressado = $_POST['txtInteressado'];
          
   				$objProcedimentoDuplicarDTO = new ProcedimentoDuplicarDTO();
          $objProcedimentoDuplicarDTO->setDblIdProcedimento($_GET['id_procedimento']);
      		$objProcedimentoDuplicarDTO->setNumIdInteressado($_POST['hdnIdInteressado']);
      		$objProcedimentoDuplicarDTO->setStrSinProcessosRelacionados(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinProcessosRelacionados']));
          $objProcedimentoDuplicarDTO->setArrIdDocumentosProcesso(PaginaSEI::getInstance()->getArrStrItensSelecionados());
					
          if ($_GET['id_procedimento']!=''){
              
              $strMostrarDocumentos = '';
              
              $arrComandos[] = '<button type="button" accesskey="u" id="btnDuplicarProcesso" name="btnDuplicarProcesso" value="Duplicar" onclick="duplicar();" class="infraButton">D<span class="infraTeclaAtalho">u</span>plicar</button>';
              $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.$strAncora).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
                     
              $objProcedimentoDTO = new ProcedimentoDTO();
              $objProcedimentoDTO->retNumIdTipoProcedimento();
              $objProcedimentoDTO->retStrDescricaoProtocolo();
              $objProcedimentoDTO->setDblIdProcedimento($_GET['id_procedimento']);
              $objProcedimentoDTO->setStrSinDocTodos('S');
  
              $objProcedimentoRN = new ProcedimentoRN();
  
              $arrObjProcedimentoDTO = $objProcedimentoRN->listarCompleto($objProcedimentoDTO);
  
              if(count($arrObjProcedimentoDTO) == 1){
                $objProcedimentoDTO = $arrObjProcedimentoDTO[0];
              }

              $objProcedimentoDTORelacionado = new ProcedimentoDTO();
    	        $objProcedimentoDTORelacionado->setDblIdProcedimento($_GET['id_procedimento']);
    	        
    	        $objProcedimentoRN = new ProcedimentoRN();
    	        if (count($objProcedimentoRN->listarRelacionados($objProcedimentoDTORelacionado))){
    	          $strMostrarOpcaoRelacionados = '';
    	        }
        
              $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
              
              $objDocumentoRN = new DocumentoRN();
  
              $strThCheck = PaginaSEI::getInstance()->getThCheck();
              
              $numDocumentos = 0;
              
              if (InfraArray::contar($objProcedimentoDTO->getArrObjDocumentoDTO())){
              	
                $bolAcaoDocumentoVisualizar = SessaoSEI::getInstance()->verificarPermissao('documento_visualizar');
              
                foreach($objProcedimentoDTO->getArrObjDocumentoDTO() as $objDocumentoDTO){
    
                  if($objDocumentoRN->verificarSelecaoDuplicacao($objDocumentoDTO)){
                    
                        $strResultadoDocumentos .= '<tr class="infraTrClara">';
                        $strResultadoDocumentos .= '<td align="center" class="infraTd">';
                        
                        $strMarcado = 'N';
                        if (!isset($_POST['hdnIdProtocolo']) || in_array($objDocumentoDTO->getDblIdDocumento(),PaginaSEI::getInstance()->getArrStrItensSelecionados())){
                          $strMarcado = 'S';
                        }
                        
                        $strResultadoDocumentos .= PaginaSEI::getInstance()->getTrCheck($numDocumentos++, $objDocumentoDTO->getDblIdDocumento(), $objDocumentoDTO->getStrNomeSerie(),$strMarcado);
                        $strResultadoDocumentos .= '</td>';
                        
                        $strResultadoDocumentos .= '<td align="center" class="infraTd">';
								        if ($bolAcaoDocumentoVisualizar){
								          $strResultadoDocumentos .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&id_documento='.$objDocumentoDTO->getDblIdDocumento()) .'" target="_blank" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" class="protocoloNormal" style="font-size:1em !important;">'.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().'</a>';
								        }else{
								          $strResultadoDocumentos .= $objDocumentoDTO->getStrProtocoloDocumentoFormatado();
								        }
                        $strResultadoDocumentos .= '</td>';

                        $strResultadoDocumentos .= '<td  class="infraTd">';
                        $strResultadoDocumentos .= PaginaSEI::tratarHTML($objDocumentoDTO->getStrNomeSerie().' '.$objDocumentoDTO->getStrNumero());
                        $strResultadoDocumentos .= '</td>';
                        
                        $strResultadoDocumentos .= '<td align="center" class="infraTd">';
                        $strResultadoDocumentos .= $objDocumentoDTO->getDtaGeracaoProtocolo();
                        $strResultadoDocumentos .= '</td>';
                        
                        $strResultadoDocumentos .= '</tr>';
                    }
                 }
    
                 if ($numDocumentos){
                 
                   $strResultadoDocumentos = '<table id="tblDocumentos" width="99%" class="infraTable" summary="Lista de documentos disponíveis para duplicação">
                                                                <caption class="infraCaption" >'.PaginaSEI::getInstance()->gerarCaptionTabela("documentos disponíveis para duplicação", $numDocumentos).'</caption> 
                                                                        <tr>
                                                                        <th class="infraTh" width="10%">'.$strThCheck.'</th>
                                                                        <th class="infraTh" width="15%">Nº SEI</th>
                                                                        <th class="infraTh">Documento</th>
                                                                        <th class="infraTh" width="15%">Data</th>
                                                                        </tr>'.
                                          $strResultadoDocumentos.
                                          '</table>';
                 }
              }
          }
          
          if ($_POST['hdnFlagDuplicarProcesso']=='1'){

              try{
              	  
                  $objProcedimentoDuplicarDTORet = $objProcedimentoRN->duplicar($objProcedimentoDuplicarDTO);

                  $bolDuplicacaoOK = true;
                  
                  //header('Location:' .SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&id_procedimento='.$objProcedimentoDuplicarDTORet->getDblIdProcedimento()));
                  //die;

              }catch(Exception $e){
                  PaginaSEI::getInstance()->processarExcecao($e);
              }
          }
          break;

      default:
          throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }
 
  $strLinkAjaxInteressados = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=contato_auto_completar_contexto_RI1225');
  $strLinkAjaxCadastroAutomatico = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=contato_cadastro_contexto_temporario');
  $strLinkInteressadosSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=contato_selecionar&tipo_selecao=1&id_object=objLupaInteressados');

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

#lblInteressado {position:absolute;left:0%;top:0%;}
#txtInteressado {position:absolute;left:0%;top:25%;width:70%;}
#imgPesquisarInteressados {position:absolute;left:71%;top:25%;}

#divSinProcessosRelacionados {position:absolute;left:0%;top:65%;width:99%;<?=$strMostrarOpcaoRelacionados?>}

#divInfraAreaTabela {<?=$strMostrarDocumentos?>}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script>
var objAutoCompletarInteressado = null;
var objContatoCadastroAutomatico = null;
var objLupaInteressados = null;

function inicializar(){
   
  <?if(PaginaSEI::getInstance()->isBolArvore()){?>
	  parent.parent.infraOcultarAviso();
	<?}?>
   
  <?if ($bolDuplicacaoOK){ ?>
     window.parent.parent.document.location.href = '<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&id_procedimento='.$objProcedimentoDuplicarDTORet->getDblIdProcedimento().'&montar_visualizacao=1')?>';
     return;
  <?}?>
   
 objAutoCompletarInteressado = new infraAjaxAutoCompletar('hdnIdInteressado','txtInteressado','<?=$strLinkAjaxInteressados?>');
 //objAutoCompletarInteressado.maiusculas = true;
 //objAutoCompletarInteressado.mostrarAviso = true;
 //objAutoCompletarInteressado.tempoAviso = 1000;
 //objAutoCompletarInteressado.tamanhoMinimo = 3;
 objAutoCompletarInteressado.limparCampo = false;
 //objAutoCompletarInteressado.bolExecucaoAutomatica = false;
 
 objAutoCompletarInteressado.prepararExecucao = function(){
   return 'palavras_pesquisa='+encodeURIComponent(document.getElementById('txtInteressado').value);
 };
 
 objAutoCompletarInteressado.processarResultado = function(id,descricao,complemento){
   if (id!=''){
     document.getElementById('hdnIdInteressado').value = id;
     document.getElementById('txtInteressado').value = descricao;
     window.status='Finalizado.';  
   }
 }
 objAutoCompletarInteressado.selecionar('<?=$strIdInteressado?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeInteressado,false)?>');


  objContatoCadastroAutomatico = new infraAjaxComplementar(null,'<?=$strLinkAjaxCadastroAutomatico?>');
  //objContatoCadastroAutomatico.mostrarAviso = false;
  //objContatoCadastroAutomatico.tempoAviso = 3000;
  //objContatoCadastroAutomatico.limparCampo = false;

  objContatoCadastroAutomatico.prepararExecucao = function(){
    return 'nome='+encodeURIComponent(document.getElementById('txtInteressado').value);
  };

  objContatoCadastroAutomatico.processarResultado = function(arr){
    if (arr!=null){
      //objAutoCompletarInteressado.processarResultado(arr['IdContato'], document.getElementById('txtInteressado').value, null);
      objAutoCompletarInteressado.selecionar(arr['IdContato'], document.getElementById('txtInteressado').value, null);
      document.getElementById('btnDuplicarProcesso').focus();
      //alert('Interessado cadastrado com sucesso.');
    }
  };

  infraAdicionarEvento(document.getElementById('txtInteressado'),'keyup',tratarEnterInteressado);

  objLupaInteressados = new infraLupaText('txtInteressado','hdnIdInteressado','<?=$strLinkInteressadosSelecao?>');
  objLupaInteressados.finalizarSelecao = function(){
    objAutoCompletarInteressado.selecionar(document.getElementById('hdnIdInteressado').value, document.getElementById('txtInteressado').value, null);
  }

  infraEfeitoTabelas();
 
  document.getElementById('txtInteressado').focus();
}

function duplicar() {

 <?if(PaginaSEI::getInstance()->isBolArvore()){?>
   parent.parent.infraExibirAviso();
 <?}else{?>
   infraExibirAviso();
 <?}?>
  
 document.getElementById('hdnFlagDuplicarProcesso').value = '1';
 document.getElementById('frmProcedimentoDuplicar').submit();
}

function tratarEnterInteressado(ev){
  if (infraGetCodigoTecla(ev) == 13 && document.getElementById('hdnIdInteressado').value==''){
    if (confirm('Nome inexistente. Deseja incluir?')){
      objContatoCadastroAutomatico.executar();
    }
  }
}

function OnSubmitForm() {
  return true;
}
//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>

<form id="frmProcedimentoDuplicar" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&acao_retorno='.PaginaSEI::getInstance()->getAcaoRetorno().$strParametros)?>">
 
    <?
    //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    ?>

   <input type="hidden" id="hdnIdProtocolo" name="hdnIdProtocolo" value="<?=$_GET['id_procedimento']?>" />  

   <div class="infraAreaDados" style="height:8em">
   
     <label id="lblInteressado" for="txtInteressado" class="infraLabelOpcional">Interessado:</label>
     <input type="text" id="txtInteressado" name="txtInteressado" class="infraText" value="<?=PaginaSEI::tratarHTML($strNomeInteressado)?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
     <input type="hidden" id="hdnIdInteressado" name="hdnIdInteressado" value="<?=$strIdInteressado?>" />
     <img id="imgPesquisarInteressados" onclick="objLupaInteressados.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Pesquisar Interessados" title="Pesquisar Interessados" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

     <div id="divSinProcessosRelacionados" class="infraDivCheckbox">
      <input type="checkbox" id="chkSinProcessosRelacionados" name="chkSinProcessosRelacionados" class="infraCheckbox" <?=$strCheckRelacionados?> <?=PaginaSEI::getInstance()->setCheckbox($objProcedimentoDuplicarDTO->getStrSinProcessosRelacionados())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>     
      <label id="lblSinProcessosRelacionados" for="chkSinProcessosRelacionados" accesskey="" class="infraLabelCheckbox">Manter as associações com os processos relacionados</label>
     </div>
              
     <input type="hidden" id="hdnFlagDuplicarProcesso" name="hdnFlagDuplicarProcesso" value="0" />
   </div>
       
 <?
 if ($numDocumentos){
   PaginaSEI::getInstance()->montarAreaTabela($strResultadoDocumentos, $numDocumentos);
 }else{
 	echo '<label>Nenhum documento disponível para duplicação.</label>';
 }
 ?>
</form>
<?
PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>