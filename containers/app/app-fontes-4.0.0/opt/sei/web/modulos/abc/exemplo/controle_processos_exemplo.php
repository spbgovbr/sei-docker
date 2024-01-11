<?

/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 02/05/2016 - criado por mga@trf4.jus.br
 *
 */

try {
  require_once dirname(__FILE__).'/../../../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $arrComandos = array();

  switch($_GET['acao']){
    
    case 'md_abc_andamento_lancar':
      
      $strTitulo = 'Formulário Controle de Processos ABC';

      if ($_GET['acao_origem']=='procedimento_controlar'){
        $arrIdProtocolo = array_merge(PaginaSEI::getInstance()->getArrStrItensSelecionados('Gerados'), PaginaSEI::getInstance()->getArrStrItensSelecionados('Recebidos'), PaginaSEI::getInstance()->getArrStrItensSelecionados('Detalhado'));
      }else{
        $arrIdProtocolo = explode(',',$_POST['hdnIdProtocolo']);
      }

      $arrComandos[] = '<button type="submit" name="sbmSalvarManual" value="Salvar (Controle Manual de Transação)" class="infraButton">Salvar (Controle Manual de Transação)</button>';
      $arrComandos[] = '<button type="submit" name="sbmSalvarAutomatico" value="Salvar (Controle Automático de Transação)" class="infraButton">Salvar (Controle Automático de Transação)</button>';
      $arrComandos[] = '<button type="button" accesskey="V" name="btnVoltar" id="btnVoltar" value="Voltar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . PaginaSEI::getInstance()->montarAncora($arrIdProtocolo) . '\';" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>';

      if (isset($_POST['sbmSalvarManual']) || isset($_POST['sbmSalvarAutomatico'])) {
        try{

          $objMdAbcTesteRN = new MdAbcTesteRN();

          if (isset($_POST['sbmSalvarManual'])) {
            $objMdAbcTesteRN->lancarAndamentosManual($arrIdProtocolo, $_POST['txtTextoAndamento']);
          } else {
            $objMdAbcTesteRN->lancarAndamentosAutomatico(array($arrIdProtocolo, $_POST['txtTextoAndamento']));
          }

          header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora($arrIdProtocolo)));
          die;

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $objSeiRN = new SeiRN();

  $arrObjSaidaConsultarProcessoAPI = array();
  foreach($arrIdProtocolo as $dblIdProtocolo) {
    $objEntradaConsultarProcedimentoAPI = new EntradaConsultarProcedimentoAPI();
    $objEntradaConsultarProcedimentoAPI->setIdProcedimento($dblIdProtocolo);
    $objEntradaConsultarProcedimentoAPI->setSinRetornarAndamentoGeracao('S');

    $arrObjSaidaConsultarProcessoAPI[] = $objSeiRN->consultarProcedimento($objEntradaConsultarProcedimentoAPI);
  }

  $numRegistros = count($arrObjSaidaConsultarProcessoAPI);

  if ($numRegistros > 0) {

    $strResultado = '';
    $strResultado .= '<table id="tblControleAbc" width="99%" class="infraTable" summary="Processos ABC">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela('Processos ABC', $numRegistros, '') . '</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="20%">Protocolo</th>';
    $strResultado .= '<th class="infraTh" width="15%">Geração</th>';
    $strResultado .= '<th class="infraTh" width="15%">Unidade</th>';
    $strResultado .= '<th class="infraTh" width="10%">Usuário</th>';
    $strResultado .= '</tr>' . "\n";

    foreach ($arrObjSaidaConsultarProcessoAPI as $objSaidaConsultarProcessoAPI) {

      $strResultado .= '<tr class="infraTrClara">';
      $strResultado .= '<td align="center" valign="top"><a target="_blank" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$objSaidaConsultarProcessoAPI->getIdProcedimento()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" alt="'.PaginaSEI::tratarHTML($objSaidaConsultarProcessoAPI->getTipoProcedimento()->getNome()).'" title="'.PaginaSEI::tratarHTML($objSaidaConsultarProcessoAPI->getTipoProcedimento()->getNome()).'" class="ancoraPadraoPreta">'.PaginaSEI::tratarHTML($objSaidaConsultarProcessoAPI->getProcedimentoFormatado()).'</a></td>'."\n";

      $objAndamentoAPI = $objSaidaConsultarProcessoAPI->getAndamentoGeracao();

      $strResultado .= '<td align="center" valign="top">'.PaginaSEI::tratarHTML($objAndamentoAPI->getDataHora()).'</td>'."\n";

      $strResultado .= '<td align="center"  valign="top">';
      $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($objAndamentoAPI->getUnidade()->getDescricao()).'" title="'.PaginaSEI::tratarHTML($objAndamentoAPI->getUnidade()->getDescricao()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAndamentoAPI->getUnidade()->getSigla()).'</a>';
      $strResultado .= '</td>';

      $strResultado .= '<td align="center"  valign="top">';
      $strResultado .= '<a alt="' . PaginaSEI::tratarHTML($objAndamentoAPI->getUsuario()->getNome()) . '" title="' . PaginaSEI::tratarHTML($objAndamentoAPI->getUsuario()->getNome()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($objAndamentoAPI->getUsuario()->getSigla()) . '</a>';
      $strResultado .= '</td>';

      $strResultado .= '</tr>';
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

#lblTextoAndamento {position:absolute;left:0%;top:0%;width:50%;}
#txtTextoAndamento {position:absolute;left:0%;top:40%;width:50%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  document.getElementById('txtTextoAndamento').focus();
}

function validarCadastro() {

  if (infraTrim(document.getElementById('txtTextoAndamento').value)=='') {
    alert('Informe o texto do andamento.');
    document.getElementById('txtTextoAndamento').focus();
    return false;
  }

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
<form id="frmFormularioTeste" method="post" onsubmit="return OnSubmitForm();" action="<?=PaginaSEI::getInstance()->formatarXHTML(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao']))?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('5em');
?>

  <label id="lblTextoAndamento" for="txtTextoAndamento" accesskey="T" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">T</span>exto para lançamento no andamento dos processos:</label>
  <input type="text" id="txtTextoAndamento" name="txtTextoAndamento" class="infraText" value="<?=PaginaSEI::tratarHTML($_POST['txtTextoAndamento'])?>" onkeypress="return infraMascaraTexto(this,event,100);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>

  <input type="hidden" id="hdnIdProtocolo" name="hdnIdProtocolo" value="<?=implode(',',$arrIdProtocolo);?>" />

</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>