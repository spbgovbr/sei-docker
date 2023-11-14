<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
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

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore', 'pagina_simples', 'id_protocolo'));

  if (isset($_GET['arvore'])) {
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  if (isset($_GET['pagina_simples'])) {
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  }

  $bolMultiplo = false;

  $arrComandos = array();

  switch ($_GET['acao']) {

    case 'protocolo_modelo_gerenciar':
      $strTitulo = 'Favoritos';

      if ($_GET['acao_origem'] == 'arvore_visualizar' && SessaoSEI::getInstance()->verificarPermissao('protocolo_modelo_cadastrar')) {

        $dto = new ProtocoloModeloDTO();
        $dto->retDblIdProtocoloModelo();
        $dto->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $dto->setDblIdProtocolo($_GET['id_protocolo']);

        $objProtocoloModeloRN = new ProtocoloModeloRN();
        if ($objProtocoloModeloRN->contar($dto) == 0) {
          header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=protocolo_modelo_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']));
          die;
        }
      }

      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $objProtocoloDTO = new ProtocoloDTO();
  $objProtocoloDTO->retStrProtocoloFormatado();
  $objProtocoloDTO->retStrStaProtocolo();
  $objProtocoloDTO->setDblIdProtocolo($_GET['id_protocolo']);

  $objProtocoloRN = new ProtocoloRN();
  $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

  if ($objProtocoloDTO == null) {
    throw new InfraException("Protocolo não encontrado.");
  }

  if ($objProtocoloDTO->getStrStaProtocolo()==ProtocoloRN::$TP_PROCEDIMENTO){
    $strTitulo .= ' do Processo ';
  }else{
    $strTitulo .= ' do Documento ';
  }

  $strTitulo .= $objProtocoloDTO->getStrProtocoloFormatado();


  $bolAcaoListar = SessaoSEI::getInstance()->verificarPermissao('protocolo_modelo_listar');
  $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('protocolo_modelo_cadastrar');
  if ($bolAcaoCadastrar) {
    $arrComandos[] = '<button type="button" id="btnAdicionar" value="Adicionar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=protocolo_modelo_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\'" class="infraButton">Adicionar</button>';
  }

  $objProtocoloModeloDTO = new ProtocoloModeloDTO();
  $objProtocoloModeloDTO->retDblIdProtocoloModelo();
  $objProtocoloModeloDTO->retStrSiglaUsuario();
  $objProtocoloModeloDTO->retStrNomeUsuario();
  $objProtocoloModeloDTO->retStrDescricao();
  $objProtocoloModeloDTO->retStrNomeGrupoProtocoloModelo();
  $objProtocoloModeloDTO->setDblIdProtocolo($_GET['id_protocolo']);
  $objProtocoloModeloDTO->retDthAlteracao();
  $objProtocoloModeloDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  $objProtocoloModeloDTO->setOrdDthAlteracao(InfraDTO::$TIPO_ORDENACAO_DESC);

  PaginaSEI::getInstance()->prepararPaginacao($objProtocoloModeloDTO);

  $objProtocoloModeloRN = new ProtocoloModeloRN();
  $arrObjProtocoloModeloDTO = $objProtocoloModeloRN->listar($objProtocoloModeloDTO);

  PaginaSEI::getInstance()->processarPaginacao($objProtocoloModeloDTO);
  $numRegistros = count($arrObjProtocoloModeloDTO);


  if ($numRegistros > 0) {

    $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('protocolo_modelo_alterar');
    $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('protocolo_modelo_excluir');

    if ($bolAcaoExcluir) {
      $arrComandos[] = '<button type="button" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton">Excluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=protocolo_modelo_excluir&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']);
    }

    $bolCheck = false;

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Favoritos.';
    $strCaptionTabela = 'Favoritos';

    $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck('', 'Infra', '', false) . '</th>' . "\n";
    //$strResultado .= '<th class="infraTh" width="6%">&nbsp;</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Grupo</th>' . "\n";
    $strResultado .= '<th class="infraTh">Descrição</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">Usuário</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">Data</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>' . "\n";
    $strResultado .= '</tr>' . "\n";
    $strCssTr = '';
    for ($i = 0; $i < $numRegistros; $i++) {

      $strCssTr = ($strCssTr == 'class="infraTrClara"') ? 'class="infraTrEscura"' : 'class="infraTrClara"';
      $strResultado .= '<tr ' . $strCssTr . '>';

      $strResultado .= '<td valign="top">' . PaginaSEI::getInstance()->getTrCheck($i, $arrObjProtocoloModeloDTO[$i]->getDblIdProtocoloModelo(), $arrObjProtocoloModeloDTO[$i]->getDthAlteracao(), 'N', 'Infra', '', false) . '</td>';

      $strResultado .= '<td align="center" valign="top">' . PaginaSEI::tratarHTML($arrObjProtocoloModeloDTO[$i]->getStrNomeGrupoProtocoloModelo()) . '</td>';

      $strResultado .= '<td valign="top">';
      $strDescricao = PaginaSEI::tratarHTML($arrObjProtocoloModeloDTO[$i]->getStrDescricao());
      $strDescricao = str_replace('&lt;b&gt;', '<b>', $strDescricao);
      $strDescricao = str_replace('&lt;/b&gt;', '</b>', $strDescricao);
      $strResultado .= $strDescricao;
      $strResultado .= '</td>';

      $strResultado .= '<td align="center" valign="top"><a alt="' . PaginaSEI::tratarHTML($arrObjProtocoloModeloDTO[$i]->getStrNomeUsuario()) . '" title="' . PaginaSEI::tratarHTML($arrObjProtocoloModeloDTO[$i]->getStrNomeUsuario()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($arrObjProtocoloModeloDTO[$i]->getStrSiglaUsuario()) . '</a></td>';
      $strResultado .= '<td align="center" valign="top">' . $arrObjProtocoloModeloDTO[$i]->getDthAlteracao() . '</td>';

      $strResultado .= '<td align="center" valign="top">';

      if ($bolAcaoAlterar) {
        $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=protocolo_modelo_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_protocolo_modelo=' . $arrObjProtocoloModeloDTO[$i]->getDblIdProtocoloModelo()) . '" ><img src="' . PaginaSEI::getInstance()->getIconeAlterar() . '" title="Alterar Favorito" alt="Alterar Favorito" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir) {
        $strId = $arrObjProtocoloModeloDTO[$i]->getDblIdProtocoloModelo();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjProtocoloModeloDTO[$i]->getStrDescricao());
      }

      if ($bolAcaoExcluir) {
        $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" ><img src="' . PaginaSEI::getInstance()->getIconeExcluir() . '" title="Excluir Favorito" alt="Excluir Favorito" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>' . "\n";
    }
    $strResultado .= '</table>';
  }

  if (PaginaSEI::getInstance()->getAcaoRetorno() == "procedimento_controlar") {
    $strAncora = $_GET['id_procedimento'];
    $arrComandos[] = '<button type="button" accesskey="V" name="btnVoltar" id="btnVoltar" value="Voltar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . PaginaSEI::getInstance()->montarAncora($strAncora) . '\';" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>';
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

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script type="javascript">

var objAjaxMarcadores = null;

function inicializar(){
  infraEfeitoTabelas();
}

function OnSubmitForm() {
  return true;
}

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Favorito?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGerenciarProtocoloModelo').action='<?=$strLinkExcluir?>';
    document.getElementById('frmGerenciarProtocoloModelo').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Favorito selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Favoritos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmGerenciarProtocoloModelo').action='<?=$strLinkExcluir?>';
    document.getElementById('frmGerenciarProtocoloModelo').submit();
  }
}
<? } ?>

<? if ($bolAcaoAlterar){ ?>
function acaoAlterar(link){
  infraAbrirJanela(link,'janelaAlterarProtocoloModelo',500,250,'location=0,status=1,resizable=1,scrollbars=1');
}
<? } ?>

//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmGerenciarProtocoloModelo" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    PaginaSEI::getInstance()->montarAreaDebug();
    //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>


  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>