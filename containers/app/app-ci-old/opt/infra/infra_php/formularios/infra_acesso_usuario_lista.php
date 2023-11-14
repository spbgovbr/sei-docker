<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 20/07/2016 - criado por mga
 *
 * Versão do Gerador de Código: 1.27.1
 *
 * Versão no CVS: $Id$
 */

try {
  //require_once 'Infra.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoInfra::getInstance()->validarLink();

  SessaoInfra::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){

    case 'infra_acesso_usuario_listar':
      $strTitulo = 'Últimos Acessos de '.SessaoInfra::getInstance()->getStrSiglaUsuario().' no Sistema '.SessaoInfra::getInstance()->getStrSiglaSistema();
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  $objInfraSip = new InfraSip(SessaoInfra::getInstance());
  $arrAcessos = $objInfraSip->listarAcessos(SessaoInfra::getInstance()->getNumIdSistema(),SessaoInfra::getInstance()->getNumIdUsuario());

  $numRegistros = count($arrAcessos);

  if ($numRegistros > 0){

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Acessos.';
    $strCaptionTabela = 'acessos';

    $strResultado .= '<table width="80%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaInfra::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="50%">Data/Hora</th>'."\n";
    $strResultado .= '<th class="infraTh" width="25%">Navegador</th>'."\n";
    $strResultado .= '<th class="infraTh" width="25%">IP</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';

    foreach($arrAcessos as $acesso){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;
      $strResultado .= '<td align="center">'.PaginaInfra::getInstance()->tratarHTML(InfraData::formatarExtenso4($acesso[InfraSip::$WS_ACESSO_DATA_HORA])).'</td>';
      $strResultado .= '<td align="center">'.PaginaInfra::getInstance()->tratarHTML($acesso[InfraSip::$WS_ACESSO_NAVEGADOR]).'</td>';
      $strResultado .= '<td align="center">'.PaginaInfra::getInstance()->tratarHTML($acesso[InfraSip::$WS_ACESSO_IP]).'</td>';

      $strResultado .= '</tr>'."\n";
    }
    $strResultado .= '</table>';
  }

  //$arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

}catch(Exception $e){
  PaginaInfra::getInstance()->processarExcecao($e);
}

PaginaInfra::getInstance()->montarDocType();
PaginaInfra::getInstance()->abrirHtml();
PaginaInfra::getInstance()->abrirHead();
PaginaInfra::getInstance()->montarMeta();
PaginaInfra::getInstance()->montarTitle(PaginaInfra::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaInfra::getInstance()->montarStyle();
PaginaInfra::getInstance()->abrirStyle();
?>

<?
PaginaInfra::getInstance()->fecharStyle();
PaginaInfra::getInstance()->montarJavaScript();
PaginaInfra::getInstance()->abrirJavaScript();
?>

function inicializar(){
  //document.getElementById('btnFechar').focus();
  infraEfeitoTabelas();
}

function validarForm(){
  return true;
}
<?
PaginaInfra::getInstance()->fecharJavaScript();
PaginaInfra::getInstance()->fecharHead();
PaginaInfra::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmInfraAcessoUsuarioLista" method="post"  onsubmit="return validarForm();"  action="<?=SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    PaginaInfra::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaInfra::getInstance()->montarAreaTabela($strResultado,$numRegistros);
    //PaginaInfra::getInstance()->montarAreaDebug();
    PaginaInfra::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaInfra::getInstance()->fecharBody();
PaginaInfra::getInstance()->fecharHtml();
?>