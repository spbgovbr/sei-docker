<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 03/09/2020 - criado por mga
 *
 * Versão do Gerador de Código: 1.42.0
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

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $bolEscolhaUnidade = SessaoSEI::getInstance()->verificarPermissao('atividade_unidade_orgao');

  switch ($_GET['acao']) {
    case 'atividade_unidade_pesquisar':
      $strTitulo = 'Relatório de Atividade na Unidade';
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();

  $arrComandos[] = '<button type="submit" accesskey="P" id="sbmPesquisar" name="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

  $objAtividadeUnidadeDTO = new AtividadeUnidadeDTO();

  if ($bolEscolhaUnidade) {
    $numIdUnidade = trim($_POST['hdnIdUnidade']);
    $strNomeUnidade = $_POST['txtUnidade'];
  }else{
    $numIdUnidade = SessaoSEI::getInstance()->getNumIdUnidadeAtual();
    $strNomeUnidade = UnidadeINT::formatarSiglaDescricao(SessaoSEI::getInstance()->getStrSiglaUnidadeAtual(),SessaoSEI::getInstance()->getStrDescricaoUnidadeAtual());
  }

  $objAtividadeUnidadeDTO->setNumIdUnidade($numIdUnidade);
  $objAtividadeUnidadeDTO->setNumIdUsuario($_POST['selUsuario']);

  $dtaPeriodoDe = $_POST['txtPeriodoDe'];
  $objAtividadeUnidadeDTO->setDtaInicio($dtaPeriodoDe);

  $dtaPeriodoA = $_POST['txtPeriodoA'];
  $objAtividadeUnidadeDTO->setDtaFim($dtaPeriodoA);

  $objAtividadeUnidadeDTO->setStrStaTipo($_POST['selTipo']);

  if ($objAtividadeUnidadeDTO->getStrStaTipo()==AtividadeUnidadeRN::$T_TOTAIS) {
    PaginaSEI::getInstance()->prepararOrdenacao($objAtividadeUnidadeDTO, 'TotalTarefas', InfraDTO::$TIPO_ORDENACAO_ASC);
  }

  $numRegistros = 0;

  if (isset($_POST['hdnFlag'])) {

    try {

      if ($objAtividadeUnidadeDTO->getStrStaTipo()==AtividadeUnidadeRN::$T_DETALHADO){
        PaginaSEI::getInstance()->prepararPaginacao($objAtividadeUnidadeDTO);
      }

      $objAtividadeUnidadeRN = new AtividadeUnidadeRN();
      $arrObjAtividadeUnidadeDTO = $objAtividadeUnidadeRN->pesquisar($objAtividadeUnidadeDTO);

      if ($objAtividadeUnidadeDTO->getStrStaTipo()==AtividadeUnidadeRN::$T_DETALHADO){
        PaginaSEI::getInstance()->processarPaginacao($objAtividadeUnidadeDTO);
      }

      $numRegistros = InfraArray::contar($arrObjAtividadeUnidadeDTO);

    } catch (Exception $e) {
      PaginaSEI::getInstance()->processarExcecao($e);
    }
  }

  $strResultado = '';
  $strGrafico = '';

  if ($numRegistros > 0) {

    $arrComandos[] = '<button type="button" accesskey="T" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton">Imprimir</button>';

    if ($objAtividadeUnidadeDTO->getStrStaTipo() == AtividadeUnidadeRN::$T_DETALHADO) {

      $strResultado .= '<table id="tblAtividade" width="99%" class="infraTable" summary="Histórico de Atividades">' . "\n";

      $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela('Atividades', $numRegistros) . '</caption>';
      $strResultado .= '<tr>';
      $strResultado .= '<th class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
      $strResultado .= '<th class="infraTh">Processo</th>';
      $strResultado .= '<th class="infraTh" width="15%">Data/Hora</th>';
      $strResultado .= '<th class="infraTh" width="15%">Unidade</th>';
      $strResultado .= '<th class="infraTh" width="10%">Usuário</th>';
      $strResultado .= '<th class="infraTh">Descrição</th>';
      $strResultado .= '</tr>' . "\n";
      $strCssTr='';

      $i = 0;
      foreach ($arrObjAtividadeUnidadeDTO as $dto) {

        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strResultado .= $strCssTr;

        $strResultado .= '<td valign="top">' . PaginaSEI::getInstance()->getTrCheck($i++, $dto->getNumIdAtividade(), $dto->getDthAbertura()) . '</td>';

        $strResultado .= "\n" . '<td align="center"  valign="top">';
        $strResultado .= '<a target="_blank" href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_procedimento=' . $dto->getDblIdProcedimento()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '" alt="' . PaginaSEI::tratarHTML($dto->getStrNomeTipoProcedimento()) . '" title="' . PaginaSEI::tratarHTML($dto->getStrNomeTipoProcedimento()) . '">' . PaginaSEI::tratarHTML($dto->getStrProtocoloFormatadoProcedimento()) . '</a>';
        $strResultado .= '</td>';

        $strResultado .= "\n" . '<td align="center" valign="top">';
        $strResultado .= substr($dto->getDthAbertura(), 0, 16);
        $strResultado .= '</td>';

        $strResultado .= "\n" . '<td align="center"  valign="top">';
        $strResultado .= '<a alt="' . PaginaSEI::tratarHTML($dto->getStrDescricaoUnidade()) . '" title="' . PaginaSEI::tratarHTML($dto->getStrDescricaoUnidade()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($dto->getStrSiglaUnidade()) . '</a>';
        $strResultado .= '</td>';

        $strResultado .= "\n" . '<td align="center"  valign="top">';
        $strResultado .= '<a alt="' . PaginaSEI::tratarHTML($dto->getStrNomeUsuario()) . '" title="' . PaginaSEI::tratarHTML($dto->getStrNomeUsuario()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($dto->getStrSiglaUsuario()) . '</a>';
        $strResultado .= '</td>';
        $strResultado .= "\n" . '<td valign="top">';
        $strResultado .= $dto->getStrNomeTarefa();
        $strResultado .= '</td>';

        $strResultado .= '</tr>';
      }
      $strResultado .= '</table>';

    }else{

      $bolAcaoAtividadeUnidadeDetalhe = SessaoSEI::getInstance()->verificarPermissao('atividade_unidade_detalhe');

      $strResultado .= '<table id="tblAtividade" width="99%" class="infraTable" summary="Totais por Atividades">' . "\n";
      $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela('Totais por Atividades', $numRegistros) . '</caption>';
      $strResultado .= '<tr>';
      $strResultado .= '<th class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
      $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($objAtividadeUnidadeDTO, 'Descrição', 'NomeTarefa', $arrObjAtividadeUnidadeDTO) . '</th>' . "\n";
      $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($objAtividadeUnidadeDTO, 'Total', 'TotalTarefas', $arrObjAtividadeUnidadeDTO) . '</th>' . "\n";
      $strResultado .= '</tr>' . "\n";
      $strCssTr = '';

      $strParametrosFiltro = '&id_unidade='.$objAtividadeUnidadeDTO->getNumIdUnidade().'&id_usuario='.$objAtividadeUnidadeDTO->getNumIdUsuario().'&dta_inicio='.$objAtividadeUnidadeDTO->getDtaInicio().'&dta_fim='.$objAtividadeUnidadeDTO->getDtaFim();

      $i = 0;
      foreach ($arrObjAtividadeUnidadeDTO as $dto) {

        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strResultado .= $strCssTr;

        $strResultado .= '<td valign="top">' . PaginaSEI::getInstance()->getTrCheck($i++, $dto->getNumTotalTarefas(), $dto->getStrNomeTarefa()) . '</td>';
        $strResultado .= '<td align="left"  valign="top">' . PaginaSEI::tratarHTML($dto->getStrNomeTarefa()) . '</td>';

        $strResultado .= '<td align="center"  valign="top">';
        if ($bolAcaoAtividadeUnidadeDetalhe){
          $strResultado .= '<a href="javascript:void(0);" onclick="abrirDetalhe(\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=atividade_unidade_detalhe&id_tarefa='.$dto->getNumIdTarefa().$strParametrosFiltro) . '\');" class="ancoraPadraoAzul">' . PaginaSEI::tratarHTML(InfraUtil::formatarMilhares($dto->getNumTotalTarefas())) . '</a>';
        }else{
          $strResultado .= PaginaSEI::tratarHTML(InfraUtil::formatarMilhares($dto->getNumTotalTarefas()));
        }
        $strResultado .= '</td>';

        $strResultado .= '</tr>';
      }

      $strResultado .= '</table>';

      /*
      $objEstatisticasRN = new EstatisticasRN();
      $arrCores = $objEstatisticasRN->getArrCores();
      $numCores = count($arrCores);
      $numGrafico = 0;
      $numCorAtual = 0;
      $arrIdTarefaCor = array();
      $arrGrafico = array();

      foreach ($arrObjAtividadeUnidadeDTO as $dto) {
        if (!array_key_exists($dto->getStrNomeTarefa(), $arrIdTarefaCor)) {
          $arrIdTarefaCor[$dto->getStrNomeTarefa()] = $arrCores[$numCorAtual];
          if ($numCorAtual + 1 == $numCores) {
            $numCorAtual = 0;
          } else {
            $numCorAtual++;
          }
        }
        $arrGrafico[] = array($dto->getStrNomeTarefa(), InfraUtil::formatarMilhares($dto->getNumTotalTarefas()), $dto->getNumTotalTarefas());
      }

      $strGrafico .= '<div id="divGrf' . (++$numGrafico) . '" >';
      $strGrafico .= $objEstatisticasRN->gerarGraficoBarrasSimples($numGrafico, EstatisticasRN::$ATIVIDADE_TOTAIS, null, $arrGrafico, 150, $arrIdTarefaCor);
      $strGrafico .= '</div>';
      */
    }
  }

  $strLinkAjaxUsuario = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=relatorio_atividade_unidade_usuario');
  $strLinkAjaxUnidade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=unidade_auto_completar_todas');
  $strSelUsuario = UsuarioINT::montarSelectPorUnidadeRI0811('null',' ',$objAtividadeUnidadeDTO->getNumIdUsuario(), $objAtividadeUnidadeDTO->getNumIdUnidade());
  $strSelTipo = AtividadeUnidadeINT::montarSelectTipo($objAtividadeUnidadeDTO->getStrStaTipo());

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
<? if (0){ ?> <style><? } ?>

  #frmAtividadeUnidadeLista {max-width: 1200px;}

  #lblUnidade {position:absolute;left:0%;top:5%;width:9%;}
  #txtUnidade {position:absolute;left:10%;top:0%;width:50%;}

  #lblUsuario {position:absolute;left:0%;top:5%;width:9%;}
  #selUsuario {position:absolute;left:10%;top:0%;width:50%;}

  #lblPeriodoDe {position:absolute;left:0%;top:5%;width:9%;}
  #txtPeriodoDe {position:absolute;left:10%;top:0%;width:10%;}
  #imgCalPeriodoD {position:absolute;left:20.5%;top:5%;}
  #lblPeriodoA {position:absolute;left:24%;top:5%;}
  #txtPeriodoA {position:absolute;left:27%;top:0%;width:10%;}
  #imgCalPeriodoA {position:absolute;left:37.5%;top:5%;}

  #lblTipo {position:absolute;left:0%;top:5%;width:9%;}
  #selTipo {position:absolute;left:10%;top:0%;width:10%;}


  <? if (0){ ?></style><? } ?>
<?
PaginaSEI::getInstance()->fecharStyle();
?>
  <script type="text/javascript" src="/infra_js/raphaeljs/raphael-min.js"></script>
  <script type="text/javascript" src="/infra_js/raphaeljs/g.raphael-min.js"></script>
  <script type="text/javascript" src="/infra_js/raphaeljs/g.bar-min.js"></script>
<?
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

<? if (0){ ?> <script type="text/javascript"> <? } ?>

  var objAjaxUsuario = null;
  var objAutoCompletarUnidade = null;
  var bolInicializando = true;

    function inicializar() {

      objAjaxUsuario = new infraAjaxMontarSelect('selUsuario','<?=$strLinkAjaxUsuario?>');
      objAjaxUsuario.prepararExecucao = function(){
        return 'idUnidade=' + document.getElementById('hdnIdUnidade').value;
      };

      <? if ($bolEscolhaUnidade){ ?>
      objAutoCompletarUnidade = new infraAjaxAutoCompletar('hdnIdUnidade','txtUnidade','<?=$strLinkAjaxUnidade?>');
      //objAutoCompletarUnidade.maiusculas = true;
      //objAutoCompletarUnidade.mostrarAviso = true;
      //objAutoCompletarUnidade.tempoAviso = 1000;
      //objAutoCompletarUnidade.tamanhoMinimo = 3;
      objAutoCompletarUnidade.limparCampo = true;
      //objAutoCompletarUnidade.bolExecucaoAutomatica = false;

      objAutoCompletarUnidade.prepararExecucao = function(){
        return 'palavras_pesquisa='+document.getElementById('txtUnidade').value+'&id_orgao=<?=SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual()?>';
      };

      objAutoCompletarUnidade.processarResultado = function(id,descricao,complemento){
        if (id!=''){
          document.getElementById('hdnIdUnidade').value = id;
          document.getElementById('txtUnidade').value = descricao;

          if (!bolInicializando) {
            objAjaxUsuario.executar();
          }
        }
      }
      objAutoCompletarUnidade.selecionar('<?=$numIdUnidade?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeUnidade,false)?>');
      <? } ?>

      infraEfeitoTabelas(true);

      bolInicializando = false;
    }

    function validarFormulario() {

      if (infraTrim(document.getElementById('hdnIdUnidade').value)=='') {
        alert('Informe a Unidade.');
        document.getElementById('txtUnidade').focus();
        return false;
      }

      if (!infraSelectSelecionado('selUsuario')) {
        alert('Selecione o Usuário.');
        return false;
      }

      if (infraTrim(document.getElementById('txtPeriodoDe').value) == "" || infraTrim(document.getElementById('txtPeriodoA').value) == "") {
        alert("Informe o período de datas.");
        if (infraTrim(document.getElementById('txtPeriodoDe').value) == "") {
          document.getElementById('txtPeriodoDe').focus();
        } else {
          document.getElementById('txtPeriodoA').focus();
        }
        return false;
      }

      if (!infraSelectSelecionado('selTipo')) {
        alert('Selecione o Tipo.');
        return false;
      }

      infraExibirAviso();

      return true;
    }


  function abrirDetalhe(link){
    infraAbrirJanelaModal(link,850,550);
  }

    <? if (0){ ?></script><? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmAtividadeUnidadeLista" onsubmit="return validarFormulario();" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSEI::getInstance()->abrirAreaDados('3.5em');
    ?>
      <label id="lblUnidade" for="txtUnidade" accesskey="" class="infraLabelObrigatorio">Unidade:</label>
      <? if ($bolEscolhaUnidade) {?>
        <input type="text" id="txtUnidade" name="txtUnidade" class="infraText" value="<?=$strNomeUnidade?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <? }else{ ?>
        <input type="text" id="txtUnidade" name="txtUnidade" class="infraText infraReadOnly" readonly="readonly" value="<?=$strNomeUnidade?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <? } ?>
      <input type="hidden" id="hdnIdUnidade" name="hdnIdUnidade" value="<?=$numIdUnidade?>" />
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->abrirAreaDados('3.5em');
    ?>
      <label id="lblUsuario" for="selUsuario" accesskey="" class="infraLabelObrigatorio">Usuário:</label>
      <select id="selUsuario" name="selUsuario" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
        <?=$strSelUsuario?>
      </select>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->abrirAreaDados('3.5em');
    ?>
      <label id="lblPeriodoDe" for="txtPeriodoDe" class="infraLabelObrigatorio">Período:</label>
      <input type="text" id="txtPeriodoDe" name="txtPeriodoDe" onkeypress="return infraMascaraData(this, event)" class="infraText" value="<?=PaginaSEI::tratarHTML($dtaPeriodoDe);?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
      <img id="imgCalPeriodoD" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" onclick="infraCalendario('txtPeriodoDe',this);" alt="Selecionar Data Inicial" title="Selecionar Data Inicial" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
      <label id="lblPeriodoA" for="txtPeriodoA" accesskey="" class="infraLabelOpcional">e</label>
      <input type="text" id="txtPeriodoA" name="txtPeriodoA" onkeypress="return infraMascaraData(this, event)" class="infraText" value="<?=PaginaSEI::tratarHTML($dtaPeriodoA);?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
      <img id="imgCalPeriodoA" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" onclick="infraCalendario('txtPeriodoA',this);" alt="Selecionar Data Final" title="Selecionar Data Final" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->abrirAreaDados('3.5em');
    ?>
      <label id="lblTipo" for="selTipo" accesskey="" class="infraLabelObrigatorio">Tipo:</label>
      <select id="selTipo" name="selTipo" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
        <?=$strSelTipo?>
      </select>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();

    echo '<div id="divTabela">';
    PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
    echo '</div>';

    echo '<br>';

    /*
    echo '<div id="divGrafico">';
    echo $strGrafico;
    echo '</div>';
    */

    PaginaSEI::getInstance()->montarAreaDebug();
    //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>

    <input type="hidden" id="hdnFlag" name="hdnFlag" value="1" />

  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
