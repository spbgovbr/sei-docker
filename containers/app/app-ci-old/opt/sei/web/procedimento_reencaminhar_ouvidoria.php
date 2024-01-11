<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 01/10/2010 - criado por alexandre_db
 *
 * Versão do Gerador de Código: 1.29.1
 *
 * Versão no CVS: $Id$
 */

try {
	require_once dirname(__FILE__).'/SEI.php';

	session_start();
	//////////////////////////////////////////////////////////////////////////////
	InfraDebug::getInstance()->setBolLigado(false);
	InfraDebug::getInstance()->setBolDebugInfra(true);
	InfraDebug::getInstance()->limpar();
	//////////////////////////////////////////////////////////////////////////////

	SessaoSEI::getInstance()->validarLink();

	SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

	$strParametros = '';
	if(isset($_GET['arvore'])){
		PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
		$strParametros .= '&arvore='.$_GET['arvore'];
	}

	if (isset($_GET['id_procedimento'])){
		$strParametros .= '&id_procedimento='.$_GET['id_procedimento'];
	}

	$arrComandos = array();

	switch($_GET['acao']){
		case 'procedimento_reencaminhar_ouvidoria':
			
			$strTitulo = 'Correção de Encaminhamento';
			
			$arrComandos[] = '<button type="submit" accesskey="E" name="sbmEncaminhar" id="sbmEncaminhar" value="Encaminhar" class="infraButton">&nbsp;&nbsp;<span class="infraTeclaAtalho">E</span>ncaminhar&nbsp;&nbsp;</button>';
			$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

			$objOuvidoriaRN = new OuvidoriaRN();
			
			
			$objProtocoloDTO = new ProtocoloDTO();
			$objProtocoloDTO->retNumIdOrgaoUnidadeGeradora();
			$objProtocoloDTO->setDblIdProtocolo($_GET['id_procedimento']);
			
			$objProtocoloRN = new ProtocoloRN();
			$objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);
			
			$numIdOrgaoOrigem = $objProtocoloDTO->getNumIdOrgaoUnidadeGeradora();
			

			$objProtocoloDTO = new ProtocoloDTO();
			$objProtocoloDTO->setDblIdProtocolo($_GET['id_procedimento']);
			$objProtocoloDTO->setNumIdOrgaoUnidadeGeradora($_POST['selOrgao']);
			
			
			if (isset($_POST['sbmEncaminhar'])) {
				try{

					$objProcedimentoDTO = $objOuvidoriaRN->reencaminhar($objProtocoloDTO);
					header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&atualizar_arvore=1'.$strParametros.PaginaSEI::montarAncora($_GET['id_procedimento'])));
					die;
					
				}catch(Exception $e){
					PaginaSEI::getInstance()->processarExcecao($e);
				}
			}
			break;

		default:
			throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
	}

	$strItensSelOrgao = OuvidoriaINT::montarSelectOuvidoriaDestino('null','&nbsp;',$objProtocoloDTO->getNumIdOrgaoUnidadeGeradora(),$numIdOrgaoOrigem);

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

#lblOrgao {position:absolute;left:0%;top:10%;} 
#selOrgao {position:absolute;left:0%;top:16%;width:35%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){ 

  <?if(PaginaSEI::getInstance()->isBolArvore()){?>
	  parent.parent.infraOcultarAviso();
	<?}?>

  document.getElementById('selOrgao').focus();
} 

function OnSubmitForm() { 
  if (!infraSelectSelecionado('selOrgao')){
    alert('Selecione o Destino.');
    document.getElementById('selOrgao').focus();
    return false;
  }
  
  <?if(PaginaSEI::getInstance()->isBolArvore()){?>
    parent.parent.infraExibirAviso(false);
  <?}else{?>
    infraExibirAviso(false);
  <?}?>
   
  return true; 
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmProcedimentoReencaminharOuvidoria" method="post"	onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?> 
  <label id="lblOrgao" for="selOrgao" class="infraLabelObrigatorio">Destino:</label>
  <select id="selOrgao" name="selOrgao" class="infraSelect"	tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
	<?=$strItensSelOrgao?>
  </select>
<?   
	PaginaSEI::getInstance()->fecharAreaDados();
	PaginaSEI::getInstance()->montarAreaDebug();
	//PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
	?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>