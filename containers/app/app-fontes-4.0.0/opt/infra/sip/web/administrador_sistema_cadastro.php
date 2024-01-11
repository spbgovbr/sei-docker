<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 30/11/2006 - criado por mga
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

  PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistema','selSistema','selOrgaoUsuario'));
  
  $objAdministradorSistemaDTO = new AdministradorSistemaDTO(true);

  $arrComandos = array();

  switch($_GET['acao']){
    case 'administrador_sistema_cadastrar':
      $strTitulo = 'Novo Administrador';
      $arrComandos[] = '<input type="submit" name="sbmCadastrarAdministradorSistema" value="Salvar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=administrador_sistema_listar').'\';" class="infraButton" />';

			//ORGAO SISTEMA
			$numIdOrgaoSistema = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema');
			if ($numIdOrgaoSistema!==''){
				$objAdministradorSistemaDTO->setNumIdOrgaoSistema($numIdOrgaoSistema);
			}else{
				$objAdministradorSistemaDTO->setNumIdOrgaoSistema(null);
			}
			
			//SISTEMA
			$numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema');
			if ($numIdSistema!==''){
				$objAdministradorSistemaDTO->setNumIdSistema($numIdSistema);
			}else{
				$objAdministradorSistemaDTO->setNumIdSistema(null);
			}
			
			//ORGAO USUARIO
			$numIdOrgaoUsuario = PaginaSip::getInstance()->recuperarCampo('selOrgaoUsuario');
			if ($numIdOrgaoUsuario!==''){
				$objAdministradorSistemaDTO->setNumIdOrgaoUsuario($numIdOrgaoUsuario);
			}else{
				$objAdministradorSistemaDTO->setNumIdOrgaoUsuario(null);
			}
							
			//USUARIO
			$objAdministradorSistemaDTO->setNumIdUsuario($_POST['hdnIdUsuario']);
		  $objAdministradorSistemaDTO->setStrSiglaUsuario($_POST['txtUsuario']);
			
      if (isset($_POST['sbmCadastrarAdministradorSistema'])) {
				try {
					$objAdministradorSistemaRN = new AdministradorSistemaRN();
					$objAdministradorSistemaDTO = $objAdministradorSistemaRN->cadastrar($objAdministradorSistemaDTO);
					header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao=administrador_sistema_listar'));
					die;
				}catch(Exception $e){
					PaginaSip::getInstance()->processarExcecao($e);
				}
      }

			
      break;
    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }
  
  $strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaTodos('null','&nbsp;',$numIdOrgaoSistema);
  $strItensSelSistema = SistemaINT::montarSelectSiglaSip('null','&nbsp;', $numIdSistema, $numIdOrgaoSistema);
  $strItensSelOrgaoUsuario = OrgaoINT::montarSelectSiglaTodos('null','&nbsp;',$numIdOrgaoUsuario);	
  //$strItensSelUsuario = UsuarioINT::montarSelectSigla('null','&nbsp;',$_POST['hdnIdUsuario'], $numIdOrgaoUsuario);
  $strLinkAjaxSistemas = SessaoSip::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=sistema_montar_select_sigla_sip');  
  $strLinkAjaxUsuario = SessaoSip::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_auto_completar_sigla_nome');   
}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - Administrador');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
#lblOrgaoSistema {position:absolute;left:0%;top:0%;width:20%;}
#selOrgaoSistema {position:absolute;left:0%;top:6%;width:20%;}

#lblSistema {position:absolute;left:0%;top:16%;width:15%;}
#selSistema {position:absolute;left:0%;top:22%;width:15%;}

#lblOrgaoUsuario {position:absolute;left:0%;top:32%;width:20%;}
#selOrgaoUsuario {position:absolute;left:0%;top:38%;width:20%;}

#lblUsuario {position:absolute;left:0%;top:48%;width:20%;}
#txtUsuario {position:absolute;left:0%;top:54%;width:20%;}
<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

var objAjaxSistemas = null;
var objAjaxUsuario = null;

function inicializar(){

  //COMBO DE SISTEMAS 
  objAjaxSistemas = new infraAjaxMontarSelectDependente('selOrgaoSistema','selSistema','<?=$strLinkAjaxSistemas?>');
  objAjaxSistemas.prepararExecucao = function(){
    return infraAjaxMontarPostPadraoSelect('null','','<?=$numIdSistema?>') + '&idOrgaoSistema='+document.getElementById('selOrgaoSistema').value;
  }
  objAjaxSistemas.processarResultado = function(){
    //alert('Carregou sistemas.');
  }
  objAjaxSistemas.executar();

  //AUTO COMPLETAR USUARIO
  objAjaxUsuario = new infraAjaxAutoCompletar('hdnIdUsuario','txtUsuario','<?=$strLinkAjaxUsuario?>');
  objAjaxUsuario.prepararExecucao = function(){
    if (!infraSelectSelecionado('selOrgaoUsuario')){
      alert('Selecione Órgão do Usuário.');
      document.getElementById('selOrgaoUsuario').focus();
      return false;
    }
    return 'sigla='+document.getElementById('txtUsuario').value + '&idOrgao='+document.getElementById('selOrgaoUsuario').value;
  };
  objAjaxUsuario.processarResultado = function(id,descricao,complemento){
    //document.getElementById('lblUsuarioNome').innerHTML = complemento;
  };
  objAjaxUsuario.selecionar('<?=$objAdministradorSistemaDTO->getNumIdUsuario();?>','<?=$objAdministradorSistemaDTO->getStrSiglaUsuario();?>');
  
  if ('<?=$_GET['acao']?>'=='administrador_sistema_cadastrar'){
    document.getElementById('selOrgaoSistema').focus();
  } else if ('<?=$_GET['acao']?>'=='administrador_sistema_consultar'){
    infraDesabilitarCamposAreaDados();
  }
}

function OnSubmitForm() {
	
  if (!infraSelectSelecionado('selOrgaoSistema')) {
    alert('Selecione Órgão do Sistema.');
    document.getElementById('selOrgaoSistema').focus();
    return false;
  }

  if (!infraSelectSelecionado('selSistema')) {
    alert('Selecione um Sistema.');
    document.getElementById('selSistema').focus();
    return false;
  }

  if (!infraSelectSelecionado('selOrgaoUsuario')) {
    alert('Selecione Órgão do Usuário.');
    document.getElementById('selOrgaoUsuario').focus();
    return false;
  }
	
  if (infraTrim(document.getElementById('hdnIdUsuario').value)=='') {
    alert('Informe um Usuário.');
    document.getElementById('txtUsuario').focus();
    return false;
  }

  return true;
}

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAdministradorSistemaCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSip::getInstance()->assinarLink('administrador_sistema_cadastro.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSip::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSip::getInstance()->montarAreaValidacao();
PaginaSip::getInstance()->abrirAreaDados('32em');
?>

  <label id="lblOrgaoSistema" for="selOrgaoSistema" accesskey="r" class="infraLabelObrigatorio">Ó<span class="infraTeclaAtalho">r</span>gão do Sistema:</label>
  <select id="selOrgaoSistema" name="selOrgaoSistema" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" >
  <?=$strItensSelOrgaoSistema?>
  </select>

  <label id="lblSistema" for="selSistema" accesskey="S" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">S</span>istema:</label>
  <select id="selSistema" name="selSistema" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" >
  <?=$strItensSelSistema?>
  </select>

  <label id="lblOrgaoUsuario" for="selOrgaoUsuario" accesskey="o" class="infraLabelObrigatorio">Órgã<span class="infraTeclaAtalho">o</span> do Usuário:</label>
  <select id="selOrgaoUsuario" name="selOrgaoUsuario" onchange="objAjaxUsuario.limpar();" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" >
  <?=$strItensSelOrgaoUsuario?>
  </select>
	
  <label id="lblUsuario" for="txtUsuario" accesskey="u" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">U</span>suário:</label>
  <input type="text" id="txtUsuario" name="txtUsuario" class="infraText" value="<?=PaginaSip::tratarHTML($objAdministradorSistemaDTO->getStrSiglaUsuario())?>" maxlength="100" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?> />
  
  <input type="hidden" id="hdnIdUsuario" name="hdnIdUsuario" value="<?=$objAdministradorSistemaDTO->getNumIdUsuario();?>" />
  <input type="hidden" id="hdnIdSistema" name="hdnIdSistema" value="<?=$objAdministradorSistemaDTO->getNumIdSistema();?>" />
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