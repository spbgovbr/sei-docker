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

  PaginaSEI::getInstance()->prepararSelecao('cpad_versao_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('id_cpad'));

  switch($_GET['acao']){
    case 'cpad_versao_listar':
      $strTitulo = 'Versões da CPAD';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  //busca versoes do cpad
  $objCpadVersaoDTO = new CpadVersaoDTO();
  $objCpadVersaoDTO->retNumIdCpadVersao();
  $objCpadVersaoDTO->retStrSigla();
  $objCpadVersaoDTO->retStrDescricao();
  $objCpadVersaoDTO->retStrSiglaOrgao();
  $objCpadVersaoDTO->retStrDescricaoOrgao();
  $objCpadVersaoDTO->retDthVersao();
  $objCpadVersaoDTO->retStrSiglaUsuario();
  $objCpadVersaoDTO->retStrSiglaUnidade();
  $objCpadVersaoDTO->retStrDescricaoUnidade();
  $objCpadVersaoDTO->retStrNomeUsuario();
  $objCpadVersaoDTO->setNumIdCpad($_GET['id_cpad']);
  //retorna tanto ativos, quanto nao ativos
  $objCpadVersaoDTO->setBolExclusaoLogica(false);

  PaginaSEI::getInstance()->prepararOrdenacao($objCpadVersaoDTO, 'Versao', InfraDTO::$TIPO_ORDENACAO_DESC);

  $objCpadVersaoRN = new CpadVersaoRN();
  $arrObjCpadVersaoDTO = $objCpadVersaoRN->listar($objCpadVersaoDTO);

  $numRegistros = count($arrObjCpadVersaoDTO);

  if ($numRegistros > 0){

    $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('cpad_versao_consultar');

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Versões da CPAD.';
    $strCaptionTabela = 'Versões da CPAD';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%" style="display: none">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objCpadVersaoDTO,'Sigla','Sigla',$arrObjCpadVersaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objCpadVersaoDTO,'Descrição','Descricao',$arrObjCpadVersaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objCpadVersaoDTO,'Data','Versao',$arrObjCpadVersaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objCpadVersaoDTO,'Usuário','SiglaUsuario',$arrObjCpadVersaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objCpadVersaoDTO,'Unidade','SiglaUnidade',$arrObjCpadVersaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      $strResultado .= '<td valign="top" style="display: none">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjCpadVersaoDTO[$i]->getNumIdCpadVersao(),$arrObjCpadVersaoDTO[$i]->getNumIdCpadVersao()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjCpadVersaoDTO[$i]->getStrSigla()).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjCpadVersaoDTO[$i]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjCpadVersaoDTO[$i]->getDthVersao()).'</td>';
      $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjCpadVersaoDTO[$i]->getStrNomeUsuario()).'" title="'.PaginaSEI::tratarHTML($arrObjCpadVersaoDTO[$i]->getStrNomeUsuario()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjCpadVersaoDTO[$i]->getStrSiglaUsuario()).'</a></td>';
      //$strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML(UnidadeINT::formatarDescricaoUnidadeOrgao($arrObjCpadVersaoDTO[$i]->getStrDescricaoUnidade(),$arrObjCpadVersaoDTO[$i]->getStrDescricaoOrgao())).'" title="'.PaginaSEI::tratarHTML(UnidadeINT::formatarDescricaoUnidadeOrgao($arrObjCpadVersaoDTO[$i]->getStrDescricaoUnidade(),$arrObjCpadVersaoDTO[$i]->getStrDescricaoOrgao())).'" class="ancoraSigla">'.PaginaSEI::tratarHTML(UnidadeINT::formatarSiglaUnidadeOrgao($arrObjCpadVersaoDTO[$i]->getStrSiglaUnidade(),$arrObjCpadVersaoDTO[$i]->getStrSiglaOrgao())).'</a></td>';
      $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjCpadVersaoDTO[$i]->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($arrObjCpadVersaoDTO[$i]->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjCpadVersaoDTO[$i]->getStrSiglaUnidade()).'</a></td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjCpadVersaoDTO[$i]->getNumIdCpadVersao());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=cpad_composicao_listar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_cpad_versao='.$arrObjCpadVersaoDTO[$i]->getNumIdCpadVersao()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Versão da CPAD" alt="Consultar Versão da CPAD" class="infraImg" /></a>&nbsp;';
      }


      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_cpad'])).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

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
    document.getElementById('btnFechar').focus();
    infraEfeitoTabelas(true);
  }

  <?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmCpadVersaoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
    //PaginaSEI::getInstance()->montarAreaDebug();
    //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
