<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 31/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.13.1
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(false);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  global $SEI_MODULOS;

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('hdnFiltroTipoProcedimento'));
  
  $objProcedimentoDTO = new ProcedimentoDTO();

  $strDesabilitar = '';
  $strDesabilitarCampo = '';

  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }

	if(isset($_GET['id_procedimento_destino'])){
		$strParametros .= '&id_procedimento_destino='.$_GET['id_procedimento_destino'];
	}

  //$arrComandos = array();
  
  switch($_GET['acao']){

    case 'procedimento_escolher_tipo':
		case 'procedimento_escolher_tipo_relacionado':

		  if ($_GET['acao']=='procedimento_escolher_tipo'){
				$strTitulo = 'Iniciar Processo';
				$strAcaoDestino = 'procedimento_gerar';
			}else{
				$strTitulo = 'Iniciar Processo Relacionado';
				$strAcaoDestino = 'procedimento_gerar_relacionado';
			}

		  $objTipoProcedimentoRN = new TipoProcedimentoRN();

    	$strFiltroTipoProcedimento = PaginaSEI::getInstance()->recuperarCampo('hdnFiltroTipoProcedimento','U');

		  $arrObjTipoProcedimentoDTO = array();

    	$strImgExibir = '';

    	if ($strFiltroTipoProcedimento=='U'){

				$objTipoProcedimentoDTO = new TipoProcedimentoDTO();
				$objTipoProcedimentoDTO->setStrSinSomenteUtilizados('S');
				$arrObjTipoProcedimentoDTO = $objTipoProcedimentoRN->listarTiposUnidade($objTipoProcedimentoDTO);

				$strImgExibir = '<a id="ancExibirTiposProcedimento" href="javascript:void(0);" onclick="exibirTiposProcedimento(\'T\');"  tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'"><img  src="'.PaginaSEI::getInstance()->getIconeMais().'" title="Exibir todos os tipos" alt="Exibir todos os tipos" class="infraImg" /></a>';

			}

			//se ao entrar na pagina nao retornou itens para a unidade entao consulta tudo
			if (!isset($_POST['hdnFiltroTipoProcedimento']) && InfraArray::contar($arrObjTipoProcedimentoDTO)==0){
				$strFiltroTipoProcedimento = 'T';
			}

			if ($strFiltroTipoProcedimento == 'T'){

				$objTipoProcedimentoDTO = new TipoProcedimentoDTO();
				$objTipoProcedimentoDTO->setStrSinSomenteUtilizados('N');
				$arrObjTipoProcedimentoDTO = $objTipoProcedimentoRN->listarTiposUnidade($objTipoProcedimentoDTO);

        $strImgExibir = '<a id="ancExibirTiposProcedimento" href="javascript:void(0);" onclick="exibirTiposProcedimento(\'U\');" tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'"><img  src="'.PaginaSEI::getInstance()->getIconeMenos().'" title="Exibir apenas os tipos já utilizados pela unidade" alt="Exibir apenas os tipos já utilizados pela unidade" class="infraImg" /></a>';

      }

			foreach($arrObjTipoProcedimentoDTO as $objTipoProcedimentoDTO){
				$arrOpcoes[$objTipoProcedimentoDTO->getNumIdTipoProcedimento()] = array($objTipoProcedimentoDTO->getStrNome(), $objTipoProcedimentoDTO->getStrSinOuvidoria());
			}

      $strSumarioTabela = 'Tabela de Tipos de Processo.';
      $strCaptionTabela = 'Tipos de Processo';

      $strResultado = '';
      $strResultado .= '<table id="tblTipoProcedimento" class="infraTable" style="background-color:white;width:100%;" summary="'.$strSumarioTabela.'">'."\n";

	    $strResultado .= '<thead><tr style="display:none">';
      $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
	    $strResultado .= '</tr></thead><tbody>';

      $numRegistros = InfraArray::contar($arrOpcoes);

      if ($numRegistros) {

				$objNivelAcessoPermitidoDTO = new NivelAcessoPermitidoDTO();
				$objNivelAcessoPermitidoDTO->retNumIdTipoProcedimento();
				$objNivelAcessoPermitidoDTO->setStrStaNivelAcesso(ProtocoloRN::$NA_SIGILOSO);

				$objNivelAcessoPermitidoRN = new NivelAcessoPermitidoRN();
				$arrNumTipoProcedimentoSigiloso = InfraArray::indexarArrInfraDTO($objNivelAcessoPermitidoRN->listar($objNivelAcessoPermitidoDTO), 'IdTipoProcedimento');

        $i = 0;
				foreach ($arrOpcoes as $numIdTipoProcedimento => $arrTipoProcedimento) {
					$strResultado .= '<tr class="infraTrClara" data-desc="' . PaginaSEI::tratarHTML(strtolower(InfraString::excluirAcentos($arrTipoProcedimento[0]))) . '">';

					$strStyleOpcao = '';

					if (isset($arrNumTipoProcedimentoSigiloso[$numIdTipoProcedimento])) {
						$strClassTd = 'class="tdOpcaoSigiloso"';
					} else {
						$strClassTd = '';
					}

					$strResultado .= '<td ' . $strClassTd . '>';
					$strResultado .= PaginaSEI::getInstance()->getTrCheck($i++, $numIdTipoProcedimento, $arrTipoProcedimento[0], 'N', 'Infra', 'style="display:none;"');
          $strResultado .= '<a style="width:100%;" href="#" onclick="escolher('.$numIdTipoProcedimento.')" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '" class="ancoraOpcao">' . PaginaSEI::tratarHTML($arrTipoProcedimento[0]) . ($arrTipoProcedimento[1] == 'S' ? '<sup>&nbsp;<span style="font-size:1.1em;">(Ouvidoria)</span></sup>' : '') . '</a>' . "\n";
					$strResultado .= '</td>';
					$strResultado .= '</tr>';
				}
				$strResultado .= '</table>';

        if (isset($_POST['hdnIdTipoProcedimento']) && $_POST['hdnIdTipoProcedimento']!='') {
          foreach ($arrOpcoes as $numIdTipoProcedimento => $arrTipoProcedimento) {
            if ($numIdTipoProcedimento == $_POST['hdnIdTipoProcedimento']) {
              header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoDestino.'&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_tipo_procedimento='.$numIdTipoProcedimento.$strParametros));
              die;
            }
          }
        }
			}

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
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


td.tdOpcaoSigiloso a{
background-color:red;
color:white;
width:100%;
}

#tblTipoProcedimento{
  border-spacing: 0px 5px;
  border-collapse: separate;
}
#tblTipoProcedimento,
#tblTipoProcedimento td{
  border: none;
  border-radius: 5px;
  padding: 0px;

}

tr.infraTrSelecionada,
tr.infraTrSelecionada td,
td.infraTdSelecionada{
  background-color:unset !important;
}

#tblTipoProcedimento td a:hover,#tblTipoProcedimento td a:focus{
  background-color:#b0b0b0;
  color: black;
}


#tblTipoProcedimento .ancoraOpcao{
  font-size: 14.5px;
  border-radius: 3px;
  padding:4px;
}

.spanRealce{
  font-size: 14.5px;
}

#ancExibirTiposProcedimento{
  position: relative;
  top: 5px;
}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script>
function inicializar(){
  infraEfeitoTabelas();
  seiPrepararFiltroTabela(document.getElementById('tblTipoProcedimento'),document.getElementById('txtFiltro'));
}  

function exibirTiposProcedimento(tipo){
  document.getElementById('hdnFiltroTipoProcedimento').value = tipo;
  document.getElementById('frmProcedimentoEscolherTipo').submit();
}

function escolher(idTipoProcedimento){
  document.getElementById('hdnIdTipoProcedimento').value = idTipoProcedimento;
  document.getElementById('frmProcedimentoEscolherTipo').submit();
}

//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmProcedimentoEscolherTipo" method="post" onsubmit="return false;" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">

  <?PaginaSEI::getInstance()->montarBarraComandosSuperior(array())?>

  <div class="mx-auto w-md-50 w-100">
  <br />
  <br />
  <label class="infraLabelObrigatorio" style="font-size:1.6em;">Escolha o Tipo do Processo:</label> <?=$strImgExibir?>
  <br />
  <br />
	<input type="text" id="txtFiltro" class="infraAutoCompletar infraText " autocomplete="off" style="position:relative;width:100%;" value="<?if (isset($_POST['txtFiltro'])) echo $_POST['txtFiltro'];?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
	<br />
  </div>
  <div class="mx-auto w-md-50 w-100">
  <?
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  ?>
  </div>

  <?PaginaSEI::getInstance()->montarBarraComandosInferior(array())?>

  <input type="hidden" id="hdnFiltroTipoProcedimento" name="hdnFiltroTipoProcedimento" value="<?=$strFiltroTipoProcedimento?>" />
  <input type="hidden" id="hdnIdTipoProcedimento" name="hdnIdTipoProcedimento" value="" />
</form>
<?
PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>