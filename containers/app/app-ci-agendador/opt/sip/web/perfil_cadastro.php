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

  PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistema','selSistema'));

  $objPerfilDTO = new PerfilDTO(true);

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'perfil_cadastrar':
      $strTitulo = 'Novo Perfil';
      $arrComandos[] = '<input type="submit" name="sbmCadastrarPerfil" value="Salvar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=perfil_listar').'\';" class="infraButton" />';

			$objPerfilDTO->setNumIdPerfil(null);
			
			//ORGAO
			$numIdOrgao = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema',SessaoSip::getInstance()->getNumIdOrgaoSistema());
			if ($numIdOrgao!==''){
				$objPerfilDTO->setNumIdOrgaoSistema($numIdOrgao);
			}else{
				$objPerfilDTO->setNumIdOrgaoSistema(null);
			}

			//SISTEMA
			$numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema');
			if ($numIdSistema!==''){
				$objPerfilDTO->setNumIdSistema($numIdSistema);
			}else{
				$objPerfilDTO->setNumIdSistema(null);
			}
			
			//$objPerfilDTO->setNumIdSistema($_POST['selSistema']);
			$objPerfilDTO->setStrNome($_POST['txtNome']);
			$objPerfilDTO->setStrDescricao($_POST['txaDescricao']);
			$objPerfilDTO->setStrSinCoordenado(PaginaSip::getInstance()->getCheckbox($_POST['chkCoordenado']));
		  $objPerfilDTO->setStrSinAtivo("S");
			
      if (isset($_POST['sbmCadastrarPerfil'])) {
				try{
					$objPerfilRN = new PerfilRN();
					$objPerfilDTO = $objPerfilRN->cadastrar($objPerfilDTO);
					PaginaSip::getInstance()->setStrMensagem('Perfil "'.$objPerfilDTO->getStrNome().'" cadastrado com sucesso.');
					header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao=perfil_listar&acao_origem='.$_GET['acao'].PaginaSip::montarAncora($objPerfilDTO->getNumIdPerfil().'-'.$objPerfilDTO->getNumIdSistema())));
					die;
				}catch(Exception $e){
					PaginaSip::getInstance()->processarExcecao($e);
				}
      } 
      break;

    case 'perfil_alterar':
      $strTitulo = 'Alterar Perfil';
      $arrComandos[] = '<input type="submit" name="sbmAlterarPerfil" value="Salvar" class="infraButton" />';

      $strDesabilitar = 'disabled="disabled"';

			if (isset($_GET['id_perfil']) && isset($_GET['id_sistema'])){
        $objPerfilDTO->setNumIdPerfil($_GET['id_perfil']);
        $objPerfilDTO->setNumIdSistema($_GET['id_sistema']);
        $objPerfilDTO->retTodos();
        $objPerfilRN = new PerfilRN();
        $arrObjPerfilDTO = $objPerfilRN->listarAdministrados($objPerfilDTO);
        if (count($arrObjPerfilDTO)!==1){
          throw new InfraException("Registro não encontrado.");
        }
				$objPerfilDTO = $arrObjPerfilDTO[0];
			} else {
				$objPerfilDTO->setNumIdPerfil($_POST['hdnIdPerfil']);
				$objPerfilDTO->setNumIdOrgaoSistema($_POST['hdnIdOrgaoSistema']);
				$objPerfilDTO->setNumIdSistema($_POST['hdnIdSistema']);
				$objPerfilDTO->setStrNome($_POST['txtNome']);
				$objPerfilDTO->setStrDescricao($_POST['txaDescricao']);
			  $objPerfilDTO->setStrSinCoordenado(PaginaSip::getInstance()->getCheckbox($_POST['chkCoordenado']));
				$objPerfilDTO->setStrSinAtivo("S");
			}

			$strAncora = PaginaSip::getInstance()->montarAncora($objPerfilDTO->getNumIdPerfil().'-'.$objPerfilDTO->getNumIdSistema());
			
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=perfil_listar'.$strAncora).'\';" class="infraButton" />';
      
      if (isset($_POST['sbmAlterarPerfil'])) {
				try{
					$objPerfilRN = new PerfilRN();
					$objPerfilRN->alterar($objPerfilDTO);
					PaginaSip::getInstance()->setStrMensagem('Perfil "'.$objPerfilDTO->getStrNome().'" alterado com sucesso.');
					header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao=perfil_listar&acao_origem='.$_GET['acao'].$strAncora));
					die;
				}catch(Exception $e){
					PaginaSip::getInstance()->processarExcecao($e);
				}
      }
			
      break;

    case 'perfil_consultar':
      $strTitulo = "Consultar Perfil";
      $arrComandos[] = '<input type="button" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().PaginaSip::getInstance()->montarAncora($_GET['id_perfil'].'-'.$_GET['id_sistema'])).'\';" class="infraButton" />';
			$objPerfilDTO->setBolExclusaoLogica(false);
      $objPerfilDTO->setNumIdPerfil($_GET['id_perfil']);
			$objPerfilDTO->setNumIdSistema($_GET['id_sistema']);
      $objPerfilDTO->retTodos();
      $objPerfilRN = new PerfilRN();
      $arrObjPerfilDTO = $objPerfilRN->listarAdministrados($objPerfilDTO);
      if (count($arrObjPerfilDTO)!=1){
        throw new InfraException("Registro não encontrado.");
      }
			$objPerfilDTO = $arrObjPerfilDTO[0]; 
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaAdministrados('null','&nbsp;',$objPerfilDTO->getNumIdOrgaoSistema());	
  $strItensSelSistema = SistemaINT::montarSelectSiglaAdministrados('null','&nbsp;', $objPerfilDTO->getNumIdSistema(), $objPerfilDTO->getNumIdOrgaoSistema());
	
}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - Perfil');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
#lblOrgaoSistema {position:absolute;left:0%;top:0%;width:20%;}
#selOrgaoSistema {position:absolute;left:0%;top:4%;width:20%;}

#lblSistema {position:absolute;left:0%;top:9%;width:20%;}
#selSistema {position:absolute;left:0%;top:13%;width:20%;}

#lblNome {position:absolute;left:0%;top:18%;width:80%;}
#txtNome {position:absolute;left:0%;top:22%;width:80%;}

#lblDescricao {position:absolute;left:0%;top:27%;width:80%;}
#txaDescricao {position:absolute;left:0%;top:31%;width:80%;}

#divSinCoordenado {position:absolute;left:0%;top:65%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='perfil_cadastrar'){
    document.getElementById('selOrgaoSistema').focus();
  } else if ('<?=$_GET['acao']?>'=='perfil_consultar'){
    infraDesabilitarCamposAreaDados();
  }
}

function OnSubmitForm() {
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

  if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('Informe Nome.');
    document.getElementById('txtNome').focus();
    return false;
  }

  return true;
}

function trocarOrgaoSistema(obj){
	document.getElementById('selSistema').value='null';
	obj.form.submit();
}

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmPerfilCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSip::getInstance()->assinarLink('perfil_cadastro.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSip::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSip::getInstance()->montarAreaValidacao();
PaginaSip::getInstance()->abrirAreaDados('54em');
?>
  <label id="lblOrgaoSistema" for="selOrgaoSistema" accesskey="r" class="infraLabelObrigatorio">Ó<span class="infraTeclaAtalho">r</span>gão do Sistema:</label>
  <select id="selOrgaoSistema" name="selOrgaoSistema" onchange="trocarOrgaoSistema(this);" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
  <?=$strItensSelOrgaoSistema?>
  </select>

  <label id="lblSistema" for="selSistema" accesskey="S" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">S</span>istema:</label>
  <select id="selSistema" name="selSistema" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
  <?=$strItensSelSistema?>
  </select>

  <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSip::tratarHTML($objPerfilDTO->getStrNome());?>" maxlength="100" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />
  
  <label id="lblDescricao" for="txaDescricao" accesskey="D" class="infraLabelOpcional"><span class="infraTeclaAtalho">D</span>escrição:</label>
  <textarea id="txaDescricao" name="txaDescricao" class="infraTextarea" rows="9" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"><?=PaginaSip::tratarHTML($objPerfilDTO->getStrDescricao());?></textarea>

  <div id="divSinCoordenado" class="infraDivCheckbox">
    <input type="checkbox" id="chkCoordenado" name="chkCoordenado" <?=PaginaSip::getInstance()->setCheckbox($objPerfilDTO->getStrSinCoordenado())?> class="infraCheckbox" />
  	<label id="lblCoordenado" accesskey="" for="chkCoordenado" class="infraLabelCheckbox">Disponível aos Coordenadores de Unidade</label>			
	</div>
	
  <input type="hidden" name="hdnIdPerfil" value="<?=$objPerfilDTO->getNumIdPerfil();?>" />
  <input type="hidden" name="hdnIdOrgaoSistema" value="<?=$objPerfilDTO->getNumIdOrgaoSistema();?>" />
  <input type="hidden" name="hdnIdSistema" value="<?=$objPerfilDTO->getNumIdSistema();?>" />
  <input type="hidden" name="hdnIdSistema" value="<?=$objPerfilDTO->getNumIdSistema();?>" />
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