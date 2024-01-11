<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 26/07/2013 - criado por mga
 *
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
		case 'procedimento_finalizar_ouvidoria':
			
			$strTitulo = 'SIM / NÃO';
			
			$arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" id="sbmSalvar" value="Salvar" class="infraButton">&nbsp;&nbsp;<span class="infraTeclaAtalho">S</span>alvar&nbsp;&nbsp;</button>';
			$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

			$objOuvidoriaRN = new OuvidoriaRN();
			
			if (!isset($_POST['hdnFlag'])){
			  
			  $objProcedimentoDTO = new ProcedimentoDTO();
			  $objProcedimentoDTO->retStrStaOuvidoria();
			  $objProcedimentoDTO->setDblIdProcedimento($_GET['id_procedimento']);
			  
			  $objProcedimentoRN = new ProcedimentoRN();
			  $objProcedimentoDTO = $objProcedimentoRN->consultarRN0201($objProcedimentoDTO);
			  
			}else{
  			$objProcedimentoDTO = new ProcedimentoDTO();
  			$objProcedimentoDTO->setStrStaOuvidoria($_POST['selStaOuvidoria']);
  			$objProcedimentoDTO->setDblIdProcedimento($_GET['id_procedimento']);
			}
			
			if (isset($_POST['sbmSalvar'])) {
				try{

					$objOuvidoriaRN->finalizar($objProcedimentoDTO);
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

	$strItensSelStaOuvidoria = OuvidoriaINT::montarSelectStaOuvidoriaFinalizacao(null,null,$objProcedimentoDTO->getStrStaOuvidoria());
	
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

#lblStaOuvidoria {position:absolute;left:0%;top:5%;} 
#selStaOuvidoria {position:absolute;left:0%;top:40%;width:20%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){ 
} 

function OnSubmitForm() { 
  return true; 
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmProcedimentoFinalizarOuvidoria" method="post"	onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
?>
<div id="divFinalizacao" class="infraAreaDados" style="height:5em;">  
  <label id="lblStaOuvidoria" for="selStaOuvidoria" class="infraLabelObrigatorio">Solicitação atendida?</label>
  <select id="selStaOuvidoria" name="selStaOuvidoria" class="infraSelect"	tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
	<?=$strItensSelStaOuvidoria?>
  </select> 
</div>

<input type="hidden" id="hdnFlag" name="hdnFlag" value="1" />
<?
PaginaSEI::getInstance()->montarAreaDebug();
//PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>