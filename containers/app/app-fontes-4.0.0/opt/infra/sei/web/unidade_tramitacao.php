<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 23/08/2010 - criado por Alexandre_db
 *
 * Versão do Gerador de Código: 1.12.0
 *
 * Versão no CVS: $Id$
 **/

try {
	require_once dirname(__FILE__).'/SEI.php';

	session_start();

	//////////////////////////////////////////////////////////////////////////////
	//InfraDebug::getInstance()->setBolLigado(false);
	//InfraDebug::getInstance()->setBolDebugInfra(true);
	//InfraDebug::getInstance()->limpar();
	//////////////////////////////////////////////////////////////////////////////

	SessaoSEI::getInstance()->validarLink();

	PaginaSEI::getInstance()->prepararSelecao('unidade_tramitacao_selecionar');

	SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

	PaginaSEI::getInstance()->salvarCamposPost(array('selGrupo','optInstitucional','optUnidade','hdnTipoSelect'));

	switch($_GET['acao']){
		 
		case 'unidade_tramitacao_selecionar':
			$strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Unidade de Tramitação','Selecionar Unidades de Tramitação');
			break;
		default:
			throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
	}

  $idProcedimento=$_GET['id_procedimento'];

	$arrComandos = array();
	$arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';

  $objAtividadeRN=new AtividadeRN();

  $objProcedimentoDTO=new ProcedimentoDTO();
	$objProcedimentoDTO->setDblIdProcedimento($idProcedimento);
  $arrObjAtividadeDTO = $objAtividadeRN->listarUnidadesTramitacao($objProcedimentoDTO);

	$numRegistros = count($arrObjAtividadeDTO);
	
	$bolCheck = true;

	$arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

	if ($numRegistros > 0){

		$strResultado = '';

		$strSumarioTabela = 'Tabela de Unidades com tramitação.';
		$strCaptionTabela = 'Unidades com tramitação';

		$strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
		$strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
		$strResultado .= '<tr>';

		if ($bolCheck) {
			$strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
		}

		$strResultado .= '<th class="infraTh">Sigla</th>'."\n";
		$strResultado .= '<th class="infraTh">Descrição</th>'."\n";
    $strResultado .= '<th class="infraTh">Data para devolver</th>'."\n";
		$strResultado .= '<th class="infraTh">Ações</th>'."\n";
		$strResultado .= '</tr>'."\n";
		$strCssTr='';

    $i=0;
    foreach ($arrObjAtividadeDTO as $dto) {

			$strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
			$strResultado .= $strCssTr;

			if ($bolCheck){
				$strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$dto->getNumIdUnidade(),$dto->getStrSiglaUnidade().' - '.$dto->getStrDescricaoUnidade()).'</td>';
			}

			$strResultado .= '<td width="15%" align="center">'.PaginaSEI::tratarHTML($dto->getStrSiglaUnidade()).'</td>';
			$strResultado .= '<td align="left" >'.PaginaSEI::tratarHTML($dto->getStrDescricaoUnidade()).'</td>';
      $strResultado .= '<td width="15%" align="center" >';
      if (!InfraString::isBolVazia($dto->getDtaPrazo())){
				$strResultado .= $dto->getDtaPrazo();
			}else{
				$strResultado .= '&nbsp;';
			}
      $strResultado.='</td>';
			$strResultado .= '<td align="center" valign="top">';

			$strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$dto->getNumIdUnidade());

			$strResultado .= '</td></tr>'."\n";

      $i++;
		}
		$strResultado .= '</table>';
	}

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


<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){ 
	  infraReceberSelecao();
	  document.getElementById('btnFecharSelecao').focus(); 
  infraEfeitoTabelas();
} 
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');

?>

<form id="frmGrupoSelecao" method="post"	action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">

<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);

	PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros,true);
	//PaginaSEI::getInstance()->montarAreaDebug();
	PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
	?>
	
</form>

	<?
	PaginaSEI::getInstance()->fecharBody();
	PaginaSEI::getInstance()->fecharHtml();
	?>