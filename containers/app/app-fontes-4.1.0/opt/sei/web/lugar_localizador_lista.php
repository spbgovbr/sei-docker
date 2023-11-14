<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/05/2008 - criado por fbv
*
* Versão do Gerador de Código: 1.16.0
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(false);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('lugar_localizador_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'lugar_localizador_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjLugarLocalizadorDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objLugarLocalizadorDTO = new LugarLocalizadorDTO();
          $objLugarLocalizadorDTO->setNumIdLugarLocalizador($arrStrIds[$i]);
          $arrObjLugarLocalizadorDTO[] = $objLugarLocalizadorDTO;
        }
        $objLugarLocalizadorRN = new LugarLocalizadorRN();
        $objLugarLocalizadorRN->excluirRN0654($arrObjLugarLocalizadorDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;


    case 'lugar_localizador_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjLugarLocalizadorDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objLugarLocalizadorDTO = new LugarLocalizadorDTO();
          $objLugarLocalizadorDTO->setNumIdLugarLocalizador($arrStrIds[$i]);
          $arrObjLugarLocalizadorDTO[] = $objLugarLocalizadorDTO;
        }
        $objLugarLocalizadorRN = new LugarLocalizadorRN();
        $objLugarLocalizadorRN->desativarRN0657($arrObjLugarLocalizadorDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'lugar_localizador_reativar':
      $strTitulo = 'Reativar Lugares de Localizadores';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjLugarLocalizadorDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objLugarLocalizadorDTO = new LugarLocalizadorDTO();
            $objLugarLocalizadorDTO->setNumIdLugarLocalizador($arrStrIds[$i]);
            $arrObjLugarLocalizadorDTO[] = $objLugarLocalizadorDTO;
          }
          $objLugarLocalizadorRN = new LugarLocalizadorRN();
          $objLugarLocalizadorRN->reativarRN0658($arrObjLugarLocalizadorDTO);
          PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;


    case 'lugar_localizador_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Lugar de Localizador','Selecionar Lugares de Localizadores');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='lugar_localizador_cadastrar'){
        if (isset($_GET['id_lugar_localizador'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_lugar_localizador']);
        }
      }
      break;

    case 'lugar_localizador_listar':
      $strTitulo = 'Lugares de Localizadores';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'lugar_localizador_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  if ($_GET['acao'] == 'lugar_localizador_listar' || $_GET['acao'] == 'lugar_localizador_selecionar'){
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('lugar_localizador_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=lugar_localizador_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }    
  
  $objLugarLocalizadorDTO = new LugarLocalizadorDTO(true);
  $objLugarLocalizadorDTO->retNumIdLugarLocalizador();
  $objLugarLocalizadorDTO->retStrNome();
  $objLugarLocalizadorDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

  if ($_GET['acao'] == 'lugar_localizador_reativar'){
    //Lista somente inativos
    $objLugarLocalizadorDTO->setBolExclusaoLogica(false);
    $objLugarLocalizadorDTO->setStrSinAtivo('N');
  }

  PaginaSEI::getInstance()->prepararOrdenacao($objLugarLocalizadorDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objLugarLocalizadorDTO);

  $objLugarLocalizadorRN = new LugarLocalizadorRN();
  $arrObjLugarLocalizadorDTO = $objLugarLocalizadorRN->listarRN0655($objLugarLocalizadorDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objLugarLocalizadorDTO);
  $numRegistros = count($arrObjLugarLocalizadorDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='lugar_localizador_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('lugar_localizador_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('lugar_localizador_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    }else if ($_GET['acao']=='lugar_localizador_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('lugar_localizador_reativar');
      $bolAcaoConsultar = false;
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('lugar_localizador_excluir');
      $bolAcaoDesativar = false;
    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('lugar_localizador_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('lugar_localizador_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('lugar_localizador_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('lugar_localizador_desativar');
    }

    
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="T" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=lugar_localizador_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=lugar_localizador_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
    

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=lugar_localizador_excluir&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }

    $strResultado = '';

    if ($_GET['acao']!='lugar_localizador_reativar'){
      $strSumarioTabela = 'Tabela de Lugares de Localizadores.';
      $strCaptionTabela = 'Lugares de Localizadores';
    }else{
      $strSumarioTabela = 'Tabela de Lugares de Localizadores Inativos.';
      $strCaptionTabela = 'Lugares de Localizadores Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n"; //70
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objLugarLocalizadorDTO,'Nome','Nome',$arrObjLugarLocalizadorDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="25%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjLugarLocalizadorDTO[$i]->getNumIdLugarLocalizador(),$arrObjLugarLocalizadorDTO[$i]->getStrNome()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjLugarLocalizadorDTO[$i]->getStrNome()).'</td>';
      $strResultado .= '<td align="center">';
      
      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjLugarLocalizadorDTO[$i]->getNumIdLugarLocalizador());      
      
      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=lugar_localizador_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_lugar_localizador='.$arrObjLugarLocalizadorDTO[$i]->getNumIdLugarLocalizador()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Lugar de Localizador" alt="Consultar Lugar de Localizador" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=lugar_localizador_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_lugar_localizador='.$arrObjLugarLocalizadorDTO[$i]->getNumIdLugarLocalizador()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Lugar de Localizador" alt="Alterar Lugar de Localizador" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjLugarLocalizadorDTO[$i]->getNumIdLugarLocalizador();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjLugarLocalizadorDTO[$i]->getStrNome());
      }

      if ($bolAcaoDesativar){
        $strResultado .= '<a href="#ID-'.$strId.'"  onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Lugar de Localizador" alt="Desativar Lugar de Localizador" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="#ID-'.$strId.'"  onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Lugar de Localizador" alt="Reativar Lugar de Localizador" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir){
        $strResultado .= '<a href="#ID-'.$strId.'"  onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Lugar de Localizador" alt="Excluir Lugar de Localizador" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'lugar_localizador_selecionar'){
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
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='lugar_localizador_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }

  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Lugar de Localizador \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmLugarLocalizadorLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmLugarLocalizadorLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Lugar de Localizador selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Lugares de Localizadores selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmLugarLocalizadorLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmLugarLocalizadorLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Lugar de Localizador \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmLugarLocalizadorLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmLugarLocalizadorLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Lugar de Localizador selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Lugares de Localizadores selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmLugarLocalizadorLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmLugarLocalizadorLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Lugar de Localizador \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmLugarLocalizadorLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmLugarLocalizadorLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Lugar de Localizador selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Lugares de Localizadores selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmLugarLocalizadorLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmLugarLocalizadorLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmLugarLocalizadorLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
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