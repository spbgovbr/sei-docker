<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 23/07/2018 - criado por mga
 *
 */

try {
  require_once dirname(__FILE__).'/Sip.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSip::getInstance()->validarLink();

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){

    case 'modulo_listar':
      $strTitulo = 'Módulos';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  $numRegistros = count($SIP_MODULOS);

  if ($numRegistros > 0){

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Módulos Carregados.';
    $strCaptionTabela = 'Módulos Carregados';

    $strResultado .= '<table width="90%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSip::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh">Nome</th>'."\n";
    $strResultado .= '<th class="infraTh">Versão</th>'."\n";
    $strResultado .= '<th class="infraTh">Instituição</th>'."\n";

    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    foreach($SIP_MODULOS as $objModulo){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      $strResultado .= '<td align="center">'.PaginaSip::tratarHTML($objModulo->getNome()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSip::tratarHTML($objModulo->getVersao()).'</td>';
      $strResultado .= '<td align="left">'.PaginaSip::tratarHTML($objModulo->getInstituicao()).'</td>';
      $strResultado .= '</tr>'."\n";
    }
    $strResultado .= '</table>';
  }

}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

function inicializar(){
  infraEfeitoTabelas();
}

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmModulosLista" method="post" action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSip::getInstance()->montarAreaTabela($strResultado,$numRegistros,true);
    //PaginaSip::getInstance()->montarAreaDebug();
    PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>