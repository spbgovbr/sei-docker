<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/07/2013 - criado por mkr@trf4.jus.br
*
* Versão do Gerador de Código: 1.33.1
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

  PaginaSEI::getInstance()->prepararSelecao('feriado_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selOrgaoListar'));

  switch($_GET['acao']){
    case 'feriado_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjFeriadoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objFeriadoDTO = new FeriadoDTO();
          $objFeriadoDTO->setNumIdFeriado($arrStrIds[$i]);
          $arrObjFeriadoDTO[] = $objFeriadoDTO;
        }
        $objFeriadoRN = new FeriadoRN();
        $objFeriadoRN->excluir($arrObjFeriadoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

/* 
    case 'feriado_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjFeriadoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objFeriadoDTO = new FeriadoDTO();
          $objFeriadoDTO->setNumIdFeriado($arrStrIds[$i]);
          $arrObjFeriadoDTO[] = $objFeriadoDTO;
        }
        $objFeriadoRN = new FeriadoRN();
        $objFeriadoRN->desativar($arrObjFeriadoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'feriado_reativar':
      $strTitulo = 'Reativar Feriados';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjFeriadoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objFeriadoDTO = new FeriadoDTO();
            $objFeriadoDTO->setNumIdFeriado($arrStrIds[$i]);
            $arrObjFeriadoDTO[] = $objFeriadoDTO;
          }
          $objFeriadoRN = new FeriadoRN();
          $objFeriadoRN->reativar($arrObjFeriadoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;

 */
    case 'feriado_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Feriado','Selecionar Feriados');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='feriado_cadastrar'){
        if (isset($_GET['id_feriado'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_feriado']);
        }
      }
      break;

    case 'feriado_listar':
      $strTitulo = 'Feriados';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'feriado_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'feriado_listar' || $_GET['acao'] == 'feriado_selecionar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('feriado_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=feriado_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  /* } */

  $objFeriadoDTO = new FeriadoDTO();
  $objFeriadoDTO->retNumIdFeriado();
  $objFeriadoDTO->retStrDescricao();
  $objFeriadoDTO->retDtaFeriado();
  $objFeriadoDTO->retStrSiglaOrgao();
  $numIdOrgao = PaginaSEI::getInstance()->recuperarCampo('selOrgaoListar');
  if ($numIdOrgao!==''){
    $objFeriadoDTO->setNumIdOrgao($numIdOrgao);
  }

/* 
  if ($_GET['acao'] == 'feriado_reativar'){
    //Lista somente inativos
    $objFeriadoDTO->setBolExclusaoLogica(false);
    $objFeriadoDTO->setStrSinAtivo('N');
  }
 */
  PaginaSEI::getInstance()->prepararOrdenacao($objFeriadoDTO, 'Descricao', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objFeriadoDTO);

  $objFeriadoRN = new FeriadoRN();
  $arrObjFeriadoDTO = $objFeriadoRN->listar($objFeriadoDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objFeriadoDTO);
  $numRegistros = count($arrObjFeriadoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='feriado_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false;//SessaoSEI::getInstance()->verificarPermissao('feriado_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('feriado_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='feriado_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('feriado_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('feriado_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('feriado_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false;//SessaoSEI::getInstance()->verificarPermissao('feriado_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('feriado_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('feriado_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('feriado_desativar');
    }

    /* 
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=feriado_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=feriado_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
     */

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=feriado_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela')).'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    /* if ($_GET['acao']!='feriado_reativar'){ */
      $strSumarioTabela = 'Tabela de Feriados.';
      $strCaptionTabela = 'Feriados';
    /* }else{
      $strSumarioTabela = 'Tabela de Feriados Inativos.';
      $strCaptionTabela = 'Feriados Inativos';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objFeriadoDTO,'Descrição','Descricao',$arrObjFeriadoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objFeriadoDTO,'Data','Feriado',$arrObjFeriadoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objFeriadoDTO,'Órgão','SiglaOrgao',$arrObjFeriadoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjFeriadoDTO[$i]->getNumIdFeriado(),$arrObjFeriadoDTO[$i]->getStrDescricao()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjFeriadoDTO[$i]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjFeriadoDTO[$i]->getDtaFeriado()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML(($arrObjFeriadoDTO[$i]->getStrSiglaOrgao()==''?'Todos':$arrObjFeriadoDTO[$i]->getStrSiglaOrgao())).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjFeriadoDTO[$i]->getNumIdFeriado());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=feriado_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_feriado='.$arrObjFeriadoDTO[$i]->getNumIdFeriado()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Feriado" alt="Consultar Feriado" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=feriado_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_feriado='.$arrObjFeriadoDTO[$i]->getNumIdFeriado()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Feriado" alt="Alterar Feriado" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjFeriadoDTO[$i]->getNumIdFeriado();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjFeriadoDTO[$i]->getStrDescricao());
      }
/* 
      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Feriado" alt="Desativar Feriado" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Feriado" alt="Reativar Feriado" class="infraImg" /></a>&nbsp;';
      }
 */

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Feriado" alt="Excluir Feriado" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'feriado_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $strItensSelOrgao = OrgaoINT::montarSelectSiglaPublicacao('','Todos',$numIdOrgao);
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
#lblOrgao {position:absolute;left:0%;top:0%;width:25%;}
#selOrgaoListar {position:absolute;left:0%;top:40%;width:25%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='feriado_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Feriado \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmFeriadoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmFeriadoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Feriado selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Feriados selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmFeriadoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmFeriadoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Feriado \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmFeriadoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmFeriadoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Feriado selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Feriados selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmFeriadoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmFeriadoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Feriado \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmFeriadoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmFeriadoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Feriado selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Feriados selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmFeriadoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmFeriadoLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmFeriadoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>
  <label id="lblOrgao" for="selOrgaoListar" class="infraLabelOpcional">Órgão:</label>
  <select id="selOrgaoListar" name="selOrgaoListar" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
  <?=$strItensSelOrgao?>
  </select>

  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>