<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/12/2006 - criado por mga
*
*
*/

try {
  require_once dirname(__FILE__).'/../SEI.php';

  session_start();
	
  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  //SessaoSEI::getInstance()->validarSessao();
  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  if (isset($_GET['id_modelo_origem'])){
    PaginaSEI::getInstance()->salvarCampo('selModeloOrigem',$_GET['id_modelo_origem']);
  } else {
    PaginaSEI::getInstance()->salvarCamposPost(array('selModeloOrigem'));
  }
  
  $objClonarModeloDTO = new ClonarModeloDTO();

  $arrComandos = array();
  
  switch($_GET['acao']){
    case 'modelo_clonar':
      
      $strTitulo = 'Clonar Modelo';

      $arrComandos[] = '<input type="submit" name="sbmClonarModelo" value="Clonar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().PaginaSEI::getInstance()->montarAncora($_GET['id_modelo_origem'])).'\';" class="infraButton" />';
			
			$numIdModelo = PaginaSEI::getInstance()->recuperarCampo('selModeloOrigem');
			if ($numIdModelo!==''){
				$objClonarModeloDTO->setNumIdModeloOrigem($numIdModelo);
			}else{
				$objClonarModeloDTO->setNumIdModeloOrigem(null);
			}
			
			$objClonarModeloDTO->setStrNomeDestino($_POST['txtNomeDestino']);
			
      if (isset($_POST['sbmClonarModelo'])) {
        try{
          $objModeloRN = new ModeloRN();
          $objModeloDTO = $objModeloRN->clonar($objClonarModeloDTO);
          PaginaSEI::getInstance()->setStrMensagem('Modelo "'.$objClonarModeloDTO->getStrNomeDestino().'" clonada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=modelo_listar&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objModeloDTO->getNumIdModelo())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
			}
      break;
      
    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelModeloOrigem = ModeloINT::montarSelectNome('null','&nbsp;', $numIdModelo);
	
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

#lblModeloOrigem {position:absolute;left:0%;top:0%;width:50%;}
#selModeloOrigem {position:absolute;left:0%;top:6%;width:50%;}

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

  if (!infraSelectSelecionado(document.getElementById('selModeloOrigem'))) {
    alert('Selecione Modelo Origem.');
    document.getElementById('selModeloOrigem').focus();
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
<form id="frmModeloClonar" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao('Clonar Modelo');
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('30em');
  ?>
	
  <label id="lblModeloOrigem" for="selModeloOrigem" accesskey="H" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">M</span>odelo Origem:</label>
  <select id="selModeloOrigem" name="selModeloOrigem" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelModeloOrigem?>
  </select>
	
  <label id="lblNomeDestino" for="txtNomeDestino" accesskey="N" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome Destino:</label>
  <input type="text" id="txtNomeDestino" name="txtNomeDestino" class="infraText" value="<?=PaginaSEI::tratarHTML($objClonarModeloDTO->getStrNomeDestino());?>" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />


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