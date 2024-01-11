<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/09/2008 - criado por mga
*
* Versão do Gerador de Código: 1.23.0
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

	PaginaSEI::getInstance()->salvarCamposPost(array('selTipoLocalizadorMigracao', 'selLocalizadorMigracao'));  
	
	$numIdLocalizadorDestino 	= PaginaSEI::getInstance()->recuperarCampo('selLocalizadorMigracao');		                  
		
  switch($_GET['acao']){

    case 'arquivamento_migrar_localizador':
    	
      	$strTitulo = 'Migrar Documentos';
   
        $objArquivamentoDTO = new ArquivamentoDTO();
        $objArquivamentoDTO->setNumIdLocalizador($numIdLocalizadorDestino);
        $objArquivamentoDTO->setDblIdProtocolo(PaginaSEI::getInstance()->getArrStrItensSelecionados());

        if (isset($_POST['sbmMigrar'])){
        	
        	try{ 
			        $objArquivamentoRN = new ArquivamentoRN();
			        $objArquivamentoRN->migrarLocalizadorRN1163($objArquivamentoDTO);
			
			      	PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
			      	header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=localizador_listar&acao_origem='.$_GET['acao'].PaginaSEI::montarAncora($_GET['id_localizador'])));
			      	die;
		                
		      }catch(Exception $e){
		        PaginaSEI::getInstance()->processarExcecao($e);
		      } 
       }  

      break;
      
    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");    
  }

  $bolAcaoMigrar = SessaoSEI::getInstance()->verificarPermissao('arquivamento_arquivar');
  
  $arrComandos = array();
  
  if ($bolAcaoMigrar){
    $arrComandos[] = '<button type="submit" accesskey="M" name="sbmMigrar" id="sbmMigrar" class="infraButton" style="width:10em;"><span class="infraTeclaAtalho">M</span>igrar</button>';
  }

	//Recuperar a Identificacao do Localizador recebido
	$objLocalizadorDTO = new LocalizadorDTO();
	$objLocalizadorDTO->retNumIdLocalizador();
	$objLocalizadorDTO->retStrIdentificacao();
  $objLocalizadorDTO->setNumIdLocalizador($_GET['id_localizador']);
  
  $objLocalizadorRN = new LocalizadorRN();
  $objLocalizadorDTO = $objLocalizadorRN->consultarRN0619($objLocalizadorDTO);	   

  $strTitulo .= ' de '.$objLocalizadorDTO->getStrIdentificacao();
  
	$objArquivamentoDTO = new ArquivamentoDTO();
  $objArquivamentoDTO->setNumTipoFkLocalizador(InfraDTO::$TIPO_FK_OBRIGATORIA);
  $objArquivamentoDTO->retDblIdProtocolo();
  $objArquivamentoDTO->retStrNomeTipoProcedimento();
  $objArquivamentoDTO->retDblIdProcedimentoDocumento();
  $objArquivamentoDTO->retStrProtocoloFormatadoProcedimento();
  $objArquivamentoDTO->retStrStaArquivamento();
  $objArquivamentoDTO->retStrProtocoloFormatadoDocumento();
  $objArquivamentoDTO->retStrNomeSerieDocumento();
  $objArquivamentoDTO->retStrNumeroDocumento();
  $objArquivamentoDTO->setNumIdLocalizador($objLocalizadorDTO->getNumIdLocalizador());
  $objArquivamentoDTO->setStrStaArquivamento(array(ArquivamentoRN::$TA_ARQUIVADO,ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO),InfraDTO::$OPER_IN);
  $objArquivamentoDTO->setStrStaEliminacao(ArquivamentoRN::$TE_NAO_ELIMINADO);
  $objArquivamentoDTO->setOrdDblIdProtocolo(InfraDTO::$TIPO_ORDENACAO_ASC);


  $objArquivamentoRN = new ArquivamentoRN();
  $arrObjArquivamentoDTO = $objArquivamentoRN->listar($objArquivamentoDTO);
	
  $numRegistros = count($arrObjArquivamentoDTO);

  if ($numRegistros > 0){
  	
  	
	  $bolAcaoProcedimentoTrabalhar = SessaoSEI::getInstance()->verificarPermissao('procedimento_trabalhar');
	  $bolAcaoDocumentoVisualizar = SessaoSEI::getInstance()->verificarPermissao('documento_visualizar');
  	
    $strResultado = '';
    
    $strCaptionTabela = 'Documentos';
   
    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n"; //90
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="25%">Processo</th>'."\n";
    $strResultado .= '<th class="infraTh" width="25%">Documento</th>'."\n";
    $strResultado .= '<th class="infraTh" width="25%">Tipo</th>'."\n";
    $strResultado .= '<th class="infraTh" width="12%">Número</th>'."\n";
    $strResultado .= '<th class="infraTh" width="12%">Estado</th>'."\n";
    $strResultado .= '</tr>'."\n";  
    $strCssTr='';

    $objArquivamentoRN = new ArquivamentoRN();
    $arrObjTipoArquivamentoSituacaoDTO = InfraArray::indexarArrInfraDTO($objArquivamentoRN->listarValoresTipoArquivamentoSituacao(),'StaArquivamento');
    
    for($i = 0;$i < $numRegistros; $i++){  

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      //coluna 1 - caixa de seleção
      $strValor = 'N';
      if (!isset($_POST['hdnFlagProtocoloMigrar'])){
        $strValor = 'S';	
      }
      $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjArquivamentoDTO[$i]->getDblIdProtocolo(),$arrObjArquivamentoDTO[$i]->getStrProtocoloFormatadoDocumento(),$strValor).'</td>';
      
      $strResultado .= '<td valign="top" align="center">';
      if ($bolAcaoProcedimentoTrabalhar){                 
      	$strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$arrObjArquivamentoDTO[$i]->getDblIdProcedimentoDocumento().'&id_documento='.$arrObjArquivamentoDTO[$i]->getDblIdProcedimentoDocumento()).'" target="_blank" class="protocoloNormal" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" title="'.PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrNomeTipoProcedimento()).'">'.PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrProtocoloFormatadoProcedimento()).'</a>';
      }else{
      	$strResultado .= PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrProtocoloFormatadoProcedimento());
      }  
      $strResultado .= '</td>';
      
      $strResultado .= '<td valign="top" align="center">';
      if ($bolAcaoDocumentoVisualizar){                 
      	$strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&acao_origem='.$_GET['acao'].'&id_documento='.$arrObjArquivamentoDTO[$i]->getDblIdProtocolo()) .'" target="_blank" class="protocoloNormal" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" >'.PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrProtocoloFormatadoDocumento()).'</a>';
      }else{
      	$strResultado .= PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrProtocoloFormatadoDocumento());
      }  
      $strResultado .= '</td>';

      $strResultado .= '<td valign="top" align="center">';
      $strResultado .= PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrNomeSerieDocumento());
      $strResultado .= '</td>';

      $strResultado .= '<td valign="top" align="center">';
      $strResultado .= PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrNumeroDocumento());
      $strResultado .= '</td>';

      $strResultado .= '<td valign="top" align="center">';
      $strResultado .= PaginaSEI::tratarHTML($arrObjTipoArquivamentoSituacaoDTO[$arrObjArquivamentoDTO[$i]->getStrStaArquivamento()]->getStrDescricao());
      $strResultado .= '</td>';
      
      $strResultado .= '</tr>'."\n";
    }
    $strResultado .= '</table>';
  }

  $arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=localizador_listar&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_localizador'])).'\'" class="infraButton" style="width:10em;"><span class="infraTeclaAtalho">C</span>ancelar</button>';
  
  $strItensSelTipoLocalizador = TipoLocalizadorINT::montarSelectNomeRI0676('null','&nbsp;',$numIdLocalizadorDestino);
  
  $strLinkAjaxLocalizador = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=protocolo_RI1132');  

	$strLinkMigrar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arquivamento_migrar_localizador&acao_destino='.$_GET['acao'].'&acao_retorno='.PaginaSEI::getInstance()->getAcaoRetorno().'&id_localizador='.$_GET['id_localizador']);
	$strLinkFechar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arquivamento_migrar_localizador&acao_destino='.PaginaSEI::getInstance()->getAcaoRetorno().'&id_localizador='.$_GET['id_localizador'].PaginaSEI::getInstance()->montarAncora($_GET['id_localizador']));
  
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

#lblTipoLocalizador {position:absolute;left:0%;top:13%;;width:40%;}
#selTipoLocalizadorMigracao {position:absolute;left:0%;top:30%;width:40%;}

#lblLocalizador {position:absolute;left:0%;top:60%;width:40%;}
#selLocalizadorMigracao {position:absolute;left:0%;top:77%;width:40%;}


<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

var objAjaxLocalizador = null;

function inicializar(){
  if ('<?=PaginaSEI::getInstance()->isBolPaginaSelecao()?>'!=''){
    infraReceberSelecao();
  }

  //Busca de localizadores ao escolher um tipo localizador
  objAjaxLocalizador = new infraAjaxMontarSelectDependente('selTipoLocalizadorMigracao','selLocalizadorMigracao','<?=$strLinkAjaxLocalizador?>');
  objAjaxLocalizador.prepararExecucao = function(){
    return infraAjaxMontarPostPadraoSelect('null','','<?=$numIdLocalizadorDestino?>') + '&idTipoLocalizador='+document.getElementById('selTipoLocalizadorMigracao').value + '&idLocalizador=<?=$_GET['id_localizador']?>';
  }  
  
  objAjaxLocalizador.executar();
    

  infraEfeitoTabelas();
}

function acaoMigrarMultipla(){

  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum documento selecionado.');
  }else if (!infraSelectSelecionado('selTipoLocalizadorMigracao')) {
    alert('Selecione um Tipo de Localizador.');
    document.getElementById('selTipoLocalizadorMigracao').focus();
    return false;
  }else if (!infraSelectSelecionado('selLocalizadorMigracao')) {
    alert('Selecione um Localizador.');
    document.getElementById('selLocalizadorMigracao').focus();
    return false;
  }else {
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmProtocoloMigrar').action='<?=$strLinkMigrar?>';
    document.getElementById('frmProtocoloMigrar').submit();  
  }
  return true;
} 
  
function validarMigrarRI1162() {

  if (!infraSelectSelecionado('selTipoLocalizadorMigracao')) {
    alert('Selecione o Tipo do Localizador de Destino.');
    document.getElementById('selTipoLocalizadorMigracao').focus();
    return false;
  }


  if (!infraSelectSelecionado('selLocalizadorMigracao')) {
    alert('Selecione um Localizador de Destino.');
    document.getElementById('selLocalizadorMigracao').focus();
    return false;
  }
  
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum documento selecionado.');
    return false;  
  }  

  return true;  
}

function OnSubmitForm(){
	return validarMigrarRI1162();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmProtocoloMigrar" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arquivamento_migrar_localizador&acao_origem='.$_GET['acao'].'&id_localizador='.$_GET['id_localizador'])?>">
  <? 
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('12em');
  ?>
	  <label id="lblTipoLocalizador" for="selTipoLocalizadorMigracao" accesskey="" class="infraLabelObrigatorio">Tipo do Localizador de Destino:</label>
	  	<select id="selTipoLocalizadorMigracao" name="selTipoLocalizadorMigracao" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
	  	<?=$strItensSelTipoLocalizador?>	  	
	  </select>
	   
	  <label id="lblLocalizador" for="selLocalizadorMigracao" accesskey="" class="infraLabelObrigatorio">Localizador de Destino:</label>
	  	<select id="selLocalizadorMigracao" name="selLocalizadorMigracao" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
	  	<?=$strItensSelLocalizador?>
	  </select>  
	  
	  <input type="hidden" id="hdnFlagProtocoloMigrar" name="hdnFlagProtocoloMigrar" value="1" />     
	       
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  ?>  
  
  <?
  //PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>