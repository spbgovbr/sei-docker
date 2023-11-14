<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/03/2020 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
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

  PaginaSEI::getInstance()->prepararSelecao('campo_pesquisa_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selPesquisa'));

  switch($_GET['acao']){
    case 'campo_pesquisa_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjCampoPesquisaDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objCampoPesquisaDTO = new CampoPesquisaDTO();
          $objCampoPesquisaDTO->setNumIdCampoPesquisa($arrStrIds[$i]);
          $arrObjCampoPesquisaDTO[] = $objCampoPesquisaDTO;
        }
        $objCampoPesquisaRN = new CampoPesquisaRN();
        $objCampoPesquisaRN->excluir($arrObjCampoPesquisaDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

/* 
    case 'campo_pesquisa_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjCampoPesquisaDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objCampoPesquisaDTO = new CampoPesquisaDTO();
          $objCampoPesquisaDTO->setNumIdCampoPesquisa($arrStrIds[$i]);
          $arrObjCampoPesquisaDTO[] = $objCampoPesquisaDTO;
        }
        $objCampoPesquisaRN = new CampoPesquisaRN();
        $objCampoPesquisaRN->desativar($arrObjCampoPesquisaDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'campo_pesquisa_reativar':
      $strTitulo = 'Reativar ';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjCampoPesquisaDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objCampoPesquisaDTO = new CampoPesquisaDTO();
            $objCampoPesquisaDTO->setNumIdCampoPesquisa($arrStrIds[$i]);
            $arrObjCampoPesquisaDTO[] = $objCampoPesquisaDTO;
          }
          $objCampoPesquisaRN = new CampoPesquisaRN();
          $objCampoPesquisaRN->reativar($arrObjCampoPesquisaDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;

 */
    case 'campo_pesquisa_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar ','Selecionar ');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='campo_pesquisa_cadastrar'){
        if (isset($_GET['id_campo_pesquisa'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_campo_pesquisa']);
        }
      }
      break;

    case 'campo_pesquisa_listar':
      $strTitulo = '';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'campo_pesquisa_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'campo_pesquisa_listar' || $_GET['acao'] == 'campo_pesquisa_selecionar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('campo_pesquisa_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNov" value="Nov" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=campo_pesquisa_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ov</button>';
    }
  /* } */

  $objCampoPesquisaDTO = new CampoPesquisaDTO();
  $objCampoPesquisaDTO->retNumIdCampoPesquisa();
  //$objCampoPesquisaDTO->retNumChave();
  //$objCampoPesquisaDTO->retStrValor();
  $numIdPesquisa = PaginaSEI::getInstance()->recuperarCampo('selPesquisa');
  if ($numIdPesquisa!==''){
    $objCampoPesquisaDTO->setNumIdPesquisa($numIdPesquisa);
  }

/* 
  if ($_GET['acao'] == 'campo_pesquisa_reativar'){
    //Lista somente inativos
    $objCampoPesquisaDTO->setBolExclusaoLogica(false);
    $objCampoPesquisaDTO->setStrSinAtivo('N');
  }
 */
  PaginaSEI::getInstance()->prepararOrdenacao($objCampoPesquisaDTO, 'IdCampoPesquisa', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objCampoPesquisaDTO);

  $objCampoPesquisaRN = new CampoPesquisaRN();
  $arrObjCampoPesquisaDTO = $objCampoPesquisaRN->listar($objCampoPesquisaDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objCampoPesquisaDTO);
  $numRegistros = count($arrObjCampoPesquisaDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='campo_pesquisa_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('campo_pesquisa_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('campo_pesquisa_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='campo_pesquisa_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('campo_pesquisa_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('campo_pesquisa_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('campo_pesquisa_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('campo_pesquisa_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('campo_pesquisa_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('campo_pesquisa_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('campo_pesquisa_desativar');
    }


    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=campo_pesquisa_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=campo_pesquisa_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }


    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=campo_pesquisa_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    /* if ($_GET['acao']!='campo_pesquisa_reativar'){ */
      $strSumarioTabela = 'Tabela de .';
      $strCaptionTabela = '';
    /* }else{
      $strSumarioTabela = 'Tabela de  Inativs.';
      $strCaptionTabela = ' Inativs';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objCampoPesquisaDTO,'','Chave',$arrObjCampoPesquisaDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objCampoPesquisaDTO,'','Valor',$arrObjCampoPesquisaDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjCampoPesquisaDTO[$i]->getNumIdCampoPesquisa(),$arrObjCampoPesquisaDTO[$i]->getNumIdCampoPesquisa()).'</td>';
      }
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjCampoPesquisaDTO[$i]->getNumChave()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjCampoPesquisaDTO[$i]->getStrValor()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjCampoPesquisaDTO[$i]->getNumIdCampoPesquisa());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=campo_pesquisa_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_campo_pesquisa='.$arrObjCampoPesquisaDTO[$i]->getNumIdCampoPesquisa()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar " alt="Consultar " class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=campo_pesquisa_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_campo_pesquisa='.$arrObjCampoPesquisaDTO[$i]->getNumIdCampoPesquisa()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar " alt="Alterar " class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjCampoPesquisaDTO[$i]->getNumIdCampoPesquisa();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjCampoPesquisaDTO[$i]->getNumIdCampoPesquisa());
      }
/* 
      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar " alt="Desativar " class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar " alt="Reativar " class="infraImg" /></a>&nbsp;';
      }
 */

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir " alt="Excluir " class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'campo_pesquisa_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  //$strItensSelPesquisa = PesquisaINT::montarSelect???????('','Todos',$numIdPesquisa);
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
#lblPesquisa {position:absolute;left:0%;top:0%;width:25%;}
#selPesquisa {position:absolute;left:0%;top:40%;width:25%;}

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='campo_pesquisa_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas(true);
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação d  \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmCampoPesquisaLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmCampoPesquisaLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma  selecionada.');
    return;
  }
  if (confirm("Confirma desativação ds  selecionads?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmCampoPesquisaLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmCampoPesquisaLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação d  \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmCampoPesquisaLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmCampoPesquisaLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma  selecionada.');
    return;
  }
  if (confirm("Confirma reativação ds  selecionads?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmCampoPesquisaLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmCampoPesquisaLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão d  \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmCampoPesquisaLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmCampoPesquisaLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma  selecionada.');
    return;
  }
  if (confirm("Confirma exclusão ds  selecionads?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmCampoPesquisaLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmCampoPesquisaLista').submit();
  }
}
<? } ?>

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmCampoPesquisaLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('4.5em');
  ?>
  <label id="lblPesquisa" for="selPesquisa" accesskey="" class="infraLabelOpcional">id_pesquisa:</label>
  <select id="selPesquisa" name="selPesquisa" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
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
