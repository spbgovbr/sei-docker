<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 22/06/2016 - criado por mga
 *
 * Versão do Gerador de Código: 1.12.0
 *
 * Versão no CVS: $Id$
 */

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $strParametros = '';
  if (isset($_GET['acesso'])){
    $strParametros .= '&acesso='.$_GET['acesso'];
  }

  $bolGeracaoOK = false;

  switch($_GET['acao']){

    case 'procedimento_acervo_sigilosos_global':
      $strTitulo = 'Acervo Global de Processos Sigilosos';

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  if ($_GET['acesso']=='1') {

    $arrComandos[] = '<button type="submit" accesskey="S" id="sbmPesquisar" name="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
    $arrComandos[] = '<button type="button" accesskey="L" id="btnLimpar" name="btnPesquisar" onclick="limpar();" value="Limpar" class="infraButton"><span class="infraTeclaAtalho">L</span>impar</button>';

    $objPesquisaSigilosoDTO = new PesquisaSigilosoDTO();
    $objPesquisaSigilosoDTO->retDtaGeracao();
    $objPesquisaSigilosoDTO->retStrProtocoloFormatado();
    $objPesquisaSigilosoDTO->retStrNomeTipoProcedimento();

    $objPesquisaSigilosoDTO->setStrSinFiltroProtocolo('S');
    $objPesquisaSigilosoDTO->setStrSinFiltroOrgao('S');
    $objPesquisaSigilosoDTO->setStrSinFiltroUnidade('S');
    $objPesquisaSigilosoDTO->setStrSinFiltroTipoProcedimento('S');
    $objPesquisaSigilosoDTO->setStrSinFiltroPeriodoAutuacao('S');
    $objPesquisaSigilosoDTO->setStrSinFiltroCredencialInativa('S');

    ProcedimentoINT::montarCamposPesquisaSigiloso($objPesquisaSigilosoDTO, $strCssSigilosos, $strJsSigilosos, $strJsInicializarSigilosos, $strJsValidarSigilosos, $strHtmlSigilosos);

    $arrObjProcedimentoDTO = array();

    if (isset($_POST['sbmPesquisar']) || $_GET['acao']==$_GET['acao_origem']){
      try {

        PaginaSEI::getInstance()->prepararOrdenacao($objPesquisaSigilosoDTO, 'Geracao', InfraDTO::$TIPO_ORDENACAO_DESC);

        $objPesquisaSigilosoDTO->setOrdDblIdProtocolo(InfraDTO::$TIPO_ORDENACAO_ASC);

        if ($objPesquisaSigilosoDTO->getStrSinCredencialInativa() == 'N') {
          PaginaSEI::getInstance()->prepararPaginacao($objPesquisaSigilosoDTO, 1000);
        }

        try {
          $objProcedimentoRN = new ProcedimentoRN();
          $arrObjProcedimentoDTO = $objProcedimentoRN->pesquisarAcervoSigilososGlobal($objPesquisaSigilosoDTO);
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }

        if ($objPesquisaSigilosoDTO->getStrSinCredencialInativa() == 'N') {
          PaginaSEI::getInstance()->processarPaginacao($objPesquisaSigilosoDTO);
        }

      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
    }


    $numRegistros = count($arrObjProcedimentoDTO);

    if ($numRegistros) {

      $bolCheck = false;

      $arrComandos[] = '<button type="button" accesskey="G" name="btnGerar" value="Gerar" onclick="gerar();" class="infraButton"><span class="infraTeclaAtalho">G</span>erar Planilha</button>';

      $strResultado = '';

      $strSumarioTabela = 'Tabela de Processos.';
      $strCaptionTabela = 'Processos';
      $strResultado .= '<table id="tblProcessos" width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
      $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
      $strResultado .= '<tr>';
      $strResultado .= '<th class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
      $strResultado .= '<th class="infraTh">Processo</th>' . "\n";
      $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objPesquisaSigilosoDTO,'Autuação','Geracao',$arrObjProcedimentoDTO).'</th>' . "\n";
      $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objPesquisaSigilosoDTO,'Tipo','NomeTipoProcedimento',$arrObjProcedimentoDTO).'</th>' . "\n";
      $strResultado .= '<th class="infraTh">Credenciais nas Unidades</th>' . "\n";
      $strResultado .= '</tr>' . "\n";

      $strCssTr = '';

      for ($i = 0; $i < $numRegistros; $i++) {

        $dblIdProcedimento = $arrObjProcedimentoDTO[$i]->getDblIdProcedimento();

        $arrObjAcessoDTO = $arrObjProcedimentoDTO[$i]->getArrObjAcessoDTO();

        $strCssTr = ($strCssTr == 'class="infraTrClara"') ? 'class="infraTrEscura"' : 'class="infraTrClara"';
        $strResultado .= '<tr '.$strCssTr.'>'."\n";

        $strAcessos = '';
        foreach ($arrObjAcessoDTO as $objAcessoDTO) {

          if ($strAcessos != '') {
            $strAcessos .= '<br/>';
          }

          $strAcessos .= '<span class="iconeLegenda" style="color:';
          if ($objAcessoDTO->getStrStaCredencialUnidade() == ProtocoloRN::$TCU_INATIVA) {
            $strAcessos .= 'black;">&#9679;';
          } else if ($objAcessoDTO->getStrStaCredencialUnidade() == ProtocoloRN::$TCU_ATIVA) {
            $strAcessos .= 'green;">&#9679;';
          }
          $strAcessos .= '</span>';
          $strAcessos .= '<a alt="'.PaginaSEI::tratarHTML($objAcessoDTO->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($objAcessoDTO->getStrDescricaoUnidade()).'" class="ancoraSigla textoLegenda">'.PaginaSEI::tratarHTML($objAcessoDTO->getStrSiglaUnidade()).'</a>';
        }

        $strResultado .= '<td align="center" valign="top">'.PaginaSEI::getInstance()->getTrCheck($i, $arrObjProcedimentoDTO[$i]->getDblIdProcedimento(), $arrObjProcedimentoDTO[$i]->getStrProtocoloProcedimentoFormatado()).'</td>'."\n";
        $strResultado .= '<td align="center" valign="top">'.PaginaSEI::tratarHTML($arrObjProcedimentoDTO[$i]->getStrProtocoloProcedimentoFormatado()).'</td>'."\n";
        $strResultado .= '<td align="center" valign="top">'.PaginaSEI::tratarHTML($arrObjProcedimentoDTO[$i]->getDtaGeracaoProtocolo()).'</td>'."\n";
        $strResultado .= '<td align="center" valign="top">'.PaginaSEI::tratarHTML($arrObjProcedimentoDTO[$i]->getStrNomeTipoProcedimento()).'</td>'."\n";

        $strResultado .= '<td align="left" valign="top">' . ($strAcessos == '' ? '&nbsp;' : $strAcessos) . '</td>' . "\n";
        $strResultado .= '</tr>' . "\n";
      }
      $strResultado .= '</table>' . "\n";

      $strLegenda = '<label id="lblLegenda" class="infraLabelOpcional">Legenda:</label>
                     <div id="divLegenda1"><span class="iconeLegenda" style="color:green;">&#9679;</span><span class="textoLegenda">Com acesso ao processo</span></div>
                     <div id="divLegenda2"><span class="iconeLegenda" style="color:black;">&#9679;</span><span class="textoLegenda">Sem acesso ao processo</span></div>';

      if ($_POST['hdnFlagGerar']=='1'){
        try{

          $objAnexoRN = new AnexoRN();
          $strArquivoTemp = $objAnexoRN->gerarNomeArquivoTemporario().'.csv';

          $strCsv = 'Processo;Autuação;Tipo;Credenciais nas Unidades'."\n";

          for ($i = 0; $i < $numRegistros; $i++) {

            if (in_array($arrObjProcedimentoDTO[$i]->getDblIdProcedimento(), PaginaSEI::getInstance()->getArrStrItensSelecionados())) {

              $strCsv .= $arrObjProcedimentoDTO[$i]->getStrProtocoloProcedimentoFormatado().';';
              $strCsv .= $arrObjProcedimentoDTO[$i]->getDtaGeracaoProtocolo().';';
              $strCsv .= '"'.str_replace('"', "\"\"", $arrObjProcedimentoDTO[$i]->getStrNomeTipoProcedimento()).'";';

              $arrObjAcessoDTO = $arrObjProcedimentoDTO[$i]->getArrObjAcessoDTO();
              $strAcessos = '';
              foreach ($arrObjAcessoDTO as $objAcessoDTO) {

                if ($strAcessos != '') {
                  $strAcessos .= "\n";
                }

                $strAcessos .= $objAcessoDTO->getStrSiglaUnidade();

                if ($objAcessoDTO->getStrStaCredencialUnidade() == ProtocoloRN::$TCU_INATIVA) {
                  $strAcessos .= ' (sem acesso)';
                }
              }
              $strCsv .= '"'.str_replace('"', "\"\"", $strAcessos).'"'."\n";
            }
          }

          if (file_put_contents(DIR_SEI_TEMP.'/'.$strArquivoTemp, $strCsv) === false) {
            throw new InfraException('Erro criando arquivo CSV temporário.');
          }

          $strNomeDownload = 'SEI-Acervo-Global-'.str_replace(array('/',' ',':'),'-',InfraData::getStrDataHoraAtual()).'.csv';

          $bolGeracaoOK = true;

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }

    }
  }

  $strLinkAcesso = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=usuario_validar_acesso&acao_origem='.$_GET['acao'].'&acao_destino=procedimento_acervo_sigilosos_global&acao_negado=procedimento_controlar');

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
  <?=$strCssSigilosos;?>

  #divInfraAreaDados{<?=($_GET['acesso']=='1'?'':'display:none;')?>}

  #lblLegenda {position:absolute;left:0%;top:0%;width:18%;}
  #divLegenda1 {position:absolute;left:18%;top:0%;width:60%;}
  #divLegenda2 {position:absolute;left:18%;top:30%;width:60%;}

  .iconeLegenda {
  margin:0;
  border:0;
  padding:0 .1em 0 0;
  display:inline-table;
  font-size:20px;
  }

  .textoLegenda{
  font-size:1.2em;
  line-height:16px;
  vertical-align:text-bottom;
  padding-left:5px;
  }


<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script>

  <?=$strJsSigilosos;?>

  function inicializar(){

    if ('<?=$_GET['acesso']?>'!='1'){
      infraAbrirJanelaModal('<?=$strLinkAcesso?>',500,300,true,'finalizar');
      return;
    }

    <?if ($bolGeracaoOK){ ?>
      window.open('<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=exibir_arquivo&nome_arquivo='.$strArquivoTemp.'&nome_download='.InfraUtil::formatarNomeArquivo($strNomeDownload).'&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']);?>');
    <?}?>

    <?=$strJsInicializarSigilosos;?>

    infraOcultarMenuSistemaEsquema();

    //infraEfeitoTabelas();
  }

  function onSubmitForm(){
    <?=$strJsValidarSigilosos;?>
    infraExibirAviso();
    return true;
  }

  function gerar() {

    if (document.getElementById('hdnInfraItensSelecionados').value==''){
      alert('Nenhum processo selecionado.');
      return;
    }

    infraExibirAviso(false);

    document.getElementById('hdnFlagGerar').value = '1';
    document.getElementById('frmProcedimentoAcervoSigilososGlobal').submit();
  }

  //</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmProcedimentoAcervoSigilososGlobal" onsubmit="return onSubmitForm()" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
    <?
    //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSEI::getInstance()->abrirAreaDados();
    echo $strHtmlSigilosos;
    PaginaSEI::getInstance()->fecharAreaDados();
    if ($strLegenda!='') {
      PaginaSEI::getInstance()->abrirAreaDados('8em');
      echo $strLegenda;
      PaginaSEI::getInstance()->fecharAreaDados();
    }
    PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
    PaginaSEI::getInstance()->montarAreaDebug();
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);

    ?>

    <input type="hidden" id="hdnFlagGerar" name="hdnFlagGerar" value="0" />
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>