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
  
  PaginaSEI::getInstance()->salvarCamposPost(array('hdnFiltroSerie'));
  
  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }

  if (isset($_GET['id_procedimento'])){
    $strParametros .= '&id_procedimento='.$_GET['id_procedimento'];
  }
  
  $strDesabilitar = '';
  $strDesabilitarCampo = '';

  $arrComandos = array();
  
  switch($_GET['acao']){

    case 'documento_escolher_tipo':

      $strTitulo = 'Gerar Documento';

      $objSerieRN = new SerieRN();

      $strFiltroSerie = PaginaSEI::getInstance()->recuperarCampo('hdnFiltroSerie','U');

      $strImgExibir = '';

      $arrObjSerieDTO = array();

      if (SessaoSEI::getInstance()->verificarPermissao('documento_gerar')){

        if ($strFiltroSerie=='U'){

          $objSerieDTO = new SerieDTO();
          $objSerieDTO->setStrSinSomenteUtilizados('S');
          $arrObjSerieDTO = $objSerieRN->listarTiposUnidade($objSerieDTO);

          $strImgExibir = '<a id="ancExibirSeries" href="javascript:void(0);" onclick="exibirSeries(\'T\');" tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'"><img src="'.PaginaSEI::getInstance()->getIconeMais().'" title="Exibir todos os tipos" alt="Exibir todos os tipos" class="infraImg" /></a>';
        }

        if (!isset($_POST['hdnFiltroSerie']) && count($arrObjSerieDTO)==0){
          $strFiltroSerie = 'T';
        }

        if ($strFiltroSerie=='T'){

          $objSerieDTO = new SerieDTO();
          $objSerieDTO->setStrSinSomenteUtilizados('N');
          $arrObjSerieDTO = $objSerieRN->listarTiposUnidade($objSerieDTO);

          $strImgExibir = '<a id="ancExibirSeries" href="javascript:void(0);" onclick="exibirSeries(\'U\');" tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'"><img src="'.PaginaSEI::getInstance()->getIconeMenos().'" title="Exibir apenas os tipos já utilizados pela unidade" alt="Exibir apenas os tipos já utilizados pela unidade" class="infraImg" /></a>';
        }
      }

      foreach($arrObjSerieDTO as $objSerieDTO){
        $arrOpcoes[] = array($objSerieDTO->getNumIdSerie(), $objSerieDTO->getStrNome(), $objSerieDTO->getStrStaAplicabilidade());
      }

      $strSumarioTabela = 'Tabela de Tipos de Documento.';

      $strResultado = '';
      $strResultado .= '<table id="tblSeries" class="infraTable" style="background-color:white;width:100%;" summary="'.$strSumarioTabela.'">'."\n";

	    $strResultado .= '<thead><tr style="display:none">';
      $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
	    $strResultado .= '</tr></thead><tbody>';

      if (SessaoSEI::getInstance()->verificarPermissao('documento_receber')){
        //coloca com espaço em branco na frente para aparecer como primeiro da lista
        $arrOpcoes[] = array(-1,' Externo', SerieRN::$TA_EXTERNO);
      }

      InfraArray::ordenarArray($arrOpcoes,1,InfraArray::$TIPO_ORDENACAO_ASC);

      $numRegistros = InfraArray::contar($arrOpcoes);

      if ($numRegistros) {

        $arrObjSerieNaoLiberados = InfraArray::indexarArrInfraDTO($objSerieRN->listarNaoLiberadosNaUnidade(),'IdSerie');

        for ($i = 0; $i < $numRegistros; $i++) {
          if (!isset($arrObjSerieNaoLiberados[$arrOpcoes[$i][0]])) {
            $strResultado .= '<tr  data-desc="' . PaginaSEI::tratarHTML(strtolower(InfraString::excluirAcentos($arrOpcoes[$i][1]))) . '">';
            $strResultado .= '<td style="padding-left:0px;padding-right:0px;">';
            $strResultado .= PaginaSEI::getInstance()->getTrCheck($i, $arrOpcoes[$i][0], $arrOpcoes[$i][1], 'N', 'Infra', 'style="display:none;"');
            $strResultado .= '<a style="width:100%;" href="#" onclick="escolher('.$arrOpcoes[$i][0].')" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '" class="ancoraOpcao">' . PaginaSEI::tratarHTML($arrOpcoes[$i][1]) . ($arrOpcoes[$i][2] == SerieRN::$TA_FORMULARIO ? '<sup>&nbsp;<span style="font-size:1.1em;">(Formulário)</span></sup>' : '') . '</a>' . "\n";
            $strResultado .= '</td>';
            $strResultado .= '</tr>';
          }
        }
      }
      $strResultado .= '</tbody></table>';

      if (isset($_POST['hdnIdSerie']) && $_POST['hdnIdSerie']!=''){
        foreach($arrOpcoes as $opcao){
          if ($opcao[0]==$_POST['hdnIdSerie']){

            $objDocumentoDTO = new DocumentoDTO();
            $objDocumentoDTO->setDblIdProcedimento($_GET['id_procedimento']);
            $objDocumentoDTO->setNumIdSerie($opcao[0]);

            DocumentoINT::validarEscolhaTipoDocumento($objDocumentoDTO);

            if ($opcao[2] == SerieRN::$TA_EXTERNO) {
              $strAcaoDestino = 'documento_receber';
            } else if ($opcao[2] == SerieRN::$TA_FORMULARIO) {
              $strAcaoDestino = 'formulario_gerar';
            } else {
              $strAcaoDestino = 'documento_gerar';
            }
            header('Location: '. SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $strAcaoDestino . '&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_serie=' . $opcao[0] . $strParametros));
            die;
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




  #tblSeries{
    border-spacing: 0px 5px;
    border-collapse: separate;
  }
  #tblSeries,
  #tblSeries td{
    border: none;
    border-radius: 5px;
    padding: 0px;

  }
  #tblSeries .ancoraOpcao{
    font-size: 14.5px;
    border-radius: 3px;
    padding:4px;
  }

  .spanRealce{
    font-size: 14.5px;
  }

  tr.infraTrSelecionada,
  tr.infraTrSelecionada td,
  td.infraTdSelecionada{
    background-color:unset !important;
  }

  #tblSeries td a:hover,#tblSeries td a:focus{
    background-color:#b0b0b0;
    color: black;
  }
  #ancExibirSeries{
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
  seiPrepararFiltroTabela(document.getElementById('tblSeries'),document.getElementById('txtFiltro'));
}  

function exibirSeries(tipo){
  document.getElementById('hdnFiltroSerie').value = tipo;
  document.getElementById('frmDocumentoEscolherTipo').submit();
}

function escolher(idSerie){
  document.getElementById('hdnIdSerie').value = idSerie;
  document.getElementById('frmDocumentoEscolherTipo').submit();
}

//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmDocumentoEscolherTipo" method="post" onsubmit="return false;" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">

  <?PaginaSEI::getInstance()->montarBarraComandosSuperior(array())?>

  <div class="mx-auto w-md-50 w-100">
    <br />
    <br />
  <label id="lblExibirSeries" class="infraLabelObrigatorio" style="font-size:1.6em;">Escolha o Tipo do Documento: </label> <?=$strImgExibir?>
  <br />
  <br />
  <input type="text" id="txtFiltro" class="infraAutoCompletar infraText " autocomplete="off"  style="position:relative;width:100%;" value="<?if (isset($_POST['txtFiltro'])) echo $_POST['txtFiltro'];?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <br />
  </div>
  <div class="mx-auto w-md-50 w-100">
<?
PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
?>
  </div>

  <?PaginaSEI::getInstance()->montarBarraComandosInferior(array())?>

  <input type="hidden" id="hdnFiltroSerie" name="hdnFiltroSerie" value="<?=$strFiltroSerie?>" />
  <input type="hidden" id="hdnIdSerie" name="hdnIdSerie" value="" />
</form>
<?
PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>