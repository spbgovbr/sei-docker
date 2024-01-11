<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/07/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.41.0
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

  PaginaSEI::getInstance()->prepararSelecao('orgao_historico_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('id_orgao'));


  switch($_GET['acao']){
    case 'orgao_historico_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjOrgaoHistoricoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objOrgaoHistoricoDTO = new OrgaoHistoricoDTO();
          $objOrgaoHistoricoDTO->setNumIdOrgaoHistorico($arrStrIds[$i]);
          $arrObjOrgaoHistoricoDTO[] = $objOrgaoHistoricoDTO;
        }
        $objOrgaoHistoricoRN = new OrgaoHistoricoRN();
        $objOrgaoHistoricoRN->excluir($arrObjOrgaoHistoricoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

/*
    case 'orgao_historico_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjOrgaoHistoricoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objOrgaoHistoricoDTO = new OrgaoHistoricoDTO();
          $objOrgaoHistoricoDTO->setNumIdOrgaoHistorico($arrStrIds[$i]);
          $arrObjOrgaoHistoricoDTO[] = $objOrgaoHistoricoDTO;
        }
        $objOrgaoHistoricoRN = new OrgaoHistoricoRN();
        $objOrgaoHistoricoRN->desativar($arrObjOrgaoHistoricoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'orgao_historico_reativar':
      $strTitulo = 'Reativar Históricos dos Órgãos';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjOrgaoHistoricoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objOrgaoHistoricoDTO = new OrgaoHistoricoDTO();
            $objOrgaoHistoricoDTO->setNumIdOrgaoHistorico($arrStrIds[$i]);
            $arrObjOrgaoHistoricoDTO[] = $objOrgaoHistoricoDTO;
          }
          $objOrgaoHistoricoRN = new OrgaoHistoricoRN();
          $objOrgaoHistoricoRN->reativar($arrObjOrgaoHistoricoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      }
      break;

 */
    case 'orgao_historico_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Histórico do Órgão','Selecionar Históricos do Órgão');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='orgao_historico_cadastrar'){
        if (isset($_GET['id_orgao_historico'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_orgao_historico']);
        }
      }
      break;

    case 'orgao_historico_listar':
      $strTitulo = 'Histórico do Órgão';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'orgao_historico_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'orgao_historico_listar' || $_GET['acao'] == 'orgao_historico_selecionar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('orgao_historico_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=orgao_historico_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  /* } */

  $objOrgaoHistoricoDTO = new OrgaoHistoricoDTO();
  $objOrgaoHistoricoDTO->retNumIdOrgaoHistorico();
  $objOrgaoHistoricoDTO->retStrSigla();
  $objOrgaoHistoricoDTO->retStrDescricao();
  $objOrgaoHistoricoDTO->retDtaInicio();
  $objOrgaoHistoricoDTO->retDtaFim();
  $numIdOrgao = PaginaSEI::getInstance()->recuperarCampo('selOrgao');
  if ($numIdOrgao!==''){
    $objOrgaoHistoricoDTO->setNumIdOrgao($numIdOrgao);
  }

/*
  if ($_GET['acao'] == 'orgao_historico_reativar'){
    //Lista somente inativos
    $objOrgaoHistoricoDTO->setBolExclusaoLogica(false);
    $objOrgaoHistoricoDTO->setStrSinAtivo('N');
  }
 */

  $objOrgaoHistoricoDTO->setOrdDtaInicio(InfraDTO::$TIPO_ORDENACAO_ASC);

  //PaginaSEI::getInstance()->prepararPaginacao($objOrgaoHistoricoDTO);

  $objOrgaoHistoricoRN = new OrgaoHistoricoRN();
  $objOrgaoHistoricoDTO->setNumIdOrgao($_GET['id_orgao']);
  $arrObjOrgaoHistoricoDTO = $objOrgaoHistoricoRN->listar($objOrgaoHistoricoDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objOrgaoHistoricoDTO);
  $numRegistros = count($arrObjOrgaoHistoricoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='orgao_historico_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('orgao_historico_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('orgao_historico_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='orgao_historico_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('orgao_historico_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('orgao_historico_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('orgao_historico_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('orgao_historico_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('orgao_historico_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('orgao_historico_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('orgao_historico_desativar');
    }

    /*
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=orgao_historico_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=orgao_historico_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
     */

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=orgao_historico_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    /* if ($_GET['acao']!='orgao_historico_reativar'){ */
      $strSumarioTabela = 'Tabela de Históricos do Órgão.';
      $strCaptionTabela = 'Históricos do Órgão';
    /* }else{
      $strSumarioTabela = 'Tabela de Históricos dos Órgãos Inativos.';
      $strCaptionTabela = 'Históricos dos Órgãos Inativos';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="12%">Data Inicial</th>'."\n";
    $strResultado .= '<th class="infraTh" width="12%">Data Final</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Sigla</th>'."\n";
    $strResultado .= '<th class="infraTh">Descrição</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck) {
        if ($arrObjOrgaoHistoricoDTO[$i]->getDtaFim() != null) {
          $strResultado .= '<td align="center" valign="top">'.PaginaSEI::getInstance()->getTrCheck($i, $arrObjOrgaoHistoricoDTO[$i]->getNumIdOrgaoHistorico(), $arrObjOrgaoHistoricoDTO[$i]->getStrSigla()).'</td>';
        } else {
          $strResultado .= '<td >&nbsp;</td>';
        }
      }

      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjOrgaoHistoricoDTO[$i]->getDtaInicio()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjOrgaoHistoricoDTO[$i]->getDtaFim()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjOrgaoHistoricoDTO[$i]->getStrSigla()).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjOrgaoHistoricoDTO[$i]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjOrgaoHistoricoDTO[$i]->getNumIdOrgaoHistorico());

      if ($bolAcaoConsultar){
        //$strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=orgao_historico_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_orgao_historico='.$arrObjOrgaoHistoricoDTO[$i]->getNumIdOrgaoHistorico()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Histórico do Órgão" alt="Consultar Histórico do Órgão" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar  && $arrObjOrgaoHistoricoDTO[$i]->getDtaFim() != null){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=orgao_historico_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_orgao_historico='.$arrObjOrgaoHistoricoDTO[$i]->getNumIdOrgaoHistorico()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Histórico do Órgão" alt="Alterar Histórico do Órgão" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjOrgaoHistoricoDTO[$i]->getNumIdOrgaoHistorico();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjOrgaoHistoricoDTO[$i]->getStrSigla());
      }
/*
      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Histórico do Órgão" alt="Desativar Histórico do Órgão" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Histórico do Órgão" alt="Reativar Histórico do Órgão" class="infraImg" /></a>&nbsp;';
      }
 */

      if ($bolAcaoExcluir && $arrObjOrgaoHistoricoDTO[$i]->getDtaFim() != null){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Histórico do Órgão" alt="Excluir Histórico do Órgão" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'orgao_historico_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=orgao_listar&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_orgao'])).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
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
#lblOrgao {position:absolute;left:0%;top:0%;width:25%;}
#selOrgao {position:absolute;left:0%;top:40%;width:25%;}

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='orgao_historico_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas(true);
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Histórico do Órgão \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmOrgaoHistoricoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmOrgaoHistoricoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Histórico do Órgão selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Históricos dos Órgãos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmOrgaoHistoricoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmOrgaoHistoricoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Histórico do Órgão \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmOrgaoHistoricoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmOrgaoHistoricoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Histórico do Órgão selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Históricos dos Órgãos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmOrgaoHistoricoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmOrgaoHistoricoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Histórico do Órgão \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmOrgaoHistoricoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmOrgaoHistoricoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Histórico do Órgão selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Históricos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmOrgaoHistoricoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmOrgaoHistoricoLista').submit();
  }
}
<? } ?>

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmOrgaoHistoricoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
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
