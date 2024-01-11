<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/08/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('grupo_bloco_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'grupo_bloco_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjGrupoBlocoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objGrupoBlocoDTO = new GrupoBlocoDTO();
          $objGrupoBlocoDTO->setNumIdGrupoBloco($arrStrIds[$i]);
          $arrObjGrupoBlocoDTO[] = $objGrupoBlocoDTO;
        }
        $objGrupoBlocoRN = new GrupoBlocoRN();
        $objGrupoBlocoRN->excluir($arrObjGrupoBlocoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'grupo_bloco_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjGrupoBlocoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objGrupoBlocoDTO = new GrupoBlocoDTO();
          $objGrupoBlocoDTO->setNumIdGrupoBloco($arrStrIds[$i]);
          $arrObjGrupoBlocoDTO[] = $objGrupoBlocoDTO;
        }
        $objGrupoBlocoRN = new GrupoBlocoRN();
        $objGrupoBlocoRN->desativar($arrObjGrupoBlocoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'grupo_bloco_reativar':
      $strTitulo = 'Reativar Grupos de Blocos';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjGrupoBlocoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objGrupoBlocoDTO = new GrupoBlocoDTO();
            $objGrupoBlocoDTO->setNumIdGrupoBloco($arrStrIds[$i]);
            $arrObjGrupoBlocoDTO[] = $objGrupoBlocoDTO;
          }
          $objGrupoBlocoRN = new GrupoBlocoRN();
          $objGrupoBlocoRN->reativar($arrObjGrupoBlocoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;

    case 'grupo_bloco_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Grupo de Bloco','Selecionar Grupos de Blocos');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='grupo_bloco_cadastrar'){
        if (isset($_GET['id_grupo_bloco'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_grupo_bloco']);
        }
      }
      break;

    case 'grupo_bloco_listar':
      $strTitulo = 'Grupos de Blocos';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'grupo_bloco_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'grupo_bloco_listar' || $_GET['acao'] == 'grupo_bloco_selecionar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('grupo_bloco_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_bloco_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  /* } */

  $objGrupoBlocoDTO = new GrupoBlocoDTO();

/* 
  if ($_GET['acao'] == 'grupo_bloco_reativar'){
    //Lista somente inativos
    $objGrupoBlocoDTO->setBolExclusaoLogica(false);
    $objGrupoBlocoDTO->setStrSinAtivo('N');
  }
 */
  PaginaSEI::getInstance()->prepararOrdenacao($objGrupoBlocoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objGrupoBlocoDTO);

  $objGrupoBlocoRN = new GrupoBlocoRN();
  $arrObjGrupoBlocoDTO = $objGrupoBlocoRN->listarUnidade($objGrupoBlocoDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objGrupoBlocoDTO);
  $numRegistros = count($arrObjGrupoBlocoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='grupo_bloco_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('grupo_bloco_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('grupo_bloco_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='grupo_bloco_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('grupo_bloco_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('grupo_bloco_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('grupo_bloco_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('grupo_bloco_reativar');
      $bolAcaoConsultar = false;
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('grupo_bloco_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('grupo_bloco_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('grupo_bloco_desativar');
    }

    if ($bolAcaoDesativar){
      //$bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_bloco_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      //$bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_bloco_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_bloco_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    /* if ($_GET['acao']!='grupo_bloco_reativar'){ */
      $strSumarioTabela = 'Tabela de Grupos de Blocos.';
      $strCaptionTabela = 'Grupos de Blocos';
    /* }else{
      $strSumarioTabela = 'Tabela de Grupos de Blocos Inativos.';
      $strCaptionTabela = 'Grupos de Blocos Inativos';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoBlocoDTO,'Nome','Nome',$arrObjGrupoBlocoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">Assinatura</th>'."\n";
    $strResultado .= '<th class="infraTh">Internos</th>'."\n";
    $strResultado .= '<th class="infraTh">Reunião</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';


    for($i = 0;$i < $numRegistros; $i++){

      $objGrupoBlocoDTO = $arrObjGrupoBlocoDTO[$i];

      if ($objGrupoBlocoDTO->getStrSinAtivo()=='S'){
        $strCssTr = '<tr class="infraTrClara">';
      }else{
        $strCssTr = '<tr class="trVermelha">';
      }

      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td align="center">'.PaginaSEI::getInstance()->getTrCheck($i,$objGrupoBlocoDTO->getNumIdGrupoBloco(),$objGrupoBlocoDTO->getStrNome()).'</td>';
      }
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($objGrupoBlocoDTO->getStrNome()).'</td>';
      $strResultado .= '<td align="center">'.GrupoBlocoINT::montarLinkBlocos($objGrupoBlocoDTO->getNumIdGrupoBloco(), $objGrupoBlocoDTO->getNumBlocosAssinatura(), 'bloco_assinatura_listar').'</td>';
      $strResultado .= '<td align="center">'.GrupoBlocoINT::montarLinkBlocos($objGrupoBlocoDTO->getNumIdGrupoBloco(), $objGrupoBlocoDTO->getNumBlocosInternos(), 'bloco_interno_listar').'</td>';
      $strResultado .= '<td align="center">'.GrupoBlocoINT::montarLinkBlocos($objGrupoBlocoDTO->getNumIdGrupoBloco(), $objGrupoBlocoDTO->getNumBlocosReuniao(), 'bloco_reuniao_listar').'</td>';

      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$objGrupoBlocoDTO->getNumIdGrupoBloco());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_bloco_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_grupo_bloco='.$objGrupoBlocoDTO->getNumIdGrupoBloco()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Grupo de Bloco" alt="Consultar Grupo de Bloco" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_bloco_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_grupo_bloco='.$objGrupoBlocoDTO->getNumIdGrupoBloco()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Grupo de Bloco" alt="Alterar Grupo de Bloco" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $objGrupoBlocoDTO->getNumIdGrupoBloco();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($objGrupoBlocoDTO->getStrNome());
      }

      if ($bolAcaoDesativar && $objGrupoBlocoDTO->getStrSinAtivo()=='S'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Grupo de Bloco" alt="Desativar Grupo de Bloco" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar && $objGrupoBlocoDTO->getStrSinAtivo()=='N'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Grupo de Bloco" alt="Reativar Grupo de Bloco" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Grupo de Bloco" alt="Excluir Grupo de Bloco" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'grupo_bloco_selecionar'){
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
<?if(0){?><style><?}?>

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='grupo_bloco_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas(true);
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Grupo de Bloco \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGrupoBlocoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmGrupoBlocoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Grupo de Bloco selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Grupos de Blocos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmGrupoBlocoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmGrupoBlocoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Grupo de Bloco \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGrupoBlocoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmGrupoBlocoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Grupo de Bloco selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Grupos de Blocos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmGrupoBlocoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmGrupoBlocoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Grupo de Bloco \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGrupoBlocoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmGrupoBlocoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Grupo de Bloco selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Grupos de Blocos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmGrupoBlocoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmGrupoBlocoLista').submit();
  }
}
<? } ?>

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmGrupoBlocoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
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
