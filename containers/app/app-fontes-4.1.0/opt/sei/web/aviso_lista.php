<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/01/2021 - criado por cas84
*
* Versão do Gerador de Código: 1.43.0
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

  PaginaSEI::getInstance()->prepararSelecao('aviso_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selStaAviso'));

  switch($_GET['acao']){
    case 'aviso_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjAvisoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objAvisoDTO = new AvisoDTO();
          $objAvisoDTO->setNumIdAviso($arrStrIds[$i]);
          $arrObjAvisoDTO[] = $objAvisoDTO;
        }
        $objAvisoRN = new AvisoRN();
        $objAvisoRN->excluir($arrObjAvisoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

/* 
    case 'aviso_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjAvisoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objAvisoDTO = new AvisoDTO();
          $objAvisoDTO->setNumIdAviso($arrStrIds[$i]);
          $arrObjAvisoDTO[] = $objAvisoDTO;
        }
        $objAvisoRN = new AvisoRN();
        $objAvisoRN->desativar($arrObjAvisoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'aviso_reativar':
      $strTitulo = 'Reativar Avisos';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjAvisoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objAvisoDTO = new AvisoDTO();
            $objAvisoDTO->setNumIdAviso($arrStrIds[$i]);
            $arrObjAvisoDTO[] = $objAvisoDTO;
          }
          $objAvisoRN = new AvisoRN();
          $objAvisoRN->reativar($arrObjAvisoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;

 */
    case 'aviso_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Aviso','Selecionar Avisos');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='aviso_cadastrar'){
        if (isset($_GET['id_aviso'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_aviso']);
        }
      }
      break;

    case 'aviso_listar':
      $strTitulo = 'Avisos';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'aviso_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'aviso_listar' || $_GET['acao'] == 'aviso_selecionar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('aviso_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=aviso_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  /* } */

  $objAvisoDTO = new AvisoDTO();
  $objAvisoDTO->retNumIdAviso();
  $objAvisoDTO->retStrStaAviso();
  $objAvisoDTO->retDthInicio();
  $objAvisoDTO->retDthFim();
  $objAvisoDTO->retStrSinLiberado();
  //$objAvisoDTO->retStrOrgaos();
  //$objAvisoDTO->retStrDescricao();
  //$objAvisoDTO->retStrLink();
  //$objAvisoDTO->retStrImagem();
  $strStaAviso = PaginaSEI::getInstance()->recuperarCampo('selStaAviso');
  if ($strStaAviso!==''){
    $objAvisoDTO->setStrStaAviso($strStaAviso);
  }

/* 
  if ($_GET['acao'] == 'aviso_reativar'){
    //Lista somente inativos
    $objAvisoDTO->setBolExclusaoLogica(false);
    $objAvisoDTO->setStrSinAtivo('N');
  }
 */
  PaginaSEI::getInstance()->prepararOrdenacao($objAvisoDTO, 'IdAviso', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objAvisoDTO);

  $objAvisoRN = new AvisoRN();
  $arrObjAvisoDTO = $objAvisoRN->listar($objAvisoDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objAvisoDTO);
  $numRegistros = count($arrObjAvisoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='aviso_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('aviso_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('aviso_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='aviso_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('aviso_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('aviso_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('aviso_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('aviso_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('aviso_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('aviso_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('aviso_desativar');
    }

    /* 
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=aviso_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=aviso_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
     */

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=aviso_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    /* if ($_GET['acao']!='aviso_reativar'){ */
      $strSumarioTabela = 'Tabela de Avisos.';
      $strCaptionTabela = 'Avisos';
    /* }else{
      $strSumarioTabela = 'Tabela de Avisos Inativos.';
      $strCaptionTabela = 'Avisos Inativos';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objAvisoDTO,'Tipo','StaAviso',$arrObjAvisoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objAvisoDTO,'Data/Hora Início','Inicio',$arrObjAvisoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objAvisoDTO,'Data/Hora Fim','Fim',$arrObjAvisoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objAvisoDTO,'Liberado','SinLiberado',$arrObjAvisoDTO).'</th>'."\n";

    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objAvisoDTO,'Órgãos','Orgaos',$arrObjAvisoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objAvisoDTO,'Descrição','Descricao',$arrObjAvisoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objAvisoDTO,'Link','Link',$arrObjAvisoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objAvisoDTO,'Imagem','Imagem',$arrObjAvisoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjAvisoDTO[$i]->getNumIdAviso(),$arrObjAvisoDTO[$i]->getNumIdAviso()).'</td>';
      }
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjAvisoDTO[$i]->getStrStaAviso() == "J" ? "Janela" : "Banner").'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjAvisoDTO[$i]->getDthInicio()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjAvisoDTO[$i]->getDthFim()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjAvisoDTO[$i]->getStrSinLiberado() == "S" ? "Sim" : "Não" ).'</td>';

      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjAvisoDTO[$i]->getStrOrgaos()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjAvisoDTO[$i]->getStrDescricao()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjAvisoDTO[$i]->getStrLink()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjAvisoDTO[$i]->getStrImagem()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjAvisoDTO[$i]->getNumIdAviso());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=aviso_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_aviso='.$arrObjAvisoDTO[$i]->getNumIdAviso()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Aviso" alt="Consultar Aviso" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=aviso_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_aviso='.$arrObjAvisoDTO[$i]->getNumIdAviso()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Aviso" alt="Alterar Aviso" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjAvisoDTO[$i]->getNumIdAviso();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjAvisoDTO[$i]->getNumIdAviso());
      }
/* 
      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Aviso" alt="Desativar Aviso" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Aviso" alt="Reativar Aviso" class="infraImg" /></a>&nbsp;';
      }
 */

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Aviso" alt="Excluir Aviso" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'aviso_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $strItensSelStaAviso = AvisoINT::montarSelectStaAviso('','Todos',$strStaAviso);
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
#lblStaAviso {position:absolute;left:0%;top:0%;width:25%;}
#selStaAviso {position:absolute;left:0%;top:40%;width:25%;}

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='aviso_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas(true);
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Aviso \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmAvisoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmAvisoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Aviso selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Avisos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmAvisoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmAvisoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Aviso \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmAvisoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmAvisoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Aviso selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Avisos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmAvisoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmAvisoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Aviso?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmAvisoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmAvisoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Aviso selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Avisos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmAvisoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmAvisoLista').submit();
  }
}
<? } ?>

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAvisoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('4.5em');
  ?>
  <label id="lblStaAviso" for="selStaAviso" accesskey="" class="infraLabelOpcional">Tipo:</label>
  <select id="selStaAviso" name="selStaAviso" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
  <?=$strItensSelStaAviso?>
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
