<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 03/08/2010 - criado por mga
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
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

  PaginaSEI::getInstance()->prepararSelecao('feed_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'feed_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjFeedDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objFeedDTO = new FeedDTO();
          $objFeedDTO->setNumIdFeed($arrStrIds[$i]);
          $arrObjFeedDTO[] = $objFeedDTO;
        }
        $objFeedRN = new FeedRN();
        $objFeedRN->excluir($arrObjFeedDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

/* 
    case 'feed_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjFeedDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objFeedDTO = new FeedDTO();
          $objFeedDTO->setNumIdFeed($arrStrIds[$i]);
          $arrObjFeedDTO[] = $objFeedDTO;
        }
        $objFeedRN = new FeedRN();
        $objFeedRN->desativar($arrObjFeedDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'feed_reativar':
      $strTitulo = 'Reativar Feeds';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjFeedDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objFeedDTO = new FeedDTO();
            $objFeedDTO->setNumIdFeed($arrStrIds[$i]);
            $arrObjFeedDTO[] = $objFeedDTO;
          }
          $objFeedRN = new FeedRN();
          $objFeedRN->reativar($arrObjFeedDTO);
          PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;

 */
    case 'feed_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Feed','Selecionar Feeds');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='feed_cadastrar'){
        if (isset($_GET['id_feed'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_feed']);
        }
      }
      break;

    case 'feed_listar':
      $strTitulo = 'Feeds';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'feed_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'feed_listar' || $_GET['acao'] == 'feed_selecionar'){ */
    //$bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('feed_cadastrar');
    //if ($bolAcaoCadastrar){
    //  $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=feed_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    //}
  /* } */

  $objFeedDTO = new FeedDTO();
  $objFeedDTO->retNumIdFeed();
  $objFeedDTO->retStrConteudo();
/* 
  if ($_GET['acao'] == 'feed_reativar'){
    //Lista somente inativos
    $objFeedDTO->setBolExclusaoLogica(false);
    $objFeedDTO->setStrSinAtivo('N');
  }
 */
  $objFeedDTO->setOrdNumIdFeed(InfraDTO::$TIPO_ORDENACAO_DESC);
  
  PaginaSEI::getInstance()->prepararPaginacao($objFeedDTO);

  $objFeedRN = new FeedRN();
  $arrObjFeedDTO = $objFeedRN->listar($objFeedDTO);

  PaginaSEI::getInstance()->processarPaginacao($objFeedDTO);
  
  $numRegistros = count($arrObjFeedDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='feed_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('feed_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('feed_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='feed_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('feed_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('feed_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('feed_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('feed_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('feed_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('feed_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('feed_desativar');
    }

    /* 
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=feed_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=feed_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
     */

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=feed_excluir&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }

    $strResultado = '';

    /* if ($_GET['acao']!='feed_reativar'){ */
      $strSumarioTabela = 'Tabela de Feeds.';
      $strCaptionTabela = 'Feeds';
    /* }else{
      $strSumarioTabela = 'Tabela de Feeds Inativos.';
      $strCaptionTabela = 'Feeds Inativos';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">Conteúdo</th>'."\n";
    $strResultado .= '<th class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjFeedDTO[$i]->getNumIdFeed(),$arrObjFeedDTO[$i]->getNumIdFeed()).'</td>';
      }
      
      $strFeed = $arrObjFeedDTO[$i]->getStrConteudo();
      
	    while(($numPosContentIni = strpos($strFeed,'<content encoding="base64binary">'))!==false && ($numPosContentFim = strpos($strFeed,'</content>'))!==false){
	      $strFeed = substr($strFeed,0,$numPosContentIni+strlen('<content encoding="base64binary">')).'['.strlen($strFeed).' bytes]'.substr($strFeed,$numPosContentFim+strlen('</content>'));
	    }

      $strFeed = preg_replace("/(\S{80})/s", "$1&#8203;", $strFeed);
      $strFeed = PaginaSEI::tratarHTML($strFeed);
      $strFeed = str_replace("\n", '<br />',$strFeed);
      
      /*
      
      //$strFeed = str_replace('/',' / ',$strFeed);
      $strFeed = str_replace(',',', ',$strFeed);
      $strFeed = str_replace(';','; ',$strFeed);
      $strFeed = str_replace('\n', '',$strFeed);
      $strFeed = str_replace("\n", '<br />',$strFeed);
      $strFeed = str_replace('&lt;br /&gt;','<br />',$strFeed);
      */
      $strResultado .= '<td>'.$strFeed.'</td>';
      $strResultado .= '<td align="center" valign="top">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjFeedDTO[$i]->getNumIdFeed());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=feed_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_feed='.$arrObjFeedDTO[$i]->getNumIdFeed()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Feed" alt="Consultar Feed" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=feed_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_feed='.$arrObjFeedDTO[$i]->getNumIdFeed()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Feed" alt="Alterar Feed" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjFeedDTO[$i]->getNumIdFeed();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjFeedDTO[$i]->getNumIdFeed());
      }
/* 
      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Feed" alt="Desativar Feed" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Feed" alt="Reativar Feed" class="infraImg" /></a>&nbsp;';
      }
 */

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Feed" alt="Excluir Feed" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'feed_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
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

function inicializar(){
  if ('<?=$_GET['acao']?>'=='feed_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  //infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Feed \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmFeedLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmFeedLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Feed selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Feeds selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmFeedLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmFeedLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Feed \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmFeedLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmFeedLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Feed selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Feeds selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmFeedLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmFeedLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Feed \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmFeedLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmFeedLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Feed selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Feeds selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmFeedLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmFeedLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmFeedLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
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