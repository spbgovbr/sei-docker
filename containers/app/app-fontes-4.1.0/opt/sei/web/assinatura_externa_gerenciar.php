<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 13/07/2011 - criado por mga
 *
 * Versão do Gerador de Código: 1.13.1
 *
 * Versão no CVS: $Id$
 */

try {
  require_once dirname(__FILE__) . '/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  $strParametros = '';
  if (isset($_GET['arvore'])) {
      PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
      $strParametros .= '&arvore=' . $_GET['arvore'];
  }

  if (isset($_GET['id_procedimento'])) {
      $strParametros .= '&id_procedimento=' . $_GET['id_procedimento'];
  }

  if (isset($_GET['id_documento'])) {
      $strParametros .= '&id_documento=' . $_GET['id_documento'];
  }

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $arrComandos = array();


  switch ($_GET['acao']) {

      case 'assinatura_externa_liberar':

          $strTitulo = 'Liberação de Assinatura Externa';

          try {

              $objAcessoExternoDTO = new AcessoExternoDTO();
              $objAcessoExternoDTO->setStrStaTipo(AcessoExternoRN::$TA_ASSINATURA_EXTERNA);
              $objAcessoExternoDTO->setStrEmailUnidade($_POST['selEmailUnidade']);
              $objAcessoExternoDTO->setDblIdProtocoloAtividade($_GET['id_procedimento']);
              $objAcessoExternoDTO->setDblIdDocumento($_GET['id_documento']);
              $objAcessoExternoDTO->setNumIdUsuarioExterno($_POST['hdnIdUsuario']);
              $objAcessoExternoDTO->setStrSinProcesso(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinProcesso']));
              $objAcessoExternoDTO->setStrSinInclusao(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinInclusao']));
              $objAcessoExternoDTO->setStrSenha($_POST['pwdSenha']);
              $objAcessoExternoDTO->setNumDias($_POST['txtDias']);

              $arr = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnProtocolos']);

              $arrObjRelAcessoExtProtocoloDTO = array();
              foreach($arr as $dblIdProtocolo){
                $objRelAcessoExtProtocoloDTO = new RelAcessoExtProtocoloDTO();
                $objRelAcessoExtProtocoloDTO->setDblIdProtocolo($dblIdProtocolo);
                $arrObjRelAcessoExtProtocoloDTO[] = $objRelAcessoExtProtocoloDTO;
              }
              $objAcessoExternoDTO->setArrObjRelAcessoExtProtocoloDTO($arrObjRelAcessoExtProtocoloDTO);

              $arr = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnSeries']);
              $arrObjRelAcessoExtSerieDTO = array();
              foreach($arr as $numIdSerie){
                $objRelAcessoExtSerieDTO = new RelAcessoExtSerieDTO();
                $objRelAcessoExtSerieDTO->setNumIdSerie($numIdSerie);
                $arrObjRelAcessoExtSerieDTO[] = $objRelAcessoExtSerieDTO;
              }
              $objAcessoExternoDTO->setArrObjRelAcessoExtSerieDTO($arrObjRelAcessoExtSerieDTO);
            
              $objAcessoExternoRN = new AcessoExternoRN();
              $ret = $objAcessoExternoRN->cadastrar($objAcessoExternoDTO);

              PaginaSEI::getInstance()->setStrMensagem(PaginaSEI::getInstance()->formatarParametrosJavaScript('Disponibilização para Assinatura Externa enviada.'."\n\n".'Verifique posteriormente a caixa postal da unidade para certificar-se de que não ocorreram problemas na entrega.'),PaginaSEI::$TIPO_MSG_AVISO);
              header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assinatura_externa_gerenciar&acao_origem=' . $_GET['acao'] . $strParametros . PaginaSEI::getInstance()->montarAncora($ret->getNumIdAcessoExterno())));
              die;

          } catch (Exception $e) {
              PaginaSEI::getInstance()->processarExcecao($e);
          }

          break;

      case 'assinatura_externa_gerenciar':
          $strTitulo = 'Gerenciar Assinaturas Externas';
          break;

      default:
          throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  if ($_POST['hdnSeries']!=''){
    $arr = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnSeries']);
    $strSeriesSel = SerieINT::montarSelectAcessoExterno(null,null,null,$arr);
  }else{
    $strSeriesSel = "";
  }

  $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
  $numHabilitarInclusaoDocumentos = $objInfraParametro->getValor('SEI_HABILITAR_ACESSO_EXTERNO_INCLUSAO_DOCUMENTO');

  $arrComandos = array();


  $objAcessoExternoDTO = new AcessoExternoDTO();
  $objAcessoExternoDTO->setDblIdDocumento($_GET['id_documento']);

  $objAcessoExternoRN = new AcessoExternoRN();
  $arrObjAcessoExternoDTO = $objAcessoExternoRN->listarLiberacoesAssinaturaExterna($objAcessoExternoDTO);

  $numRegistros = count($arrObjAcessoExternoDTO);

  $bolAcaoLiberar = SessaoSEI::getInstance()->verificarPermissao('assinatura_externa_liberar');
  $bolAcaoCancelarLiberacao = SessaoSEI::getInstance()->verificarPermissao('assinatura_externa_cancelar');

  if ($bolAcaoLiberar) {
      $strLinkLiberar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assinatura_externa_liberar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . $strParametros);
  }

  if ($numRegistros > 0) {

      if ($bolAcaoCancelarLiberacao) {
          //$arrComandos[] = '<button type="submit" accesskey="a" name="sbmCancelarLiberacao" id="sbmCancelarLiberacao" onclick="acaoCassacaoMultipla();" value="Cancelar Liberação" class="infraButton">C<span class="infraTeclaAtalho">a</span>ssar</button>';
          $strLinkCancelarLiberacao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assinatura_externa_cancelar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . $strParametros);
      }

      //$arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

      $strResultado = '';

      $strSumarioTabela = 'Tabela de Liberações de Assinaturas Externas.';
      $strCaptionTabela = 'Liberações de Assinatura Externa';

      $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n"; //90
      $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
      $strResultado .= '<tr>';
      $strResultado .= '<th class="infraTh" width="1%" style="display:none;">' . PaginaSEI::getInstance()->getThCheck('', 'Infra', 'style="display:none;"') . '</th>' . "\n";
      $strResultado .= '<th class="infraTh" >Usuário</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="10%">Unidade</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="10%">Liberação</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="10%">Validade</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="10%">Visualização</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="10%">Utilização</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="10%">Cancelamento</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="10%">Ações</th>' . "\n";
      //$strResultado .= '<th class="infraTh">Ações</th>'."\n";
      $strResultado .= '</tr>' . "\n";
      $strCssTr = '';

      $n = 0;
      foreach ($arrObjAcessoExternoDTO as $objAcessoExternoDTO) {

          $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
          $strResultado .= $strCssTr;

          $strResultado .= "\n" . '<td valign="top" style="display:none;">';
          $strResultado .= PaginaSEI::getInstance()->getTrCheck($n++, $objAcessoExternoDTO->getNumIdAcessoExterno(), $objAcessoExternoDTO->getStrSiglaContato() . '/' . $objAcessoExternoDTO->getStrSiglaUnidade(), 'N', 'Infra', 'style="visibility:hidden;"');
          $strResultado .= '</td>';

          $strResultado .= "\n" . '<td align="center"  valign="top">';
          $strResultado .= '<a alt="' . PaginaSEI::tratarHTML($objAcessoExternoDTO->getStrNomeContato()) . '" title="' . PaginaSEI::tratarHTML($objAcessoExternoDTO->getStrNomeContato()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($objAcessoExternoDTO->getStrSiglaContato()) . '</a>';
          $strResultado .= '</td>';

          $strResultado .= "\n" . '<td align="center"  valign="top">';
          $strResultado .= '<a alt="' . PaginaSEI::tratarHTML($objAcessoExternoDTO->getStrDescricaoUnidade()) . '" title="' . PaginaSEI::tratarHTML($objAcessoExternoDTO->getStrDescricaoUnidade()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($objAcessoExternoDTO->getStrSiglaUnidade()) . '</a>';
          $strResultado .= '</td>' . "\n";

          $strResultado .= '<td align="center" valign="top">' . substr($objAcessoExternoDTO->getDthAberturaAtividade(), 0, 16) . '</td>' . "\n";

          $strResultado .= '<td align="center" valign="top">' . $objAcessoExternoDTO->getDtaValidade() . '</td>' . "\n";

          $strResultado .= '<td align="center" valign="top">' . substr($objAcessoExternoDTO->getDthVisualizacao(), 0, 16) . '</td>' . "\n";

          $strResultado .= '<td align="center" valign="top">';
          if ($objAcessoExternoDTO->getDthUtilizacao() != null) {
              $strResultado .= substr($objAcessoExternoDTO->getDthUtilizacao(), 0, 16);
          } else {
              $strResultado .= '&nbsp;';
          }
          $strResultado .= '</td>' . "\n";

          $strResultado .= '<td align="center" valign="top">';
          if ($objAcessoExternoDTO->getDthCancelamento() != null) {
              $strResultado .= substr($objAcessoExternoDTO->getDthCancelamento(), 0, 16);
          } else {
              $strResultado .= '&nbsp;';
          }
          $strResultado .= '</td>' . "\n";

          $strResultado .= '<td align="center" valign="top">';

          $strDetalhes = '';
          $strOnClick = '';
          $arrObjRelAcessoExtProtocoloDTO = $objAcessoExternoDTO->getArrObjRelAcessoExtProtocoloDTO();

          if (InfraArray::contar($arrObjRelAcessoExtProtocoloDTO) == 0){
            if ($objAcessoExternoDTO->getStrSinProcesso()=='S') {
              $strDetalhes = 'Visualização integral do processo';
              $strIcone = Icone::ACESSO_EXTERNO_INTEGRAL;
            }else{
              //$strDetalhes = 'Sem acesso ao processo';
            }
          }else{
            $strIcone = Icone::ACESSO_EXTERNO_PARCIAL;
            $strDetalhes = 'Com disponibilização de documentos (clique aqui para ver a relação)';
            $strOnClick = 'onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);visualizarDetalhes(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acesso_externo_protocolo_detalhe&acao_origem='.$_GET['acao'].'&id_acesso_externo='.$objAcessoExternoDTO->getNumIdAcessoExterno().'&id_procedimento='.$objAcessoExternoDTO->getDblIdProtocoloAtividade()).'\')"';
          }

          $strResultado .= '<a href="javascript:void(0)" '.$strOnClick.' '.PaginaSEI::montarTitleTooltip($strDetalhes) . '><img src="'.$strIcone.'" class="infraImg" /></a>';

          if ($numHabilitarInclusaoDocumentos == '1'){
            if ($objAcessoExternoDTO->getStrSinInclusao() == "S") {
              $strResultado .= '<a href="javascript:void(0)" onclick="visualizarSeries(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=rel_acesso_ext_serie_detalhar&acao_origem='.$_GET['acao'].'&id_acesso_externo='.$objAcessoExternoDTO->getNumIdAcessoExterno()).'\')" '.PaginaSEI::montarTitleTooltip("Permitida inclusão de documentos (clique aqui para ver a relação)") . '><img src="'.Icone::ACESSO_EXTERNO_INCLUSAO.'" class="infraImg" /></a>';
            }else{
              //$strResultado .= '<a href="javascript:void(0)" '.PaginaSEI::montarTitleTooltip("Não permitida inclusão de documentos") . '><img src="'.Icone::ACESSO_EXTERNO_DETALHES_DOCUMENTO.'" class="infraImg" /></a>';
            }
          }

          if ($bolAcaoCancelarLiberacao && $objAcessoExternoDTO->getStrSinAtivo() == 'S' && ($objAcessoExternoDTO->getDthUtilizacao()==null || $objAcessoExternoDTO->getStrSinProcesso() == 'S' || $objAcessoExternoDTO->getStrSinInclusao() == 'S')) {
              $strResultado .= '<a href="#ID-' . $objAcessoExternoDTO->getNumIdAcessoExterno() . '"  onclick="acaoCancelarLiberacao(\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assinatura_externa_cancelar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . $strParametros . '&id_acesso_externo=' . $objAcessoExternoDTO->getNumIdAcessoExterno()) . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeRemover() . '" title="Cancelar Liberação de Assinatura Externa" alt="Cancelar Liberação de Assinatura Externa" class="infraImg" /></a>&nbsp;';
          } else {
              $strResultado .= '<span style="line-height:1.5em">&nbsp;</span>';
          }
          $strResultado .= '</td>';


          $strResultado .= '</tr>' . "\n";
      }
      $strResultado .= '</table>';
  }

  //$arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'])).'\'" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

  $strItensSelEmailUnidade = EmailUnidadeINT::montarSelectEmail('null', '&nbsp;', $_POST['selEmailUnidade']);

  $strLinkAjaxUsuario = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_externo_auto_completar');

  $strDisplayInclusao = ($numHabilitarInclusaoDocumentos == '1') ? '' : 'display:none;';

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
#lblEmailUnidade {position:absolute;left:0%;top:0%;}
#selEmailUnidade {position:absolute;left:0%;top:38%;width:50%;}

#lblUsuario {position:absolute;left:0%;top:0%;}
#txtUsuario {position:absolute;left:0%;top:38%;width:50%}

#divInclusao {<?=$strDisplayInclusao?>}
#divSinProcesso {position:absolute;left:0%;top:25%;}
#divSinInclusao {position:absolute;left:0%;top:25%;}

#lblProtocolos {position:absolute;left:0%;top:0%;}
#selProtocolos {position:absolute;left:0%;top:17%;width:92%;}
#divOpcoesProtocolos {position:absolute;left:93%;top:20%;}

#lblSeries {position:absolute;left:0%;top:0%;}
#selSeries {position:absolute;left:0%;top:17%;width:92%;}
#divOpcoesSeries {position:absolute;left:93%;top:20%;}

#lblDias {position:absolute;left:0%;top:5%;}
#txtDias {position:absolute;left:0%;top:43%;width:15%;}

#lblSenha {position:absolute;left:18%;top:5%;}
#pwdSenha {position:absolute;left:18%;top:43%;width:20%;}

#btnLiberar {position:absolute;left:0%;top:20%;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

$(document).ready(function(){
  new MaskedPassword(document.getElementById("pwdSenha"), '\u25CF');
});

var objAutoCompletarUsuario = null;
var objLupaProtocolos = null;
var objLupaSeries = null;

function inicializar(){

  objAutoCompletarUsuario = new infraAjaxAutoCompletar('hdnIdUsuario','txtUsuario','<?= $strLinkAjaxUsuario ?>');
  objAutoCompletarUsuario.limparCampo = true;

  objAutoCompletarUsuario.prepararExecucao = function(){
    return 'palavras_pesquisa='+document.getElementById('txtUsuario').value;
  };

  objLupaProtocolos	= new infraLupaSelect('selProtocolos','hdnProtocolos','<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acesso_externo_protocolo_selecionar&tipo_selecao=2&id_object=objLupaProtocolos&id_procedimento='.$_GET['id_procedimento'].'&id_documento=' . $_GET['id_documento'])?>');
  objLupaSeries	= new infraLupaSelect('selSeries','hdnSeries','<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=serie_selecionar_acesso_externo&tipo_selecao=2&id_object=objLupaSeries')?>');


<? if ($_GET['acao'] == 'assinatura_externa_liberar') { ?>
    objAutoCompletarUsuario.selecionar('<?= $_POST['hdnIdUsuario'] ?>','<?= $_POST['txtUsuario'] ?>');
<? } ?>

  document.getElementById('selEmailUnidade').focus();

  infraEfeitoTabelas();

  trocarVisualizacaoProcesso();
  trocarInclusaoDocumentos();

}


<? if ($bolAcaoLiberar) { ?>

function liberar(){

  if (document.getElementById('selEmailUnidade').value == 'null' || document.getElementById('selEmailUnidade').value == '') {
    alert('E-mail da unidade não informado.');
    document.getElementById('selEmailUnidade').focus();
    return;
  }

  if (infraTrim(document.getElementById('hdnIdUsuario').value)==''){
    alert('Informe um Usuário Externo.');
    document.getElementById('txtUsuario').focus();
    return;
  }


  if (document.getElementById('chkSinInclusao').checked && document.getElementById('selSeries').options.length==0) {
    alert('Nenhum tipo de documento selecionado para inclusão.');
    document.getElementById('selSeries').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtDias').value) == '') {
    alert('Validade da liberação não informada.');
    document.getElementById('txtDias').focus();
    return false;
  }

  if (document.getElementById('txtDias').value <= 0){
    alert('Validade do acesso deve ser de pelo menos um dia.');
    document.getElementById('txtDias').focus();
    return false;
  }

  if (document.getElementById('pwdSenha').value == '') {
    alert('Senha não informada.');
    document.getElementById('pwdSenha').focus();
    return false;
  }

  document.getElementById('frmGerenciarAssinaturaExterna').target = '_self';
  document.getElementById('frmGerenciarAssinaturaExterna').action = '<?= $strLinkLiberar ?>';
  document.getElementById('frmGerenciarAssinaturaExterna').submit();
}

<? } ?>

<? if ($bolAcaoCancelarLiberacao) { ?>
function acaoCancelarLiberacao(link){
  parent.infraAbrirJanelaModal(link,600,250);
}

function acaoCancelamentoLiberacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma liberação de assinatura externa selecionada.');
    return;
  }
  acaoCancelarLiberacao(null);
}
<? } ?>
  function trocarInclusaoDocumentos(){
    if (!document.getElementById('chkSinInclusao').checked){
    document.getElementById('divTiposDocumento').style.display = 'none';
    }else{
      document.getElementById('divTiposDocumento').style.display = '';
    }
  }

function visualizarDetalhes(link){
  infraAbrirJanelaModal(link,700,400);
}

function visualizarSeries(link){
  infraAbrirJanelaModal(link,700,400);
}

function OnSubmitForm() {
  return true;
}

function trocarVisualizacaoProcesso(){
  if (document.getElementById('chkSinProcesso').checked){
    document.getElementById('divRestricao').style.display = 'none';
    document.getElementById('selProtocolos').options.length = 0;
  }else{
    document.getElementById('divRestricao').style.display = '';
  }
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<form id="frmGerenciarAssinaturaExterna" method="post" onsubmit="return OnSubmitForm();"
      action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] . $strParametros) ?>">
    <?
    //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    ?>

    <div id="divEmailUnidade" class="infraAreaDados" style="height:5em;">
      <label id="lblEmailUnidade" for="selEmailUnidade" accesskey="" class="infraLabelObrigatorio">E-mail da Unidade:</label>
      <select id="selEmailUnidade" name="selEmailUnidade" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
          <?= $strItensSelEmailUnidade ?>
      </select>
    </div>

    <div id="divUsuario" class="infraAreaDados" style="height:5em;">
      <label id="lblUsuario" for="selUsuario" class="infraLabelObrigatorio">Liberar Assinatura Externa para:</label>
      <input type="text" id="txtUsuario" name="txtUsuario" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
      <input type="hidden" id="hdnIdUsuario" name="hdnIdUsuario" class="infraText" value=""/>
    </div>

    <div id="divProcesso" class="infraAreaDados" style="height:3.5em;">
      <div id="divSinProcesso" class="infraDivCheckbox">
        <input type="checkbox" id="chkSinProcesso" name="chkSinProcesso" onchange="trocarVisualizacaoProcesso()" class="infraCheckbox" <?= PaginaSEI::getInstance()->setCheckbox(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinProcesso']))?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
        <label id="lblSinProcesso" for="chkSinProcesso" accesskey="" class="infraLabelCheckbox">Com visualização integral do processo</label>
      </div>
    </div>

  <div id="divRestricao" class="infraAreaDados" style="height:11em;">
    <label id="lblProtocolos" for="selProtocolos" class="infraLabelOpcional">Protocolos adicionais disponibilizados para consulta (clique na lupa para selecionar):</label>
    <select id="selProtocolos" name="selProtocolos" size="5" multiple="multiple" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"></select>
    <div id="divOpcoesProtocolos">
      <img id="imgLupaProtocolos" onclick="objLupaProtocolos.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Protocolos" title="Selecionar Protocolos" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
      <br />
      <img id="imgExcluirProtocolos" onclick="objLupaProtocolos.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Protocolos Selecionados" title="Remover Protocolos Selecionados" class="infraImgNormal" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    </div>
    <input type="hidden" id="hdnProtocolos" name="hdnProtocolos" value="<?=$_POST['hdnProtocolos']?>" />
  </div>

    <div id="divInclusao" class="infraAreaDados" style="height:3.5em;">
      <div id="divSinInclusao" class="infraDivCheckbox">
        <input type="checkbox" id="chkSinInclusao" name="chkSinInclusao" onchange="trocarInclusaoDocumentos()"   class="infraCheckbox" <?= PaginaSEI::getInstance()->setCheckbox(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinInclusao']))?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
        <label id="lblSinInclusao" for="chkSinInclusao" accesskey="" class="infraLabelCheckbox">Permitir inclusão de documentos</label>
      </div>
    </div>

    <div id="divTiposDocumento" class="infraAreaDados" style="height:11em;">
      <label id="lblSeries" for="selSeries" class="infraLabelOpcional">Tipos de documentos liberados para inclusão (clique na lupa para selecionar):</label>
      <select id="selSeries" name="selSeries" size="5" multiple="multiple" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
        <?=$strSeriesSel?>
      </select>
      <div id="divOpcoesSeries">
        <img id="imgLupaSeries" onclick="objLupaSeries.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Tipos de Documentos" title="Selecionar Tipos de Documentos" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
        <br />
        <img id="imgExcluirSeries" onclick="objLupaSeries.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Tipos de Documentos Selecionados" title="Remover Tipos de Documentos Selecionados" class="infraImgNormal" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
      </div>
      <input type="hidden" id="hdnSeries" name="hdnSeries" value="<?=$_POST['hdnSeries']?>" />
    </div>

    <div id="divValidadeSenha" class="infraAreaDados" style="height:5em;">
      <label id="lblDias" for="txtDias" class="infraLabelObrigatorio">Validade (dias):</label>
      <input type="text" id="txtDias" name="txtDias" class="infraText" value="<?=PaginaSEI::tratarHTML($_POST['txtDias'])?>" onkeypress="return infraMascaraNumero(this,event);" maxlength="4" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

      <label id="lblSenha" for="pwdSenha" accesskey="" class="infraLabelObrigatorio">Senha:</label>
      <?=InfraINT::montarInputPassword('pwdSenha', '', 'tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'"')?>
    </div>

    <div id="divBotao" class="infraAreaDados" style="height:2.5em;">
      <button type="button" name="btnLiberar" id="btnLiberar" onclick="liberar();" accesskey="L" value="Liberar" class="infraButton" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">&nbsp;&nbsp;<span class="infraTeclaAtalho">L</span>iberar&nbsp;&nbsp;</button>
    </div>
    <br />
    <?
    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    PaginaSEI::getInstance()->montarAreaDebug();
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>