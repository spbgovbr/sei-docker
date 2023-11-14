<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/12/2006 - criado por mga
*
*
*/

try {
  require_once dirname(__FILE__).'/Sip.php';

  session_start();
	
  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  //SessaoSip::getInstance()->validarSessao();
  SessaoSip::getInstance()->validarLink();

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  if (isset($_GET['id_orgao_sistema_origem']) && isset($_GET['id_sistema_origem'])){
    PaginaSip::getInstance()->salvarCampo('selOrgaoSistemaOrigem',$_GET['id_orgao_sistema_origem']);
    PaginaSip::getInstance()->salvarCampo('selSistemaOrigem',$_GET['id_sistema_origem']);
  } else {
    PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistemaOrigem','selSistemaOrigem'));
  }
  
  $objClonarSistemaDTO = new ClonarSistemaDTO();

  $arrComandos = array();
  
  switch($_GET['acao']){
    case 'sistema_clonar':
      
      $strTitulo = 'Clonar Sistema';
      $arrComandos[] = '<input type="submit" name="sbmClonarSistema" value="Clonar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().PaginaSip::getInstance()->montarAncora($_GET['id_sistema_origem'])).'\';" class="infraButton" />';
			
			$numIdOrgao = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistemaOrigem');
			if ($numIdOrgao!==''){
				$objClonarSistemaDTO->setNumIdOrgaoSistemaOrigem($numIdOrgao);
			}else{
				$objClonarSistemaDTO->setNumIdOrgaoSistemaOrigem(null);
			}

			$numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistemaOrigem');
			if ($numIdSistema!==''){
				$objClonarSistemaDTO->setNumIdSistemaOrigem($numIdSistema);
			}else{
				$objClonarSistemaDTO->setNumIdSistemaOrigem(null);
			}
			
			$objClonarSistemaDTO->setNumIdOrgaoSistemaDestino($_POST['selOrgaoSistemaDestino']);
			$objClonarSistemaDTO->setStrSiglaDestino($_POST['txtSiglaDestino']);
			
      if (isset($_POST['sbmClonarSistema'])) {
        try{
          $objSistemaRN = new SistemaRN();
          $objSistemaDTO = $objSistemaRN->clonar($objClonarSistemaDTO);
          PaginaSip::getInstance()->setStrMensagem('Sistema "'.$objClonarSistemaDTO->getStrSiglaDestino().'" clonado com sucesso.');
          header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao=sistema_listar&acao_origem='.$_GET['acao'].PaginaSip::getInstance()->montarAncora($objSistemaDTO->getNumIdSistema())));
          die;
        }catch(Exception $e){
          PaginaSip::getInstance()->processarExcecao($e);
        }
			}
      break;
      
    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelOrgaoSistemaOrigem = OrgaoINT::montarSelectSiglaAdministrados('null','&nbsp;', $numIdOrgao);
  $strItensSelSistemaOrigem = SistemaINT::montarSelectSiglaAdministrados('null','&nbsp;', $numIdSistema, $numIdOrgao);
  $strItensSelOrgaoDestino = OrgaoINT::montarSelectSiglaTodos('null','&nbsp;', $_POST['selOrgaoSistemaDestino']);
	
}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
} 

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - Sistemas');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>

#lblOrgaoSistemaOrigem {position:absolute;left:0%;top:0%;width:20%;}
#selOrgaoSistemaOrigem {position:absolute;left:0%;top:6%;width:20%;}

#lblSistemaOrigem {position:absolute;left:0%;top:16%;width:20%;}
#selSistemaOrigem {position:absolute;left:0%;top:22%;width:20%;}

#lblOrgaoSistemaDestino {position:absolute;left:0%;top:32%;width:20%;}
#selOrgaoSistemaDestino {position:absolute;left:0%;top:38%;width:20%;}

#lblSiglaDestino {position:absolute;left:0%;top:48%;width:15%;}
#txtSiglaDestino {position:absolute;left:0%;top:54%;width:15%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

function trocarOrgaoSistema(obj){
	document.getElementById('selSistemaOrigem').value='null';
	obj.form.submit();
}

function OnSubmitForm() {
  return validarForm();
}

function validarForm() {
  if (!infraSelectSelecionado(document.getElementById('selOrgaoSistemaOrigem'))) {
    alert('Selecione Órgão do Sistema Origem.');
    document.getElementById('selOrgaoSistemaOrigem').focus();
    return false;
  }

  if (!infraSelectSelecionado(document.getElementById('selSistemaOrigem'))) {
    alert('Selecione um Sistema Origem.');
    document.getElementById('selSistemaOrigem').focus();
    return false;
  }

  if (!infraSelectSelecionado(document.getElementById('selOrgaoSistemaDestino'))) {
    alert('Selecione Órgão do Sistema Destino.');
    document.getElementById('selOrgaoSistemaDestino').focus();
    return false;
  }
	
  if (infraTrim(document.getElementById('txtSiglaDestino').value)=='') {
    alert('Informe Sigla de Destino.');
    document.getElementById('txtSiglaDestino').focus();
    return false;
  }

  infraExibirAviso(false);
  
  return true;
}

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody('Clonar Sistema');
?>
<form id="frmRecursoLista" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSip::getInstance()->assinarLink('sistema_clonar.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSip::getInstance()->montarBarraLocalizacao('Clonar Sistema');
  PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSip::getInstance()->abrirAreaDados('30em');
  ?>
	
  <label id="lblOrgaoSistemaOrigem" for="selOrgaoSistemaOrigem" accesskey="o" class="infraLabelObrigatorio">Órgã<span class="infraTeclaAtalho">o</span> Origem:</label>
  <select id="selOrgaoSistemaOrigem" name="selOrgaoSistemaOrigem" onchange="trocarOrgaoSistema(this);" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" >
  <?=$strItensSelOrgaoSistemaOrigem?>
  </select>


  <label id="lblSistemaOrigem" for="selSistemaOrigem" accesskey="S" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">S</span>istema Origem:</label>
  <select id="selSistemaOrigem" name="selSistemaOrigem" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
  <?=$strItensSelSistemaOrigem?>
  </select>

  <label id="lblOrgaoSistemaDestino" for="selOrgaoSistemaDestino" accesskey="D" class="infraLabelObrigatorio">Órgão <span class="infraTeclaAtalho">D</span>estino:</label>
  <select id="selOrgaoSistemaDestino" name="selOrgaoSistemaDestino" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" >
  <?=$strItensSelOrgaoDestino?>
  </select>
	
	
  <label id="lblSiglaDestino" for="txtSiglaDestino" accesskey="S" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">S</span>igla Destino:</label>
  <input type="text" id="txtSiglaDestino" name="txtSiglaDestino" class="infraText" value="<?=PaginaSip::tratarHTML($objClonarSistemaDTO->getStrSiglaDestino());?>" maxlength="15" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />


  <?
  PaginaSip::getInstance()->fecharAreaDados();
  //PaginaSip::getInstance()->montarAreaDebug();
  //PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>