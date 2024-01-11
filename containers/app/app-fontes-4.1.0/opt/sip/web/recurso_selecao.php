<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 07/05/2009 - criado por mga
 *
 * Versão do Gerador de Código: 1.26.0
 *
 * Versão no CVS: $Id$
 */

try {
  require_once dirname(__FILE__) . '/Sip.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSip::getInstance()->validarLink();

  PaginaSip::getInstance()->prepararSelecao('recurso_selecionar_auditoria');

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  PaginaSip::getInstance()->salvarCamposPost(array('txtNomeRecurso'));


  $strParametros = '';
  if (isset($_GET['id_orgao_sistema'])) {
    $strParametros .= '&id_orgao_sistema=' . $_GET['id_orgao_sistema'];
  }

  if (isset($_GET['id_sistema'])) {
    $strParametros .= '&id_sistema=' . $_GET['id_sistema'];
  }

  //print_r($_SESSION['INFRA_PAGINA']);die;

  switch ($_GET['acao']) {
    case 'recurso_selecionar_auditoria':
      $strTitulo = PaginaSip::getInstance()->getTituloSelecao('Selecionar Recurso', 'Selecionar Recursos');
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();

  $arrComandos[] = '<input type="submit" id="btnPesquisar" value="Pesquisar" class="infraButton" />';

  $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';

  $objRecursoDTO = new RecursoDTO(true);
  $objRecursoDTO->retNumIdSistema();
  $objRecursoDTO->retNumIdRecurso();
  $objRecursoDTO->retStrNome();
  //$objRecursoDTO->retStrDescricao();
  //$objRecursoDTO->retStrCaminho();
  $objRecursoDTO->retNumIdOrgaoSistema();


  $numIdOrgao = $_GET['id_orgao_sistema'];
  $numIdSistema = $_GET['id_sistema'];

  $objRecursoDTO->setNumIdOrgaoSistema($numIdOrgao);
  $objRecursoDTO->setNumIdSistema($numIdSistema);

  $strNomePesquisa = PaginaSip::getInstance()->recuperarCampo('txtNomeRecurso');
  if ($strNomePesquisa !== '') {
    $objRecursoDTO->setStrNome('%' . $strNomePesquisa . '%', InfraDTO::$OPER_LIKE);
  }

  PaginaSip::getInstance()->prepararOrdenacao($objRecursoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  PaginaSip::getInstance()->prepararPaginacao($objRecursoDTO, 100);

  $objRecursoRN = new RecursoRN();
  $arrObjRecursoDTO = $objRecursoRN->listar($objRecursoDTO);

  PaginaSip::getInstance()->processarPaginacao($objRecursoDTO);
  $numRegistros = count($arrObjRecursoDTO);

  if ($numRegistros > 0) {
    $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('recurso_consultar');
    $bolCheck = true;

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Recursos.';
    $strCaptionTabela = 'Recursos';

    $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSip::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">' . PaginaSip::getInstance()->getThCheck() . '</th>' . "\n";
    }
    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objRecursoDTO, 'Nome', 'Nome', $arrObjRecursoDTO) . '</th>' . "\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objRecursoDTO,'Descrição','Descricao',$arrObjRecursoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objRecursoDTO,'Caminho','Caminho',$arrObjRecursoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>' . "\n";
    $strResultado .= '</tr>' . "\n";
    $strCssTr = '';
    for ($i = 0; $i < $numRegistros; $i++) {
      $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck) {
        $strResultado .= '<td valign="center">' . PaginaSip::getInstance()->getTrCheck($i, $arrObjRecursoDTO[$i]->getNumIdRecurso(), $arrObjRecursoDTO[$i]->getStrNome()) . '</td>';
      }
      $strResultado .= '<td>' . PaginaSip::tratarHTML($arrObjRecursoDTO[$i]->getStrNome()) . '</td>';
      //$strResultado .= '<td>'.$arrObjRecursoDTO[$i]->getStrDescricao().'</td>';
      //$strResultado .= '<td>'.$arrObjRecursoDTO[$i]->getStrCaminho().'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSip::getInstance()->getAcaoTransportarItem($i, $arrObjRecursoDTO[$i]->getNumIdRecurso());

      if ($bolAcaoConsultar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=recurso_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_sistema=' . $arrObjRecursoDTO[$i]->getNumIdSistema() . '&id_recurso=' . $arrObjRecursoDTO[$i]->getNumIdRecurso()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeConsultar() . '" title="Consultar Recurso" alt="Consultar Recurso" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>' . "\n";
    }
    $strResultado .= '</table>';
  }

  $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
} catch (Exception $e) {
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>

  #lblNomeRecurso {left:0%;top:0%;width:70%;}
  #txtNomeRecurso {left:0%;top:40%;width:70%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

  function inicializar(){
  infraReceberSelecao();
  document.getElementById('btnFecharSelecao').focus();
  infraEfeitoTabelas();
  }

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmRecursoSelecao" method="post" action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] . $strParametros)?>">
    <?
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSip::getInstance()->abrirAreaDados($numHeightDados);
    ?>
    <label id="lblNomeRecurso" for="txtNomeRecurso" accesskey="N" class="infraLabelOpcional"><span
        class="infraTeclaAtalho">N</span>ome:</label>
    <input type="text" id="txtNomeRecurso" name="txtNomeRecurso" class="infraText"
           value="<?=PaginaSip::tratarHTML($strNomePesquisa)?>" maxlength="50"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    //PaginaSip::getInstance()->montarAreaDebug();
    PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>