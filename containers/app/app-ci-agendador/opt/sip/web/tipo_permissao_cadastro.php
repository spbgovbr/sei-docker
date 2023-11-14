<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/12/2006 - criado por mga
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

  $objTipoPermissaoDTO = new TipoPermissaoDTO();


  $arrComandos = array();

  switch($_GET['acao']){
    case 'tipo_permissao_cadastrar':
      $strTitulo = 'Novo Tipo de Permissão';
      $arrComandos[] = '<input type="submit" name="sbmCadastrarTipoPermissao" value="Salvar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=tipo_permissao_listar').'\';" class="infraButton" />';
			
			$objTipoPermissaoDTO->setNumIdTipoPermissao(null);
		  $objTipoPermissaoDTO->setStrDescricao($_POST['txtDescricao']);
			
      if (isset($_POST['sbmCadastrarTipoPermissao'])) {
				try{
					$objTipoPermissaoRN = new TipoPermissaoRN();
					$objTipoPermissaoDTO = $objTipoPermissaoRN->cadastrar($objTipoPermissaoDTO);
					header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao=tipo_permissao_listar&msg=Tipo de Permissão "'.$objTipoPermissaoDTO->getStrDescricao().'" cadastrado com sucesso.'));
					die;
				}catch(Exception $e){
					PaginaSip::getInstance()->processarExcecao($e);
				}
      }
      break;

    case 'tipo_permissao_alterar':
      $strTitulo = 'Alterar Tipo de Permissão';
      $arrComandos[] = '<input type="submit" name="sbmAlterarTipoPermissao" value="Salvar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=tipo_permissao_listar').'\';" class="infraButton" />';

			if (isset($_GET['id_tipo_permissao'])){
        $objTipoPermissaoDTO->setNumIdTipoPermissao($_GET['id_tipo_permissao']);
        $objTipoPermissaoDTO->retTodos();
        $objTipoPermissaoRN = new TipoPermissaoRN();
        $objTipoPermissaoDTO = $objTipoPermissaoRN->consultar($objTipoPermissaoDTO);
        if ($objTipoPermissaoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
			}else{
				$objTipoPermissaoDTO->setNumIdTipoPermissao($_POST['hdnIdTipoPermissao']);
				$objTipoPermissaoDTO->setStrDescricao($_POST['txtDescricao']);
			}
			
      if (isset($_POST['sbmAlterarTipoPermissao'])) {
				try{
					$objTipoPermissaoRN = new TipoPermissaoRN();
					$objTipoPermissaoRN->alterar($objTipoPermissaoDTO);
					header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao=tipo_permissao_listar'));
					die;
				}catch(Exception $e){
					PaginaSip::getInstance()->processarExcecao($e);
				}
      }
      break;

    case 'tipo_permissao_consultar':
      $strTitulo = "Consultar Tipo de Permissão";
      $arrComandos[] = '<input type="button" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=tipo_permissao_listar').'\';" class="infraButton" />';
      $objTipoPermissaoDTO->setNumIdTipoPermissao($_GET['id_tipo_permissao']);
      $objTipoPermissaoDTO->retTodos();
      $objTipoPermissaoRN = new TipoPermissaoRN();
      $objTipoPermissaoDTO = $objTipoPermissaoRN->consultar($objTipoPermissaoDTO);
      if ($objTipoPermissaoDTO==null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }


}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - Tipo de Permissão');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
#lblDescricao {position:absolute;left:0%;top:0%;width:50%;}
#txtDescricao {position:absolute;left:0%;top:6%;width:50%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='tipo_permissao_cadastrar'){
    document.getElementById('txtDescricao').focus();
  } else if ('<?=$_GET['acao']?>'=='tipo_permissao_consultar'){
    infraDesabilitarCamposAreaDados();
  }
}

function OnSubmitForm() {
  if (infraTrim(document.getElementById('txtDescricao').value)=='') {
    alert('Informe Descrição.');
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
<form id="frmTipoPermissaoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSip::getInstance()->assinarLink('tipo_permissao_cadastro.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSip::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSip::getInstance()->montarAreaValidacao();
PaginaSip::getInstance()->abrirAreaDados('30em');
?>
  <label id="lblDescricao" for="txtDescricao" accesskey="D" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">D</span>escrição:</label>
  <input type="text" id="txtDescricao" name="txtDescricao" class="infraText" value="<?=PaginaSip::tratarHTML($objTipoPermissaoDTO->getStrDescricao());?>" maxlength="50" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />

  <input type="hidden" id="hdnIdTipoPermissao" name="hdnIdTipoPermissao" value="<?=$objTipoPermissaoDTO->getNumIdTipoPermissao();?>" />
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