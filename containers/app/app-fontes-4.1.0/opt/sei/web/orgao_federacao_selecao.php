<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/12/2019 - criado por mga
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

  PaginaSEI::getInstance()->prepararSelecao('orgao_federacao_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selInstalacaoFederacaoEnvio', 'txtPalavrasPesquisaFederacaoEnvio'));

  switch($_GET['acao']){

    case 'orgao_federacao_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Órgão do SEI Federação','Selecionar Órgãos do SEI Federação');
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  $arrComandos[] = '<button type="button" name="btnPesquisarOrgaos" id="btnPesquisarOrgaos" onclick="pesquisar()" value="Pesquisar" class="infraButton">Pesquisar</button>';
  $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';

  $numRegistros = 0;
  $strResultado = '';
  $strMsg = '';

  $objAcessoFederacaoDTO = new AcessoFederacaoDTO();

  $numIdInstalacaoFederacaoEnvio = PaginaSEI::getInstance()->recuperarCampo('selInstalacaoFederacaoEnvio');
  if ($numIdInstalacaoFederacaoEnvio != '') {
    $objAcessoFederacaoDTO->setStrIdInstalacaoFederacaoDest($numIdInstalacaoFederacaoEnvio);
  }

  $strPalavrasPesquisa = PaginaSEI::getInstance()->recuperarCampo('txtPalavrasPesquisaFederacaoEnvio');
  $objAcessoFederacaoDTO->setStrPalavrasPesquisa($strPalavrasPesquisa);

  $numInstalacoes = 0;

  if ($_GET['acao_origem']=='orgao_federacao_selecionar') {
    $objAcessoFederacaoRN = new AcessoFederacaoRN();
    $arrObjInstalacaoFederacaoDTO = $objAcessoFederacaoRN->pesquisarOrgaosUnidadesEnvio($objAcessoFederacaoDTO);
    $numInstalacoes = count($arrObjInstalacaoFederacaoDTO);
  }

  if ($numInstalacoes) {

    $arrObjInstalacaoFederacaoDTO = InfraArray::indexarArrInfraDTO($arrObjInstalacaoFederacaoDTO, 'IdInstalacaoFederacao');

    $arrInstalacoesFederacao = array();
    $arrOrgaosFederacao = array();
    $arrObjOrgaoFederacaoDTO = array();
    foreach ($arrObjInstalacaoFederacaoDTO as $objInstalacaoFederacaoDTO) {
      if ($objInstalacaoFederacaoDTO->getObjInfraException() == null) {
        $arrInstalacoesFederacao[$objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao()] = array($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao(), $objInstalacaoFederacaoDTO->getStrSigla(), $objInstalacaoFederacaoDTO->getStrDescricao());
        foreach ($objInstalacaoFederacaoDTO->getArrObjOrgaoFederacaoDTO() as $objOrgaoFederacaoDTO) {
          if (!isset($arrOrgaosFederacao[$objOrgaoFederacaoDTO->getStrIdOrgaoFederacao()])) {
            $arrOrgaosFederacao[$objOrgaoFederacaoDTO->getStrIdOrgaoFederacao()] = array($objOrgaoFederacaoDTO->getStrIdInstalacaoFederacao(), $objOrgaoFederacaoDTO->getStrIdOrgaoFederacao(), $objOrgaoFederacaoDTO->getStrSigla(), $objOrgaoFederacaoDTO->getStrDescricao());
            $arrObjOrgaoFederacaoDTO[] = $objOrgaoFederacaoDTO;
          }
        }
      }
    }

    $strMsg = SeiINT::montarMensagemErroFederacao($arrObjInstalacaoFederacaoDTO, 'Não foi possível listar os órgãos da instalação', 'Não foi possível listar os órgãos das instalações');

    $numRegistros = count($arrObjOrgaoFederacaoDTO);

    if ($numRegistros > 0) {

      //InfraArray::ordenarArrInfraDTO($arrObjOrgaoFederacaoDTO, 'Sigla', InfraArray::$TIPO_ORDENACAO_ASC);

      $strResultado = '';

      $strSumarioTabela = 'Tabela de Órgãos do SEI Federação.';
      $strCaptionTabela = 'Órgãos do SEI Federação';

      $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n"; //90
      $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros).'</caption>';
      $strResultado .= '<tr>';
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
      $strResultado .= '<th class="infraTh" width="20%">Sigla</th>'."\n";
      $strResultado .= '<th class="infraTh">Descrição</th>'."\n";
      $strResultado .= '<th class="infraTh" width="15%">Unidade Recebimento</th>'."\n";
      $strResultado .= '<th class="infraTh" width="15%">Instalação</th>'."\n";
      $strResultado .= '<th class="infraTh" width="8%">Ações</th>'."\n";
      $strResultado .= '</tr>'."\n";
      $strCssTr = '';

      $n = 0;

      foreach ($arrObjOrgaoFederacaoDTO as $objOrgaoFederacaoDTO) {

        $objInstalacaoFederacaoDTO = $arrObjInstalacaoFederacaoDTO[$objOrgaoFederacaoDTO->getStrIdInstalacaoFederacao()];
        $objUnidadeFederacaoDTO = $objOrgaoFederacaoDTO->getArrObjUnidadeFederacaoDTO()[0];

        $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
        $strResultado .= $strCssTr;

        $strResultado .= "\n".'<td valign="top">';
        $strResultado .= PaginaSEI::getInstance()->getTrCheck($n, $objOrgaoFederacaoDTO->getStrIdOrgaoFederacao(), OrgaoFederacaoINT::formatarIdentificacao($objOrgaoFederacaoDTO->getStrSigla(), $objOrgaoFederacaoDTO->getStrDescricao(), $objInstalacaoFederacaoDTO->getStrSigla()));
        $strResultado .= '</td>';

        $strResultado .= "\n".'<td align="center"  valign="top">'.PaginaSEI::tratarHTML($objOrgaoFederacaoDTO->getStrSigla()).'</td>';
        $strResultado .= "\n".'<td align="left"  valign="top">'.PaginaSEI::tratarHTML($objOrgaoFederacaoDTO->getStrDescricao()).'</td>';

        $strResultado .= "\n".'<td align="center"  valign="top">';
        $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($objUnidadeFederacaoDTO->getStrDescricao()).'" title="'.PaginaSEI::tratarHTML($objUnidadeFederacaoDTO->getStrDescricao()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objUnidadeFederacaoDTO->getStrSigla()).'</a>';
        $strResultado .= '</td>'."\n";

        $strResultado .= "\n".'<td align="center"  valign="top">';
        $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($objInstalacaoFederacaoDTO->getStrDescricao()).'" title="'.PaginaSEI::tratarHTML($objInstalacaoFederacaoDTO->getStrDescricao()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objInstalacaoFederacaoDTO->getStrSigla()).'</a>';
        $strResultado .= '</td>'."\n";

        $strResultado .= '<td align="center">';
        $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($n++,$objOrgaoFederacaoDTO->getStrIdOrgaoFederacao());
        $strResultado .= '</td>'."\n";

        $strResultado .= '</tr>'."\n";
      }
      $strResultado .= '</table>';
    }
  }

  $strItensselInstalacaoFederacaoEnvio = InstalacaoFederacaoINT::montarSelectSigla('', 'Todas', $numIdInstalacaoFederacaoEnvio);
  $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

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

#divInstalacao {display:none;}
#lblInstalacaoFederacaoEnvio {position:absolute;left:0%;top:5%;}
#selInstalacaoFederacaoEnvio {position:absolute;left:0%;top:43%;width:30%;}

#lblPalavrasPesquisaFederacaoEnvio {position:absolute;left:32%;top:5%;}
#txtPalavrasPesquisaFederacaoEnvio {position:absolute;left:32%;top:43%;width:60%;}

#divPesquisar {position:absolute;left:64%;top:43%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  infraReceberSelecao();
  document.getElementById('btnFecharSelecao').focus();
  infraEfeitoTabelas();
}

function pesquisar(){

  if (document.getElementById('selInstalacaoFederacaoEnvio').options.length==0){
    alert('Nenhuma instalação encontrada.');
    return false;
  }

  if (!infraSelectSelecionado(document.getElementById('selInstalacaoFederacaoEnvio'))){
    alert('Nenhum opção de instalação selecionada.');
    document.getElementById('selInstalacaoFederacaoEnvio').focus();
    return false;
  }

  infraExibirAviso();

  if (document.getElementById('hdnInfraItensSelecionados')!=null) {
    document.getElementById('hdnInfraItensSelecionados').value = '';
  }

  document.getElementById('frmOrgaoFederacaoSelecao').submit();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmOrgaoFederacaoSelecao" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&id_orgao='.$_GET['id_orgao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>

  <label id="lblInstalacaoFederacaoEnvio" for="selInstalacaoFederacaoEnvio" accesskey="" class="infraLabelOpcional">Instalação:</label>
  <select id="selInstalacaoFederacaoEnvio" name="selInstalacaoFederacaoEnvio" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
    <?=$strItensselInstalacaoFederacaoEnvio?>
  </select>

  <label id="lblPalavrasPesquisaFederacaoEnvio" for="txtPalavrasPesquisaFederacaoEnvio" accesskey="" class="infraLabelOpcional">Texto para pesquisa:</label>
  <input type="text" id="txtPalavrasPesquisaFederacaoEnvio" name="txtPalavrasPesquisaFederacaoEnvio" class="infraText" value="<?=PaginaSEI::tratarHTML($strPalavrasPesquisa)?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  ?>
  <?=$strMsg?>
  <br>
  <?

  if ($numRegistros) {
    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
  }

  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);

  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>