<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/11/2015 - criado por mga
*
* Versão do Gerador de Código: 1.36.0
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

  PaginaSEI::getInstance()->prepararSelecao('marcador_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  //PaginaSEI::getInstance()->salvarCamposPost(array());

  switch($_GET['acao']){
    case 'marcador_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMarcadorDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objMarcadorDTO = new MarcadorDTO();
          $objMarcadorDTO->setNumIdMarcador($arrStrIds[$i]);
          $arrObjMarcadorDTO[] = $objMarcadorDTO;
        }
        $objMarcadorRN = new MarcadorRN();
        $objMarcadorRN->excluir($arrObjMarcadorDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;


    case 'marcador_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMarcadorDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objMarcadorDTO = new MarcadorDTO();
          $objMarcadorDTO->setNumIdMarcador($arrStrIds[$i]);
          $arrObjMarcadorDTO[] = $objMarcadorDTO;
        }
        $objMarcadorRN = new MarcadorRN();
        $objMarcadorRN->desativar($arrObjMarcadorDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'marcador_reativar':
      $strTitulo = 'Reativar Marcadores';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjMarcadorDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objMarcadorDTO = new MarcadorDTO();
            $objMarcadorDTO->setNumIdMarcador($arrStrIds[$i]);
            $arrObjMarcadorDTO[] = $objMarcadorDTO;
          }
          $objMarcadorRN = new MarcadorRN();
          $objMarcadorRN->reativar($arrObjMarcadorDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;


    case 'marcador_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Marcador','Selecionar Marcadores');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='marcador_cadastrar'){
        if (isset($_GET['id_marcador'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_marcador']);
        }
      }
      break;

    case 'marcador_listar':
      $strTitulo = 'Marcadores';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'marcador_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  if ($_GET['acao'] == 'marcador_listar' || $_GET['acao'] == 'marcador_selecionar'){
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('marcador_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=marcador_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }

  $objMarcadorDTO = new MarcadorDTO();
  $objMarcadorDTO->setBolExclusaoLogica(false);
  $objMarcadorDTO->retNumIdMarcador();
  $objMarcadorDTO->retStrNome();
  //$objMarcadorDTO->retStrDescricao();
  $objMarcadorDTO->retStrStaIcone();
  $objMarcadorDTO->retStrSinAtivo();
  //$objMarcadorDTO->retNumProcessos();

  $objMarcadorDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());


  /*
  if ($_GET['acao'] == 'marcador_reativar'){
    //Lista somente inativos
    $objMarcadorDTO->setBolExclusaoLogica(false);
    $objMarcadorDTO->setStrSinAtivo('N');
  }
  */

  PaginaSEI::getInstance()->prepararOrdenacao($objMarcadorDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objMarcadorDTO);

  $objMarcadorRN = new MarcadorRN();
  $arrObjMarcadorDTO = $objMarcadorRN->listar($objMarcadorDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objMarcadorDTO);
  $numRegistros = count($arrObjMarcadorDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='marcador_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false; //SessaoSEI::getInstance()->verificarPermissao('marcador_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('marcador_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    }else if ($_GET['acao']=='marcador_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('marcador_reativar');
      $bolAcaoConsultar = false; //SessaoSEI::getInstance()->verificarPermissao('marcador_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('marcador_excluir');
      $bolAcaoDesativar = false;
    }else{
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('marcador_reativar');
      $bolAcaoConsultar = false; //SessaoSEI::getInstance()->verificarPermissao('marcador_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('marcador_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('marcador_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('marcador_desativar');
    }

    
    if ($bolAcaoDesativar){
      //$bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=marcador_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      //$bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=marcador_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
    

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=marcador_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    if ($_GET['acao']!='marcador_reativar'){
      $strSumarioTabela = 'Tabela de Marcadores.';
      $strCaptionTabela = 'Marcadores';
    }else{
      $strSumarioTabela = 'Tabela de Marcadores Inativos.';
      $strCaptionTabela = 'Marcadores Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="5%">Ícone</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMarcadorDTO,'Nome','Nome',$arrObjMarcadorDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMarcadorDTO,'Descrição','Descricao',$arrObjMarcadorDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMarcadorDTO,'Ícone','StaIcone',$arrObjMarcadorDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objMarcadorDTO,'ID','IdMarcador',$arrObjMarcadorDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';

    $objMarcadorRN = new MarcadorRN();
    $arrIcones = InfraArray::indexarArrInfraDTO($objMarcadorRN->listarValoresIcone(),'StaIcone');

    for($i = 0;$i < $numRegistros; $i++){

      if ($arrObjMarcadorDTO[$i]->getStrSinAtivo()=='S'){
        //$strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strCssTr = '<tr class="infraTrClara">';
      }else{
        $strCssTr = '<tr class="trVermelha">';
      }

      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjMarcadorDTO[$i]->getNumIdMarcador(),$arrObjMarcadorDTO[$i]->getStrNome()).'</td>';
      }
      $strResultado .= '<td align="center"><a href="#" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.$arrIcones[$arrObjMarcadorDTO[$i]->getStrStaIcone()]->getStrArquivo().'" title="'.PaginaSEI::tratarHTML($arrIcones[$arrObjMarcadorDTO[$i]->getStrStaIcone()]->getStrDescricao()).'" alt="'.PaginaSEI::tratarHTML($arrIcones[$arrObjMarcadorDTO[$i]->getStrStaIcone()]->getStrDescricao()).'" class="infraImg" /></a></td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMarcadorDTO[$i]->getStrNome()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMarcadorDTO[$i]->getStrDescricao()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjMarcadorDTO[$i]->getStrStaIcone()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjMarcadorDTO[$i]->getNumIdMarcador()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjMarcadorDTO[$i]->getNumIdMarcador());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=marcador_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_marcador='.$arrObjMarcadorDTO[$i]->getNumIdMarcador()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Marcador" alt="Consultar Marcador" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=marcador_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_marcador='.$arrObjMarcadorDTO[$i]->getNumIdMarcador()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Marcador" alt="Alterar Marcador" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjMarcadorDTO[$i]->getNumIdMarcador();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjMarcadorDTO[$i]->getStrNome());
      }

      if ($bolAcaoDesativar && $arrObjMarcadorDTO[$i]->getStrSinAtivo()=='S'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Marcador" alt="Desativar Marcador" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar && $arrObjMarcadorDTO[$i]->getStrSinAtivo()=='N'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Marcador" alt="Reativar Marcador" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Marcador" alt="Excluir Marcador" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'marcador_selecionar'){
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
//<script type="text/javascript">

function inicializar(){
  if ('<?=$_GET['acao']?>'=='marcador_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Marcador \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMarcadorLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmMarcadorLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Marcador selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Marcadores selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmMarcadorLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmMarcadorLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Marcador \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMarcadorLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmMarcadorLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Marcador selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Marcadores selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmMarcadorLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmMarcadorLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Marcador \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMarcadorLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmMarcadorLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Marcador selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Marcadores selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmMarcadorLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmMarcadorLista').submit();
  }
}
<? } ?>

//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmMarcadorLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  //PaginaSEI::getInstance()->abrirAreaDados('10em');
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