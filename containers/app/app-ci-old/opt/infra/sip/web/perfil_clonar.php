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
	
  if (isset($_GET['id_orgao_sistema']) && isset($_GET['id_sistema']) && isset($_GET['id_perfil_origem'])){
    PaginaSip::getInstance()->salvarCampo('selOrgaoSistema',$_GET['id_orgao_sistema']);
    PaginaSip::getInstance()->salvarCampo('selSistema',$_GET['id_sistema']);
    PaginaSip::getInstance()->salvarCampo('selPerfilOrigem',$_GET['id_perfil_origem']);
  } else {
    PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistema','selSistema','selPerfilOrigem'));
  }
  
  
  $arrComandos = array();

	$objClonarPerfilDTO = new ClonarPerfilDTO(true);
	

  switch($_GET['acao']){
    case 'perfil_clonar':
      $strTitulo = 'Clonar Perfil';
      $arrComandos[] = '<input type="submit" name="sbmClonarPerfil" value="Clonar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem=perfil_clonar'.PaginaSip::getInstance()->montarAncora($_GET['id_perfil_origem'].'-'.$_GET['id_sistema'])).'\';" class="infraButton" />';

			$numIdOrgao = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema',SessaoSip::getInstance()->getNumIdOrgaoSistema());
			if ($numIdOrgao!==''){
				$objClonarPerfilDTO->setNumIdOrgaoSistema($numIdOrgao);
			}else{
				$objClonarPerfilDTO->setNumIdOrgaoSistema(null);
			}

			$numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema');
			if ($numIdSistema!==''){
				$objClonarPerfilDTO->setNumIdSistema($numIdSistema);
			}else{
				$objClonarPerfilDTO->setNumIdSistema(null);
			}
			
			$numIdPerfil = PaginaSip::getInstance()->recuperarCampo('selPerfilOrigem');
			if ($numIdPerfil!==''){
				$objClonarPerfilDTO->setNumIdPerfilOrigem($numIdPerfil);
			}else{
				$objClonarPerfilDTO->setNumIdPerfilOrigem(null);
			}
			$objClonarPerfilDTO->setStrPerfilDestino($_POST['txtPerfilDestino']);
      
      if (isset($_POST['sbmClonarPerfil'])) {
        try{
          $objPerfilRN = new PerfilRN();
          $objPerfilDTO = $objPerfilRN->clonar($objClonarPerfilDTO);
          PaginaSip::getInstance()->setStrMensagem('Perfil "'.$objClonarPerfilDTO->getStrPerfilDestino().'" clonado com sucesso.');
          header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao=perfil_listar&acao_origem='.$_GET['acao'].PaginaSip::getInstance()->montarAncora($objPerfilDTO->getNumIdPerfil().'-'.$objPerfilDTO->getNumIdSistema())));
          die;
        }catch(Exception $e){
          PaginaSip::getInstance()->processarExcecao($e);
        }
			}
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

	$strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaAdministrados('null','&nbsp;',$objClonarPerfilDTO->getNumIdOrgaoSistema());
  $strItensSelSistema = SistemaINT::montarSelectSiglaAdministrados('null','&nbsp;',$objClonarPerfilDTO->getNumIdSistema(), $objClonarPerfilDTO->getNumIdOrgaoSistema());
  $strItensSelPerfilOrigem = PerfilINT::montarSelectNome('null','&nbsp;',$objClonarPerfilDTO->getNumIdPerfilOrigem(), $objClonarPerfilDTO->getNumIdSistema());

}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - Clonar Perfil');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
#lblOrgaoSistema {position:absolute;left:0%;top:0%;width:25%;}
#selOrgaoSistema {position:absolute;left:0%;top:6%;width:25%;}

#lblSistema {position:absolute;left:0%;top:16%;width:25%;}
#selSistema {position:absolute;left:0%;top:22%;width:25%;}

#lblPerfilOrigem {position:absolute;left:0%;top:32%;width:40%;}
#selPerfilOrigem {position:absolute;left:0%;top:38%;width:40%;}

#lblPerfilDestino {position:absolute;left:0%;top:48%;width:40%;}
#txtPerfilDestino {position:absolute;left:0%;top:54%;width:40%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

function OnSubmitForm() {
  
  if (!validarForm()){
    return false;
  }
  
  return true;
}

function validarForm(){
  if (!infraSelectSelecionado(document.getElementById('selOrgaoSistema'))) {
    alert('Selecione um Órgão do Sistema.');
    document.getElementById('selOrgaoSistema').focus();
    return false;
  }
	
  if (!infraSelectSelecionado(document.getElementById('selSistema'))) {
    alert('Selecione um Sistema.');
    document.getElementById('selSistema').focus();
    return false;
  }

  if (!infraSelectSelecionado(document.getElementById('selPerfilOrigem'))) {
    alert('Selecione um Perfil de Origem.');
    document.getElementById('selPerfilOrigem').focus();
    return false;
  }
  
  if (infraTrim(document.getElementById('txtPerfilDestino').value)=='') {
    alert('Informe Perfil de Destino.');
    document.getElementById('txtPerfilDestino').focus();
    return false;
  }
  
  return true;
}

function trocarOrgaoSistema(obj){
	document.getElementById('selSistema').value='null';
	trocarSistema(obj);
}

function trocarSistema(obj){
	document.getElementById('selPerfilOrigem').value='null';
	obj.form.submit();
}

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo);
?>
<form id="frmPerfilClonar" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSip::getInstance()->assinarLink('perfil_clonar.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSip::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSip::getInstance()->montarAreaValidacao();
PaginaSip::getInstance()->abrirAreaDados('30em');
?>
  <label id="lblOrgaoSistema" for="selOrgaoSistema" accesskey="r" class="infraLabelObrigatorio">Ó<span class="infraTeclaAtalho">r</span>gão do Sistema:</label>
  <select id="selOrgaoSistema" name="selOrgaoSistema" onchange="trocarOrgaoSistema(this);" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" >
  <?=$strItensSelOrgaoSistema?>
  </select>

  <label id="lblSistema" for="selSistema" accesskey="S" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">S</span>istema:</label>
  <select id="selSistema" name="selSistema" onchange="trocarSistema(this);" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
  <?=$strItensSelSistema?>
  </select>

  <label id="lblPerfilOrigem" for="selPerfilOrigem" accesskey="O" class="infraLabelObrigatorio">Perfil <span class="infraTeclaAtalho">O</span>rigem:</label>
  <select id="selPerfilOrigem" name="selPerfilOrigem" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
  <?=$strItensSelPerfilOrigem?>
  </select>
	
  <label id="lblPerfilDestino" for="txtPerfilDestino" accesskey="D" class="infraLabelObrigatorio">Perfil <span class="infraTeclaAtalho">D</span>estino:</label>
  <input type="text" id="txtPerfilDestino" name="txtPerfilDestino" class="infraText" value="<?=PaginaSip::tratarHTML($objClonarPerfilDTO->getStrPerfilDestino());?>" maxlength="50" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />
	
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