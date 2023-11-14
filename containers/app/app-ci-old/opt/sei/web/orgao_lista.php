<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 13/10/2009 - criado por mga
*
* Versão do Gerador de Código: 1.29.1
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

  PaginaSEI::getInstance()->prepararSelecao('orgao_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'orgao_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjOrgaoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objOrgaoDTO = new OrgaoDTO();
          $objOrgaoDTO->setNumIdOrgao($arrStrIds[$i]);
          $arrObjOrgaoDTO[] = $objOrgaoDTO;
        }
        $objOrgaoRN = new OrgaoRN();
        $objOrgaoRN->excluirRN1351($arrObjOrgaoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;


    case 'orgao_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjOrgaoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objOrgaoDTO = new OrgaoDTO();
          $objOrgaoDTO->setNumIdOrgao($arrStrIds[$i]);
          $arrObjOrgaoDTO[] = $objOrgaoDTO;
        }
        $objOrgaoRN = new OrgaoRN();
        $objOrgaoRN->desativarRN1355($arrObjOrgaoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'orgao_reativar':
      $strTitulo = 'Reativar Órgãos';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjOrgaoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objOrgaoDTO = new OrgaoDTO();
            $objOrgaoDTO->setNumIdOrgao($arrStrIds[$i]);
            $arrObjOrgaoDTO[] = $objOrgaoDTO;
          }
          $objOrgaoRN = new OrgaoRN();
          $objOrgaoRN->reativarRN1356($arrObjOrgaoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;


    case 'orgao_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Órgão','Selecionar Órgãos');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='orgao_cadastrar'){
        if (isset($_GET['id_orgao'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_orgao']);
        }
      }
      break;

    case 'orgao_listar':
      $strTitulo = 'Órgãos';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'orgao_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  if ($_GET['acao'] == 'orgao_listar' || $_GET['acao'] == 'orgao_selecionar'){
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('orgao_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=orgao_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }

  $objOrgaoDTO = new OrgaoDTO();
  $objOrgaoDTO->retNumIdOrgao();
  $objOrgaoDTO->retStrSigla();
  $objOrgaoDTO->retStrDescricao();

  if ($_GET['acao'] == 'orgao_reativar'){
    //Lista somente inativos
    $objOrgaoDTO->setBolExclusaoLogica(false);
    $objOrgaoDTO->setStrSinAtivo('N');
  }

  PaginaSEI::getInstance()->prepararOrdenacao($objOrgaoDTO, 'Sigla', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objOrgaoDTO);

  $objOrgaoRN = new OrgaoRN();
  $arrObjOrgaoDTO = $objOrgaoRN->listarRN1353($objOrgaoDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objOrgaoDTO);
  $numRegistros = count($arrObjOrgaoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='orgao_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('orgao_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('orgao_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    }else if ($_GET['acao']=='orgao_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('orgao_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('orgao_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('orgao_excluir');
      $bolAcaoDesativar = false;
    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('orgao_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('orgao_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('orgao_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('orgao_desativar');
    }

    $bolAcaoVerHistorico = SessaoSEI::getInstance()->verificarPermissao('orgao_historico_listar');

    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=orgao_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=orgao_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
    

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=orgao_excluir&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }



    $strResultado = '';

    if ($_GET['acao']!='orgao_reativar'){
      $strSumarioTabela = 'Tabela de Órgãos.';
      $strCaptionTabela = 'Órgãos';
    }else{
      $strSumarioTabela = 'Tabela de Órgãos Inativos.';
      $strCaptionTabela = 'Órgãos Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n"; //80
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objOrgaoDTO,'ID','IdOrgao',$arrObjOrgaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">'.PaginaSEI::getInstance()->getThOrdenacao($objOrgaoDTO,'Sigla','Sigla',$arrObjOrgaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objOrgaoDTO,'Descrição','Descricao',$arrObjOrgaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjOrgaoDTO[$i]->getNumIdOrgao(),$arrObjOrgaoDTO[$i]->getStrSigla()).'</td>';
      }
      $strResultado .= '<td align="center">'.$arrObjOrgaoDTO[$i]->getNumIdOrgao().'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjOrgaoDTO[$i]->getStrSigla()).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjOrgaoDTO[$i]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjOrgaoDTO[$i]->getNumIdOrgao());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=orgao_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_orgao='.$arrObjOrgaoDTO[$i]->getNumIdOrgao()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Órgão" alt="Consultar Órgão" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=orgao_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_orgao='.$arrObjOrgaoDTO[$i]->getNumIdOrgao()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Órgão" alt="Alterar Órgão" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjOrgaoDTO[$i]->getNumIdOrgao();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjOrgaoDTO[$i]->getStrSigla());
      }

      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Órgão" alt="Desativar Órgão" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Órgão" alt="Reativar Órgão" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Órgão" alt="Excluir Órgão" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoVerHistorico){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=orgao_historico_listar&acao_retorno='.$_GET['acao'].'&id_orgao='.$arrObjOrgaoDTO[$i]->getNumIdOrgao().'&acao_origem='.$_GET['acao']).'"  tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::HISTORICO.'" title="Histórico do Órgão" alt="Histórico do Órgão" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'orgao_selecionar'){
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
  if ('<?=$_GET['acao']?>'=='orgao_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }

  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Órgão \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmOrgaoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmOrgaoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Órgão selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Órgãos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmOrgaoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmOrgaoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Órgão \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmOrgaoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmOrgaoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Órgão selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Órgãos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmOrgaoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmOrgaoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Órgão \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmOrgaoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmOrgaoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Órgão selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Órgãos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmOrgaoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmOrgaoLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmOrgaoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
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