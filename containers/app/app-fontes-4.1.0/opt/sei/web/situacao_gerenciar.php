<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 08/09/2014 - criado por bcu
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__) . '/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $numIdSituacao = null;

  $strDesabilitar = '';

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore','id_procedimento','id_acompanhamento','id_usuario_atribuicao'));

  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  $arrComandos = array();

  $bolMultiplo = false;

  $objAndamentoSituacaoRN = new AndamentoSituacaoRN();

  switch ($_GET['acao']) {
    case 'andamento_situacao_gerenciar':
      $strTitulo = 'Gerenciar Ponto de Controle';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmGerenciarSituacao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

      $objAndamentoSituacaoDTO = new AndamentoSituacaoDTO();

      if (isset($_GET['id_procedimento'])) {
        $arrIdProtocolo = array($_GET['id_procedimento']);
      } else if ($_GET['acao_origem'] == 'procedimento_controlar') {
        $arrItensControleProcesso = array_merge(PaginaSEI::getInstance()->getArrStrItensSelecionados('Gerados'), PaginaSEI::getInstance()->getArrStrItensSelecionados('Recebidos'),PaginaSEI::getInstance()->getArrStrItensSelecionados('Detalhado'));
        $arrIdProtocolo = $arrItensControleProcesso;
      } else {
        $arrIdProtocolo = explode(',',$_POST['hdnIdProtocolo']);
      }

      if ($_GET['id_acompanhamento']!='') {
        $strAncora = $_GET['id_acompanhamento'];
      }else{
        $strAncora = $arrIdProtocolo;
      }

      if (!PaginaSEI::getInstance()->isBolArvore()) {
        $arrComandos[] = '<button type="button" accesskey="V" name="btnVoltar" id="btnVoltar" value="Voltar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . PaginaSEI::getInstance()->montarAncora($strAncora) . '\';" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>';
      }

      if (InfraArray::contar($arrIdProtocolo) > 1){
        $bolMultiplo = true;
      }

      $numIdSituacao = $_POST['selSituacao'];

      if (isset($_POST['sbmGerenciarSituacao'])) {

        $objAndamentoSituacaoDTO->setDblIdProcedimento($arrIdProtocolo);
        $objAndamentoSituacaoDTO->setNumIdSituacao($numIdSituacao);

        try {

          $objAndamentoSituacaoRN = new AndamentoSituacaoRN();
          $ret = $objAndamentoSituacaoRN->gerenciar($objAndamentoSituacaoDTO);
          //PaginaSEI::getInstance()->adicionarMensagem('Ponto de Controle "'.$ret->getNumIdSituacao().'" registrado com sucesso.');
          header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&atualizar_arvore=1' . PaginaSEI::getInstance()->montarAncora($strAncora)));
          die;
        } catch (Exception $e) {
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  if ($_GET['acao_origem']!='andamento_situacao_gerenciar') {
    $objAndamentoSituacaoDTO = new AndamentoSituacaoDTO();
    $objAndamentoSituacaoDTO->setDistinct(true);
    $objAndamentoSituacaoDTO->retNumIdSituacao();
    $objAndamentoSituacaoDTO->setDblIdProcedimento($arrIdProtocolo,InfraDTO::$OPER_IN);
    $objAndamentoSituacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
    $objAndamentoSituacaoDTO->setStrSinUltimo('S');
    $arrObjAndamentoSituacaoDTO = $objAndamentoSituacaoRN->listar($objAndamentoSituacaoDTO);

    if (count($arrObjAndamentoSituacaoDTO) == 1) {
      $numIdSituacao = $arrObjAndamentoSituacaoDTO[0]->getNumIdSituacao();
    }
  }else{
    $numIdSituacao = $_POST['hdnIdSituacao'];
  }

  $strResultado = '';
  $numRegistrosAndamento = 0;

  if (!$bolMultiplo) {

    $objProcedimentoDTO = new ProcedimentoDTO();
    $objProcedimentoDTO->setDblIdProcedimento($arrIdProtocolo[0]);
    $objProcedimentoDTO->retNumIdTipoProcedimento();

    $objProcedimentoRN = new ProcedimentoRN();
    $objProcedimentoDTO = $objProcedimentoRN->consultarRN0201($objProcedimentoDTO);

    if ($objProcedimentoDTO == null) {
      throw new InfraException("Processo não encontrado.");
    }

    $objAndamentoSituacaoDTO = new AndamentoSituacaoDTO();
    $objAndamentoSituacaoDTO->retDthExecucao();
    $objAndamentoSituacaoDTO->retNumIdUsuario();
    $objAndamentoSituacaoDTO->retStrSiglaUsuario();
    $objAndamentoSituacaoDTO->retStrNomeUsuario();
    $objAndamentoSituacaoDTO->retNumIdSituacao();
    $objAndamentoSituacaoDTO->retStrNomeSituacao();
    $objAndamentoSituacaoDTO->retStrSinAtivoSituacao();
    $objAndamentoSituacaoDTO->retNumIdAndamentoSituacao();
    $objAndamentoSituacaoDTO->setDblIdProcedimento($arrIdProtocolo[0]);
    $objAndamentoSituacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
    $objAndamentoSituacaoDTO->setOrdNumIdAndamentoSituacao(InfraDTO::$TIPO_ORDENACAO_DESC);

    PaginaSEI::getInstance()->prepararPaginacao($objAndamentoSituacaoDTO, 100);

    $objAndamentoSituacaoRN = new AndamentoSituacaoRN();
    $arrObjAndamentoSituacaoDTO = $objAndamentoSituacaoRN->listar($objAndamentoSituacaoDTO);

    PaginaSEI::getInstance()->processarPaginacao($objAndamentoSituacaoDTO);

    $numRegistrosAndamento = count($arrObjAndamentoSituacaoDTO);

    if ($numRegistrosAndamento > 0) {

      $bolCheck = false;

      $strResultado = '';

      $strResultado .= '<table id="tblHistorico" width="99%" class="infraTable" summary="Histórico de Pontos de Controle">' . "\n";
      $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela('Histórico de Pontos de Controle', $numRegistrosAndamento, '') . '</caption>';
      $strResultado .= '<tr>';
      $strResultado .= '<th class="infraTh" width="20%">Data/Hora</th>';
      //$strResultado .= '<th class="infraTh" width="15%">Unidade</th>';
      $strResultado .= '<th class="infraTh" width="20%">Usuário</th>';
      $strResultado .= '<th class="infraTh">Ponto de Controle</th>';
      $strResultado .= '</tr>' . "\n";

      $strQuebraLinha = '<span style="line-height:.5em"><br /></span>';

      foreach ($arrObjAndamentoSituacaoDTO as $objAndamentoSituacaoDTO) {

        $strResultado .= '<tr class="infraTrClara">'."\n";
        $strResultado .= '<td align="center" valign="top">'.$objAndamentoSituacaoDTO->getDthExecucao().'</td>';

        /*
        $strResultado .= '<td align="center"  valign="top">';
        $strResultado .= '<a alt="'.$objAndamentoSituacaoDTO->getStrDescricaoUnidade().'" title="'.$objAndamentoSituacaoDTO->getStrDescricaoUnidade().'" class="ancoraSigla">'.$objAndamentoSituacaoDTO->getStrSiglaUnidade().'</a>';
        $strResultado .= '</td>';
        */

        $strResultado .= "\n" . '<td align="center"  valign="top">';
        $strResultado .= '<a alt="' . PaginaSEI::tratarHTML($objAndamentoSituacaoDTO->getStrNomeUsuario()) . '" title="' . PaginaSEI::tratarHTML($objAndamentoSituacaoDTO->getStrNomeUsuario()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($objAndamentoSituacaoDTO->getStrSiglaUsuario()) . '</a>';
        $strResultado .= '</td>';
        $strResultado .= "\n" . '<td valign="top">';

        if ($objAndamentoSituacaoDTO->getNumIdSituacao()!=null) {
          $strResultado .= PaginaSEI::tratarHTML(SituacaoINT::formatarSituacaoDesativada($objAndamentoSituacaoDTO->getStrNomeSituacao(),$objAndamentoSituacaoDTO->getStrSinAtivoSituacao()));
        }else{
          $strResultado .= '[Ponto de Controle removido]';
        }

        $strResultado .= '</td>';
        $strResultado .= '</tr>';
      }
      $strResultado .= '</table>';
    }
  }

  $strItensSelSituacao = SituacaoINT::montarSelectNome('null', '&nbsp', $numIdSituacao, SessaoSEI::getInstance()->getNumIdUnidadeAtual());

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
#lblSituacao {position:absolute;left:0%;top:0%;width:50%;}
#selSituacao {position:absolute;left:0%;top:40%;width:50%;}

#tblHistorico td{
  padding:.2em;
}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
  document.getElementById('selSituacao').focus();
  infraEfeitoTabelas();

}

function validarCadastro() {
  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmGerenciarSituacao" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?

PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('5em');
?>
  <label id="lblSituacao" for="selSituacao" class="infraLabelOpcional">Ponto de Controle:</label>
  <select id="selSituacao" name="selSituacao" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelSituacao?>
  </select>
  <input type="hidden" id="hdnIdProtocolo" name="hdnIdProtocolo" value="<?=implode(',',$arrIdProtocolo)?>" />
  <?
  PaginaSEI::getInstance()->fecharAreaDados();

  if (!$bolMultiplo) {
    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistrosAndamento);
  }
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>

<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>