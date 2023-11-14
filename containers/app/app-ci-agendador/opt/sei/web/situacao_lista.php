<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/09/2014 - criado por bcu
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

  PaginaSEI::getInstance()->prepararSelecao('situacao_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selUnidade'));

  switch($_GET['acao']){
    case 'situacao_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjSituacaoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objSituacaoDTO = new SituacaoDTO();
          $objSituacaoDTO->setNumIdSituacao($arrStrIds[$i]);
          $arrObjSituacaoDTO[] = $objSituacaoDTO;
        }
        $objSituacaoRN = new SituacaoRN();
        $objSituacaoRN->excluir($arrObjSituacaoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;


    case 'situacao_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjSituacaoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objSituacaoDTO = new SituacaoDTO();
          $objSituacaoDTO->setNumIdSituacao($arrStrIds[$i]);
          $arrObjSituacaoDTO[] = $objSituacaoDTO;
        }
        $objSituacaoRN = new SituacaoRN();
        $objSituacaoRN->desativar($arrObjSituacaoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'situacao_reativar':
      $strTitulo = 'Reativar Pontos de Controle';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjSituacaoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objSituacaoDTO = new SituacaoDTO();
            $objSituacaoDTO->setNumIdSituacao($arrStrIds[$i]);
            $arrObjSituacaoDTO[] = $objSituacaoDTO;
          }
          $objSituacaoRN = new SituacaoRN();
          $objSituacaoRN->reativar($arrObjSituacaoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;


    case 'situacao_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Ponto de Controle','Selecionar Pontos de Controle');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='situacao_cadastrar'){
        if (isset($_GET['id_situacao'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_situacao']);
        }
      }
      break;

    case 'situacao_listar':
      $strTitulo = 'Pontos de Controle';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'situacao_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  if ($_GET['acao'] == 'situacao_listar' || $_GET['acao'] == 'situacao_selecionar'){
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('situacao_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=situacao_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }

  $objSituacaoDTO = new SituacaoDTO();
  $objSituacaoDTO->retNumIdSituacao();
  $objSituacaoDTO->retStrNome();
  //$objSituacaoDTO->retStrDescricao();
  $objSituacaoDTO->retStrSinAtivo();


  /*
  if ($_GET['acao'] == 'situacao_reativar'){
    //Lista somente inativos
    $objSituacaoDTO->setBolExclusaoLogica(false);
    $objSituacaoDTO->setStrSinAtivo('N');
  }
  */

  $objSituacaoDTO->setBolExclusaoLogica(false);

  PaginaSEI::getInstance()->prepararOrdenacao($objSituacaoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objSituacaoDTO);

  $objSituacaoRN = new SituacaoRN();
  $arrObjSituacaoDTO = $objSituacaoRN->listar($objSituacaoDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objSituacaoDTO);
  $numRegistros = count($arrObjSituacaoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='situacao_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('situacao_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('situacao_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    }else if ($_GET['acao']=='situacao_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('situacao_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('situacao_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('situacao_excluir');
      $bolAcaoDesativar = false;
    }else{
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('situacao_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('situacao_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('situacao_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('situacao_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('situacao_desativar');
    }

    
    if ($bolAcaoDesativar){
      $bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=situacao_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=situacao_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
    

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=situacao_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela')).'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    if ($_GET['acao']!='situacao_reativar'){
      $strSumarioTabela = 'Tabela de Pontos de Controle.';
      $strCaptionTabela = 'Pontos de Controle';
    }else{
      $strSumarioTabela = 'Tabela de Pontos de Controle Inativos.';
      $strCaptionTabela = 'Pontos de Controle Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objSituacaoDTO,'Nome','Nome',$arrObjSituacaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){


      if ($arrObjSituacaoDTO[$i]->getStrSinAtivo()=='S'){
        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      }else{
        $strCssTr = '<tr class="trVermelha">';
      }

      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td>'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjSituacaoDTO[$i]->getNumIdSituacao(),$arrObjSituacaoDTO[$i]->getStrNome()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjSituacaoDTO[$i]->getStrNome()).'</td>';


      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjSituacaoDTO[$i]->getNumIdSituacao());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=situacao_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_situacao='.$arrObjSituacaoDTO[$i]->getNumIdSituacao()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Ponto de Controle" alt="Consultar Ponto de Controle" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=situacao_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_situacao='.$arrObjSituacaoDTO[$i]->getNumIdSituacao()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Ponto de Controle" alt="Alterar Ponto de Controle" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjSituacaoDTO[$i]->getNumIdSituacao();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjSituacaoDTO[$i]->getStrNome());
      }

      if ($bolAcaoDesativar && $arrObjSituacaoDTO[$i]->getStrSinAtivo()=='S'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Ponto de Controle" alt="Desativar Ponto de Controle" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar && $arrObjSituacaoDTO[$i]->getStrSinAtivo()=='N'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Ponto de Controle" alt="Reativar Ponto de Controle" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Ponto de Controle" alt="Excluir Ponto de Controle" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'situacao_selecionar'){
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
  if ('<?=$_GET['acao']?>'=='situacao_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Ponto de Controle \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmSituacaoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmSituacaoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Ponto de Controle selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Pontos de Controle selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmSituacaoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmSituacaoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Ponto de Controle \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmSituacaoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmSituacaoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Ponto de Controle selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Pontos de Controle selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmSituacaoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmSituacaoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Ponto de Controle \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmSituacaoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmSituacaoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Ponto de Controle selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Pontos de Controle selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmSituacaoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmSituacaoLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmSituacaoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
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
?>