<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/12/2006 - criado por mga
*
*
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();
	
  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  //SessaoSEI::getInstance()->validarSessao();
  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  if (isset($_GET['id_tipo_formulario_origem'])){
    PaginaSEI::getInstance()->salvarCampo('selTipoFormularioOrigem',$_GET['id_tipo_formulario_origem']);
  } else {
    PaginaSEI::getInstance()->salvarCamposPost(array('selTipoFormularioOrigem'));
  }
  
  $objClonarTipoFormularioDTO = new ClonarTipoFormularioDTO();

  $arrComandos = array();
  
  switch($_GET['acao']){
    case 'tipo_formulario_clonar':
      
      $strTitulo = 'Clonar Tipo de Formulário';

      $arrComandos[] = '<input type="submit" name="sbmClonarTipoFormulario" value="Clonar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().PaginaSEI::getInstance()->montarAncora($_GET['id_tipo_formulario_origem'])).'\';" class="infraButton" />';
			
			$numIdTipoFormulario = PaginaSEI::getInstance()->recuperarCampo('selTipoFormularioOrigem');
			if ($numIdTipoFormulario!==''){
				$objClonarTipoFormularioDTO->setNumIdTipoFormularioOrigem($numIdTipoFormulario);
			}else{
				$objClonarTipoFormularioDTO->setNumIdTipoFormularioOrigem(null);
			}
			
			$objClonarTipoFormularioDTO->setStrNomeDestino($_POST['txtNomeDestino']);
			
      if (isset($_POST['sbmClonarTipoFormulario'])) {
        try{
          $objTipoFormularioRN = new TipoFormularioRN();
          $objTipoFormularioDTO = $objTipoFormularioRN->clonar($objClonarTipoFormularioDTO);

          PaginaSEI::getInstance()->setStrMensagem('Tipo de Formulário "'.$objClonarTipoFormularioDTO->getStrNomeDestino().'" clonado com sucesso.');

          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tipo_formulario_listar&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objTipoFormularioDTO->getNumIdTipoFormulario())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
			}
      break;
      
    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelTipoFormularioOrigem = TipoFormularioINT::montarSelectNome('null','&nbsp;', $numIdTipoFormulario);
	
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

#lblTipoFormularioOrigem {position:absolute;left:0%;top:0%;width:50%;}
#selTipoFormularioOrigem {position:absolute;left:0%;top:6%;width:50%;}

#lblNomeDestino {position:absolute;left:0%;top:16%;width:50%;}
#txtNomeDestino {position:absolute;left:0%;top:22%;width:50%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  document.getElementById('txtNomeDestino').focus();
}

function OnSubmitForm() {
  return validarForm();
}

function validarForm() {

  if (!infraSelectSelecionado(document.getElementById('selTipoFormularioOrigem'))) {
    alert('Selecione Tipo de Formulário Origem.');
    document.getElementById('selTipoFormularioOrigem').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtNomeDestino').value)=='') {
    alert('Informe Nome de Destino.');
    document.getElementById('txtNomeDestino').focus();
    return false;
  }

  return true;
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmTipoFormularioClonar" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao('Clonar TipoFormulario');
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('30em');
  ?>
	
  <label id="lblTipoFormularioOrigem" for="selTipoFormularioOrigem" accesskey="T" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">T</span>ipo de Formulário Origem:</label>
  <select id="selTipoFormularioOrigem" name="selTipoFormularioOrigem" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelTipoFormularioOrigem?>
  </select>
	
  <label id="lblNomeDestino" for="txtNomeDestino" accesskey="N" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome Destino:</label>
  <input type="text" id="txtNomeDestino" name="txtNomeDestino" class="infraText" value="<?=PaginaSEI::tratarHTML($objClonarTipoFormularioDTO->getStrNomeDestino());?>" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />


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