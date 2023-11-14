<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 03/08/2022 - criado por mgb29
*
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('documento_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('id_procedimento','tipo_selecao_documento'));

  ///PaginaSEI::getInstance()->salvarCamposPost(array(''));

  //$numSeg = InfraUtil::verificarTempoProcessamento();

  $arrNumIdSerie = array();
  if(isset($_POST['selSerie'])){
    $arrNumIdSerie = $_POST['selSerie'];
    if (!is_array($arrNumIdSerie)){
      $arrNumIdSerie = array($arrNumIdSerie);
    }
  }

  $arrNumIdUnidade = array();
  if(isset($_POST['selUnidade'])){
    $arrNumIdUnidade = $_POST['selUnidade'];
    if (!is_array($arrNumIdUnidade)){
      $arrNumIdUnidade= array($arrNumIdUnidade);
    }
  }

  $objOrgaoDTO = null;

  switch($_GET['acao']){

    case 'documento_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Documento','Selecionar Documentos');
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  
  $arrComandos[] = '<input type="submit" id="btnPesquisar" value="Pesquisar" class="infraButton" />';  
  
  $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';

  $objDocumentoDTO = new DocumentoDTO();
  $objDocumentoDTO->setDblIdProcedimento($_GET['id_procedimento']);

  $strProtocolos = $_POST['txtProtocolo'];

  if(!InfraString::isBolVazia($strProtocolos)) {
    $objDocumentoDTO->setStrProtocoloDocumentoFormatado($strProtocolos);
  }

  if (InfraArray::contar($arrNumIdSerie)) {
    $objDocumentoDTO->setNumIdSerie($arrNumIdSerie);
  }

  if (InfraArray::contar($arrNumIdUnidade)) {
    $objDocumentoDTO->setNumIdUnidadeGeradoraProtocolo($arrNumIdUnidade);
  }

  switch($_GET['tipo_selecao_documento']){
    case DocumentoINT::$TSD_EMAIL:
      $objDocumentoDTO->setStrSinEmail('S');
      break;

    case DocumentoINT::$TSD_PDF:
      $objDocumentoDTO->setStrSinPdf('S');
      break;

    case DocumentoINT::$TSD_ZIP:
      $objDocumentoDTO->setStrSinZip('S');
      break;
  }

  PaginaSEI::getInstance()->prepararPaginacao($objDocumentoDTO, 100);

  $objDocumentoRN = new DocumentoRN();
  $arrObjDocumentoDTO = $objDocumentoRN->listarSelecao($objDocumentoDTO);

  PaginaSEI::getInstance()->processarPaginacao($objDocumentoDTO);

  $numRegistros = InfraArray::contar($arrObjDocumentoDTO);

  if ($numRegistros > 0){

    $bolAcaoDocumentoVisualizar = SessaoSEI::getInstance()->verificarPermissao('documento_visualizar');

    $bolCheck = true;

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Documentos.';
    $strCaptionTabela = 'Documentos';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Nº SEI</th>'."\n";
    $strResultado .= '<th class="infraTh">Documento</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Data</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Unidade</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";

    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $objDocumentoDTO = $arrObjDocumentoDTO[$i];

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td>'.PaginaSEI::getInstance()->getTrCheck($i,$objDocumentoDTO->getDblIdDocumento(),DocumentoINT::formatarIdentificacaoComProtocolo($objDocumentoDTO)).'</td>';
      }

      $strResultado .= '<td width="15%" align="center">';
      if ($bolAcaoDocumentoVisualizar) {
        $strResultado .= '<a onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&id_documento='.$objDocumentoDTO->getDblIdDocumento()).'" target="_blank" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" class="protocoloNormal" style="font-size:1em !important;">'.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().'</a>';
      } else {
        $strResultado .= $objDocumentoDTO->getStrProtocoloDocumentoFormatado();
      }
      $strResultado .= '</td>';

      $strResultado .= '<td>'.DocumentoINT::formatarIdentificacao($objDocumentoDTO).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($objDocumentoDTO->getDtaGeracaoProtocolo()).'</td>';
      $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($objDocumentoDTO->getStrDescricaoUnidadeGeradoraProtocolo()).'" title="'.PaginaSEI::tratarHTML($objDocumentoDTO->getStrDescricaoUnidadeGeradoraProtocolo()).'" class="ancoraSigla" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'">'.PaginaSEI::tratarHTML($objDocumentoDTO->getStrSiglaUnidadeGeradoraProtocolo()).'</a></td>';
      $strResultado .= '<td align="center">';
      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$objDocumentoDTO->getDblIdDocumento());
      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  //$arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

  $strOptionsSeries = SerieINT::montarSelectMultiploProcedimento($_GET['id_procedimento'],$arrNumIdSerie);
  $strOptionsUnidades = UnidadeINT::montarSelectMultiploUnidadesDocumentosProcesso($_GET['id_procedimento'],$arrNumIdUnidade);

  //$numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
  //InfraDebug::getInstance()->gravar('#'.$numSeg.' s');

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
  #lblProtocolo {position:absolute;left:0%;top:0%;}
  #txtProtocolo {position:absolute;left:0%;top:40%;width:99%}

  #lblSerie {position:absolute;left:0%;top:0%;}
  #selSerie, .multipleSelectSerie {position:absolute;left:0%;top:40%;width:49%;}

  #lblUnidade {position:absolute;left:50%;top:0%;}
  #selUnidade, .multipleSelectUnidade {position:absolute;left:50%;top:40%;width:49%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

$( document ).ready(function() {
  $("#selSerie").multipleSelect({
  filter: false,
  minimumCountSelected: 1,
  selectAll: false,
});

$("#selUnidade").multipleSelect({
  filter: false,
  minimumCountSelected: 1,
  selectAll: false,
  });
});

function inicializar(){
  infraReceberSelecao();
  document.getElementById('txtProtocolo').focus();
  infraEfeitoTabelas();
}

function tratarEnter(ev){
  var key = infraGetCodigoTecla(ev);
  if (key == 13){
    document.getElementById('frmDocumentoSelecao').submit();
  }
  return true;
}

function OnSubmitForm(){
  infraExibirAviso();
  return true;
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmDocumentoSelecao" method="post" onsubmit="return OnSubmitForm()" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&id_orgao='.$_GET['id_orgao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>
  <label id="lblProtocolo" for="txtProtocolo" accesskey="" class="infraLabelOpcional">Protocolo (separe múltiplos protocolos com vírgulas ","):</label>
  <input type="text" id="txtProtocolo" name="txtProtocolo" onkeypress="return infraMascaraNumero(this,event,null,',');" onkeyup="return tratarEnter(event);" class="infraText" value="<?=$strProtocolos?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>
  <label id="lblSerie" for="selSerie" accesskey="" class="infraLabelOpcional">Tipo:</label>
  <select style="display: none" multiple id="selSerie" name="selSerie[]" class="infraSelect multipleSelectSerie" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strOptionsSeries;?>
  </select>

  <label id="lblUnidade" for="selUnidade" accesskey="" class="infraLabelOpcional">Unidade geradora:</label>
  <select style="display: none" multiple id="selUnidade" name="selUnidade[]" class="infraSelect multipleSelectUnidade" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strOptionsUnidades;?>
  </select>
  <?
  PaginaSEI::getInstance()->fecharAreaDados();

  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>