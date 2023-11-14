<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 13/07/2011 - criado por mga
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
  
  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
  
  $arrComandos = array();
  
  switch($_GET['acao']){
  	
  	case 'credencial_assinatura_conceder':
  		
  		$strTitulo = 'Concessão de Credencial de Assinatura';
  		
  		try{
  			
		  $objConcederCredencialAssinaturaDTO = new ConcederCredencialAssinaturaDTO();
		    
	      $objConcederCredencialAssinaturaDTO->setDblIdProcedimento($_GET['id_procedimento']);
	      $objConcederCredencialAssinaturaDTO->setDblIdDocumento($_GET['id_documento']);
	      $objConcederCredencialAssinaturaDTO->setNumIdUsuario($_POST['hdnIdUsuario']);
	      $objConcederCredencialAssinaturaDTO->setNumIdUnidade($_POST['selUnidade']);
	      
	      $arrAtividadesOrigem = explode(',',$_POST['hdnIdAtividades']);
	      $objConcederCredencialAssinaturaDTO->setArrAtividadesOrigem(InfraArray::gerarArrInfraDTO('AtividadeDTO','IdAtividade',explode(',',$_POST['hdnIdAtividades'])));
	      
          $objAtividadeRN = new AtividadeRN();
	      $ret = $objAtividadeRN->concederCredencialAssinatura($objConcederCredencialAssinaturaDTO);
	      
	      PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
	      
	      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=credencial_assinatura_gerenciar&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::getInstance()->montarAncora($ret->getNumIdAtividade())));
	      die;
          
  		}catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
  		}
  		
      break;
  		
    case 'credencial_assinatura_cassar':
    	
    	$strTitulo = 'Cassação de Credencial de Assinatura';
    	
      try{
      	
      	$arrObjAtividadeDTO = InfraArray::gerarArrInfraDTO('AtividadeDTO','IdAtividade',PaginaSEI::getInstance()->getArrStrItensSelecionados());
      	
        $objAtividadeRN = new AtividadeRN();
        $objAtividadeRN->cassarCredencialAssinatura($arrObjAtividadeDTO);
        
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::getInstance()->montarAncora(implode(',',PaginaSEI::getInstance()->getArrStrItensSelecionados()))));
      die;
  	
    case 'credencial_assinatura_gerenciar':
      $strTitulo = 'Gerenciar Credenciais de Assinatura';
	  break;
	
	default:
	  throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  $objDocumentoDTO = new DocumentoDTO();
  $objDocumentoDTO->setDblIdDocumento($_GET['id_documento']);
	  
  $objAtividadeRN = new AtividadeRN();
  $arrObjAtividadeDTO = $objAtividadeRN->listarCredenciaisAssinatura($objDocumentoDTO);
	
  $numRegistros = count($arrObjAtividadeDTO);

  $bolAcaoConceder = SessaoSEI::getInstance()->verificarPermissao('credencial_assinatura_conceder');
  $bolAcaoCassar = SessaoSEI::getInstance()->verificarPermissao('credencial_assinatura_cassar');
  	
  if ($bolAcaoConceder){
  	$strLinkConceder = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=credencial_assinatura_conceder&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].$strParametros);
  }
  
  if ($numRegistros > 0){
  	
    if ($bolAcaoCassar){
    	//$arrComandos[] = '<button type="submit" accesskey="a" name="sbmCassar" id="sbmCassar" onclick="acaoCassacaoMultipla();" value="Cassar" class="infraButton">C<span class="infraTeclaAtalho">a</span>ssar</button>';
      $strLinkCassar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=credencial_assinatura_cassar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].$strParametros);
    }
  	
    //$arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Credenciais de Assinatura.';
    $strCaptionTabela = 'Credenciais de Assinatura';

    $strResultado .= '<table id="tblCredenciaisAssinatura" width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n"; //90
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%" style="display:none;">'.PaginaSEI::getInstance()->getThCheck('','Infra','style="display:none;"').'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">De</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Para</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">Concessão</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">Cassação</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">Utilização</th>'."\n";
    $strResultado .= '<th class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    
    $n = 0;
    foreach($arrObjAtividadeDTO as $objAtividadeDTO){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      $strResultado .= "\n".'<td valign="top" style="display:none;">';
      $strResultado .= PaginaSEI::getInstance()->getTrCheck($n++,$objAtividadeDTO->getNumIdAtividade(),$objAtividadeDTO->getStrSiglaUsuario().'/'.$objAtividadeDTO->getStrSiglaUnidade(),'N','Infra','style="visibility:hidden;"');
      $strResultado .= '</td>';

      $strResultado .= "\n".'<td align="center"  valign="top">';
      $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrNomeUsuarioOrigem()).'" title="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrNomeUsuarioOrigem()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrSiglaUsuarioOrigem()).'</a>';
      $strResultado .= '</td>';

      $strResultado .= "\n".'<td align="center"  valign="top">';
      $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrNomeUsuario()).'" title="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrNomeUsuario()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrSiglaUsuario()).'</a>';
      $strResultado .= '<br>';
      $strResultado .= '( <a alt="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrSiglaUnidade()).' )</a>';
      $strResultado .= '</td>';

			$strSituacao = '';
			switch($objAtividadeDTO->getNumIdTarefa()){
				case TarefaRN::$TI_CONCESSAO_CREDENCIAL_ASSINATURA:
					$strSituacao .= '<td align="center" valign="top">'.substr($objAtividadeDTO->getDthAbertura(),0,16).'</td>';
					$strSituacao .= '<td>&nbsp;</td>';
					$strSituacao .= '<td>&nbsp;</td>';
					break;
        	
        case TarefaRN::$TI_CONCESSAO_CREDENCIAL_ASSINATURA_CASSADA:
        	$strSituacao .= '<td align="center" valign="top">'.substr($objAtividadeDTO->getDthAbertura(),0,16).'</td>';
        	$strSituacao .= '<td align="center" valign="top">';
        	
        	foreach($objAtividadeDTO->getArrObjAtributoAndamentoDTO() as $objAtributoAndamentoDTO){
        		if ($objAtributoAndamentoDTO->getStrNome() == 'DATA_HORA'){
        			$strSituacao .= substr($objAtributoAndamentoDTO->getStrValor(),0,16);
        		}
        	}
        	$strSituacao .= '</td>';
        	$strSituacao .= '<td>&nbsp;</td>';
        	break;
        	
        case TarefaRN::$TI_CONCESSAO_CREDENCIAL_ASSINATURA_UTILIZADA:
        	$strSituacao .= '<td align="center" valign="top">'.substr($objAtividadeDTO->getDthAbertura(),0,16).'</td>';
        	$strSituacao .= '<td>&nbsp;</td>';
        	$strSituacao .= '<td align="center" valign="top">';
        	
        	foreach($objAtividadeDTO->getArrObjAtributoAndamentoDTO() as $objAtributoAndamentoDTO){
        		if ($objAtributoAndamentoDTO->getStrNome() == 'DATA_HORA'){
        			$strSituacao .= substr($objAtributoAndamentoDTO->getStrValor(),0,16);
        		}
        	}
        	$strSituacao .= '</td>';
        	break;
        	
			}
			$strResultado .= $strSituacao;
      
			$strResultado .= "\n".'<td align="center" valign="top">';
		  if ($bolAcaoCassar && $objAtividadeDTO->getNumIdTarefa() == TarefaRN::$TI_CONCESSAO_CREDENCIAL_ASSINATURA){
        $strResultado .= '<a href="#ID-'.$objAtividadeDTO->getNumIdAtividade().'"  onclick="acaoCassar(\''.$objAtividadeDTO->getNumIdAtividade().'\',\''.$objAtividadeDTO->getStrSiglaUsuario().'/'.$objAtividadeDTO->getStrSiglaUnidade().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::CREDENCIAL_CASSAR.'" title="Cassar Credencial de Assinatura" alt="Cassar Credencial de Assinatura" class="infraImg" /></a>&nbsp;';
      }else{
      	$strResultado .= '<span style="line-height:1.5em">&nbsp;</span>';
      }
			$strResultado .= '</td>';
      
      $strResultado .= '</tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  
  //$arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'])).'\'" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

  $strLinkAjaxUsuario = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_auto_completar');
  $strLinkAjaxUnidadesUsuario = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_unidades_permissao');
  $strLinkAjaxVerificarCredencialProcesso = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=acesso_pesquisar_credencial_processo');

  //busca andamentos abertos do processo para validar na hora de salvar (verifica se ocorreu alteracao) 
  if ($_GET['acao_origem']=='arvore_visualizar' || 
      $_GET['acao_origem']=='credencial_assinatura_conceder' ||
      $_GET['acao_origem']=='credencial_assinatura_cassar'){
      	
  	$objAtividadeRN = new AtividadeRN();
  	$objPesquisaPendenciaDTO = new PesquisaPendenciaDTO();
  	$objPesquisaPendenciaDTO->setDblIdProtocolo($_GET['id_procedimento']);
  	$objPesquisaPendenciaDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
  	$objPesquisaPendenciaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  	$arrObjProcedimentoDTO = $objAtividadeRN->listarPendenciasRN0754($objPesquisaPendenciaDTO);

  	if (count($arrObjProcedimentoDTO)==0){
  		throw new InfraException('Processo não encontrado.');
  	}
  	$arrAtividadesOrigem = InfraArray::converterArrInfraDTO($arrObjProcedimentoDTO[0]->getArrObjAtividadeDTO(),'IdAtividade');
  }else {
  	if ($_POST['hdnIdAtividades']!=''){
  		$arrAtividadesOrigem = explode(',',$_POST['hdnIdAtividades']);
  	}
  }
  $arrNumIdAtividades = implode(',',$arrAtividadesOrigem);

  
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
#lblUsuario {position:absolute;left:0%;top:10%;}
#txtUsuario {position:absolute;left:0%;top:45%;width:40%;}
#lblUnidade {position:absolute;left:41%;top:10%;visibility:hidden;}
#selUnidade {position:absolute;left:41%;top:45%;width:40%;visibility:hidden;}
#btnConceder {position:absolute;left:84%;top:45%;visibility:hidden;}

#tblCredenciaisAssinatura td {padding:.4em;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

var objAutoCompletarUsuario = null;
var objAjaxUnidadesUsuario = null;
var objTabelaUsuariosUnidades = null;
var bolRemontandoTela = false;
var objVerificarCredenciaisProcesso = null;

function inicializar(){

  objAutoCompletarUsuario = new infraAjaxAutoCompletar('hdnIdUsuario','txtUsuario','<?=$strLinkAjaxUsuario?>');
  //objAutoCompletarUsuario.maiusculas = true;
  //objAutoCompletarUsuario.mostrarAviso = true;
  //objAutoCompletarUsuario.tempoAviso = 1000;
  //objAutoCompletarUsuario.tamanhoMinimo = 3;
  objAutoCompletarUsuario.limparCampo = true;
  //objAutoCompletarUsuario.bolExecucaoAutomatica = false;

  objAutoCompletarUsuario.prepararExecucao = function(){
    return 'palavras_pesquisa='+document.getElementById('txtUsuario').value;
  };
  
  objAutoCompletarUsuario.processarResultado = function(id,descricao,complemento){
    if (id!=''){
      objAjaxUnidadesUsuario.executar();
    }else{
	    document.getElementById('lblUnidade').style.visibility = 'hidden';
      document.getElementById('selUnidade').style.visibility = 'hidden';
      document.getElementById('selUnidade').options.length = 0;
      document.getElementById('btnConceder').style.visibility = 'hidden';
    }
  };
  
  objAjaxUnidadesUsuario = new infraAjaxMontarSelect('selUnidade','<?=$strLinkAjaxUnidadesUsuario?>');
	  objAjaxUnidadesUsuario.prepararExecucao = function(){
	    return 'id_usuario='+document.getElementById('hdnIdUsuario').value;
	  };
	  objAjaxUnidadesUsuario.processarResultado = function(nroItens){
	    
	    document.getElementById('lblUnidade').style.visibility = 'hidden';
      document.getElementById('selUnidade').style.visibility = 'hidden';
      document.getElementById('btnConceder').style.visibility = 'hidden';
	    
      if (document.getElementById('selUnidade').options.length == 1){
        if (document.getElementById('selUnidade').options[0].value=='null'){
          alert('Usuário não tem acesso a nenhuma unidade.');
        }else{
          document.getElementById('selUnidade').options[0].selected = true;
          document.getElementById('btnConceder').style.left = '41%'; 
          document.getElementById('btnConceder').style.visibility = 'visible';
        }
	    }else if (document.getElementById('selUnidade').options.length > 1){
	      document.getElementById('lblUnidade').style.visibility = 'visible';
	      document.getElementById('selUnidade').style.visibility = 'visible';
        document.getElementById('btnConceder').style.left = '82%'; 
	      document.getElementById('selUnidade').focus();
	      
	      if (bolRemontandoTela){
	        infraSelectSelecionarItem('selUnidade','<?=$_POST['selUnidade']?>');
	        escolheuUnidade();
	      }
	    }
	  }
	  
	  objVerificarCredenciaisProcesso = new infraAjaxComplementar(null,'<?=$strLinkAjaxVerificarCredencialProcesso?>');
	  
	  objVerificarCredenciaisProcesso.prepararExecucao = function(){
	    return 'IdUsuario=' + document.getElementById('hdnIdUsuario').value + '&IdUnidade=' + document.getElementById('selUnidade').value + '&IdProtocolo=' + <?=$_GET['id_procedimento']?>;
	  };
	  
	  objVerificarCredenciaisProcesso.processarResultado = function(arr){
	    if (arr!=null){
	      var bolConceder = false; 
	       
	      if (arr['Total']=='0'){
	        if (confirm("Usuário receberá automaticamente credencial de acesso ao processo.\nDeseja continuar?")){
	          bolConceder = true;
	        }
	      }else{
	        bolConceder = true;
	      } 
	    
	      if (bolConceder){
				  document.getElementById('frmGerenciarCredenciaisAssinatura').action = '<?=$strLinkConceder?>';
				  document.getElementById('frmGerenciarCredenciaisAssinatura').submit();
	      }
	    }
	  };
  
	  
  
<? if ($_GET['acao']=='credencial_assinatura_conceder'){ ?>
  //erro ao conceder remonta a tela
  bolRemontandoTela = true;
  objAutoCompletarUsuario.selecionar('<?=$_POST['hdnIdUsuario']?>','<?=$_POST['txtUsuario']?>');
<? }else{ ?>
	document.getElementById('txtUsuario').focus();
<? } ?>	
	
  infraEfeitoTabelas();
}

<? if ($bolAcaoConceder){ ?>

function conceder(){
  if (infraTrim(document.getElementById('hdnIdUsuario'))==''){
    alert('Informe um Usuário.');
    document.getElementById('txtUsuario').focus();
    return;
  }

  if (!infraSelectSelecionado('selUnidade')){
    alert('Selecione uma Unidade.');
    document.getElementById('selUnidade').focus();
    return;
  }

  //objVerificarCredenciaisProcesso.executar();
  
  document.getElementById('frmGerenciarCredenciaisAssinatura').action = '<?=$strLinkConceder?>';
	document.getElementById('frmGerenciarCredenciaisAssinatura').submit();
}

function escolheuUnidade(){
  if (!infraSelectSelecionado('selUnidade')){
    document.getElementById('btnConceder').style.visibility = 'hidden'; 
  }else{
    document.getElementById('btnConceder').style.visibility = 'visible';
    document.getElementById('btnConceder').focus();
  }
}
<? } ?>

<? if ($bolAcaoCassar){ ?>
function acaoCassar(id,desc){
  if (confirm("Confirma cassação da credencial de assinatura \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGerenciarCredenciaisAssinatura').action='<?=$strLinkCassar?>';
    document.getElementById('frmGerenciarCredenciaisAssinatura').submit();
  }
}

function acaoCassacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma credencial de assinatura selecionada.');
    return;
  }
  if (confirm("Confirma cassação das credenciais de assinatura selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmGerenciarCredenciaisAssinatura').action='<?=$strLinkCassar?>';
    document.getElementById('frmGerenciarCredenciaisAssinatura').submit();
  }
}
<? } ?>



function OnSubmitForm() {
	return true;
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmGerenciarCredenciaisAssinatura" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
	//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
	PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
	//PaginaSEI::getInstance()->montarAreaValidacao();
?>	
  <div id="divUsuarios" class="infraAreaDados" style="height:6em;">
	 	<label id="lblUsuario" for="txtUsuario" class="infraLabelOpcional">Conceder Credencial de Assinatura para:</label>
	  <input type="text" id="txtUsuario" name="txtUsuario" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
	  <input type="hidden" id="hdnIdUsuario" name="hdnIdUsuario" class="infraText" value="" />
	  
	 	<label id="lblUnidade" for="selUnidade" class="infraLabelOpcional">Unidade:</label>
	  <select id="selUnidade" name="selUnidade" class="infraSelect" onchange="escolheuUnidade();" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
	  </select>
	  
	  <button type="button" name="btnConceder" id="btnConceder" onclick="conceder();" accesskey="C" value="Conceder" class="infraButton"><span class="infraTeclaAtalho">C</span>onceder</button>
  </div>
<?	
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
	PaginaSEI::getInstance()->montarAreaDebug();
	PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
?>
  
  <input type="hidden" id="hdnIdAtividades" name="hdnIdAtividades" value="<?=$arrNumIdAtividades;?>" />
  
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>