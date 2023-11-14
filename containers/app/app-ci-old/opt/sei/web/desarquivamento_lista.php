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
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();
  
  
  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

	PaginaSEI::getInstance()->salvarCamposPost(array('selUnidade'));

  $arrObjArquivamentoDTO = array();
	
  switch($_GET['acao']){
  	
    case 'arquivamento_desarquivar':
      $strTitulo = 'Desarquivamento';

      try{
		  	
      	$objSolicitacaoDesarquivamentoDTO = new SolicitacaoDesarquivamentoDTO();
        $objSolicitacaoDesarquivamentoDTO->setNumIdUsuario($_POST['selUsuario']);
        $objSolicitacaoDesarquivamentoDTO->setStrSenha($_POST['pwdSenha']);
        $objSolicitacaoDesarquivamentoDTO->setArrObjArquivamentoDTO(InfraArray::gerarArrInfraDTO('ArquivamentoDTO','IdProtocolo',PaginaSEI::getInstance()->getArrStrItensSelecionados()));

        $objArquivamentoRN = new ArquivamentoRN();
        $objArquivamentoRN->desarquivarRN1147($objSolicitacaoDesarquivamentoDTO);
        
        //PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        
	      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arquivamento_desarquivamento_listar&acao_origem='.$_GET['acao']));
	      die;
        
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e, true);
      }
      break;
      
    case 'arquivamento_cancelar_solicitacao_desarquivamento':
      try{
      	$strTitulo = 'Desarquivamento';

        $arrObjArquivamentoDTO = array();
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objArquivamentoDTO = new ArquivamentoDTO();
          $objArquivamentoDTO->setDblIdProtocolo($arrStrIds[$i]);
          $arrObjArquivamentoDTO[] = $objArquivamentoDTO;
        }
        
        $objArquivamentoRN = new ArquivamentoRN();
        $objArquivamentoRN->cancelarSolicitacaoDesarquivamento($arrObjArquivamentoDTO);
        
        //PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');

        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arquivamento_desarquivamento_listar&acao_origem='.$_GET['acao']));
        die;
        
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
       
      break;
            
    case 'arquivamento_desarquivamento_listar':
      $strTitulo = 'Desarquivamento';
      break; 
    	
    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
     
  }

  $objUnidadeDTO = new UnidadeDTO();
  $objUnidadeDTO->setNumIdUnidade(PaginaSEI::getInstance()->recuperarCampo('selUnidade'));

	$objArquivamentoRN = new ArquivamentoRN();
	$arrObjArquivamentoDTO = $objArquivamentoRN->listarParaDesarquivamento($objUnidadeDTO);
	
  $numRegistros = count($arrObjArquivamentoDTO);

  $arrComandos = array();

  if ($numRegistros > 0){
  	
    $bolAcaoProtocoloDesarquivar = SessaoSEI::getInstance()->verificarPermissao('arquivamento_desarquivar');
	  $bolAcaoProtocoloCancelarSolicitacaoDesarquivamento = SessaoSEI::getInstance()->verificarPermissao('arquivamento_cancelar_solicitacao_desarquivamento');
  	
	  if ($bolAcaoProtocoloDesarquivar){
      $arrComandos[] = '<button type="button" accesskey="e" name="btnDesarquivar" id="btnDesarquivar" onclick="acaoDesarquivarMultipla();" class="infraButton" style="width:10em;">D<span class="infraTeclaAtalho">e</span>sarquivar</button>';
      $strLinkProtocoloDesarquivar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arquivamento_desarquivar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']);
	  }
      	
	  if ($bolAcaoProtocoloCancelarSolicitacaoDesarquivamento){
      $arrComandos[] = '<button type="button" accesskey="C" id="btnCancelarSolicitacao" value="Cancelar Solicitações" onclick="acaoCancelarSolicitacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar Solitações</button>';
      $strLinkCancelarSolicitacaoDesarquivamento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arquivamento_cancelar_solicitacao_desarquivamento&acao_origem='.$_GET['acao']);
	  }
  	
	  $bolAcaoProcedimentoTrabalhar = SessaoSEI::getInstance()->verificarPermissao('procedimento_trabalhar');
		$bolAcaoDocumentoVisualizar = SessaoSEI::getInstance()->verificarPermissao('documento_visualizar');
  	
    $strResultado = '';
    
    $strCaptionTabela = 'Documentos';
    $strSumarioTabela = 'Documentos para Desarquivamento';
      
    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">Processo</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Documento</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Tipo</th>'."\n";
    $strResultado .= '<th class="infraTh" width="8%">Número</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Unidade</th>'."\n";
    $strResultado .= '<th class="infraTh" width="8%">Usuário</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Localizador</th>'."\n";
    $strResultado .= '<th class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";  
    $strCssTr='';

    for($i = 0;$i < $numRegistros; $i++){  

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;
      
      $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjArquivamentoDTO[$i]->getDblIdProtocolo(), $arrObjArquivamentoDTO[$i]->getStrProtocoloFormatadoDocumento()).'</td>'."\n";
      
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
      	$strResultado .= PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrProtocoloFormatado());
      }  
      $strResultado .= '</td>';

      $strResultado .= '<td valign="top" align="center">';
      $strResultado .= PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrNomeSerieDocumento());
      $strResultado .= '</td>';

      $strResultado .= '<td valign="top" align="center">';
      $strResultado .= PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrNumeroDocumento());
      $strResultado .= '</td>';

      $strResultado .= '<td valign="top" align="center">';
		  $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrDescricaoUnidadeSolicitacao()).'" title="'.PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrDescricaoUnidadeSolicitacao()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrSiglaUnidadeSolicitacao()).'</a>';
      $strResultado .= '</td>';
      
			$strResultado .= "\n".'<td align="center"  valign="top">';
		  $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrNomeUsuarioSolicitacao()).'" title="'.PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrNomeUsuarioSolicitacao()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrSiglaUsuarioSolicitacao()).'</a>';
			$strResultado .= '</td>';
      
      $strResultado .= '<td valign="top" align="center">';
      	
     	$strCorLocalizador = '';
     	if ($arrObjArquivamentoDTO[$i]->getStrStaEstadoLocalizador()==LocalizadorRN::$EA_ABERTO){
     		$strCorLocalizador = 'style="color:green;"';
     	}else{
     		$strCorLocalizador = 'style="color:red;"';
    	}
      $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=localizador_protocolos_listar&acao_origem='.$_GET['acao'].'&id_localizador='.$arrObjArquivamentoDTO[$i]->getNumIdLocalizador()).'" target="_blank" class="linkFuncionalidade" '.$strCorLocalizador.' tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" title="'.$arrObjArquivamentoDTO[$i]->getStrNomeTipoLocalizador().'">'.LocalizadorINT::montarIdentificacaoRI1132($arrObjArquivamentoDTO[$i]->getStrSiglaTipoLocalizador(),$arrObjArquivamentoDTO[$i]->getNumSeqLocalizadorLocalizador()).'</a>';
      $strResultado .= '</td>';
      
      $strResultado .= '<td valign="top" align="center">';
      
      if ($bolAcaoProtocoloDesarquivar && $arrObjArquivamentoDTO[$i]->getStrStaArquivamento()==ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO && $arrObjArquivamentoDTO[$i]->getNumIdUnidadeLocalizador()==SessaoSEI::getInstance()->getNumIdUnidadeAtual()){
				$strResultado .= '<a href="#ID-'.$arrObjArquivamentoDTO[$i]->getDblIdProtocolo().'"  onclick="acaoDesarquivar(\''.$arrObjArquivamentoDTO[$i]->getDblIdProtocolo().'\',\''.$arrObjArquivamentoDTO[$i]->getStrProtocoloFormatadoDocumento().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::ARQUIVO_DESARQUIVAR.'" title="Desarquivar Documento" alt="Desarquivar Documento" class="infraImg" /></a>&nbsp;';
      }
      
      if ($bolAcaoProtocoloCancelarSolicitacaoDesarquivamento && $arrObjArquivamentoDTO[$i]->getStrStaArquivamento()==ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO && $arrObjArquivamentoDTO[$i]->getNumIdUnidadeLocalizador()==SessaoSEI::getInstance()->getNumIdUnidadeAtual()){
				$strResultado .= '<a href="#ID-'.$arrObjArquivamentoDTO[$i]->getDblIdProtocolo().'"  onclick="acaoCancelarSolicitacao(\''.$arrObjArquivamentoDTO[$i]->getDblIdProtocolo().'\',\''.$arrObjArquivamentoDTO[$i]->getStrProtocoloFormatadoDocumento().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeMenos().'" title="Cancelar Solicitação de Desarquivamento" alt="Cancelar Solicitação de Desarquivamento" class="infraImg" /></a>&nbsp;';
      }
	    $strResultado .= '</td>';
	      
      $strResultado .= '</tr>';
	      
    }
    $strResultado .= '</table>';
  }

  $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  
  $strItensSelUnidade = ProtocoloINT::montarSelectUnidadesSolicitantesDesarquivamento('null','&nbsp;',$objUnidadeDTO->getNumIdUnidade());
  
  if ($objUnidadeDTO->getNumIdUnidade()!=null){
    $strItensSelUsuario = UsuarioINT::montarSelectPorUnidadeRI0811('null','&nbsp;',$_POST['selUsuario'], $objUnidadeDTO->getNumIdUnidade());
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

#lblUnidade {position:absolute;left:0%;top:0%;}
#selUnidade {position:absolute;left:0%;top:12%;width:25%;}

#lblUsuario {position:absolute;left:0%;top:30%;}
#selUsuario {position:absolute;left:0%;top:42%;width:50%;}

#lblSenha {position:absolute;left:0%;top:60%;}
#pwdSenha {position:absolute;left:0%;top:72%;width:25%;}


<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

$(document).ready(function(){
  new MaskedPassword(document.getElementById("pwdSenha"), '\u25CF');
});

function inicializar(){


  infraEfeitoTabelas();
  
  self.setTimeout("document.getElementById('selUnidade').focus()",100);
}


<? if ($bolAcaoProtocoloDesarquivar){ ?>

function validarUsuarioSenha(){
  if (!infraSelectSelecionado('selUsuario')){
    alert('Selecione um usuário.');
    document.getElementById('selUsuario').focus();
    return false;
  } 

  if (infraTrim(document.getElementById('pwdSenha').value)==''){
    alert('Senha não informada.');
    document.getElementById('pwdSenha').focus();
    return false;
  }
  
  return true;
}

function acaoDesarquivar(id,desc){

  if (!validarUsuarioSenha()) {
    return;
  }

  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmProtocoloDesarquivamento').action='<?=$strLinkProtocoloDesarquivar?>';
  document.getElementById('frmProtocoloDesarquivamento').submit();
}

function acaoDesarquivarMultipla(){

  if (!validarUsuarioSenha()) {
    return;
  }

  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum documento selecionado.');
    return;
  }
  
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmProtocoloDesarquivamento').action='<?=$strLinkProtocoloDesarquivar?>';
  document.getElementById('frmProtocoloDesarquivamento').submit();
}
<? } ?>


<? if ($bolAcaoProtocoloCancelarSolicitacaoDesarquivamento){ ?>

function acaoCancelarSolicitacao(id,desc){
  if (confirm("Confirma cancelamento da solicitação de desarquivamento do documento \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmProtocoloDesarquivamento').action='<?=$strLinkCancelarSolicitacaoDesarquivamento?>';
    document.getElementById('frmProtocoloDesarquivamento').submit();
  }
}

function acaoCancelarSolicitacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum documento selecionado.');
    return;
  }
  if (confirm("Confirma cancelamento das solicitações de desarquivamento dos documentos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmProtocoloDesarquivamento').action='<?=$strLinkCancelarSolicitacaoDesarquivamento?>';
    document.getElementById('frmProtocoloDesarquivamento').submit();
  }
}
<? } ?>


<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmProtocoloDesarquivamento" onsubmit="return false;" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <? 
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('15em');
  ?>
  
  <label id="lblUnidade" for="selUnidade" accesskey="U" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">U</span>nidade Solicitante:</label>
  	<select id="selUnidade" name="selUnidade" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  	<?=$strItensSelUnidade?>	  	
  </select>

 	<label id="lblUsuario" for="selUsuario" accesskey="R" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">R</span>etirado por:</label>
  <select id="selUsuario" name="selUsuario" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelUsuario?>
  </select>
	
  <label id="lblSenha" for="pwdSenha" accesskey="S" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">S</span>enha:</label>
  <?=InfraINT::montarInputPassword('pwdSenha', '', 'tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'"')?>
	
  <?
  PaginaSEI::getInstance()->fecharAreaDados(); 
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>