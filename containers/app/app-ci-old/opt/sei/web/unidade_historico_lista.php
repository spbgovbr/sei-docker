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

  PaginaSEI::getInstance()->prepararSelecao('unidade_historico_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('id_unidade','arvore'));

  switch($_GET['acao']){
    case 'unidade_historico_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjUnidadeHistoricoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objUnidadeHistoricoDTO = new UnidadeHistoricoDTO();
          $objUnidadeHistoricoDTO->setNumIdUnidadeHistorico($arrStrIds[$i]);
          $arrObjUnidadeHistoricoDTO[] = $objUnidadeHistoricoDTO;
        }
        $objUnidadeHistoricoRN = new UnidadeHistoricoRN();
        $objUnidadeHistoricoRN->excluir($arrObjUnidadeHistoricoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

/* 
    case 'unidade_historico_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjUnidadeHistoricoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objUnidadeHistoricoDTO = new UnidadeHistoricoDTO();
          $objUnidadeHistoricoDTO->setNumIdUnidadeHistorico($arrStrIds[$i]);
          $arrObjUnidadeHistoricoDTO[] = $objUnidadeHistoricoDTO;
        }
        $objUnidadeHistoricoRN = new UnidadeHistoricoRN();
        $objUnidadeHistoricoRN->desativar($arrObjUnidadeHistoricoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'unidade_historico_reativar':
      $strTitulo = 'Reativar Históricos das Unidades';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjUnidadeHistoricoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objUnidadeHistoricoDTO = new UnidadeHistoricoDTO();
            $objUnidadeHistoricoDTO->setNumIdUnidadeHistorico($arrStrIds[$i]);
            $arrObjUnidadeHistoricoDTO[] = $objUnidadeHistoricoDTO;
          }
          $objUnidadeHistoricoRN = new UnidadeHistoricoRN();
          $objUnidadeHistoricoRN->reativar($arrObjUnidadeHistoricoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;

 */
    case 'unidade_historico_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Histórico da Unidade','Selecionar Históricos da Unidade');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='unidade_historico_cadastrar'){
        if (isset($_GET['id_unidade_historico'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_unidade_historico']);
        }
      }
      break;

    case 'unidade_historico_listar':
      $strTitulo = 'Histórico da Unidade';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'unidade_historico_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'unidade_historico_listar' || $_GET['acao'] == 'unidade_historico_selecionar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('unidade_historico_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_historico_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  /* } */

  $objUnidadeHistoricoDTO = new UnidadeHistoricoDTO();
  $objUnidadeHistoricoDTO->retNumIdUnidadeHistorico();
  $objUnidadeHistoricoDTO->retStrSigla();
  $objUnidadeHistoricoDTO->retStrDescricao();
  $objUnidadeHistoricoDTO->retDtaInicio();
  $objUnidadeHistoricoDTO->retDtaFim();
  $objUnidadeHistoricoDTO->retStrSiglaOrgao();
  $objUnidadeHistoricoDTO->retStrDescricaoOrgao();
  $objUnidadeHistoricoDTO->setNumIdUnidade($_GET['id_unidade']);

  /*
    if ($_GET['acao'] == 'unidade_historico_reativar'){
      //Lista somente inativos
      $objUnidadeHistoricoDTO->setBolExclusaoLogica(false);
      $objUnidadeHistoricoDTO->setStrSinAtivo('N');
    }
   */
  $objUnidadeHistoricoDTO->setOrdDtaInicio(InfraDTO::$TIPO_ORDENACAO_ASC);

  //PaginaSEI::getInstance()->prepararPaginacao($objUnidadeHistoricoDTO);

  $objUnidadeHistoricoRN = new UnidadeHistoricoRN();
  $arrObjUnidadeHistoricoDTO = $objUnidadeHistoricoRN->listar($objUnidadeHistoricoDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objUnidadeHistoricoDTO);
  $numRegistros = count($arrObjUnidadeHistoricoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='unidade_historico_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('unidade_historico_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('unidade_historico_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='unidade_historico_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('unidade_historico_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('unidade_historico_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('unidade_historico_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false;
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('unidade_historico_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('unidade_historico_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('unidade_historico_desativar');
    }

    /*
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_historico_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_historico_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
     */

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_historico_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    /* if ($_GET['acao']!='unidade_historico_reativar'){ */
      $strSumarioTabela = 'Tabela de Históricos da Unidade.';
      $strCaptionTabela = 'Históricos da Unidade';
    /* }else{
      $strSumarioTabela = 'Tabela de Históricos das Unidades Inativos.';
      $strCaptionTabela = 'Históricos das Unidades Inativos';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="10%" >Data Inicial</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%" >Data Final</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%" >Sigla</th>'."\n";
    $strResultado .= '<th class="infraTh">Descrição</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%" >Órgão</th>'."\n";

    $strResultado .= '<th class="infraTh" width="10%" >Ações</th>'."\n";

    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck) {
        if ($arrObjUnidadeHistoricoDTO[$i]->getDtaFim() != null) {
          $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i, $arrObjUnidadeHistoricoDTO[$i]->getNumIdUnidadeHistorico(), $arrObjUnidadeHistoricoDTO[$i]->getStrSigla()).'</td>';
        } else {
          $strResultado .= '<td>&nbsp;</td>';
        }
      }

      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjUnidadeHistoricoDTO[$i]->getDtaInicio()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjUnidadeHistoricoDTO[$i]->getDtaFim()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjUnidadeHistoricoDTO[$i]->getStrSigla()).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjUnidadeHistoricoDTO[$i]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjUnidadeHistoricoDTO[$i]->getStrDescricaoOrgao()).'" title="'.PaginaSEI::tratarHTML($arrObjUnidadeHistoricoDTO[$i]->getStrDescricaoOrgao()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjUnidadeHistoricoDTO[$i]->getStrSiglaOrgao()).'</a>';

      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $arrObjUnidadeHistoricoDTO[$i]->getNumIdUnidadeHistorico());

      if ($bolAcaoConsultar) {
        //$strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_historico_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_unidade_historico='.$arrObjUnidadeHistoricoDTO[$i]->getNumIdUnidadeHistorico()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Histórico da Unidade" alt="Consultar Histórico da Unidade" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar && $arrObjUnidadeHistoricoDTO[$i]->getDtaFim() != null) {
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_historico_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_unidade_historico='.$arrObjUnidadeHistoricoDTO[$i]->getNumIdUnidadeHistorico()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Histórico da Unidade" alt="Alterar Histórico da Unidade" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
        $strId = $arrObjUnidadeHistoricoDTO[$i]->getNumIdUnidadeHistorico();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjUnidadeHistoricoDTO[$i]->getStrSigla());
      }
      /*
            if ($bolAcaoDesativar){
              $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Histórico da Unidade" alt="Desativar Histórico da Unidade" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoReativar){
              $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Histórico da Unidade" alt="Reativar Histórico da Unidade" class="infraImg" /></a>&nbsp;';
            }
       */

      if ($bolAcaoExcluir && $arrObjUnidadeHistoricoDTO[$i]->getDtaFim() != null) {
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Histórico da Unidade" alt="Excluir Histórico da Unidade" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td>'."\n";

      $strResultado .= '</tr>'."\n";
    }
    $strResultado .= '</table>';
  }

  if ($_GET['acao'] == 'unidade_historico_selecionar') {
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  } else {
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).PaginaSEI::getInstance()->montarAncora($_GET['id_unidade']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
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
  if ('<?=$_GET['acao']?>'=='unidade_historico_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas(true);
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Histórico da Unidade \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmUnidadeHistoricoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmUnidadeHistoricoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Histórico da Unidade selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Históricos das Unidades selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmUnidadeHistoricoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmUnidadeHistoricoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Histórico da Unidade \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmUnidadeHistoricoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmUnidadeHistoricoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Histórico da Unidade selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Históricos das Unidades selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmUnidadeHistoricoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmUnidadeHistoricoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Histórico da Unidade \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmUnidadeHistoricoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmUnidadeHistoricoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Histórico da Unidade selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Históricos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmUnidadeHistoricoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmUnidadeHistoricoLista').submit();
  }
}
<? } ?>

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmUnidadeHistoricoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
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
