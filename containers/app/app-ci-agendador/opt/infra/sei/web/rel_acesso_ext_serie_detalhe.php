<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 03/08/2016 - criado por mga
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

  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch ($_GET['acao']) {

    case 'rel_acesso_ext_serie_detalhar':
      $strTitulo = 'Tipos de Documentos Liberados para Inclusão';
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();

  $arrIdSerie = null;
  $objRelAcessoExtSerieDTO = new RelAcessoExtSerieDTO();
  $objRelAcessoExtSerieDTO->retNumIdAcessoExterno();
  $objRelAcessoExtSerieDTO->retNumIdSerie();
  $objRelAcessoExtSerieDTO->setNumIdAcessoExterno($_GET['id_acesso_externo']);

  $objRelAcessoExtSerieRN = new RelAcessoExtSerieRN();
  $arrIdSerie = InfraArray::converterArrInfraDTO($objRelAcessoExtSerieRN->listar($objRelAcessoExtSerieDTO),'IdSerie');

  $objSerieDTO = new SerieDTO();
  $objSerieDTO->retNumIdSerie();
  $objSerieDTO->retStrNome();
  //$objSerieDTO->retStrDescricao();
  $objSerieDTO->retStrNomeGrupoSerie();
  $objSerieDTO->retStrSinUsuarioExterno();


  if (count($arrIdSerie)==0){
    $objSerieDTO->setStrSinUsuarioExterno("S");
  }else{
    $objSerieDTO->setNumIdSerie($arrIdSerie, InfraDTO::$OPER_IN);
  }

  PaginaSEI::getInstance()->prepararOrdenacao($objSerieDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  PaginaSEI::getInstance()->prepararPaginacao($objSerieDTO);

  $objSerieRN = new SerieRN();
  $arrObjSerieDTO = $objSerieRN->listarRN0646($objSerieDTO);

  PaginaSEI::getInstance()->processarPaginacao($objSerieDTO);

  $numRegistros = InfraArray::contar($arrObjSerieDTO);

  if ($numRegistros > 0){
    $strResultado = '';

    $strSumarioTabela = 'Tabela de Tipos de Documentos.';
    $strCaptionTabela = 'Tipos de Documentos';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    //$strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objSerieDTO,'ID','IdSerie',$arrObjSerieDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objSerieDTO,'Nome','Nome',$arrObjSerieDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objSerieDTO,'Grupo','NomeGrupoSerie',$arrObjSerieDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objSerieDTO,'Usuário Externo','SinUsuarioExterno',$arrObjSerieDTO).'</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      //$strResultado .= '<td align="center">'.$arrObjSerieDTO[$i]->getNumIdSerie().'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjSerieDTO[$i]->getStrNome()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjSerieDTO[$i]->getStrNomeGrupoSerie()).'</td>';
      //$strResultado .= '<td align="center" width="15%">'.($arrObjSerieDTO[$i]->getStrSinUsuarioExterno() == 'S' ? "Sim" : "").'</td>';

      $strResultado .= '</tr>'."\n";
    }
    $strResultado .= '</table>';
  }

  //$arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

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
  function inicializar(){
  infraEfeitoTabelas();
  }
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmGrupoSerieLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&id_acesso_externo='.$_GET['id_acesso_externo'])?>">
    <?
    //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->abrirAreaDados('5em');
    //PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
    //PaginaSEI::getInstance()->montarAreaDebug();
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>