<?

try {
  require_once dirname(__FILE__) . '/../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoConsultaProcessual::getInstance()->validarLink();

  $strStaCriterioPesquisa = null;
  $strValorPesquisa = null;
  $arrIdOrgao = null;

  $bolExibirOrgaos = true;
  $numRegistros = 0;
  $strResultado = '';

  switch ($_GET['acao']) {
    case 'consulta_processual_pesquisar':
    case 'consulta_processual_voltar':

      $strTitulo = 'Consulta Processual';

      $objInfrParametro = new InfraParametro(BancoSEI::getInstance());
      if ($objInfrParametro->getValor('SEI_HABILITAR_CONSULTA_PROCESSUAL')==ConsultaProcessualRN::$CP_DESABILITADA){
        throw new InfraException('Consulta processual desabilitada.',null,null,false);
      }

      CaptchaSEI::getInstance()->configurarCaptcha('Consulta Processual');

      $arrComandos = array();

      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->retNumIdOrgao();
      $objOrgaoDTO->retStrSigla();
      $objOrgaoDTO->retStrDescricao();
      $objOrgaoDTO->setStrSinConsultaProcessual('S');
      $objOrgaoDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objOrgaoRN = new OrgaoRN();
      $arrObjOrgaoDTO = $objOrgaoRN->listarRN1353($objOrgaoDTO);

      if (count($arrObjOrgaoDTO) == 0) {
        throw new InfraException('Nenhum órgão configurado para a Consulta Processual.');
      }

      if (count($arrObjOrgaoDTO) == 1 && $_GET['id_orgao'] == $arrObjOrgaoDTO[0]->getNumIdOrgao()) {
        $bolExibirOrgaos = false;
      }

      if ($_GET['acao'] == 'consulta_processual_voltar') {
        $strStaCriterioPesquisa = SessaoConsultaProcessual::getInstance()->getAtributo('CONSULTA_PROCESSUAL_CRITERIO_TIPO');
        $strValorPesquisa = SessaoConsultaProcessual::getInstance()->getAtributo('CONSULTA_PROCESSUAL_CRITERIO_VALOR');
        $arrIdOrgao = SessaoConsultaProcessual::getInstance()->getAtributo('CONSULTA_PROCESSUAL_ORGAOS');
      } else {
        $strStaCriterioPesquisa = $_POST['selCriterioPesquisa'];
        $strValorPesquisa = $_POST['txtValorPesquisa'];
        if ($bolExibirOrgaos) {
          $arrIdOrgao = $_POST['selOrgao'];
          if (!is_array($arrIdOrgao)) {
            $arrIdOrgao = array($arrIdOrgao);
          }
        } else {
          $arrIdOrgao = array($arrObjOrgaoDTO[0]->getNumIdOrgao());
        }
      }

      SessaoConsultaProcessual::getInstance()->removerDadosSessao();

      if (isset($_POST['hdnInfraCaptcha']) && $_POST['hdnInfraCaptcha'] == '1') {

        BancoSEI::setBolReplica(false);
        CaptchaSEI::getInstance()->setObjInfraIBanco(BancoSEI::getInstance());

        if (!CaptchaSEI::getInstance()->verificar()) {
          PaginaConsultaProcessual::getInstance()->setStrMensagem('Código de confirmação inválido.');
        } else {
          try {

            BancoSEI::setBolReplica(true);

            $objConsultaProcessualDTO = new ConsultaProcessualDTO();
            $objConsultaProcessualDTO->setStrStaCriterioPesquisa($strStaCriterioPesquisa);
            $objConsultaProcessualDTO->setStrValorPesquisa($strValorPesquisa);
            $objConsultaProcessualDTO->setNumIdOrgaoUnidadeGeradora($arrIdOrgao);

            $objConsultaProcessualRN = new ConsultaProcessualRN();
            $objConsultaProcessualRN->validarCriterios($objConsultaProcessualDTO);

            SessaoConsultaProcessual::getInstance()->salvarDadosSessao($objConsultaProcessualDTO);

            header('Location: ' . SessaoConsultaProcessual::getInstance()->assinarLink('controlador_consulta_processual.php?acao=consulta_processual_resultado'));
            die;
          } catch (Exception $e) {
            PaginaConsultaProcessual::getInstance()->processarExcecao($e);
          }
        }
      }

      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $strSelCriterios = ConsultaProcessualINT::montarSelectStaCriterio('null', '&nbsp;', $strStaCriterioPesquisa);

  if ($bolExibirOrgaos) {
    $strOptionsOrgaos = '';
    foreach ($arrObjOrgaoDTO as $objOrgaoDTO_Select) {
      $strOptionsOrgaos .= '<option value="' . $objOrgaoDTO_Select->getNumIdOrgao() . '"';
      if (InfraArray::contar($arrIdOrgao)) {
        if (in_array($objOrgaoDTO_Select->getNumIdOrgao(), $arrIdOrgao)) {
          $strOptionsOrgaos .= ' selected="selected"';
        }
      } else {
        $strOptionsOrgaos .= ' selected="selected"';
      }
      $strOptionsOrgaos .= '>' . PaginaConsultaProcessual::tratarHTML($objOrgaoDTO_Select->getStrSigla()) . '</option>' . "\n";
    }
  }
} catch (Exception $e) {
  PaginaConsultaProcessual::getInstance()->processarExcecao($e);
}
PaginaConsultaProcessual::getInstance()->montarDocType();
PaginaConsultaProcessual::getInstance()->abrirHtml();
PaginaConsultaProcessual::getInstance()->abrirHead();
PaginaConsultaProcessual::getInstance()->montarMeta();
PaginaConsultaProcessual::getInstance()->montarTitle(PaginaConsultaProcessual::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaConsultaProcessual::getInstance()->montarStyle();
CaptchaSEI::getInstance()->montarStyle();
PaginaConsultaProcessual::getInstance()->abrirStyle();
?>

  #frmConsultaProcessual {max-width: 1200px;}

<?
PaginaConsultaProcessual::getInstance()->fecharStyle();
PaginaConsultaProcessual::getInstance()->montarJavaScript();
CaptchaSEI::getInstance()->montarJavascript();
PaginaConsultaProcessual::getInstance()->abrirJavaScript();
?><?
if (0){ ?>
  <script type="text/javascript"> <?}?>

    <? if ($bolExibirOrgaos){ ?>
    $(document).ready(function () {
      $("#selOrgao").multipleSelect({
        filter: false,
        minimumCountSelected: 1,
        selectAll: true,
      });
    });
    <? } ?>

    function inicializar() {
      infraEfeitoTabelas();
    }

    function onSubmitForm() {
      return validarForm();
    }

    function tratarValorPesquisa(campo, event) {
      if (document.getElementById('selCriterioPesquisa').value == '<?=ConsultaProcessualRN::$TC_CNPJ_INTERESSADO?>') {
        return infraMascaraCnpj(campo, event);
      } else if (document.getElementById('selCriterioPesquisa').value == '<?=ConsultaProcessualRN::$TC_CPF_INTERESSADO?>') {
        return infraMascaraCpf(campo, event);
      } else {
        return infraLimitarTexto(campo, event, 100);
      }
    }

    function trocarCriterio() {
      document.getElementById('txtValorPesquisa').value = '';
      document.getElementById('txtValorPesquisa').focus();
    }

    function validarForm() {

      if (!infraSelectSelecionado(document.getElementById('selCriterioPesquisa'))) {
        alert('Informe o Critério de Pesquisa.');
        document.getElementById('selCriterioPesquisa').focus();
        return false;
      }

      if (infraTrim(document.getElementById('txtValorPesquisa').value) == '') {
        alert('Informe o Valor do Critério de pesquisa.');
        document.getElementById('txtValorPesquisa').focus();
        return false;
      }

      if (document.getElementById('selCriterioPesquisa').value == '<?=ConsultaProcessualRN::$TC_NOME_INTERESSADO?>') {
        var nome = infraTrim(document.getElementById('txtValorPesquisa').value);
        var bolErro = false;
        if (nome == '') {
          bolErro = true;
        } else {
          var nomeSplit = nome.infraReplaceAll('  ',' ').split(' ');
          if (nomeSplit.length < <?=ConsultaProcessualRN::$QUANTIDADE_PALAVRAS_MINIMA?>) {
            bolErro = true;
          } else {
            for (var i = 0; i < nomeSplit.length; i++) {
              if (nomeSplit[i].length < <?=ConsultaProcessualRN::$QUANTIDADE_LETRAS_MINIMA?>) {
                bolErro = true;
                break;
              }
            }
          }
        }

        if (bolErro) {
          alert('Nome do Interessado deve ser composto ao menos de <?=ConsultaProcessualRN::$QUANTIDADE_PALAVRAS_MINIMA?> partículas com <?=ConsultaProcessualRN::$QUANTIDADE_LETRAS_MINIMA?> caracteres cada uma.');
          document.getElementById('txtValorPesquisa').focus();
          return false;
        }
      } else if (document.getElementById('selCriterioPesquisa').value == '<?=ConsultaProcessualRN::$TC_CNPJ_INTERESSADO?>') {
        if (!infraValidarCnpj(document.getElementById('txtValorPesquisa').value)) {
          alert('CNPJ inválido.');
          document.getElementById('txtValorPesquisa').focus();
          return false;
        }
      } else if (document.getElementById('selCriterioPesquisa').value == '<?=ConsultaProcessualRN::$TC_CPF_INTERESSADO?>') {
        if (!infraValidarCpf(document.getElementById('txtValorPesquisa').value)) {
          alert('CPF inválido.');
          document.getElementById('txtValorPesquisa').focus();
          return false;
        }
      }

      <? if ($bolExibirOrgaos){ ?>
      if (document.getElementById('selOrgao').value == '') {
        alert('Informe ao menos um Órgão para pesquisa.');
        document.getElementById('selOrgao').focus();
        return false;
      }
      <? } ?>

      <? CaptchaSEI::getInstance()->validarOnSubmit('frmConsultaProcessual'); ?>

      return true;
    }

    <?
    if (0){ ?></script> <?
} ?>

<?
PaginaConsultaProcessual::getInstance()->fecharJavaScript();
PaginaConsultaProcessual::getInstance()->fecharHead();
PaginaConsultaProcessual::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmConsultaProcessual" name="frmConsultaProcessual" method="post" onsubmit="return onSubmitForm();" action="<?=SessaoConsultaProcessual::getInstance()->assinarLink('controlador_consulta_processual.php?acao=consulta_processual_pesquisar&acao_origem=' . $_GET['acao'])?>">
    <?
    PaginaConsultaProcessual::getInstance()->montarBarraComandosSuperior($arrComandos);
    ?>
    <div id="divPrincipal" class="infraAreaDados"
    ">

    <div class="infraAreaDados d-flex flex-column  mb-1">
      <div class=" mx-0 px-0 pt-2">
        <label id="lblCriterioPesquisa" for="selCriterioPesquisa" accesskey="" class="infraLabelObrigatorio">Critério de Pequisa:</label>
      </div>
      <div class="w-50 pl-0  pt-1 ">
        <select id="selCriterioPesquisa" name="selCriterioPesquisa" onchange="trocarCriterio()" class="w-100  infraSelect multipleSelect" tabindex="<?=PaginaConsultaProcessual::getInstance()->getProxTabDados()?>">
          <?=$strSelCriterios?>
        </select>
      </div>
    </div>

    <div class="infraAreaDados d-flex flex-column  mb-1">
      <div class="mx-0 px-0 pt-2">
        <label id="lblValorPesquisa" for="txtValorPesquisa" accesskey="" class="infraLabelObrigatorio">Valor do Critério:</label>
      </div>
      <div class="pl-0  w-50 pt-1 ">
        <input type="text" id="txtValorPesquisa" name="txtValorPesquisa" class="w-100 infraText" maxlength="100" onkeypress="return tratarValorPesquisa(this,event);" value="<?=PaginaConsultaProcessual::tratarHTML($strValorPesquisa)?>"
               tabindex="<?=PaginaConsultaProcessual::getInstance()->getProxTabDados()?>"/>
      </div>
    </div>

    <?
    if ($bolExibirOrgaos) { ?>
      <div class="infraAreaDados d-flex flex-column  mb-1">
        <div class="mx-0 px-0 pt-2">
          <label id="lblOrgao" for="selOrgao" accesskey="" class="infraLabelObrigatorio">Órgão:</label>
        </div>
        <div class="pl-0 w-50 pt-1 ">
          <select multiple id="selOrgao" name="selOrgao[]" class="w-50  infraSelect multipleSelect" tabindex="<?=PaginaConsultaProcessual::getInstance()->getProxTabDados()?>">
            <?=$strOptionsOrgaos;?>
          </select>
        </div>
      </div>
    <?
    } ?>

    <div class="infraAreaDados d-flex flex-column  mb-1">
      <div class="mx-0 px-0 pt-2">
        <?
        CaptchaSEI::getInstance()->montarHtml(PaginaConsultaProcessual::getInstance()->getProxTabDados()); ?>
      </div>
    </div>

    <button type="submit" id="sbmPesquisar" name="sbmPesquisar" value="Pesquisar" class="infraButton" tabindex="<?=PaginaConsultaProcessual::getInstance()->getProxTabDados()?>">Pesquisar</button>

    </div>
  </form>
<?
PaginaConsultaProcessual::getInstance()->montarAreaDebug();
PaginaConsultaProcessual::getInstance()->fecharBody();
PaginaConsultaProcessual::getInstance()->fecharHtml();
