<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 25/10/2011 - criado por mga
*
*
*/

try {
  require_once dirname(__FILE__).'/Sip.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSip::getInstance()->validarLink();

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistema','selSistema'));
  
  $objRegraAuditoriaDTO = new RegraAuditoriaDTO(true);

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'regra_auditoria_cadastrar':
      $strTitulo = 'Nova Regra de Auditoria';
      $arrComandos[] = '<input type="submit" name="sbmCadastrarRegraAuditoria" value="Salvar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton" />';
			
      $objRegraAuditoriaDTO->setNumIdRegraAuditoria(null);
      
			//ORGAO SISTEMA
			$numIdOrgaoSistema = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema');
			if ($numIdOrgaoSistema!==''){
				$objRegraAuditoriaDTO->setNumIdOrgaoSistema($numIdOrgaoSistema);
			}else{
				$objRegraAuditoriaDTO->setNumIdOrgaoSistema(null);
			}
			
			//SISTEMA
			$numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema');
			if ($numIdSistema!==''){
				$objRegraAuditoriaDTO->setNumIdSistema($numIdSistema);
			}else{
				$objRegraAuditoriaDTO->setNumIdSistema(null);
			}
			
      $arrRecursos = PaginaSip::getInstance()->getArrValuesSelect($_POST['hdnRecursos']);
      $arrObjRelRegraAuditoriaRecursoDTO = array();
      foreach($arrRecursos as $numIdRecurso){
      	$objRelRegraAuditoriaRecursoDTO = new RelRegraAuditoriaRecursoDTO();
      	$objRelRegraAuditoriaRecursoDTO->setNumIdRecurso($numIdRecurso);
      	$arrObjRelRegraAuditoriaRecursoDTO[] = $objRelRegraAuditoriaRecursoDTO;
      }
      $objRegraAuditoriaDTO->setArrObjRelRegraAuditoriaRecursoDTO($arrObjRelRegraAuditoriaRecursoDTO);

		  		  
		  $objRegraAuditoriaDTO->setStrDescricao($_POST['txtDescricao']);
		  
		  $objRegraAuditoriaDTO->setStrSinAtivo('S');
			
      if (isset($_POST['sbmCadastrarRegraAuditoria'])) {
				try{
					$objRegraAuditoriaRN = new RegraAuditoriaRN();
					$objRegraAuditoriaDTO = $objRegraAuditoriaRN->cadastrar($objRegraAuditoriaDTO);
					PaginaSip::getInstance()->setStrMensagem('Regra de Auditoria cadastrada com sucesso.');
			    header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSip::getInstance()->montarAncora($objRegraAuditoriaDTO->getNumIdRegraAuditoria())));
					die;
				}catch(Exception $e){
					PaginaSip::getInstance()->processarExcecao($e);
				}
      }
      break;

    case 'regra_auditoria_alterar':
      $strTitulo = 'Alterar Regra de Auditoria';
      $arrComandos[] = '<input type="submit" name="sbmAlterarRegraAuditoria" value="Salvar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSip::getInstance()->montarAncora($_GET['id_auditoria'])).'\';" class="infraButton" />';

      $strDesabilitar = 'disabled="disabled"';
			
			if (isset($_GET['id_auditoria'])){
        $objRegraAuditoriaDTO->setNumIdRegraAuditoria($_GET['id_auditoria']);
        $objRegraAuditoriaDTO->setBolExclusaoLogica(false);
        $objRegraAuditoriaDTO->retTodos(true);
        $objRegraAuditoriaRN = new RegraAuditoriaRN();
        $objRegraAuditoriaDTO = $objRegraAuditoriaRN->consultar($objRegraAuditoriaDTO);
        if ($objRegraAuditoriaDTO===null){
          throw new InfraException("Registro não encontrado.");
        }
				
			} else {
				$objRegraAuditoriaDTO->setNumIdRegraAuditoria($_POST['hdnIdRegraAuditoria']);
				$objRegraAuditoriaDTO->setNumIdOrgaoSistema($_POST['hdnIdOrgaoSistema']);
				$objRegraAuditoriaDTO->setNumIdSistema($_POST['hdnIdSistema']);
				$objRegraAuditoriaDTO->setStrDescricao($_POST['txtDescricao']);
				
	      $arrRecursos = PaginaSip::getInstance()->getArrValuesSelect($_POST['hdnRecursos']);
	      $arrObjRelRegraAuditoriaRecursoDTO = array();
	      foreach($arrRecursos as $numIdRecurso){
	      	$objRelRegraAuditoriaRecursoDTO = new RelRegraAuditoriaRecursoDTO();
	      	$objRelRegraAuditoriaRecursoDTO->setNumIdRecurso($numIdRecurso);
	      	$arrObjRelRegraAuditoriaRecursoDTO[] = $objRelRegraAuditoriaRecursoDTO;
	      }
	      $objRegraAuditoriaDTO->setArrObjRelRegraAuditoriaRecursoDTO($arrObjRelRegraAuditoriaRecursoDTO);
				
			}

      if (isset($_POST['sbmAlterarRegraAuditoria'])) {
				try{
					$objRegraAuditoriaRN = new RegraAuditoriaRN();
					$objRegraAuditoriaRN->alterar($objRegraAuditoriaDTO);
					PaginaSip::getInstance()->setStrMensagem('Regra de Auditoria alterada com sucesso.');
					header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSip::getInstance()->montarAncora($objRegraAuditoriaDTO->getNumIdRegraAuditoria())));
					die;
				}catch(Exception $e){
					PaginaSip::getInstance()->processarExcecao($e);
				}
				
      }
      break;

    case 'regra_auditoria_consultar':
      $strTitulo = "Consultar Regra de Auditoria";
			$arrComandos[] = '<input type="button" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSip::getInstance()->montarAncora($_GET['id_auditoria'])).'\';" class="infraButton" />';
		
			$objRegraAuditoriaDTO->setNumIdRegraAuditoria($_GET['id_auditoria']);
			$objRegraAuditoriaDTO->setBolExclusaoLogica(false);
			$objRegraAuditoriaDTO->retTodos(true);
			$objRegraAuditoriaRN = new RegraAuditoriaRN();
			$objRegraAuditoriaDTO = $objRegraAuditoriaRN->consultar($objRegraAuditoriaDTO);
			if ($objRegraAuditoriaDTO===null){
				throw new InfraException("Registro não encontrado.");
			}
			
			//Carrega combos permitindo sistema e orgao da RegraAuditoria
			$strItensSelOrgaoSistema = InfraINT::montarItemSelect($objRegraAuditoriaDTO->getNumIdOrgaoSistema(),$objRegraAuditoriaDTO->getStrSiglaOrgaoSistema(),true);
			$strItensSelSistema = InfraINT::montarItemSelect($objRegraAuditoriaDTO->getNumIdSistema(), $objRegraAuditoriaDTO->getStrSiglaSistema(),true);
			
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }


	//Na consulta posiciona diretamente não precisa buscar no banco
	if ($_GET['acao']!='regra_auditoria_consultar'){
		$strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaAutorizados('null','&nbsp;',$objRegraAuditoriaDTO->getNumIdOrgaoSistema());	
		$strItensSelSistema = SistemaINT::montarSelectSiglaAutorizados('null','&nbsp;', $objRegraAuditoriaDTO->getNumIdSistema(), $objRegraAuditoriaDTO->getNumIdOrgaoSistema());
	}	
	
	//AJAX
  $strLinkAjaxSistemas = SessaoSip::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=sistema_montar_select_sigla_autorizados');  
  $strLinkAjaxRecurso = SessaoSip::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=recurso_auto_completar_nome');     	 
  $strLinkRecursoSelecao = SessaoSip::getInstance()->assinarLink('controlador.php?acao=recurso_selecionar_auditoria&acao_origem='.$_GET['acao'].'&id_orgao_sistema='.$objRegraAuditoriaDTO->getNumIdOrgaoSistema().'&id_sistema='.$objRegraAuditoriaDTO->getNumIdSistema().'&tipo_selecao=2&id_object=objLupaRecursos');
	
  $strItensSelRecursos = RelRegraAuditoriaRecursoINT::montarSelectRecursos($objRegraAuditoriaDTO->getNumIdRegraAuditoria());
  
	if ($objRegraAuditoriaDTO->getNumIdOrgaoSistema()==null || $objRegraAuditoriaDTO->getNumIdSistema()==null){
		$strExibirLupa = 'visibility:hidden;';
	}
	
	
}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - Regra de Auditoria');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
#lblOrgaoSistema {position:absolute;left:0%;top:0%;width:22%;}
#selOrgaoSistema {position:absolute;left:0%;top:14%;width:22%;}

#lblSistema {position:absolute;left:0%;top:33%;width:22%;}
#selSistema {position:absolute;left:0%;top:47%;width:22%;}

#lblDescricao {position:absolute;left:0%;top:65%;width:80%;}
#txtDescricao {position:absolute;left:0%;top:79%;width:80%;}

#lblRecursos {position:absolute;left:0%;top:0%;}
#txtRecurso {position:absolute;left:0%;top:5%;width:47%;border:.1em solid #666;}
#selRecursos {position:absolute;left:0%;top:12%;width:81%;}
#imgLupaRecursos {position:absolute;left:82%;top:12%;<?=$strExibirLupa?>}
#imgExcluirRecursos {position:absolute;left:82%;top:18%;<?=$strExibirLupa?>}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

var objAjaxSistemas = null;
var objLupaRecursos = null;

function inicializar(){
  //COMBO DE SISTEMAS 
  objAjaxSistemas = new infraAjaxMontarSelectDependente('selOrgaoSistema','selSistema','<?=$strLinkAjaxSistemas?>');
  objAjaxSistemas.prepararExecucao = function(){
    return infraAjaxMontarPostPadraoSelect('null','','') + '&idOrgaoSistema='+document.getElementById('selOrgaoSistema').value;
  }
  objAjaxSistemas.processarResultado = function(){
    //alert('Carregou sistemas.');
    infraSelectLimpar('selRecursos');
  }
  
  if ('<?=$_GET['acao']?>'=='regra_auditoria_cadastrar' && '<?=$numIdSistema?>'==''){
    objAjaxSistemas.executar();
  }
  
  objLupaRecursos = new infraLupaSelect('selRecursos','hdnRecursos','<?=$strLinkRecursoSelecao?>');
  
  objAutoCompletarRecurso = new infraAjaxAutoCompletar('hdnIdRecurso','txtRecurso','<?=$strLinkAjaxRecurso?>');
  //objAutoCompletarRecurso.maiusculas = true;
  //objAutoCompletarRecurso.mostrarAviso = true;
  //objAutoCompletarRecurso.tempoAviso = 1000;
  //objAutoCompletarRecurso.tamanhoMinimo = 3;
  objAutoCompletarRecurso.limparCampo = true;
  //objAutoCompletarRecurso.bolExecucaoAutomatica = false;

  objAutoCompletarRecurso.prepararExecucao = function(){
    return 'nome='+document.getElementById('txtRecurso').value + '&idSistema='+document.getElementById('selSistema').value;
  };
  
  objAutoCompletarRecurso.processarResultado = function(id,descricao,complemento){
    if (id!=''){
      var options = document.getElementById('selRecursos').options;
      
      for(var i=0;i < options.length;i++){
        if (options[i].value == id){
          self.setTimeout('alert(\'Recurso já consta na lista.\')',100);
          break;
        }
      }
      
      if (i==options.length){
      
        for(i=0;i < options.length;i++){
         options[i].selected = false;
        }
      
        opt = infraSelectAdicionarOption(document.getElementById('selRecursos'),descricao,id);
        
        objLupaRecursos.atualizar();
        
        opt.selected = true;
      }
      
      document.getElementById('txtRecurso').value = '';
      document.getElementById('txtRecurso').focus();
    }
     
  };
  
  
  if ('<?=$_GET['acao']?>'=='regra_auditoria_cadastrar'){
    document.getElementById('selOrgaoSistema').focus();
  } else if ('<?=$_GET['acao']?>'=='regra_auditoria_consultar'){
    infraDesabilitarCamposAreaDados();
  }
}

function trocarOrgaoSistema(){
  infraSelectLimpar('selSistema');
  infraSelectLimpar('selRecursos');
  document.getElementById('frmRegraAuditoriaCadastro').submit();
}


function trocarSistema(){
  infraSelectLimpar('selRecursos');
  document.getElementById('frmRegraAuditoriaCadastro').submit();
}

function OnSubmitForm() {
	
	if (!validarForm()){
		return false;
	}
  
	return true;
}

function validarForm(){
	
  if (!infraSelectSelecionado(document.getElementById('selOrgaoSistema'))) {
    alert('Selecione Órgão do Sistema.');
    document.getElementById('selOrgaoSistema').focus();
    return false;
  }
	
  if (!infraSelectSelecionado(document.getElementById('selSistema'))) {
    alert('Selecione um Sistema.');
    document.getElementById('selSistema').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtDescricao'))=='') {
    alert('Informe a Descrição.');
    document.getElementById('txtDescricao').focus();
    return false;
  }

  return true;
}

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmRegraAuditoriaCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSip::getInstance()->assinarLink('regra_auditoria_cadastro.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSip::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSip::getInstance()->montarAreaValidacao();

?>
  <div id="divCabecalho" class="infraAreaDados" style="height:15em;">

  <label id="lblOrgaoSistema" for="selOrgaoSistema" accesskey="" class="infraLabelObrigatorio">Órgão do Sistema:</label>
  <select id="selOrgaoSistema" name="selOrgaoSistema" onchange="trocarOrgaoSistema();" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
  <?=$strItensSelOrgaoSistema?>
  </select>
	
  <label id="lblSistema" for="selSistema" accesskey="S" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">S</span>istema:</label>
  <select id="selSistema" name="selSistema" onchange="trocarSistema();" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
  <?=$strItensSelSistema?>
  </select>
  
  <label id="lblDescricao" for="txtDescricao" accesskey="D" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">D</span>escrição:</label>
  <input type="text" id="txtDescricao" name="txtDescricao" class="infraText" onkeypress="return infraLimitarTexto(this,event,250);" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" value="<?=PaginaSip::tratarHTML($objRegraAuditoriaDTO->getStrDescricao());?>" />
  </div>
  
  <div id="divRecursos" class="infraAreaDados" style="height:37em;">
	 	<label id="lblRecursos" for="selRecursos" class="infraLabelOpcional">Recursos:</label>
	  <input type="text" id="txtRecurso" name="txtRecurso" class="infraText" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />
	  <input type="hidden" id="hdnIdRecurso" name="hdnIdRecurso" class="infraText" value="" />
	  <select id="selRecursos" name="selRecursos" size="<?=PaginaSip::getInstance()->isBolNavegadorChrome()?'19':'20'?>" multiple="multiple" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
	  <?=$strItensSelRecursos?>
	  </select>
	  <img id="imgLupaRecursos" onclick="objLupaRecursos.selecionar(700,500);" src="<?=PaginaSip::getInstance()->getIconePesquisar()?>" alt="Selecionar Recursos" title="Selecionar Recursos" class="infraImg" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />
	  <img id="imgExcluirRecursos" onclick="objLupaRecursos.remover();" src="<?=PaginaSip::getInstance()->getIconeRemover()?>" alt="Remover Recursos Selecionados" title="Remover Recursos Selecionados" class="infraImg" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />
  </div>  
  
  <input type="hidden" id="hdnIdRegraAuditoria" name="hdnIdRegraAuditoria" value="<?=$objRegraAuditoriaDTO->getNumIdRegraAuditoria();?>" />
  <input type="hidden" id="hdnIdOrgaoSistema" name="hdnIdOrgaoSistema" value="<?=$objRegraAuditoriaDTO->getNumIdOrgaoSistema();?>" />
  <input type="hidden" id="hdnIdSistema" name="hdnIdSistema" value="<?=$objRegraAuditoriaDTO->getNumIdSistema();?>" />
  <input type="hidden" id="hdnRecursos" name="hdnRecursos" value="<?=$_POST['hdnRecursos']?>" />
  <?
	
  PaginaSip::getInstance()->montarAreaDebug();
  //PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>