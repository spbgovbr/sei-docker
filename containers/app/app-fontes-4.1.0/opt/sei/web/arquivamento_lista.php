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

  PaginaSEI::getInstance()->salvarCamposPost(array('txtProtocolo', 'selLocalizador', 'selTipoLocalizador'));
  
  if ($_GET['acao']=='arquivamento_pesquisar' || $_GET['acao_origem']=='arquivamento_pesquisar'){
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  }
	
	$numIdTipoLocalizador = PaginaSEI::getInstance()->recuperarCampo('selTipoLocalizador');	
	$numIdLocalizador	= PaginaSEI::getInstance()->recuperarCampo('selLocalizador');
	
	if ($_GET['acao_origem']=='arquivamento_listar'){
	  $strProtocolos = '';
	}else{
	  $strProtocolos = PaginaSEI::getInstance()->recuperarCampo('txtProtocolo');
	}

  switch($_GET['acao']){

    case 'arquivamento_arquivar':
      	
      $strTitulo = 'Arquivamento';

      try{
		  	
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();

        $objArquivamentoDTO = new ArquivamentoDTO();
        $objArquivamentoDTO->setNumIdLocalizador($numIdLocalizador);
        $objArquivamentoDTO->setDblIdProtocolo($arrStrIds);

        $objArquivamentoRN = new ArquivamentoRN();
        $objArquivamentoRN->arquivarRN1133($objArquivamentoDTO);

        //PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }

      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&arquivados='.implode(',',$arrStrIds).PaginaSEI::montarAncora($arrStrIds)));
      die;
         		
    case 'arquivamento_receber':
      try{
      	$strTitulo = 'Arquivamento';

        $arrObjArquivamentoDTO = array();
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objArquivamentoDTO = new ArquivamentoDTO();
          $objArquivamentoDTO->setDblIdProtocolo($arrStrIds[$i]);
          $arrObjArquivamentoDTO[] = $objArquivamentoDTO;
        }
        
        $objArquivamentoRN = new ArquivamentoRN();
        $objArquivamentoRN->receber($arrObjArquivamentoDTO);
        
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;
      
    case 'arquivamento_cancelar_recebimento':
      try{
      	$strTitulo = 'Arquivamento';

        $arrObjArquivamentoDTO = array();
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objArquivamentoDTO = new ArquivamentoDTO();
          $objArquivamentoDTO->setDblIdProtocolo($arrStrIds[$i]);
          $arrObjArquivamentoDTO[] = $objArquivamentoDTO;
        }
        
        $objArquivamentoRN = new ArquivamentoRN();
        $objArquivamentoRN->cancelarRecebimento($arrObjArquivamentoDTO);
        
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'arquivamento_pesquisar':
      $strTitulo = 'Consultar para Arquivamento';
      break;  
      
    case 'arquivamento_listar':
     	$strTitulo = 'Arquivamento';
      break;
      
    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
     
  }

	$bolAcaoProtocoloArquivamentoPesquisar = SessaoSEI::getInstance()->verificarPermissao('arquivamento_pesquisar');
	
	$objArquivamentoRN = new ArquivamentoRN();
	
  $objArquivamentoDTO = new ArquivamentoDTO();

  if (isset($_GET['arquivados']) && $_GET['arquivados']!='') {
    $objArquivamentoDTO->setArrDblIdArquivados(explode(',',$_GET['arquivados']));
  }

  if ($_GET['acao']=='arquivamento_pesquisar' || $_GET['acao_origem']=='arquivamento_pesquisar'){
    $objArquivamentoDTO->setStrProtocoloFormatadoDocumento($strProtocolos);
  }

  $numRegistros = 0;

  //if ($_GET['acao']=='arquivamento_listar' || ($_GET['acao']=='arquivamento_pesquisar' && isset($_POST['txtProtocolo']))){
    PaginaSEI::getInstance()->prepararPaginacao($objArquivamentoDTO);

    $arrObjArquivamentoDTO = array();

    try {
      $arrObjArquivamentoDTO = $objArquivamentoRN->listarParaArquivamentoRN1161($objArquivamentoDTO);
    }catch(Exception $e){
      PaginaSEI::getInstance()->processarExcecao($e);
    }

   	PaginaSEI::getInstance()->processarPaginacao($objArquivamentoDTO);
  	
    $numRegistros = count($arrObjArquivamentoDTO);
  //}

  $arrComandos = array();
	
  
  if ($bolAcaoProtocoloArquivamentoPesquisar && $_GET['acao']=='arquivamento_listar'){
    $arrComandos[] = '<button type="button" accesskey="o" id="btnPesquisar" value="Consultar" onclick="infraAbrirJanelaModal(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arquivamento_pesquisar&acao_origem='.$_GET['acao']).'\',800,600);" class="infraButton" style="">C<span class="infraTeclaAtalho">o</span>nsultar</button>';
    $arrComandos[] = '<button type="submit" accesskey="" id="sbmAtualizar" value="Atualizar" class="infraButton" style="">Atualizar</button>';
  }
  
  if ($numRegistros > 0){

    $bolAcaoProtocoloArquivar = SessaoSEI::getInstance()->verificarPermissao('arquivamento_arquivar');
	  $bolAcaoProtocoloArquivamentoReceber = SessaoSEI::getInstance()->verificarPermissao('arquivamento_receber');
	  $bolAcaoProtocoloArquivamentoCancelarRecebimento = SessaoSEI::getInstance()->verificarPermissao('arquivamento_cancelar_recebimento');
	  $bolAcaoProcedimentoTrabalhar = SessaoSEI::getInstance()->verificarPermissao('procedimento_trabalhar');
		$bolAcaoDocumentoVisualizar = SessaoSEI::getInstance()->verificarPermissao('documento_visualizar');
  	
	  if ($bolAcaoProtocoloArquivar){
	    $arrComandos[] = '<button type="button" accesskey="A" name="btnArquivar" id="btnArquivar" value="Arquivar" onclick="acaoArquivarMultipla();" class="infraButton" style=""><span class="infraTeclaAtalho">A</span>rquivar</button>';
	    $strLinkArquivar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arquivamento_arquivar&acao_origem='.$_GET['acao']);
	  }
  	
    $strResultado = '';
    
    $strCaptionTabela = 'Documentos';
    $strSumarioTabela = 'Documentos para Arquivamento';
      
    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    $strResultado .= '<th class="infraTh">Processo</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Documento</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Tipo</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Número</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Estado</th>'."\n";
    //$strResultado .= '<th class="infraTh" width="10%">Usuário</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%" >Localizadores do Processo</th>'."\n";
    $strResultado .= '<th class="infraTh" width="5%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";  
    $strCssTr='';

    $objArquivamentoRN = new ArquivamentoRN();
    $arrObjTipoArquivamentoSituacaoDTO = InfraArray::indexarArrInfraDTO($objArquivamentoRN->listarValoresTipoArquivamentoSituacao(),'StaArquivamento');
    
    
    $bolFlagNaoArquivado = false;
    $bolFlagRecebido = false;
    
    for($i = 0;$i < $numRegistros; $i++){  

      //die($arrObjArquivamentoDTO[$i]->__toString());

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      $strAtributosCheckbox = '';
      if ($arrObjArquivamentoDTO[$i]->getStrStaArquivamento()==ArquivamentoRN::$TA_ARQUIVADO) {
        $strAtributosCheckbox = 'disabled="disabled" style="display:none;"';
      }

      $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i, $arrObjArquivamentoDTO[$i]->getDblIdProtocolo(), $arrObjArquivamentoDTO[$i]->getStrProtocoloFormatadoDocumento(),'N','Infra',$strAtributosCheckbox).'</td>'."\n";


      $strResultado .= '<td valign="top" align="center">';
      if ($bolAcaoProcedimentoTrabalhar && $arrObjArquivamentoDTO[$i]->getNumCodigoAcesso() > 0){
      	$strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$arrObjArquivamentoDTO[$i]->getDblIdProcedimentoDocumento().'&id_documento='.$arrObjArquivamentoDTO[$i]->getDblIdProcedimentoDocumento()).'" target="_blank" class="protocoloNormal" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" title="'.PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrNomeTipoProcedimento()).'">'.PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrProtocoloFormatadoProcedimento()).'</a>';
      }else{
      	$strResultado .= '<a href="javascript:void(0);" onclick="alert(\'Processo sigiloso.\');" class="protocoloNormal" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" >'.PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrProtocoloFormatadoProcedimento()).'</a>';
      }  
      $strResultado .= '</td>';
      
      $strResultado .= '<td valign="top" align="center">';
      if ($bolAcaoDocumentoVisualizar && $arrObjArquivamentoDTO[$i]->getNumCodigoAcesso() > 0){
      	$strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&acao_origem='.$_GET['acao'].'&id_documento='.$arrObjArquivamentoDTO[$i]->getDblIdProtocolo()) .'" target="_blank" class="protocoloNormal" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" >'.PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrProtocoloFormatadoDocumento()).'</a>';
      }else{
      	$strResultado .= '<a href="javascript:void(0);" onclick="alert(\'Sem acesso ao documento.\');" class="protocoloNormal" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" >'.PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrProtocoloFormatadoDocumento()).'</a>';
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

      /*
			$strResultado .= '<td valign="top" align="center" >';
      if ($arrObjArquivamentoDTO[$i]->getNumIdUsuarioArquivamento()!=null) {
        $strResultado .= '<a alt="' . PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrNomeUsuarioArquivamento()) . '" title="' . PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrNomeUsuarioArquivamento()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrSiglaUsuarioArquivamento()) . '</a>';
      }else{
        $strResultado .= '&nbsp;';
      }
			$strResultado .= '</td>';
      */

      $strResultado .= '<td valign="top" align="center">';
      $arrObjLocalizadorDTO = $arrObjArquivamentoDTO[$i]->getArrObjLocalizadorDTO();
      for($j=0;$j<count($arrObjLocalizadorDTO);$j++){

        if ($j > 0) {
          $strResultado .= '<br />';
        }

        $strClassLocalizador = '';
        if ($arrObjLocalizadorDTO[$j]->getNumIdUnidade()==SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {
          if ($arrObjLocalizadorDTO[$j]->getStrStaEstado() == LocalizadorRN::$EA_ABERTO) {
            $strClassLocalizador = ' localizadorAberto';
          } else {
            $strClassLocalizador = ' localizadorFechado';
          }
        }

        $strPrefixo1 = '';
        $strPrefixo2 = '';
        if ($arrObjArquivamentoDTO[$i]->getNumIdLocalizador() == $arrObjLocalizadorDTO[$j]->getNumIdLocalizador()) {
          $strPrefixo2 = '&raquo;&nbsp;';
          $strTitleLocalizador = ' (protocolo está associado com este localizador)';
        } else {
          $strPrefixo1 = '&nbsp;&nbsp;&nbsp;';
          $strTitleLocalizador = '';
        }

        if ($arrObjLocalizadorDTO[$j]->getNumIdUnidade()==SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {
          $strResultado .= $strPrefixo1 . '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=localizador_protocolos_listar&acao_origem=' . $_GET['acao'] . '&id_localizador=' . $arrObjLocalizadorDTO[$j]->getNumIdLocalizador().PaginaSEI::getInstance()->montarAncora($arrObjArquivamentoDTO[$i]->getDblIdProtocolo())).'" target="_blank" class="linkFuncionalidade' . $strClassLocalizador . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '" title="' . PaginaSEI::tratarHTML($arrObjLocalizadorDTO[$j]->getStrNomeTipoLocalizador() . ' / ' . $arrObjLocalizadorDTO[$j]->getStrNomeLugarLocalizador() . $strTitleLocalizador) . '">' . $strPrefixo2 . LocalizadorINT::montarIdentificacaoRI1132($arrObjLocalizadorDTO[$j]->getStrSiglaTipoLocalizador(), $arrObjLocalizadorDTO[$j]->getNumSeqLocalizador()) . '</a>';
        }else{
          $strResultado .= $strPrefixo1 . '<a href="javascript:void(0);" onclick="alert(\'Localizador pertence a unidade '.$arrObjLocalizadorDTO[$j]->getStrSiglaUnidadeLocalizador().'.\');" class="ancoraSigla" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '" title="' . PaginaSEI::tratarHTML('Localizador pertence a unidade '.$arrObjLocalizadorDTO[$j]->getStrSiglaUnidadeLocalizador()) .'">' . $strPrefixo2 . LocalizadorINT::montarIdentificacaoRI1132($arrObjLocalizadorDTO[$j]->getStrSiglaTipoLocalizador(), $arrObjLocalizadorDTO[$j]->getNumSeqLocalizador()) . '</a>';
        }
      }
      $strResultado .= '</td>';
      
      $strResultado .= '<td valign="top" align="center">';
      
      if ($bolAcaoProtocoloArquivamentoReceber && ($arrObjArquivamentoDTO[$i]->getStrStaArquivamento()==ArquivamentoRN::$TA_NAO_ARQUIVADO || $arrObjArquivamentoDTO[$i]->getStrStaArquivamento()==ArquivamentoRN::$TA_DESARQUIVADO)){
      	
      	$bolFlagNaoArquivado = true;
      	
				$strResultado .= '<a href="#ID-'.$arrObjArquivamentoDTO[$i]->getDblIdProtocolo().'"  onclick="acaoReceber(\''.$arrObjArquivamentoDTO[$i]->getDblIdProtocolo().'\',\''.$arrObjArquivamentoDTO[$i]->getStrProtocoloFormatadoDocumento().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::ARQUIVO_RECEBER.'" title="Receber" alt="Receber" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoProtocoloArquivamentoCancelarRecebimento && 
          ($arrObjArquivamentoDTO[$i]->getStrStaArquivamento()==ArquivamentoRN::$TA_RECEBIDO) &&
          $arrObjArquivamentoDTO[$i]->getNumIdUnidadeRecebimento()==SessaoSEI::getInstance()->getNumIdUnidadeAtual() &&
          $_GET['acao']=='arquivamento_listar'){
      	
      	$bolFlagRecebido = true;
      	
				$strResultado .= '<a href="#ID-'.$arrObjArquivamentoDTO[$i]->getDblIdProtocolo().'"  onclick="acaoCancelarRecebimento(\''.$arrObjArquivamentoDTO[$i]->getDblIdProtocolo().'\',\''.$arrObjArquivamentoDTO[$i]->getStrProtocoloFormatadoDocumento().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::ARQUIVO_CANCELAR_RECEBIMENTO.'" title="Cancelar Recebimento" alt="Cancelar Recebimento" class="infraImg" /></a>&nbsp;';
      }
      
	    $strResultado .= '</td>';  
	      
      $strResultado .= '</tr>';
	      
    }
    $strResultado .= '</table>';
    
    
	  if ($bolFlagNaoArquivado){
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReceber" value="Receber" onclick="acaoReceberMultipla();" class="infraButton" style=""><span class="infraTeclaAtalho">R</span>eceber</button>';
      $strLinkReceber = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arquivamento_receber&acao_origem='.$_GET['acao']);
	  }
	  
	  if ($bolFlagRecebido){
      $arrComandos[] = '<button type="button" accesskey="C" id="btnCancelarRecebimento" value="Cancelar Recebimento" onclick="acaoCancelarRecebimentoMultipla();" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar Recebimento</button>';	  	
	  	$strLinkCancelarRecebimento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arquivamento_cancelar_recebimento&acao_origem='.$_GET['acao']);
	  }
  }

  if ($_GET['acao']=='arquivamento_listar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }
	
  $strItensSelTipoLocalizador = TipoLocalizadorINT::montarSelectNomeRI0676('null','&nbsp;',$numIdTipoLocalizador);
  $strLinkAjaxLocalizador = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=protocolo_RI1132');
  $strLinkOpenerListar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arquivamento_listar');
  
  
  if ($_GET['acao']=='arquivamento_listar'){
    $strDisplayPesquisa = 'display:none;';
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

#divPesquisa {<?=$strDisplayPesquisa?>}
#lblProtocolo {position:absolute;left:0%;top:0%;}
#txtProtocolo {position:absolute;left:0%;top:37%;width:80%;}

#lblTipoLocalizador {position:absolute;left:0%;top:0%;}
#selTipoLocalizador {position:absolute;left:0%;top:20%;width:60%;}

#lblLocalizador {position:absolute;left:0%;top:50%;}
#selLocalizador {position:absolute;left:0%;top:70%;width:40%;}

a.localizadorAberto{
color:green;
}

a.localizadorFechado{
color:red;
}

a.localizadorAssociado{
  padding:0 .1em 0 .1em;
  border-right:5px solid #a0a0a0;
  border-left:5px solid #a0a0a0;
}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

var objAjaxLocalizador = null;

function inicializar(){

  //Busca de localizadores ao escolher um tipo localizador
  objAjaxLocalizador = new infraAjaxMontarSelectDependente('selTipoLocalizador','selLocalizador','<?=$strLinkAjaxLocalizador?>');
  objAjaxLocalizador.prepararExecucao = function(){
    return infraAjaxMontarPostPadraoSelect('null','','<?=$numIdLocalizador?>') + '&idTipoLocalizador='+document.getElementById('selTipoLocalizador').value;
  }  
  
  objAjaxLocalizador.executar();
    

  infraEfeitoTabelas();
  
  if (document.getElementById('btnPesquisar')!=null){
    self.setTimeout("document.getElementById('btnPesquisar').focus()",200);
  }else if (document.getElementById('txtProtocolo')!=null){
    self.setTimeout("document.getElementById('txtProtocolo').focus()",200);
  }
  
}

<? if ($bolAcaoProtocoloArquivar){ ?>
 
function acaoArquivarMultipla() {

  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum documento selecionado.');
    return;
  }

  if (!infraSelectSelecionado('selTipoLocalizador')) {
    alert('Selecione um Tipo de Localizador.');
    document.getElementById('selTipoLocalizador').focus();
    return false;
  }

  if (!infraSelectSelecionado('selLocalizador')) {
    alert('Selecione um Localizador.');
    document.getElementById('selLocalizador').focus();
    return false;
  }
  
  document.getElementById('frmProtocoloArquivamento').action='<?=$strLinkArquivar?>';
  document.getElementById('frmProtocoloArquivamento').submit();
}
<? } ?>

<? if ($bolAcaoProtocoloArquivamentoReceber){ ?>
function acaoReceber(id,desc){
  //if (confirm("Confirma recebimento do documento \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmProtocoloArquivamento').action='<?=$strLinkReceber?>';
    document.getElementById('frmProtocoloArquivamento').submit();
  //}
}

function acaoReceberMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum documento selecionado.');
    return;
  }
  //if (confirm("Confirma recebimento dos documentos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmProtocoloArquivamento').action='<?=$strLinkReceber?>';
    document.getElementById('frmProtocoloArquivamento').submit();
  //}
}
<? } ?>

<? if ($bolAcaoProtocoloArquivamentoCancelarRecebimento){ ?>
function acaoCancelarRecebimento(id,desc){
  if (confirm("Confirma cancelamento do recebimento do documento \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmProtocoloArquivamento').action='<?=$strLinkCancelarRecebimento?>';
    document.getElementById('frmProtocoloArquivamento').submit();
  }
}

function acaoCancelarRecebimentoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum documento selecionado.');
    return;
  }
  if (confirm("Confirma cancelamento do recebimento dos documentos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmProtocoloArquivamento').action='<?=$strLinkCancelarRecebimento?>';
    document.getElementById('frmProtocoloArquivamento').submit();
  }
}
<? } ?>

function finalizar(){
  if ('<?=$_GET['acao']?>'=='arquivamento_pesquisar'){
    //window.opener.location.reload();
    //window.opener.location.href = '<?=$strLinkOpenerListar?>';
    //self.focus();
  }
}

function tratarEnter(ev){
  
  var key = infraGetCodigoTecla(ev);
  
  if (key == 13){
    document.getElementById('frmProtocoloArquivamento').submit();
  }
  
  return true;
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();" onunload="finalizar();"');
?>
<form id="frmProtocoloArquivamento" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <? 
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  ?>
  
  <div id="divPesquisa" class="infraAreaDados" style="height:5em">
    <label id="lblProtocolo" for="txtProtocolo" accesskey="" class="infraLabelObrigatorio">Protocolo (separe múltiplos protocolos com vírgulas ","):</label>
    <input type="text" id="txtProtocolo" name="txtProtocolo" onkeypress="return infraMascaraNumero(this,event,null,',');" onkeyup="return tratarEnter(event);" class="infraText" value="<?=$strProtocolos?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  </div>
  
  <div id="divDadosArquivamento" class="infraAreaDados" style="height:10em">
	  <label id="lblTipoLocalizador" for="selTipoLocalizador" accesskey="" class="infraLabelObrigatorio">Tipo do Localizador:</label>
	  	<select id="selTipoLocalizador" name="selTipoLocalizador" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
	  	<?=$strItensSelTipoLocalizador?>	  	
	  </select>
	   
	  <label id="lblLocalizador" for="selLocalizador" accesskey="" class="infraLabelObrigatorio">Localizador:</label>
	  	<select id="selLocalizador" name="selLocalizador" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
	    </select>
	</div>    
	      
  <?
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>