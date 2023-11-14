<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/08/2014 - criado por bcu
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

  PaginaSEI::getInstance()->prepararSelecao('lembrete_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selUsuario'));

  switch($_GET['acao']){
    case 'lembrete_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjLembreteDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objLembreteDTO = new LembreteDTO();
          $objLembreteDTO->setNumIdLembrete($arrStrIds[$i]);
          $arrObjLembreteDTO[] = $objLembreteDTO;
        }
        $objLembreteRN = new LembreteRN();
        $objLembreteRN->excluir($arrObjLembreteDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;


    case 'lembrete_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjLembreteDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objLembreteDTO = new LembreteDTO();
          $objLembreteDTO->setNumIdLembrete($arrStrIds[$i]);
          $arrObjLembreteDTO[] = $objLembreteDTO;
        }
        $objLembreteRN = new LembreteRN();
        $objLembreteRN->desativar($arrObjLembreteDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'lembrete_reativar':
      $strTitulo = 'Meus Lembretes';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjLembreteDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objLembreteDTO = new LembreteDTO();
            $objLembreteDTO->setNumIdLembrete($arrStrIds[$i]);
            $arrObjLembreteDTO[] = $objLembreteDTO;
          }
          $objLembreteRN = new LembreteRN();
          $objLembreteRN->reativar($arrObjLembreteDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;


    case 'lembrete_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Lembrete','Selecionar Lembretes');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='lembrete_cadastrar'){
        if (isset($_GET['id_lembrete'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_lembrete']);
        }
      }
      break;

    case 'lembrete_listar':
    case 'lembrete_visualizar':
      $strTitulo = 'Lembretes';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'lembrete_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  if ($_GET['acao'] == 'lembrete_listar' || $_GET['acao'] == 'lembrete_selecionar' || $_GET['acao'] == 'lembrete_visualizar'){
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('lembrete_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=lembrete_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }

  $objLembreteDTO = new LembreteDTO();
  $objLembreteDTO->retNumIdLembrete();
  $objLembreteDTO->retStrConteudo();
  //$objLembreteDTO->retNumPosicaoX();
  //$objLembreteDTO->retNumPosicaoY();
  //$objLembreteDTO->retNumLargura();
  //$objLembreteDTO->retNumAltura();
  //$objLembreteDTO->retStrCor();
  $objLembreteDTO->retDthLembrete();
  $objLembreteDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());

  if ($_GET['acao'] == 'lembrete_reativar'){
    //Lista somente inativos
    $objLembreteDTO->setBolExclusaoLogica(false);
    $objLembreteDTO->setStrSinAtivo('N');
  }

  $objLembreteDTO->setOrdDthLembrete(InfraDTO::$TIPO_ORDENACAO_DESC);
  //PaginaSEI::getInstance()->prepararOrdenacao($objLembreteDTO, 'Conteudo', InfraDTO::$TIPO_ORDENACAO_ASC);
  PaginaSEI::getInstance()->prepararPaginacao($objLembreteDTO);

  $objLembreteRN = new LembreteRN();
  $arrObjLembreteDTO = $objLembreteRN->listar($objLembreteDTO);

  PaginaSEI::getInstance()->processarPaginacao($objLembreteDTO);
  $numRegistros = count($arrObjLembreteDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='lembrete_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('lembrete_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('lembrete_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    }else if ($_GET['acao']=='lembrete_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('lembrete_reativar');
      $bolAcaoConsultar = false;
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('lembrete_excluir');
      $bolAcaoDesativar = false;
    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('lembrete_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('lembrete_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('lembrete_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('lembrete_desativar');
    }

    
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=lembrete_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReabrir" value="Reabrir" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eabrir</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=lembrete_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
    

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=lembrete_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela')).'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    if ($_GET['acao']!='lembrete_reativar'){
      $strSumarioTabela = 'Tabela de Lembretes.';
      $strCaptionTabela = 'Lembretes';
    }else{
      $strSumarioTabela = 'Tabela de Lembretes Fechados.';
      $strCaptionTabela = 'Lembretes Fechados';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th width="15%" class="infraTh">Data/Hora</th>'."\n";
    $strResultado .= '<th class="infraTh">Conteúdo</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objLembreteDTO,'Posição X','PosicaoX',$arrObjLembreteDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objLembreteDTO,'Posição Y','PosicaoY',$arrObjLembreteDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objLembreteDTO,'Largura','Largura',$arrObjLembreteDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objLembreteDTO,'Altura','Altura',$arrObjLembreteDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objLembreteDTO,'Cor','Cor',$arrObjLembreteDTO).'</th>'."\n";
    $strResultado .= '<th width="10%" class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjLembreteDTO[$i]->getNumIdLembrete(),$arrObjLembreteDTO[$i]->getDthLembrete()).'</td>';
      }
      $strResultado .= '<td align="center">'.$arrObjLembreteDTO[$i]->getDthLembrete().'</td>';
      $strResultado .= '<td>'.str_replace("<br>",'&nbsp;',$arrObjLembreteDTO[$i]->getStrConteudo()).'</td>';
      //$strResultado .= '<td>'.$arrObjLembreteDTO[$i]->getNumPosicaoX().'</td>';
      //$strResultado .= '<td>'.$arrObjLembreteDTO[$i]->getNumPosicaoY().'</td>';
      //$strResultado .= '<td>'.$arrObjLembreteDTO[$i]->getNumLargura().'</td>';
      //$strResultado .= '<td>'.$arrObjLembreteDTO[$i]->getNumAltura().'</td>';
      //$strResultado .= '<td>'.$arrObjLembreteDTO[$i]->getStrCor().'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjLembreteDTO[$i]->getNumIdLembrete());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=lembrete_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_lembrete='.$arrObjLembreteDTO[$i]->getNumIdLembrete()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Lembrete" alt="Consultar Lembrete" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=lembrete_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_lembrete='.$arrObjLembreteDTO[$i]->getNumIdLembrete()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Lembrete" alt="Alterar Lembrete" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjLembreteDTO[$i]->getNumIdLembrete();
      }

      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Lembrete" alt="Desativar Lembrete" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reabrir Lembrete" alt="Reabrir Lembrete" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Lembrete" alt="Excluir Lembrete" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }

  /*
  if ($_GET['acao'] == 'lembrete_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="V" id="btnVoltar" value="Voltar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>';
  }
  */

  $strLinkVisualizar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=lembrete_visualizar&acao_origem='.$_GET['acao']);

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
  if ('<?=$_GET['acao']?>'=='lembrete_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    //document.getElementById('btnVoltar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id){
  if (confirm("Confirma desativação do lembrete?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmLembreteLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmLembreteLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum lembrete selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos lembretes selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmLembreteLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmLembreteLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id){
  //if (confirm("Confirma reabertura do lembrete?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmLembreteLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmLembreteLista').submit();
  //}
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum lembrete selecionado.');
    return;
  }
  //if (confirm("Confirma reabertura dos lembretes selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmLembreteLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmLembreteLista').submit();
  //}
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id){
  if (confirm("Confirma exclusão do lembrete?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmLembreteLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmLembreteLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum lembrete selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos lembretes selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmLembreteLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmLembreteLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmLembreteLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  ?>
  <a id="ancListar" href="<?=$strLinkVisualizar;?>"  class="ancoraPadraoPreta">Ver abertos</a>
  <?
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>