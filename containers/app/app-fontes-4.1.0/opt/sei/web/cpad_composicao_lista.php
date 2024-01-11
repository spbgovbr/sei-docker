<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/11/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('id_cpad'));

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);


  switch($_GET['acao']){

    case 'cpad_composicao_listar':
      $strTitulo = 'Composição da CPAD';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }


  $arrComandos = array();

  //busca composicao da versao
  $objCpadComposicaoDTO = new CpadComposicaoDTO();
  $objCpadComposicaoDTO->retNumIdCpadComposicao();
  $objCpadComposicaoDTO->retStrSinPresidente();
  $objCpadComposicaoDTO->retStrSiglaUsuario();
  $objCpadComposicaoDTO->retStrNomeUsuario();
  $objCpadComposicaoDTO->retStrExpressaoCargo();
  $objCpadComposicaoDTO->setNumIdCpadVersao($_GET['id_cpad_versao']);

  PaginaSEI::getInstance()->prepararOrdenacao($objCpadComposicaoDTO, 'Ordem', InfraDTO::$TIPO_ORDENACAO_ASC);

  $objCpadComposicaoRN = new CpadComposicaoRN();
  $arrObjCpadComposicaoDTO = $objCpadComposicaoRN->listar($objCpadComposicaoDTO);

  $numRegistros = count($arrObjCpadComposicaoDTO);

  if ($numRegistros > 0){
    $strResultado = '';

    $strSumarioTabela = 'Tabela da Composição da CPAD.';
    $strCaptionTabela = 'Composição da CPAD';

    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_cpad_versao'])).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="30%">'.PaginaSEI::getInstance()->getThOrdenacao($objCpadComposicaoDTO,'Usuário','SiglaUsuario',$arrObjCpadComposicaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objCpadComposicaoDTO,'Cargo','ExpressaoCargo',$arrObjCpadComposicaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%" >Presidente</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjCpadComposicaoDTO[$i]->getStrNomeUsuario()).'" title="'.PaginaSEI::tratarHTML($arrObjCpadComposicaoDTO[$i]->getStrNomeUsuario()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjCpadComposicaoDTO[$i]->getStrSiglaUsuario()).'</a></td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjCpadComposicaoDTO[$i]->getStrExpressaoCargo()).'</td>';
      $strResultado .= '<td align="center">'.($arrObjCpadComposicaoDTO[$i]->getStrSinPresidente() == 'S' ? 'Sim' : 'Não') .'</td>';
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
<?if(0){?><style><?}?>

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){
  infraEfeitoTabelas(true);
}

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmCpadComposicaoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&id_cpad_versao='.$_GET['id_cpad_versao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
