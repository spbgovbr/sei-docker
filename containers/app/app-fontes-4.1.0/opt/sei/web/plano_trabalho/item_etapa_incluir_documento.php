<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 27/09/2022 - criado por mgb29
 *
 */

try {
  require_once dirname(__FILE__).'/../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  global $SEI_MODULOS;

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  if (isset($_GET['arvore'])) {
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  $objDocumentoDTO = new DocumentoDTO();
  $objDocumentoDTO->setDblIdProcedimento($_GET['id_procedimento']);

  if (isset($_POST['selSerie'])) {
    $objDocumentoDTO->setNumIdSerie($_POST['selSerie']);
  }else{
    $objDocumentoDTO->setNumIdSerie($_GET['id_serie']);
  }

  $objDocumentoDTO->setNumIdPlanoTrabalho($_GET['id_plano_trabalho']);
  $objDocumentoDTO->setNumIdEtapaTrabalho($_GET['id_etapa_trabalho']);
  $objDocumentoDTO->setNumIdItemEtapa($_GET['id_item_etapa']);

  if (isset($_GET['id_operacao'])) {
    $objDocumentoDTO->setStrIdOperacao($_GET['id_operacao']);
  }else{
    $objDocumentoDTO->setStrIdOperacao(InfraULID::gerar());
  }

  if ($_GET['acao_origem'] == 'documento_gerar') {
    $strSinExterno = 'N';
  }else if ($_GET['acao_origem'] == 'documento_receber'){
    $strSinExterno = 'S';
  }else{
    $strSinExterno = PaginaSEI::getInstance()->getCheckbox($_POST['chkSinExterno']);
  }

  $strParametros = '&arvore=' . $_GET['arvore'] .
    '&id_procedimento=' . $objDocumentoDTO->getDblIdProcedimento() .
    '&id_plano_trabalho=' . $objDocumentoDTO->getNumIdPlanoTrabalho() .
    '&id_etapa_trabalho=' . $objDocumentoDTO->getNumIdEtapaTrabalho() .
    '&id_item_etapa=' . $objDocumentoDTO->getNumIdItemEtapa().
    '&id_operacao='.$objDocumentoDTO->getStrIdOperacao();

  $strItensSelSerie = RelItemEtapaSerieINT::montarSelectInclusaoDocumento('null', '&nbsp;', $objDocumentoDTO->getNumIdSerie(), $objDocumentoDTO->getNumIdItemEtapa(), $numIdSerieSelecionada);
  $strLinkAjaxDadosSerie = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=serie_dados');

  $numTabSelSerie = PaginaSEI::getInstance()->getProxTabDados();
  $numTabSinExterno = PaginaSEI::getInstance()->getProxTabDados();

  $objDocumentoAPI = new DocumentoAPI();
  $objDocumentoAPI->setIdProcedimento($objDocumentoDTO->getDblIdProcedimento());
  $objDocumentoAPI->setIdSerie($numIdSerieSelecionada);
  $objDocumentoAPI->setIdPlanoTrabalho($objDocumentoDTO->getNumIdPlanoTrabalho());
  $objDocumentoAPI->setIdEtapaTrabalho($objDocumentoDTO->getNumIdEtapaTrabalho());
  $objDocumentoAPI->setIdItemEtapa($objDocumentoDTO->getNumIdItemEtapa());
  $objDocumentoAPI->setIdOperacao($objDocumentoDTO->getStrIdOperacao());

  $arrObjPaginaComplementoAPI = array();
  $bolProcessamentoModulo = false;
  foreach ($SEI_MODULOS as $seiModulo) {
    if (($objPaginaComplementoAPI = $seiModulo->executar('processarPaginaInclusaoDocumentoItemEtapa', $objDocumentoAPI))!=null){
      $arrObjPaginaComplementoAPI[] = $objPaginaComplementoAPI;
      $bolProcessamentoModulo = true;
    }
  }

  if (!$bolProcessamentoModulo && $_GET['acao_origem']=='plano_trabalho_detalhar'){
    $objRelItemEtapaSerieDTO = new RelItemEtapaSerieDTO();
    $objRelItemEtapaSerieDTO->retNumIdSerie();
    $objRelItemEtapaSerieDTO->retStrStaAplicabilidadeSerie();
    $objRelItemEtapaSerieDTO->setNumIdItemEtapa($objDocumentoDTO->getNumIdItemEtapa());

    $objRelItemEtapaSerieRN = new RelItemEtapaSerieRN();
    $arrObjRelItemEtapaSerieDTO = $objRelItemEtapaSerieRN->listar($objRelItemEtapaSerieDTO);

    if (count($arrObjRelItemEtapaSerieDTO)==1 && ($arrObjRelItemEtapaSerieDTO[0]->getStrStaAplicabilidadeSerie() == SerieRN::$TA_INTERNO || $arrObjRelItemEtapaSerieDTO[0]->getStrStaAplicabilidadeSerie() == SerieRN::$TA_EXTERNO)){

      header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' .
          ($arrObjRelItemEtapaSerieDTO[0]->getStrStaAplicabilidadeSerie() == SerieRN::$TA_INTERNO ? 'documento_gerar' : 'documento_receber') .
          '&acao_origem=' . $_GET['acao'] .
          '&acao_retorno=plano_trabalho_detalhar' .
          '&id_serie=' . $arrObjRelItemEtapaSerieDTO[0]->getNumIdSerie().
          '&ocultar_texto_inicial=S&bloquear_tipo_documento=S'.
          $strParametros));

      die;
    }
  }




  $arrComandos = array();

  switch ($_GET['acao']) {
    case 'item_etapa_incluir_documento':

      $strTitulo = 'Gerar Documento do Plano de Trabalho';

      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmContinuar" value="Salvar" class="infraButton">Continuar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . $strParametros . PaginaSEI::montarAncora($objDocumentoDTO->getNumIdEtapaTrabalho() . '-' . $objDocumentoDTO->getNumIdItemEtapa())) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmContinuar'])) {
        try {

          header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' .
              ($strSinExterno == 'N' ? 'documento_gerar' : 'documento_receber') .
              '&acao_origem=' . $_GET['acao'] .
              '&acao_retorno=' . $_GET['acao'] .
              '&id_serie=' . $objDocumentoDTO->getNumIdSerie().
              '&ocultar_texto_inicial=S&bloquear_tipo_documento=S'.
              $strParametros));

          die;

        } catch (Exception $e) {
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }


} catch (Exception $e) {
  PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>

  #lblSerie {position:absolute;left:0%;top:0%;width:60%;}
  #selSerie {position:absolute;left:0%;top:40%;width:60%;}
  #divSinExterno {position:absolute;left:62%;top:45%;display:none;}

<?

foreach($arrObjPaginaComplementoAPI as $objPaginaComplementoAPI){
  echo $objPaginaComplementoAPI->getCss();
}

PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?
if (0){ ?> <script type="text/javascript"> <? } ?>

    var objAjaxDadosSerie = null;

    <?
    foreach($arrObjPaginaComplementoAPI as $objPaginaComplementoAPI){
      echo $objPaginaComplementoAPI->getJavascriptGlobal();
    }
    ?>

    function inicializar() {

      objAjaxDadosSerie = new infraAjaxComplementar(null, '<?=$strLinkAjaxDadosSerie?>');
      objAjaxDadosSerie.prepararExecucao = function () {
        if (document.getElementById('selSerie').value != '') {
          return 'id_serie=' + document.getElementById('selSerie').value;
        } else {
          return false;
        }
      };
      objAjaxDadosSerie.processarResultado = function (arr) {
        if (arr != null) {
          if (arr['StaAplicabilidade'] != undefined) {
            if (arr['StaAplicabilidade'] == '<?=SerieRN::$TA_INTERNO?>') {
              document.getElementById('divSinExterno').style.display = 'none';
              document.getElementById('chkSinExterno').checked = false;
              document.getElementById('chkSinExterno').onclick = function () {
                return false;
              }
            } else if (arr['StaAplicabilidade'] == '<?=SerieRN::$TA_EXTERNO?>') {
              document.getElementById('divSinExterno').style.display = 'block';
              document.getElementById('chkSinExterno').checked = true;
              document.getElementById('chkSinExterno').onclick = function () {
                return false;
              }
            } else if (arr['StaAplicabilidade'] == '<?=SerieRN::$TA_INTERNO_EXTERNO?>') {
              document.getElementById('divSinExterno').style.display = 'block';
              document.getElementById('chkSinExterno').onclick = function () {
                return true;
              }
            }
          }
        }
      };

      trocarSerie();

      <?
      foreach($arrObjPaginaComplementoAPI as $objPaginaComplementoAPI){
        echo $objPaginaComplementoAPI->getJavascriptInicializacao();
      }
      ?>
    }

    function validarCadastro() {

      if (!infraSelectSelecionado('selSerie')) {
        alert('Selecione um Tipo de Documento.');
        document.getElementById('selSerie').focus();
        return false;
      }

      <?
      foreach($arrObjPaginaComplementoAPI as $objPaginaComplementoAPI){
        echo $objPaginaComplementoAPI->getJavascriptValidacao();
      }
      ?>

      return true;
    }

    function OnSubmitForm() {
      return validarCadastro();
    }

    function trocarSerie() {
      if (document.getElementById('selSerie').value == 'null') {
        document.getElementById('divSinExterno').style.display = 'none';
      } else {
        objAjaxDadosSerie.executar();
      }
    }

    function trocarContratacao() {
      infraSelectLimpar(document.getElementById('selContratacaoDerivada'));
      document.getElementById('frmItemEtapaIncluirDocumento').submit();
    }

    <? if (0){ ?></script><? } ?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmItemEtapaIncluirDocumento" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] . $strParametros)?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    PaginaSEI::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblSerie" for="selSerie" accesskey="" class="infraLabelObrigatorio">Tipo do Documento:</label>
    <select id="selSerie" name="selSerie" onchange="this.form.submit()" class="infraSelect" tabindex="<?=$numTabSelSerie?>">
      <?=$strItensSelSerie?>
    </select>

    <div id="divSinExterno" class="infraDivCheckbox infraAreaDados" style="height:3em;">
      <input type="checkbox" id="chkSinExterno" name="chkSinExterno" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($strSinExterno)?> tabindex="<?=$numTabSinExterno?>" />
      <label id="lblSinExterno" for="chkSinExterno" accesskey="" class="infraLabelCheckbox">Externo</label>
    </div>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();

    foreach($arrObjPaginaComplementoAPI as $objPaginaComplementoAPI){
      echo $objPaginaComplementoAPI->getHtml();
    }

    ?>
  </form>
<?
PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>