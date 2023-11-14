<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/01/2011 - criado por jonatas_db
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
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

  PaginaSEI::getInstance()->prepararSelecao('controle_interno_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'controle_interno_excluir':

      PaginaSEI::getInstance()->prepararBarraProgresso2($strTitulo,null,false);

      try{

        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjControleInternoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objControleInternoDTO = new ControleInternoDTO();
          $objControleInternoDTO->setNumIdControleInterno($arrStrIds[$i]);
          $arrObjControleInternoDTO[] = $objControleInternoDTO;
        }
        $objControleInternoRN = new ControleInternoRN();
        $objControleInternoRN->excluir($arrObjControleInternoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }

      PaginaSEI::getInstance()->finalizarBarraProgresso2(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=controle_interno_listar&acao_origem='.$_GET['acao']));
      die;

/* 
    case 'controle_interno_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjControleInternoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objControleInternoDTO = new ControleInternoDTO();
          $objControleInternoDTO->setNumIdControleInterno($arrStrIds[$i]);
          $arrObjControleInternoDTO[] = $objControleInternoDTO;
        }
        $objControleInternoRN = new ControleInternoRN();
        $objControleInternoRN->desativar($arrObjControleInternoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'controle_interno_reativar':
      $strTitulo = 'Reativar Controles Internos';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjControleInternoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objControleInternoDTO = new ControleInternoDTO();
            $objControleInternoDTO->setNumIdControleInterno($arrStrIds[$i]);
            $arrObjControleInternoDTO[] = $objControleInternoDTO;
          }
          $objControleInternoRN = new ControleInternoRN();
          $objControleInternoRN->reativar($arrObjControleInternoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;

 */
    case 'controle_interno_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Controle Interno','Selecionar Controles Internos');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='controle_interno_cadastrar'){
        if (isset($_GET['id_controle_interno'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_controle_interno']);
        }
      }
      break;

    case 'controle_interno_listar':
      $strTitulo = 'Critérios de Controle Interno';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'controle_interno_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'controle_interno_listar' || $_GET['acao'] == 'controle_interno_selecionar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('controle_interno_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=controle_interno_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  /* } */

  $objControleInternoDTO = new ControleInternoDTO();
  $objControleInternoDTO->retNumIdControleInterno();
  $objControleInternoDTO->retStrDescricao();
/* 
  if ($_GET['acao'] == 'controle_interno_reativar'){
    //Lista somente inativos
    $objControleInternoDTO->setBolExclusaoLogica(false);
    $objControleInternoDTO->setStrSinAtivo('N');
  }
 */
  PaginaSEI::getInstance()->prepararOrdenacao($objControleInternoDTO, 'Descricao', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objControleInternoDTO);

  $objControleInternoRN = new ControleInternoRN();
  $arrObjControleInternoDTO = $objControleInternoRN->listar($objControleInternoDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objControleInternoDTO);
  $numRegistros = count($arrObjControleInternoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='controle_interno_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('controle_interno_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('controle_interno_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='controle_interno_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('controle_interno_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('controle_interno_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('controle_interno_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('controle_interno_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('controle_interno_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('controle_interno_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('controle_interno_desativar');
    }

    /* 
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=controle_interno_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=controle_interno_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
     */

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=controle_interno_excluir&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }

    $strResultado = '';

    /* if ($_GET['acao']!='controle_interno_reativar'){ */
      $strSumarioTabela = 'Tabela de Critérios de Controle Interno.';
      $strCaptionTabela = 'Critérios de Controle Interno';
    /* }else{
      $strSumarioTabela = 'Tabela de Controles Internos Inativos.';
      $strCaptionTabela = 'Controles Internos Inativos';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objControleInternoDTO,'Descrição','Descricao',$arrObjControleInternoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjControleInternoDTO[$i]->getNumIdControleInterno(),$arrObjControleInternoDTO[$i]->getStrDescricao()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjControleInternoDTO[$i]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjControleInternoDTO[$i]->getNumIdControleInterno());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=controle_interno_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_controle_interno='.$arrObjControleInternoDTO[$i]->getNumIdControleInterno()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Controle Interno" alt="Consultar Controle Interno" class="infraImgNormal" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=controle_interno_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_controle_interno='.$arrObjControleInternoDTO[$i]->getNumIdControleInterno()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Controle Interno" alt="Alterar Controle Interno" class="infraImgNormal" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjControleInternoDTO[$i]->getNumIdControleInterno();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjControleInternoDTO[$i]->getStrDescricao());
      }
/* 
      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Controle Interno" alt="Desativar Controle Interno" class="infraImgNormal" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Controle Interno" alt="Reativar Controle Interno" class="infraImgNormal" /></a>&nbsp;';
      }
 */

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Controle Interno" alt="Excluir Controle Interno" class="infraImgNormal" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'controle_interno_selecionar'){
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
  if ('<?=$_GET['acao']?>'=='controle_interno_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoImagens();
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Critério de Controle Interno \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmControleInternoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmControleInternoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Critério de Controle Interno selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Critérios de Controle Interno selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmControleInternoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmControleInternoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Critério de Controle Interno \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmControleInternoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmControleInternoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Critério de Controle Interno selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Critérios de Controle Interno selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmControleInternoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmControleInternoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("ATENÇÃO: esta operação pode ser demorada.\n\nConfirma exclusão do Critério de Controle Interno \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    abrirJanelaExclusao();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Critério de Controle Interno selecionado.');
    return;
  }
  if (confirm("ATENÇÃO: esta operação pode ser demorada.\n\nConfirma exclusão dos Critérios de Controle Interno selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    abrirJanelaExclusao();
  }
}

function abrirJanelaExclusao(){

  infraAbrirJanelaModal('<?=$strLinkExcluir?>',600,200);

  var frm = document.getElementById('frmControleInternoLista');
  var actionAnterior = frm.action;
  frm.target = 'modal-frame';
  frm.action='<?=$strLinkExcluir?>';
  frm.submit();
  frm.action = actionAnterior;
}

<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmControleInternoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  //PaginaSEI::getInstance()->abrirAreaDados('5em');
  //PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>