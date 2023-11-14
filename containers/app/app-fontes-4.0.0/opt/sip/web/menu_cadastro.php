<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/01/2007 - criado por mga
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
  
  $objMenuDTO = new MenuDTO(true);

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'menu_cadastrar':
      $strTitulo = 'Novo Menu';
      $arrComandos[] = '<input type="submit" name="sbmCadastrarMenu" value="Salvar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=menu_listar').'\';" class="infraButton" />';
			
			$objMenuDTO->setNumIdMenu(null);
			
			//ORGAO
			$numIdOrgao = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema',SessaoSip::getInstance()->getNumIdOrgaoSistema());
			if ($numIdOrgao!==''){
				$objMenuDTO->setNumIdOrgaoSistema($numIdOrgao);
			}else{
				$objMenuDTO->setNumIdOrgaoSistema(null);
			}

			//SISTEMA
			$numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema');
			if ($numIdSistema!==''){
				$objMenuDTO->setNumIdSistema($numIdSistema);
			}else{
				$objMenuDTO->setNumIdSistema(null);
			}
			
      $objMenuDTO->setStrNome($_POST['txtNome']);
      $objMenuDTO->setStrDescricao($_POST['txtDescricao']);
      $objMenuDTO->setStrSinAtivo("S");

      if (isset($_POST['sbmCadastrarMenu'])) {
        try{
          $objMenuRN = new MenuRN();
          $objMenuDTO = $objMenuRN->cadastrar($objMenuDTO);
          header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao=menu_listar'));
          die;
        }catch(Exception $e){
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'menu_alterar':
      $strTitulo = 'Alterar Menu';
      $arrComandos[] = '<input type="submit" name="sbmAlterarMenu" value="Salvar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=menu_listar').'\';" class="infraButton" />';

      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_menu'])){
        $objMenuDTO->setNumIdMenu($_GET['id_menu']);
        $objMenuDTO->retTodos();
        $objMenuRN = new MenuRN();
        $objMenuDTO = $objMenuRN->consultar($objMenuDTO);
        if ($objMenuDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objMenuDTO->setNumIdMenu($_POST['hdnIdMenu']);
				$objMenuDTO->setNumIdOrgaoSistema($_POST['selOrgaoSistema']);
        $objMenuDTO->setNumIdSistema($_POST['selSistema']);
        $objMenuDTO->setStrNome($_POST['txtNome']);
        $objMenuDTO->setStrDescricao($_POST['txtDescricao']);
        $objMenuDTO->setStrSinAtivo("S");
      }

      if (isset($_POST['sbmAlterarMenu'])) {
        try{
          $objMenuRN = new MenuRN();
          $objMenuRN->alterar($objMenuDTO);
          header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao=menu_listar'));
          die;
        }catch(Exception $e){
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'menu_consultar':
      $strTitulo = "Consultar Menu";
      $arrComandos[] = '<input type="button" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=menu_listar').'\';" class="infraButton" />';
      $objMenuDTO->setNumIdMenu($_GET['id_menu']);
      $objMenuDTO->retTodos();
      $objMenuRN = new MenuRN();
      $objMenuDTO = $objMenuRN->consultar($objMenuDTO);
      if ($objMenuDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaAdministrados('null','&nbsp;',$objMenuDTO->getNumIdOrgaoSistema());	
	$strItensSelSistema = SistemaINT::montarSelectSiglaAdministrados('null','&nbsp;', $objMenuDTO->getNumIdSistema(), $objMenuDTO->getNumIdOrgaoSistema());

}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - Menu');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
#lblOrgaoSistema {position:absolute;left:0%;top:0%;width:20%;}
#selOrgaoSistema {position:absolute;left:0%;top:6%;width:20%;}

#lblSistema {position:absolute;left:0%;top:16%;width:20%;}
#selSistema {position:absolute;left:0%;top:22%;width:20%;}

#lblNome {position:absolute;left:0%;top:32%;width:30%;}
#txtNome {position:absolute;left:0%;top:38%;width:30%;}

#lblDescricao {position:absolute;left:0%;top:48%;width:80%;}
#txtDescricao {position:absolute;left:0%;top:54%;width:80%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='menu_cadastrar'){
    document.getElementById('selOrgaoSistema').focus();
  } else if ('<?=$_GET['acao']?>'=='menu_consultar'){
    infraDesabilitarCamposAreaDados();
  }
}

function OnSubmitForm() {
  return validarForm();
}

function validarForm() {
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
<form id="frmMenuCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSip::getInstance()->assinarLink(basename(__FILE__).'?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSip::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSip::getInstance()->montarAreaValidacao();
PaginaSip::getInstance()->abrirAreaDados('32em');
?>
  <label id="lblOrgaoSistema" for="selOrgaoSistema" accesskey="r" class="infraLabelObrigatorio">Ó<span class="infraTeclaAtalho">r</span>gão do Sistema:</label>
  <select id="selOrgaoSistema" name="selOrgaoSistema" onchange="trocarOrgaoSistema(this);" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
  <?=$strItensSelOrgaoSistema?>
  </select>

  <label id="lblSistema" for="selSistema" accesskey="S" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">S</span>istema:</label>
  <select id="selSistema" name="selSistema" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
  <?=$strItensSelSistema?>
  </select>

  <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSip::tratarHTML($objMenuDTO->getStrNome());?>" maxlength="50" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />

  <label id="lblDescricao" for="txtDescricao" accesskey="D" class="infraLabelOpcional"><span class="infraTeclaAtalho">D</span>escrição:</label>
  <input type="text" id="txtDescricao" name="txtDescricao" class="infraText" value="<?=PaginaSip::tratarHTML($objMenuDTO->getStrDescricao());?>" maxlength="200" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />

  <input type="hidden" id="hdnIdMenu" name="hdnIdMenu" value="<?=$objMenuDTO->getNumIdMenu();?>" />
	
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