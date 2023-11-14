<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 25/06/2012 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
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

  PaginaSEI::getInstance()->prepararSelecao('tarja_assinatura_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'tarja_assinatura_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjTarjaAssinaturaDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objTarjaAssinaturaDTO = new TarjaAssinaturaDTO();
          $objTarjaAssinaturaDTO->setNumIdTarjaAssinatura($arrStrIds[$i]);
          $arrObjTarjaAssinaturaDTO[] = $objTarjaAssinaturaDTO;
        }
        $objTarjaAssinaturaRN = new TarjaAssinaturaRN();
        $objTarjaAssinaturaRN->excluir($arrObjTarjaAssinaturaDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'tarja_assinatura_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Tarja','Selecionar Tarjas');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='tarja_assinatura_cadastrar'){
        if (isset($_GET['id_tarja_assinatura'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_tarja_assinatura']);
        }
      }
      break;

    case 'tarja_assinatura_listar':
      $strTitulo = 'Tarjas';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'tarja_assinatura_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'tarja_assinatura_listar' || $_GET['acao'] == 'tarja_assinatura_selecionar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('tarja_assinatura_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNova" value="Nova" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tarja_assinatura_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ova</button>';
    }
  /* } */

  $objTarjaAssinaturaDTO = new TarjaAssinaturaDTO();
  $objTarjaAssinaturaDTO->retNumIdTarjaAssinatura();
  $objTarjaAssinaturaDTO->retStrStaTarjaAssinatura();
  //$objTarjaAssinaturaDTO->retStrTexto();
  //$objTarjaAssinaturaDTO->retStrLogo();

/* 
  if ($_GET['acao'] == 'tarja_assinatura_reativar'){
    //Lista somente inativos
    $objTarjaAssinaturaDTO->setBolExclusaoLogica(false);
    $objTarjaAssinaturaDTO->setStrSinAtivo('N');
  }
 */
  
  $objTarjaAssinaturaDTO->setOrdStrStaTarjaAssinatura(InfraDTO::$TIPO_ORDENACAO_ASC);
  
  //PaginaSEI::getInstance()->prepararPaginacao($objTarjaAssinaturaDTO);

  $objTarjaAssinaturaRN = new TarjaAssinaturaRN();
  $arrObjTarjaAssinaturaDTO = $objTarjaAssinaturaRN->listar($objTarjaAssinaturaDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objTarjaAssinaturaDTO);
  $numRegistros = count($arrObjTarjaAssinaturaDTO);

  if ($numRegistros > 0){

    $bolCheck = true;

    if ($_GET['acao']=='tarja_assinatura_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('tarja_assinatura_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('tarja_assinatura_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='tarja_assinatura_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('tarja_assinatura_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('tarja_assinatura_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('tarja_assinatura_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('tarja_assinatura_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('tarja_assinatura_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('tarja_assinatura_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('tarja_assinatura_desativar');
    }

    /* 
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tarja_assinatura_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tarja_assinatura_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
     */

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tarja_assinatura_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela')).'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    /* if ($_GET['acao']!='tarja_assinatura_reativar'){ */
      $strSumarioTabela = 'Tabela de Tarjas.';
      $strCaptionTabela = 'Tarjas';
    /* }else{
      $strSumarioTabela = 'Tabela de Tarjas Inativas.';
      $strCaptionTabela = 'Tarjas Inativas';
    } */

    $strResultado .= '<table width="70%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%" style="display:none;">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">Tipo</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objTarjaAssinaturaDTO,'Texto','Texto',$arrObjTarjaAssinaturaDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objTarjaAssinaturaDTO,'Logotipo','Logo',$arrObjTarjaAssinaturaDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';

    $objTarjaAssinaturaRN = new TarjaAssinaturaRN();
    $arrTipoDTOTarjaAssinatura = InfraArray::indexarArrInfraDTO($objTarjaAssinaturaRN->listarTiposTarjaAssinatura(),'StaTipo');

    for($i = 0;$i < $numRegistros; $i++){

      if (!isset($arrTipoDTOTarjaAssinatura[$arrObjTarjaAssinaturaDTO[$i]->getStrStaTarjaAssinatura()])){
        throw new InfraException('Tipo da tarja de assinatura ['.$arrObjTarjaAssinaturaDTO[$i]->getStrStaTarjaAssinatura().'] não encontrado.');
      }

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top" style="display:none;">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjTarjaAssinaturaDTO[$i]->getNumIdTarjaAssinatura(),$arrObjTarjaAssinaturaDTO[$i]->getStrStaTarjaAssinatura()).'</td>';
      }

      $strResultado .= '<td align="left">'.$arrTipoDTOTarjaAssinatura[$arrObjTarjaAssinaturaDTO[$i]->getStrStaTarjaAssinatura()]->getStrDescricao().'</td>';
      
      //$strResultado .= '<td>'.$arrObjTarjaAssinaturaDTO[$i]->getStrTexto().'</td>';
      //$strResultado .= '<td>'.$arrObjTarjaAssinaturaDTO[$i]->getStrLogo().'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjTarjaAssinaturaDTO[$i]->getNumIdTarjaAssinatura());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tarja_assinatura_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_tarja_assinatura='.$arrObjTarjaAssinaturaDTO[$i]->getNumIdTarjaAssinatura()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Tarja" alt="Consultar Tarja" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tarja_assinatura_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_tarja_assinatura='.$arrObjTarjaAssinaturaDTO[$i]->getNumIdTarjaAssinatura()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Tarja" alt="Alterar Tarja" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjTarjaAssinaturaDTO[$i]->getNumIdTarjaAssinatura();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjTarjaAssinaturaDTO[$i]->getStrStaTarjaAssinatura());
      }

     if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Tarja" alt="Excluir Tarja" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'tarja_assinatura_selecionar'){
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
  if ('<?=$_GET['acao']?>'=='tarja_assinatura_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação da Tarja \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmTarjaAssinaturaLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmTarjaAssinaturaLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Tarja selecionada.');
    return;
  }
  if (confirm("Confirma desativação das Tarjas selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmTarjaAssinaturaLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmTarjaAssinaturaLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação da Tarja \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmTarjaAssinaturaLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmTarjaAssinaturaLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Tarja selecionada.');
    return;
  }
  if (confirm("Confirma reativação das Tarjas selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmTarjaAssinaturaLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmTarjaAssinaturaLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão da Tarja \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmTarjaAssinaturaLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmTarjaAssinaturaLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Tarja selecionada.');
    return;
  }
  if (confirm("Confirma exclusão das Tarjas selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmTarjaAssinaturaLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmTarjaAssinaturaLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmTarjaAssinaturaLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>