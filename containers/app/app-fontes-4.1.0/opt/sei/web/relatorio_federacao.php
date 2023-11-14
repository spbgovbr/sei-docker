<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 10/06/2021 - criado por mga
 *
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

  switch($_GET['acao']){

    case 'relatorio_federacao_gerar':
      $strTitulo = 'Processos do SEI Federação';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  $arrComandos[] = '<button type="submit" accesskey="S" id="sbmPesquisar" name="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
  $arrComandos[] = '<button type="button" accesskey="L" id="btnLimpar" name="btnPesquisar" onclick="limpar();" value="Limpar" class="infraButton"><span class="infraTeclaAtalho">L</span>impar</button>';

  $objRelatorioFederacaoDTO = new RelatorioFederacaoDTO();

  $strIdOrgaoEscolha = $_POST['selOrgao'];
  if ($strIdOrgaoEscolha!=''){
    $objRelatorioFederacaoDTO->setStrIdOrgaoFederacao($strIdOrgaoEscolha);
  }

  $strProtocolo = $_POST['txtProtocolo'];

  if(!InfraString::isBolVazia($strProtocolo)) {
    $objRelatorioFederacaoDTO->setStrProtocoloFormatado($strProtocolo);
  }

  $strStaSentido = $_POST['selStaSentido'];
  if ($strStaSentido!=''){
    $objRelatorioFederacaoDTO->setStrStaSentido($strStaSentido);
  }

  $dtaInicio = $_POST['txtDataInicio'];
  $dtaFim	= $_POST['txtDataFim'];

  $objRelatorioFederacaoDTO->setDtaInicio($dtaInicio);

  if (!InfraString::isBolVazia($dtaFim)) {
    $objRelatorioFederacaoDTO->setDtaFim($dtaFim);
  }else{
    $objRelatorioFederacaoDTO->setDtaFim($dtaInicio);
  }


  $arrObjRelatorioFederacaoDTO = array();

  if (isset($_POST['sbmPesquisar']) || $_GET['acao']==$_GET['acao_origem']){

    PaginaSEI::getInstance()->prepararPaginacao($objRelatorioFederacaoDTO, 100);

    try {
      $objAcessoFederacaoRN = new AcessoFederacaoRN();
      $arrObjRelatorioFederacaoDTO = $objAcessoFederacaoRN->gerarRelatorioProcessos($objRelatorioFederacaoDTO);
    }catch(Exception $e){
      PaginaSEI::getInstance()->processarExcecao($e);
    }

    PaginaSEI::getInstance()->processarPaginacao($objRelatorioFederacaoDTO);
  }

  $numRegistros = count($arrObjRelatorioFederacaoDTO);

  if ($numRegistros > 0){

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Processos.';
    $strCaptionTabela = 'Processos';

    $strResultado .= '<table id="tblProcessos" width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n"; //90
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%" style="display:none;" rowspan="2">'.PaginaSEI::getInstance()->getThCheck('','Infra','style="display:none;"').'</th>'."\n";
    $strResultado .= '<th class="infraTh" rowspan="2">Processo<br>Origem</th>'."\n";
    $strResultado .= '<th class="infraTh" rowspan="2">Processo<br>Local</th>'."\n";
    $strResultado .= '<th class="infraTh" rowspan="2">Tipo<br>Local</th>'."\n";
    $strResultado .= '<th class="infraTh" colspan="7">Tramitações Diretas</th>'."\n";
    $strResultado .= '<th class="infraTh" rowspan="2">Órgãos<br>Participantes</th>'."\n";;
    $strResultado .= '</tr>';

    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" >Sentido</th>'."\n";
    $strResultado .= '<th class="infraTh" >Data/Hora</th>'."\n";
    $strResultado .= '<th class="infraTh" >Órgão Local</th>'."\n";
    $strResultado .= '<th class="infraTh" >Unidade Local</th>'."\n";
    $strResultado .= '<th class="infraTh" >Órgão Remoto</th>'."\n";
    $strResultado .= '<th class="infraTh" >Unidade Remota</th>'."\n";
    $strResultado .= '<th class="infraTh" >Instalação</th>'."\n";
    $strResultado .= '</tr>';

    $strCssTr='';

    $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
    $strIdInstalacaoFederacalLocal = $objInstalacaoFederacaoRN->obterIdInstalacaoFederacaoLocal();

    foreach($arrObjRelatorioFederacaoDTO as $objRelatorioFederacaoDTO){

      $objProtocoloFederacaoDTO = $objRelatorioFederacaoDTO->getObjProtocoloFederacaoDTO();
      $objProtocoloDTO = $objRelatorioFederacaoDTO->getObjProtocoloDTO();
      $arrObjAcessoFederacaoDTO = $objRelatorioFederacaoDTO->getArrObjAcessoFederacaoDTO();
      $arrObjOrgaoFederacaoDTO = $objRelatorioFederacaoDTO->getArrObjOrgaoFederacaoDTO();

      $dblIdProcedimento = $objProtocoloDTO->getDblIdProtocolo();

      $arrTramitacoes = array();
      foreach($arrObjAcessoFederacaoDTO as $objAcessoFederacaoDTO){
        if ($objAcessoFederacaoDTO->getStrIdInstalacaoFederacaoRem()==$strIdInstalacaoFederacalLocal || $objAcessoFederacaoDTO->getStrIdInstalacaoFederacaoDest()==$strIdInstalacaoFederacalLocal){
          $arrTramitacoes[] = $objAcessoFederacaoDTO;
        }
      }
      $numTramitacoes = count($arrTramitacoes);

      $strCssTr = ($strCssTr == 'class="infraTrClara"') ? 'class="infraTrEscura"' : 'class="infraTrClara"';
      $strResultado .= '<tr name="trProcesso'.$dblIdProcedimento.'" '.$strCssTr.'>'."\n";

      $strResultado .= "\n".'<td style="display:none;" rowspan="'.$numTramitacoes.'">';
      $strResultado .= PaginaSEI::getInstance()->getTrCheck($n++,$objProtocoloFederacaoDTO->getStrIdProtocoloFederacao(),$objProtocoloFederacaoDTO->getStrProtocoloFormatado(),'N','Infra','style="visibility:hidden;"');
      $strResultado .= '</td>';

      $strResultado .= "\n".'<td align="center" rowspan="'.$numTramitacoes.'">'.str_replace('.','.<wbr>',PaginaSEI::tratarHTML($objProtocoloFederacaoDTO->getStrProtocoloFormatado())).'</td>';


      $strResultado .= "\n".'<td align="center" rowspan="'.$numTramitacoes.'">';

      $strResultado .= '<a tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" alt="'.PaginaSEI::tratarHTML($objProtocoloDTO->getStrNomeTipoProcedimentoProcedimento()).'" title="'.PaginaSEI::tratarHTML($objProtocoloDTO->getStrNomeTipoProcedimentoProcedimento()).'" class="'.($objProtocoloDTO->getStrStaNivelAcessoGlobal()==ProtocoloRN::$NA_SIGILOSO?'protocoloSigiloso':'protocoloNormal').'" ';
      if ($objProtocoloDTO->getNumCodigoAcesso() > 0) {
        $strResultado .= 'style="text-decoration:underline" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$objProtocoloDTO->getDblIdProtocolo()).'" target="_blank" ';
      } else {
        $strResultado .= 'href="javascript:void(0)" onclick="alert(\'Sem acesso ao processo.\')"';
      }
      $strResultado .= '>'.str_replace('.','.<wbr>',PaginaSEI::tratarHTML($objProtocoloDTO->getStrProtocoloFormatado())).'</a>'."\n";

      $strResultado .= "\n".'</td>';

      $strResultado .= "\n".'<td align="center" rowspan="'.$numTramitacoes.'">'.PaginaSEI::tratarHTML($objProtocoloDTO->getStrNomeTipoProcedimentoProcedimento()).'</td>';

      if ($numTramitacoes){
        $objAcessoFederacaoDTO = $arrTramitacoes[0];
        if ($objAcessoFederacaoDTO->getStrIdInstalacaoFederacaoRem()==$strIdInstalacaoFederacalLocal){
          $strResultado .= "\n".'<td align="center" class="separador">Enviado</td>';
          $strResultado .= "\n".'<td align="center">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getDthLiberacao()).'</td>';
          $strResultado .= "\n".'<td align="center"><a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoOrgaoFederacaoRem()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoOrgaoFederacaoRem()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaOrgaoFederacaoRem()).'</a></td>';
          $strResultado .= "\n".'<td align="center"><a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoUnidadeFederacaoRem()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoUnidadeFederacaoRem()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaUnidadeFederacaoRem()).'</a></td>';
          $strResultado .= "\n".'<td align="center"><a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoOrgaoFederacaoDest()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoOrgaoFederacaoDest()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaOrgaoFederacaoDest()).'</a></td>';
          $strResultado .= "\n".'<td align="center"><a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoUnidadeFederacaoDest()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoUnidadeFederacaoDest()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaUnidadeFederacaoDest()).'</a></td>';
          $strResultado .= "\n".'<td align="center"><a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoInstalacaoFederacaoDest()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoInstalacaoFederacaoDest()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaInstalacaoFederacaoDest()).'</a></td>';
        }else{
          $strResultado .= "\n".'<td align="center" class="separador">Recebido</td>';
          $strResultado .= "\n".'<td align="center">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getDthLiberacao()).'</td>';
          $strResultado .= "\n".'<td align="center"><a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoOrgaoFederacaoDest()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoOrgaoFederacaoDest()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaOrgaoFederacaoDest()).'</a></td>';
          $strResultado .= "\n".'<td align="center"><a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoUnidadeFederacaoDest()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoUnidadeFederacaoDest()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaUnidadeFederacaoDest()).'</a></td>';
          $strResultado .= "\n".'<td align="center"><a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoOrgaoFederacaoRem()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoOrgaoFederacaoRem()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaOrgaoFederacaoRem()).'</a></td>';
          $strResultado .= "\n".'<td align="center"><a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoUnidadeFederacaoRem()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoUnidadeFederacaoRem()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaUnidadeFederacaoRem()).'</a></td>';
          $strResultado .= "\n".'<td align="center"><a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoInstalacaoFederacaoRem()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoInstalacaoFederacaoRem()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaInstalacaoFederacaoRem()).'</a></td>';
        }
      }else{
        $strResultado .= "\n".'<td colspan="7" class="separador">&nbsp</td>';
      }

      $numOrgaos = count($arrObjOrgaoFederacaoDTO);
      $strResultado .= "\n".'<td align="center" rowspan="'.$numTramitacoes.'" class="separador">';
      for($i=0;$i<$numOrgaos;$i++){

        $objOrgaoFederacaoDTO = $arrObjOrgaoFederacaoDTO[$i];

        if ($i){
          $strResultado .= '<br>';
        }

        $strOrigem = '';
        if ($objOrgaoFederacaoDTO->getStrSinOrigem()=='S'){
          $strOrigem = '<img src="'.Icone::FEDERACAO_ORIGEM.'" alt="Órgão origem do processo no SEI Federação" title="Órgão origem do processo no SEI Federação" style="vertical-align:bottom"/>';
        }

        $strResultado .= "\n".'<div style="display:inline">'.$strOrigem.'<a alt="'.PaginaSEI::tratarHTML($objOrgaoFederacaoDTO->getStrDescricao()).'" title="'.PaginaSEI::tratarHTML($objOrgaoFederacaoDTO->getStrDescricao()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objOrgaoFederacaoDTO->getStrSigla()).'</a></div>';
      }
      $strResultado .= "\n".'</td>';

      $strResultado .= "\n".'</tr>'."\n";

      for($i=1;$i<$numTramitacoes;$i++){
        $objAcessoFederacaoDTO = $arrTramitacoes[$i];

        $strResultado .= '<tr name="trProcesso'.$dblIdProcedimento.'" '.$strCssTr.'>'."\n";

        if ($objAcessoFederacaoDTO->getStrIdInstalacaoFederacaoRem()==$strIdInstalacaoFederacalLocal){
          $strResultado .= "\n".'<td align="center" class="separador">Enviado</td>';
          $strResultado .= "\n".'<td align="center">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getDthLiberacao()).'</td>';
          $strResultado .= "\n".'<td align="center"><a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoOrgaoFederacaoRem()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoOrgaoFederacaoRem()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaOrgaoFederacaoRem()).'</a></td>';
          $strResultado .= "\n".'<td align="center"><a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoUnidadeFederacaoRem()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoUnidadeFederacaoRem()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaUnidadeFederacaoRem()).'</a></td>';
          $strResultado .= "\n".'<td align="center"><a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoOrgaoFederacaoDest()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoOrgaoFederacaoDest()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaOrgaoFederacaoDest()).'</a></td>';
          $strResultado .= "\n".'<td align="center"><a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoUnidadeFederacaoDest()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoUnidadeFederacaoDest()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaUnidadeFederacaoDest()).'</a></td>';
          $strResultado .= "\n".'<td align="center"><a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoInstalacaoFederacaoDest()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoInstalacaoFederacaoDest()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaInstalacaoFederacaoDest()).'</a></td>';
        }else{
          $strResultado .= "\n".'<td align="center" class="separador">Recebido</td>';
          $strResultado .= "\n".'<td align="center">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getDthLiberacao()).'</td>';
          $strResultado .= "\n".'<td align="center"><a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoOrgaoFederacaoDest()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoOrgaoFederacaoDest()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaOrgaoFederacaoDest()).'</a></td>';
          $strResultado .= "\n".'<td align="center"><a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoUnidadeFederacaoDest()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoUnidadeFederacaoDest()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaUnidadeFederacaoDest()).'</a></td>';
          $strResultado .= "\n".'<td align="center"><a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoOrgaoFederacaoRem()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoOrgaoFederacaoRem()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaOrgaoFederacaoRem()).'</a></td>';
          $strResultado .= "\n".'<td align="center"><a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoUnidadeFederacaoRem()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoUnidadeFederacaoRem()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaUnidadeFederacaoRem()).'</a></td>';
          $strResultado .= "\n".'<td align="center"><a alt="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoInstalacaoFederacaoRem()).'" title="'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrDescricaoInstalacaoFederacaoRem()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoFederacaoDTO->getStrSiglaInstalacaoFederacaoRem()).'</a></td>';
        }

        $strResultado .= '</tr>'."\n";
      }
    }

    $strResultado .= '</table>';
  }

  $strItensSelOrgaos = OrgaoFederacaoINT::montarSelectSigla('null','Todos',$strIdOrgaoEscolha);
  $strItensSelStaSentido = AcessoFederacaoINT::montarSelectStaSentido('null','Todos',$strStaSentido);

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

td.separador {border-left: 1px solid #dee2e6;}
td.aglutinador {border-top:0 !important;}

#divInfraAreaDados {max-width:800px;}

#lblOrgao {position:absolute;left:0%;top:0%;}
#selOrgao {position:absolute;left:0%;top:20%;width:30%;}

#lblProtocolo {position:absolute;left:0%;top:50%;}
#txtProtocolo {position:absolute;left:0%;top:70%;width:30%}

#lblStaSentido {position:absolute;left:35%;top:0%;}
#selStaSentido {position:absolute;left:35%;top:20%;width:15%;}

#lblDataInicio {position:absolute;left:35%;top:50%;}
#txtDataInicio {position:absolute;left:35%;top:70%;width:15%;}
#imgCalDataInicio {position:absolute;left:51%;top:72%;}

#lblDataFim 	{position:absolute;left:55%;top:72%;}
#txtDataFim 	{position:absolute;left:58%;top:70%;width:15%;}
#imgCalDataFim {position:absolute;left:74%;top:72%;}


<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
  //<script>

  function inicializar(){

    infraOcultarMenuSistemaEsquema();

    seiGerarEfeitoTabelasRowSpan('tblProcessos')
  }


  function onSubmitForm(){

    if (infraTrim(document.getElementById('txtDataInicio').value)!='') {
      if (!infraValidarData(document.getElementById('txtDataInicio'))) {
        return false;
      }
      if (infraTrim(document.getElementById('txtDataFim').value)!='') {
        if (!infraValidarData(document.getElementById('txtDataFim'))) {
          return false;
        }
      }

    }else if (infraTrim(document.getElementById('txtDataFim').value)!=''){
      alert('Data inicial deve ser informada.');
      return false;
    }

    infraExibirAviso();
    return true;
  }

  function limpar(){
    infraSelectSelecionarItem('selOrgao','null');
    infraSelectSelecionarItem('selStaSentido','null');
    document.getElementById('txtDataFim').value = '';
    document.getElementById('txtDataInicio').value = '';
    document.getElementById('txtProtocolo').value = '';
  }

  //</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmRelatorioSEIFederacao" onsubmit="return onSubmitForm()" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('10em');
?>

    <label id="lblOrgao" for="selOrgao" class="infraLabelOpcional">Órgão Tramitação Direta:</label>
    <select id="selOrgao" name="selOrgao" onchange="this.form.submit()" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?=$strItensSelOrgaos?>
    </select>

    <label id="lblProtocolo" for="txtProtocolo" accesskey="" class="infraLabelOpcional">Nº do Processo:</label>
    <input type="text" id="txtProtocolo" name="txtProtocolo" class="infraText" value="<?=PaginaSEI::tratarHTML($strProtocolo)?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <label id="lblStaSentido" for="selStaSentido" class="infraLabelOpcional">Sentido Tramitação:</label>
    <select id="selStaSentido" name="selStaSentido" onchange="this.form.submit()" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?=$strItensSelStaSentido?>
    </select>

    <label id="lblDataInicio" for="txtDataInicio" accesskey="P" class="infraLabelOpcional"><span class="infraTeclaAtalho">P</span>eríodo:</label>
    <input type="text" id="txtDataInicio" name="txtDataInicio" class="infraText" value="<?=$dtaInicio?>" onkeypress="return infraMascaraData(this, event)" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <img id="imgCalDataInicio" title="Selecionar Data Inicial" alt="Selecionar Data Inicial" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg" onclick="infraCalendario('txtDataInicio',this);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <label id="lblDataFim" for="txtDataFim" accesskey="" class="infraLabelOpcional">a</label>
    <input type="text" id="txtDataFim" name="txtDataFim" class="infraText" value="<?=$dtaFim?>" onkeypress="return infraMascaraData(this, event)" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <img id="imgCalDataFim" title="Selecionar Data Final" alt="Selecionar Data Final" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg" onclick="infraCalendario('txtDataFim',this);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

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