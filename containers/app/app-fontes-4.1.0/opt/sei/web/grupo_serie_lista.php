<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 01/07/2008 - criado por fbv
*
* Versão do Gerador de Código: 1.19.0
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

  PaginaSEI::getInstance()->prepararSelecao('grupo_serie_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'grupo_serie_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjGrupoSerieDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objGrupoSerieDTO = new GrupoSerieDTO();
          $objGrupoSerieDTO->setNumIdGrupoSerie($arrStrIds[$i]);
          $arrObjGrupoSerieDTO[] = $objGrupoSerieDTO;
        }
        $objGrupoSerieRN = new GrupoSerieRN();
        $objGrupoSerieRN->excluirRN0779($arrObjGrupoSerieDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;


    case 'grupo_serie_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjGrupoSerieDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objGrupoSerieDTO = new GrupoSerieDTO();
          $objGrupoSerieDTO->setNumIdGrupoSerie($arrStrIds[$i]);
          $arrObjGrupoSerieDTO[] = $objGrupoSerieDTO;
        }
        $objGrupoSerieRN = new GrupoSerieRN();
        $objGrupoSerieRN->desativarRN0781($arrObjGrupoSerieDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'grupo_serie_reativar':
      $strTitulo = 'Reativar Grupos de Tipos de Documento';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjGrupoSerieDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objGrupoSerieDTO = new GrupoSerieDTO();
            $objGrupoSerieDTO->setNumIdGrupoSerie($arrStrIds[$i]);
            $arrObjGrupoSerieDTO[] = $objGrupoSerieDTO;
          }
          $objGrupoSerieRN = new GrupoSerieRN();
          $objGrupoSerieRN->reativarRN0782($arrObjGrupoSerieDTO);
          PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;


    case 'grupo_serie_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Grupo de Tipos de Documento','Selecionar Grupos de Tipos de Documento');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='grupo_serie_cadastrar'){
        if (isset($_GET['id_grupo_serie'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_grupo_serie']);
        }
      }
      break;

    case 'grupo_serie_listar':
      $strTitulo = 'Grupos de Tipos de Documento';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'grupo_serie_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  if ($_GET['acao'] == 'grupo_serie_listar' || $_GET['acao'] == 'grupo_serie_selecionar'){
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('grupo_serie_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_serie_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }
  
  $objGrupoSerieDTO = new GrupoSerieDTO();
  $objGrupoSerieDTO->retNumIdGrupoSerie();
  $objGrupoSerieDTO->retStrNome();
  //$objGrupoSerieDTO->retStrDescricao();

  if ($_GET['acao'] == 'grupo_serie_reativar'){
    //Lista somente inativos
    $objGrupoSerieDTO->setBolExclusaoLogica(false);
    $objGrupoSerieDTO->setStrSinAtivo('N');
  }

  PaginaSEI::getInstance()->prepararOrdenacao($objGrupoSerieDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objGrupoSerieDTO);

  $objGrupoSerieRN = new GrupoSerieRN();
  $arrObjGrupoSerieDTO = $objGrupoSerieRN->listarRN0778($objGrupoSerieDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objGrupoSerieDTO);
  $numRegistros = count($arrObjGrupoSerieDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='grupo_serie_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('grupo_serie_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('grupo_serie_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolAcaoListarSeries = false;
      $bolCheck = true;
    }else if ($_GET['acao']=='grupo_serie_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('grupo_serie_reativar');
      $bolAcaoConsultar = false;
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('grupo_serie_excluir');
      $bolAcaoDesativar = false;
      $bolAcaoListarSeries = false;
    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('grupo_serie_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('grupo_serie_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('grupo_serie_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('grupo_serie_desativar');
      $bolAcaoListarSeries  = SessaoSEI::getInstance()->verificarPermissao('serie_listar');
    }

    
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_serie_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_serie_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
    

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_serie_excluir&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }

    $strResultado = '';

    if ($_GET['acao']!='grupo_serie_reativar'){
      $strSumarioTabela = 'Tabela de Grupos de Tipos de Documento.';
      $strCaptionTabela = 'Grupos de Tipos de Documento';
    }else{
      $strSumarioTabela = 'Tabela de Grupos de Tipos de Documento Inativos.';
      $strCaptionTabela = 'Grupos de Tipos de Documento Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n"; //60
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoSerieDTO,'Nome','Nome',$arrObjGrupoSerieDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoSerieDTO,'Descrição','Descricao',$arrObjGrupoSerieDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjGrupoSerieDTO[$i]->getNumIdGrupoSerie(),$arrObjGrupoSerieDTO[$i]->getStrNome()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjGrupoSerieDTO[$i]->getStrNome()).'</td>';
      //$strResultado .= '<td>'.$arrObjGrupoSerieDTO[$i]->getStrDescricao().'</td>';
      $strResultado .= '<td align="center">';
      
      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjGrupoSerieDTO[$i]->getNumIdGrupoSerie());
      
      if ($bolAcaoListarSeries){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=serie_listar&acao_retorno=grupo_serie_listar&id_grupo_serie='.$arrObjGrupoSerieDTO[$i]->getNumIdGrupoSerie()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::ORGANOGRAMA.'" title="Listar Tipos de Documento" alt="Listar Tipos de Documento" class="infraImg" /></a>&nbsp;';
      }
      
      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_serie_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_grupo_serie='.$arrObjGrupoSerieDTO[$i]->getNumIdGrupoSerie()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Grupo de Tipos de Documento" alt="Consultar Grupo de Tipos de Documento" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_serie_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_grupo_serie='.$arrObjGrupoSerieDTO[$i]->getNumIdGrupoSerie()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Grupo de Tipos de Documento" alt="Alterar Grupo de Tipos de Documento" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjGrupoSerieDTO[$i]->getNumIdGrupoSerie();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjGrupoSerieDTO[$i]->getStrNome());
      }

      if ($bolAcaoDesativar){
        $strResultado .= '<a href="#ID-'.$strId.'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Grupo de Tipos de Documento" alt="Desativar Grupo de Tipos de Documento" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="#ID-'.$strId.'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Grupo de Tipos de Documento" alt="Reativar Grupo de Tipos de Documento" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir){
        $strResultado .= '<a href="#ID-'.$strId.'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Grupo de Tipos de Documento" alt="Excluir Grupo de Tipos de Documento" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'grupo_serie_selecionar'){
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
  if ('<?=$_GET['acao']?>'=='grupo_serie_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }

  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Grupo de Tipos de Documento \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGrupoSerieLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmGrupoSerieLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Grupo de Tipos de Documento selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Grupos de Tipos de Documento selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmGrupoSerieLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmGrupoSerieLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Grupo de Tipos de Documento \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGrupoSerieLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmGrupoSerieLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Grupo de Tipos de Documento selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Grupos de Tipos de Documento selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmGrupoSerieLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmGrupoSerieLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Grupo de Tipos de Documento \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGrupoSerieLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmGrupoSerieLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Grupo de Tipos de Documento selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Grupos de Tipos de Documento selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmGrupoSerieLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmGrupoSerieLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmGrupoSerieLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
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