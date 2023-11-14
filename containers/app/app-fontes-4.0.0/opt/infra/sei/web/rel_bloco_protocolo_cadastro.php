<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 02/10/2009 - criado por fbv@trf4.gov.br
*
* Versão do Gerador de Código: 1.29.1
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

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('id_procedimento', 'id_bloco_anotacao', 'id_bloco', 'sta_estado', 'nao_assinados'));

  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
  
  PaginaSEI::getInstance()->salvarCamposPost(array('selProtocolo','selBloco'));

  $objRelBlocoProtocoloDTO = new RelBlocoProtocoloDTO();

  $strDesabilitar = '';

  $arrComandos = array();
  
  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }

  $bolFlagAlteracaoOK = false;
  $bolNaoEncontrado = false;

  switch($_GET['acao']){
      
    case 'rel_bloco_protocolo_cadastrar':
      
      if ($_GET['acao_origem']=='procedimento_controlar' || $_GET['acao_origem']=='arvore_visualizar'){
        
       	$numIdBloco = $_POST['hdnIdBloco'];
       	
        if ($_GET['acao_origem']=='procedimento_controlar'){
        	$arrProtocolos = array_merge(PaginaSEI::getInstance()->getArrStrItensSelecionados('Gerados'),PaginaSEI::getInstance()->getArrStrItensSelecionados('Recebidos'),PaginaSEI::getInstance()->getArrStrItensSelecionados('Detalhado'));
        }else{
          
          if (isset($_GET['id_procedimento'])){
            $arrProtocolos = array($_GET['id_procedimento']);  
          }else if (isset($_GET['id_documento'])){
        	  $arrProtocolos = array($_GET['id_documento']);  
          }
        }
        
        $arrObjRelBlocoProtocoloDTO = array();
        $arrAncora = array();
        foreach($arrProtocolos as $dblIdProtocolo){
        	$objRelBlocoProtocoloDTO = new RelBlocoProtocoloDTO();
        	$objRelBlocoProtocoloDTO->setNumIdBloco($numIdBloco);
        	$objRelBlocoProtocoloDTO->setDblIdProtocolo($dblIdProtocolo);
        	$objRelBlocoProtocoloDTO->setStrAnotacao(null);
        	$arrObjRelBlocoProtocoloDTO[] = $objRelBlocoProtocoloDTO;
        	$arrAncora[] = $objRelBlocoProtocoloDTO->getDblIdProtocolo().'-'.$objRelBlocoProtocoloDTO->getNumIdBloco();
        }      	

      		$arrAncora = array();
      		foreach($arrObjRelBlocoProtocoloDTO as $objRelBlocoProtocoloDTO){
      		  $arrAncora[] = $objRelBlocoProtocoloDTO->getDblIdProtocolo().'-'.$objRelBlocoProtocoloDTO->getNumIdBloco();
      		}
        
      	//$strResultado = '0';
      	
      	try{
      	  
      		$objRelBlocoProtocoloRN = new RelBlocoProtocoloRN();
      		$objRelBlocoProtocoloRN->cadastrarMultiplo($arrObjRelBlocoProtocoloDTO);
      	
      		//$strResultado = '1';
      		
      		if (InfraArray::contar($arrProtocolos)==1){
      		  PaginaSEI::getInstance()->setStrMensagem('Protocolo inserido com sucesso no bloco '.$numIdBloco.'.');  
      		}else{
      		  PaginaSEI::getInstance()->setStrMensagem('Protocolos inseridos com sucesso no bloco '.$numIdBloco.'.');  
      		}
      		
      	}catch(Exception $e){
      		PaginaSEI::getInstance()->processarExcecao($e);
      	}

      	//$strParametros .= '&atualizar_arvore='.$strResultado;
      	
      	$strAcaoDestino = $_GET['acao_origem'];
      	
      	if ($_GET['acao_origem']=='procedimento_controlar'){
      	  //controle de processos vai para a lista de protocolos do bloco diretamente
    		  $strAcaoDestino = 'rel_bloco_protocolo_listar';
      	}else if ($_GET['acao_origem']=='arvore_visualizar'){
      	  //visualizando a árvore tem que voltar para que a lista não seja exibida no iframe
      	  $strAcaoDestino = 'arvore_visualizar';
      	}else{
      	  $strAcaoDestino = PaginaSEI::getInstance()->getAcaoRetorno();
      	}

      	header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDestino.'&acao_origem='.$_GET['acao'].'&id_bloco='.$numIdBloco.$strParametros.PaginaSEI::getInstance()->montarAncora($arrAncora)));
    		die;

      } /* else {
      	$strTitulo = 'Novo Rel_Bloco_Protocolo';
      	$arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarRelBlocoProtocolo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      	$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros)).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      	$dblIdProtocolo = PaginaSEI::getInstance()->recuperarCampo('selProtocolo');
      	if ($dblIdProtocolo!==''){
      		$objRelBlocoProtocoloDTO->setDblIdProtocolo($dblIdProtocolo);
      	}else{
      		$objRelBlocoProtocoloDTO->setDblIdProtocolo(null);
      	}

      	$numIdBloco = PaginaSEI::getInstance()->recuperarCampo('selBloco');
      	if ($numIdBloco!==''){
      		$objRelBlocoProtocoloDTO->setNumIdBloco($numIdBloco);
      	}else{
      		$objRelBlocoProtocoloDTO->setNumIdBloco(null);
      	}

      	if (isset($_POST['sbmCadastrarRelBlocoProtocolo'])) {
      		try{
      			$objRelBlocoProtocoloRN = new RelBlocoProtocoloRN();
      			$objRelBlocoProtocoloDTO = $objRelBlocoProtocoloRN->cadastrarMultiplo($objRelBlocoProtocoloDTO);
      			//PaginaSEI::getInstance()->setStrMensagem('Rel_Bloco_Protocolo "'.$objRelBlocoProtocoloDTO->getDblIdProtocolo().'" cadastrado com sucesso.');
      			header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_documento='.$objRelBlocoProtocoloDTO->getDblIdProtocolo().'&id_bloco='.$objRelBlocoProtocoloDTO->getNumIdBloco().PaginaSEI::getInstance()->montarAncora($objRelBlocoProtocoloDTO->getDblIdProtocolo().'-'.$objRelBlocoProtocoloDTO->getNumIdBloco())));
      			die;
      		}catch(Exception $e){
      			PaginaSEI::getInstance()->processarExcecao($e);
      		}
      	}
      }*/
      
      break;

      
    case 'rel_bloco_protocolo_alterar':
      $strTitulo = 'Anotações';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarRelBlocoProtocolo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if ($_GET['acao_origem']=='rel_bloco_protocolo_listar'){
        
        $objRelBlocoProtocoloDTO->setDblIdProtocolo($_GET['id_documento']);
        $objRelBlocoProtocoloDTO->setNumIdBloco($_GET['id_bloco_anotacao']);
        $objRelBlocoProtocoloDTO->retTodos();
        $objRelBlocoProtocoloRN = new RelBlocoProtocoloRN();
        $objRelBlocoProtocoloDTO = $objRelBlocoProtocoloRN->consultarRN1290($objRelBlocoProtocoloDTO);
        if ($objRelBlocoProtocoloDTO==null){
        	
        	//para nao dar erro na montagem da página
        	$objRelBlocoProtocoloDTO = new RelBlocoProtocoloDTO();
          $objRelBlocoProtocoloDTO->setDblIdProtocolo(null);
          $objRelBlocoProtocoloDTO->setNumIdBloco(null);
          $objRelBlocoProtocoloDTO->setStrAnotacao(null);
        	
          $strLinkRetorno = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.'&'.PaginaSEI::getParametroRandom());

          $bolNaoEncontrado = true;
        	
        }
      } else {
        
        $objRelBlocoProtocoloDTO->setDblIdProtocolo($_POST['hdnIdProtocolo']);
        $objRelBlocoProtocoloDTO->setNumIdBloco($_POST['hdnIdBloco']);
        $objRelBlocoProtocoloDTO->setStrAnotacao($_POST['txtAnotacao']);
      }

      //$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarRelBlocoProtocolo'])) {
        try{
          $objRelBlocoProtocoloRN = new RelBlocoProtocoloRN();
          $objRelBlocoProtocoloRN->alterarRN1288($objRelBlocoProtocoloDTO);
          //PaginaSEI::getInstance()->setStrMensagem('Anotações do protocolo no bloco alteradas com sucesso.');
          $strLinkRetorno = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.'&'.PaginaSEI::getParametroRandom().PaginaSEI::getInstance()->montarAncora($objRelBlocoProtocoloDTO->getDblIdProtocolo().'-'.$objRelBlocoProtocoloDTO->getNumIdBloco()));
          //die;
          $bolFlagAlteracaoOK = true;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;
      
		/*
    case 'rel_bloco_protocolo_consultar':
      $strTitulo = 'Consultar Rel_Bloco_Protocolo';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_documento'].'-'.$_GET['id_bloco']))).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objRelBlocoProtocoloDTO->setDblIdProtocolo($_GET['id_documento']);
      $objRelBlocoProtocoloDTO->setNumIdBloco($_GET['id_bloco']);
      $objRelBlocoProtocoloDTO->setBolExclusaoLogica(false);
      $objRelBlocoProtocoloDTO->retTodos();
      $objRelBlocoProtocoloRN = new RelBlocoProtocoloRN();
      $objRelBlocoProtocoloDTO = $objRelBlocoProtocoloRN->consultarRN1290($objRelBlocoProtocoloDTO);
      if ($objRelBlocoProtocoloDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;
		*/
      
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
#lblAnotacao {position:absolute;left:0%;top:0%;width:25%;}
#txtAnotacao {position:absolute;left:0%;top:6%;width:95%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){

  if ('<?=$bolNaoEncontrado?>' == '1'){
    alert('Documento foi retirado do bloco.');
    window.parent.location = '<?=$strLinkRetorno?>';
    self.setTimeout('infraFecharJanelaModal()',200);
    return;
  }

  if ('<?=$_GET['acao']?>'=='rel_bloco_protocolo_cadastrar'){
    document.getElementById('txtAnotacao').focus();
  } else if ('<?=$_GET['acao']?>'=='rel_bloco_protocolo_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    
    if ('<?=$bolFlagAlteracaoOK?>'=='1'){
      window.parent.location = '<?=$strLinkRetorno?>';
      self.setTimeout('infraFecharJanelaModal()',200);
      return;
    }else{
      document.getElementById('txtAnotacao').focus();
    }
  }
  
  infraEfeitoTabelas();
}

function validarCadastroRI1297() {
  return true;
}

function OnSubmitForm() {
  return validarCadastroRI1297();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmRelBlocoProtocoloCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('24em');
?>
  <!-- <label id="lblAnotacao" for="txtAnotacao" accesskey="" class="infraLabelObrigatorio">Anotações:</label> -->
	<textarea id="txtAnotacao" name="txtAnotacao" rows="<?=PaginaSEI::getInstance()->isBolNavegadorFirefox()?'10':'11'?>" class="infraTextarea" maxlength="2000" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objRelBlocoProtocoloDTO->getStrAnotacao());?></textarea>
	
  <input type="hidden" id="hdnIdProtocolo" name="hdnIdProtocolo" value="<?=$objRelBlocoProtocoloDTO->getDblIdProtocolo();?>" />
  <input type="hidden" id="hdnIdBloco" name="hdnIdBloco" value="<?=$objRelBlocoProtocoloDTO->getNumIdBloco();?>" />
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