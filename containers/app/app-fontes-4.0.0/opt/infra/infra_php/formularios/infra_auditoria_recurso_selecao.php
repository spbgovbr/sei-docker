<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 26/06/2020 - criado por mga
 *
 */

try {

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoInfra::getInstance()->validarLink();

  PaginaInfra::getInstance()->prepararSelecao('infra_auditoria_recurso_selecionar');

  //SessaoInfra::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){

    case 'infra_auditoria_recurso_selecionar':
      $strTitulo = PaginaInfra::getInstance()->getTituloSelecao('Selecionar Recurso Auditado','Selecionar Recursos Auditados');
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';

  $objInfraAuditoriaDTO = new InfraAuditoriaDTO();
  $objInfraAuditoriaDTO->setDistinct(true);
  $objInfraAuditoriaDTO->retStrRecurso();
  $objInfraAuditoriaDTO->setOrdStrRecurso(InfraDTO::$TIPO_ORDENACAO_ASC);

  $objInfraAuditoriaRN = new InfraAuditoriaRN();
  $arrObjInfraAuditoriaDTO = $objInfraAuditoriaRN->listar($objInfraAuditoriaDTO);

  if (BancoAuditoria::getInstance() != null) {

    $objBancoInfra = BancoInfra::getInstance();

    BancoInfra::setObjInfraIBanco(BancoAuditoria::getInstance());

    try {
      $objInfraAuditoriaRN = new InfraAuditoriaRN();
      $arr = $objInfraAuditoriaRN->listar($objInfraAuditoriaDTO);
      $arrObjInfraAuditoriaDTO = array_merge($arrObjInfraAuditoriaDTO, $arr);
      $arrObjInfraAuditoriaDTO = InfraArray::distinctArrInfraDTO($arrObjInfraAuditoriaDTO,'Recurso');
      InfraArray::ordenarArrInfraDTO($arrObjInfraAuditoriaDTO,'Recurso',InfraArray::$TIPO_ORDENACAO_ASC);
    } catch (Exception $e) {
      BancoInfra::setObjInfraIBanco($objBancoInfra);
      throw $e;
    }

    BancoInfra::setObjInfraIBanco($objBancoInfra);
  }

  $numRegistros = count($arrObjInfraAuditoriaDTO);

  if ($numRegistros > 0){
    
    $strResultado = '';

    $strSumarioTabela = 'Tabela de Recursos Auditados.';
    $strCaptionTabela = 'Recursos Auditados';
    $strResultado .= '<table width="90%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaInfra::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">'.PaginaInfra::getInstance()->getThCheck().'</th>'."\n";
    $strResultado .= '<th class="infraTh">Nome</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;
      $strResultado .= '<td valign="top">'.PaginaInfra::getInstance()->getTrCheck($i,$arrObjInfraAuditoriaDTO[$i]->getStrRecurso(),$arrObjInfraAuditoriaDTO[$i]->getStrRecurso()).'</td>';
      $strResultado .= '<td>'.InfraPagina::tratarHTML($arrObjInfraAuditoriaDTO[$i]->getStrRecurso()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaInfra::getInstance()->getAcaoTransportarItem($i,$arrObjInfraAuditoriaDTO[$i]->getStrRecurso());

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  
  $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

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
  infraReceberSelecao();
  document.getElementById('btnFecharSelecao').focus();
  infraEfeitoTabelas();
}

<?
PaginaInfra::getInstance()->fecharJavaScript();
PaginaInfra::getInstance()->fecharHead();
PaginaInfra::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmAuditoriaRecursoSelecao" method="post" action="<?=SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    //PaginaInfra::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaInfra::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaInfra::getInstance()->montarAreaTabela($strResultado,$numRegistros);
    PaginaInfra::getInstance()->montarAreaDebug();
    PaginaInfra::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaInfra::getInstance()->fecharBody();
PaginaInfra::getInstance()->fecharHtml();
?>