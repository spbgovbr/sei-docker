<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 08/08/2012 - criado por mga
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id$
*/

try {
  //require_once dirname(__FILE__).'/Infra.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoInfra::getInstance()->validarLink();

  PaginaInfra::getInstance()->prepararSelecao('infra_navegador_selecionar');

  SessaoInfra::getInstance()->validarPermissao($_GET['acao']);
  
  PaginaInfra::getInstance()->salvarCamposPost(array('txtDthInicialNavegadores','txtDthFinalNavegadores'));

  if (isset($_POST['sbmPesquisar'])){
    PaginaInfra::getInstance()->salvarCampo('chkSinIgnorarVersao',$_POST['chkSinIgnorarVersao']);
  }
  
  switch($_GET['acao']){
    case 'infra_navegador_excluir':
      try{
        $arrStrIds = PaginaInfra::getInstance()->getArrStrItensSelecionados();
        $arrObjInfraNavegadorDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objInfraNavegadorDTO = new InfraNavegadorDTO();
          $objInfraNavegadorDTO->setDblIdInfraNavegador($arrStrIds[$i]);
          $arrObjInfraNavegadorDTO[] = $objInfraNavegadorDTO;
        }
        $objInfraNavegadorRN = new InfraNavegadorRN();
        $objInfraNavegadorRN->excluir($arrObjInfraNavegadorDTO);
        PaginaInfra::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaInfra::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

/* 
    case 'infra_navegador_desativar':
      try{
        $arrStrIds = PaginaInfra::getInstance()->getArrStrItensSelecionados();
        $arrObjInfraNavegadorDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objInfraNavegadorDTO = new InfraNavegadorDTO();
          $objInfraNavegadorDTO->setDblIdInfraNavegador($arrStrIds[$i]);
          $arrObjInfraNavegadorDTO[] = $objInfraNavegadorDTO;
        }
        $objInfraNavegadorRN = new InfraNavegadorRN();
        $objInfraNavegadorRN->desativar($arrObjInfraNavegadorDTO);
        PaginaInfra::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaInfra::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'infra_navegador_reativar':
      $strTitulo = 'Reativar Navegadores';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaInfra::getInstance()->getArrStrItensSelecionados();
          $arrObjInfraNavegadorDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objInfraNavegadorDTO = new InfraNavegadorDTO();
            $objInfraNavegadorDTO->setDblIdInfraNavegador($arrStrIds[$i]);
            $arrObjInfraNavegadorDTO[] = $objInfraNavegadorDTO;
          }
          $objInfraNavegadorRN = new InfraNavegadorRN();
          $objInfraNavegadorRN->reativar($arrObjInfraNavegadorDTO);
          PaginaInfra::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaInfra::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;

 */
    case 'infra_navegador_selecionar':
      $strTitulo = PaginaInfra::getInstance()->getTituloSelecao('Selecionar Navegador','Selecionar Navegadores');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='infra_navegador_cadastrar'){
        if (isset($_GET['id_infra_navegador'])){
          PaginaInfra::getInstance()->adicionarSelecionado($_GET['id_infra_navegador']);
        }
      }
      break;

    case 'infra_navegador_listar':
      $strTitulo = 'Navegadores';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  
  $arrComandos[] = '<button type="submit" accesskey="P" id="sbmPesquisar" name="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
  
    
  if ($_GET['acao'] == 'infra_navegador_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'infra_navegador_listar' || $_GET['acao'] == 'infra_navegador_selecionar'){ */
    $bolAcaoCadastrar = SessaoInfra::getInstance()->verificarPermissao('infra_navegador_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao=infra_navegador_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  /* } */

  $objInfraNavegadorDTO = new InfraNavegadorDTO();
  //$objInfraNavegadorDTO->retDblIdInfraNavegador();
  //$objInfraNavegadorDTO->retStrIdentificacao();
  //$objInfraNavegadorDTO->retStrVersao();
  //$objInfraNavegadorDTO->retStrUserAgent();
  //$objInfraNavegadorDTO->retStrIp();
  //$objInfraNavegadorDTO->retDthAcesso();
  
  $dthInicial = PaginaInfra::getInstance()->recuperarCampo('txtDthInicialNavegadores');
  if (!InfraString::isBolVazia($dthInicial)){
    $objInfraNavegadorDTO->setDthInicial($dthInicial);
  }
  
  $dthFinal = PaginaInfra::getInstance()->recuperarCampo('txtDthFinalNavegadores');
  if (!InfraString::isBolVazia($dthFinal)){
    $objInfraNavegadorDTO->setDthFinal($dthFinal);	
  }
  
  $objInfraNavegadorDTO->setStrSinIgnorarVersao(PaginaInfra::getInstance()->getCheckbox(PaginaInfra::getInstance()->recuperarCampo('chkSinIgnorarVersao','N')));
  
/* 
  if ($_GET['acao'] == 'infra_navegador_reativar'){
    //Lista somente inativos
    $objInfraNavegadorDTO->setBolExclusaoLogica(false);
    $objInfraNavegadorDTO->setStrSinAtivo('N');
  }
 */
  PaginaInfra::getInstance()->prepararOrdenacao($objInfraNavegadorDTO, 'TotalAcessos', InfraDTO::$TIPO_ORDENACAO_DESC);
  //PaginaInfra::getInstance()->prepararPaginacao($objInfraNavegadorDTO);

  try{
    $objInfraNavegadorRN = new InfraNavegadorRN();
    $arrObjInfraNavegadorDTO = $objInfraNavegadorRN->pesquisar($objInfraNavegadorDTO);
  }catch(Exception $e){
    PaginaInfra::getInstance()->processarExcecao($e);
  } 
  
  //PaginaInfra::getInstance()->processarPaginacao($objInfraNavegadorDTO);
  $numRegistros = count($arrObjInfraNavegadorDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='infra_navegador_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false; //SessaoInfra::getInstance()->verificarPermissao('infra_navegador_consultar');
      $bolAcaoAlterar = false; //SessaoInfra::getInstance()->verificarPermissao('infra_navegador_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='infra_navegador_reativar'){
      $bolAcaoReativar = SessaoInfra::getInstance()->verificarPermissao('infra_navegador_reativar');
      $bolAcaoConsultar = SessaoInfra::getInstance()->verificarPermissao('infra_navegador_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoInfra::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoInfra::getInstance()->verificarPermissao('infra_navegador_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false; //SessaoInfra::getInstance()->verificarPermissao('infra_navegador_consultar');
      $bolAcaoAlterar = false;//SessaoInfra::getInstance()->verificarPermissao('infra_navegador_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoInfra::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = false; //SessaoInfra::getInstance()->verificarPermissao('infra_navegador_excluir');
      $bolAcaoDesativar = false;//SessaoInfra::getInstance()->verificarPermissao('infra_navegador_desativar');
    }

    /* 
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoInfra::getInstance()->assinarLink('controlador.php?acao=infra_navegador_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoInfra::getInstance()->assinarLink('controlador.php?acao=infra_navegador_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
     */

    /*
    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoInfra::getInstance()->assinarLink('controlador.php?acao=infra_navegador_excluir&acao_origem='.$_GET['acao']);
    }
    */

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela')).'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    /* if ($_GET['acao']!='infra_navegador_reativar'){ */
      $strSumarioTabela = 'Tabela de Navegadores.';
      $strCaptionTabela = 'Navegadores';
    /* }else{
      $strSumarioTabela = 'Tabela de Navegadores Inativos.';
      $strCaptionTabela = 'Navegadores Inativos';
    } */

    $strResultado .= '<table width="75%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaInfra::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    
    /*
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaInfra::getInstance()->getThCheck().'</th>'."\n";
    }
    */
    
    $strResultado .= '<th class="infraTh" width="20%">'.PaginaInfra::getInstance()->getThOrdenacao($objInfraNavegadorDTO,'Acessos','TotalAcessos',$arrObjInfraNavegadorDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaInfra::getInstance()->getThOrdenacao($objInfraNavegadorDTO,'Identificação','Identificacao',$arrObjInfraNavegadorDTO).'</th>'."\n";
    
    if ($objInfraNavegadorDTO->getStrSinIgnorarVersao()=='N'){
      $strResultado .= '<th class="infraTh" width="10%">Versão</th>'."\n";
    }
    
    //$strResultado .= '<th class="infraTh">'.PaginaInfra::getInstance()->getThOrdenacao($objInfraNavegadorDTO,'User Agent','UserAgent',$arrObjInfraNavegadorDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaInfra::getInstance()->getThOrdenacao($objInfraNavegadorDTO,'IP','Ip',$arrObjInfraNavegadorDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaInfra::getInstance()->getThOrdenacao($objInfraNavegadorDTO,'Data/Hora','Acesso',$arrObjInfraNavegadorDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh" width="10%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      
      //if ($bolCheck){
      //  $strResultado .= '<td valign="top">'.PaginaInfra::getInstance()->getTrCheck($i,$arrObjInfraNavegadorDTO[$i]->getDblIdInfraNavegador(),$arrObjInfraNavegadorDTO[$i]->getStrIdentificacao()).'</td>';
      //}
      
      $strResultado .= '<td align="center">'.PaginaInfra::getInstance()->tratarHTML($arrObjInfraNavegadorDTO[$i]->getStrTotalFormatado()).'</td>';
      $strResultado .= '<td>'.PaginaInfra::getInstance()->tratarHTML($arrObjInfraNavegadorDTO[$i]->getStrIdentificacao()).'</td>';
      
      if ($objInfraNavegadorDTO->getStrSinIgnorarVersao()=='N'){
        $strResultado .= '<td align="center">'.PaginaInfra::getInstance()->tratarHTML($arrObjInfraNavegadorDTO[$i]->getStrVersao()).'</td>';
      }
      
      //$strResultado .= '<td>'.$arrObjInfraNavegadorDTO[$i]->getStrUserAgent().'</td>';
      //$strResultado .= '<td>'.$arrObjInfraNavegadorDTO[$i]->getStrIp().'</td>';
      //$strResultado .= '<td>'.$arrObjInfraNavegadorDTO[$i]->getDthAcesso().'</td>';
      
      /*
      $strResultado .= '<td align="center">';

      //$strResultado .= PaginaInfra::getInstance()->getAcaoTransportarItem($i,$arrObjInfraNavegadorDTO[$i]->getDblIdInfraNavegador());

      
      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoInfra::getInstance()->assinarLink('controlador.php?acao=infra_navegador_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_infra_navegador='.$arrObjInfraNavegadorDTO[$i]->getDblIdInfraNavegador())).'" tabindex="'.PaginaInfra::getInstance()->getProxTabTabela().'"><img src="'.PaginaInfra::getInstance()->getIconeConsultar().'" title="Consultar Navegador" alt="Consultar Navegador" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoInfra::getInstance()->assinarLink('controlador.php?acao=infra_navegador_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_infra_navegador='.$arrObjInfraNavegadorDTO[$i]->getDblIdInfraNavegador())).'" tabindex="'.PaginaInfra::getInstance()->getProxTabTabela().'"><img src="'.PaginaInfra::getInstance()->getIconeAlterar().'" title="Alterar Navegador" alt="Alterar Navegador" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjInfraNavegadorDTO[$i]->getDblIdInfraNavegador();
        $strDescricao = PaginaInfra::getInstance()->formatarParametrosJavaScript($arrObjInfraNavegadorDTO[$i]->getStrIdentificacao());
      }
      
 
      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaInfra::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaInfra::getInstance()->getProxTabTabela().'"><img src="'.PaginaInfra::getInstance()->getIconeDesativar().'" title="Desativar Navegador" alt="Desativar Navegador" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaInfra::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaInfra::getInstance()->getProxTabTabela().'"><img src="'.PaginaInfra::getInstance()->getIconeReativar().'" title="Reativar Navegador" alt="Reativar Navegador" class="infraImg" /></a>&nbsp;';
      }
 

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaInfra::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaInfra::getInstance()->getProxTabTabela().'"><img src="'.PaginaInfra::getInstance()->getIconeExcluir().'" title="Excluir Navegador" alt="Excluir Navegador" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td>';
      */
      $strResultado .= '</tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'infra_navegador_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

}catch(Exception $e){
  PaginaInfra::getInstance()->processarExcecao($e);
} 

PaginaInfra::getInstance()->montarDocType();
PaginaInfra::getInstance()->abrirHtml();
PaginaInfra::getInstance()->abrirHead();
PaginaInfra::getInstance()->montarMeta();
PaginaInfra::getInstance()->montarTitle(PaginaInfra::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaInfra::getInstance()->montarStyle();
PaginaInfra::getInstance()->abrirStyle();
?>
#lblDthInicialNavegadores {position:absolute;left:0%;top:10%;}
#txtDthInicialNavegadores {position:absolute;left:11%;top:0%;width:16%;}
#imgCalDthInicialNavegadores {position:absolute;left:28%;top:0%;}

#lblDthFinalNavegadores {position:absolute;left:31.5%;top:10%;}
#txtDthFinalNavegadores {position:absolute;left:34%;top:0%;width:16%;}
#imgCalDthFinalNavegadores {position:absolute;left:51%;top:0%;}

#divSinIgnorarVersao {position:absolute;left:60%;top:0%;}

<?
PaginaInfra::getInstance()->fecharStyle();
PaginaInfra::getInstance()->montarJavaScript();
PaginaInfra::getInstance()->abrirJavaScript();
?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='infra_navegador_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Navegador \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmInfraNavegadorLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmInfraNavegadorLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Navegador selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Navegadores selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmInfraNavegadorLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmInfraNavegadorLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Navegador \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmInfraNavegadorLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmInfraNavegadorLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Navegador selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Navegadores selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmInfraNavegadorLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmInfraNavegadorLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Navegador \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmInfraNavegadorLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmInfraNavegadorLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Navegador selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Navegadores selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmInfraNavegadorLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmInfraNavegadorLista').submit();
  }
}
<? } ?>

function validarForm(){
  
  if (infraTrim(document.getElementById('txtDthInicialNavegadores').value)!=''){
    if (!infraValidarDataHora(document.getElementById('txtDthInicialNavegadores'))){
      document.getElementById('txtDthInicialNavegadores').focus();
      return false;
    }
  }

  if (infraTrim(document.getElementById('txtDthFinalNavegadores').value)!=''){
    if (!infraValidarDataHora(document.getElementById('txtDthFinalNavegadores'))){
      document.getElementById('txtDthFinalNavegadores').focus();
      return false;
    }
  }
  
  return true;
}


<?
PaginaInfra::getInstance()->fecharJavaScript();
PaginaInfra::getInstance()->fecharHead();
PaginaInfra::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmInfraNavegadorLista" method="post" onsubmit="return validarForm();" action="<?=SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaInfra::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaInfra::getInstance()->abrirAreaDados('3em');
  ?>
  <label id="lblDthInicialNavegadores" for="txtDthInicialNavegadores" accesskey="" class="infraLabelOpcional" >Período:</label>
  <input type="text" id="txtDthInicialNavegadores" name="txtDthInicialNavegadores" onkeypress="return infraMascaraDataHora(this, event)" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($dthInicial)?>" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <img src="<?=PaginaInfra::getInstance()->getIconeCalendario()?>" id="imgCalDthInicialNavegadores" title="Selecionar Data/Hora Inicial" alt="Selecionar Data/Hora Inicial" class="infraImg" onclick="infraCalendario('txtDthInicialNavegadores', this, true,'<?=InfraData::getStrDataAtual().' 00:00'?>');" />
  
  <label id="lblDthFinalNavegadores" for="txtDthFinalNavegadores" accesskey="" class="infraLabelOpcional" >a</label>
  <input type="text" id="txtDthFinalNavegadores" name="txtDthFinalNavegadores" onkeypress="return infraMascaraDataHora(this, event)" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($dthFinal)?>" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <img src="<?=PaginaInfra::getInstance()->getIconeCalendario()?>" id="imgCalDthFinalNavegadores" title="Selecionar Data/Hora Final" alt="Selecionar Data/Hora Final" class="infraImg" onclick="infraCalendario('txtDthFinalNavegadores', this, true, '<?=InfraData::getStrDataAtual().' 23:59'?>');" />
  
  <div id="divSinIgnorarVersao" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinIgnorarVersao" name="chkSinIgnorarVersao" class="infraCheckbox" <?=PaginaInfra::getInstance()->setCheckbox($objInfraNavegadorDTO->getStrSinIgnorarVersao())?>  tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>"/>
    <label id="lblSinIgnorarVersao" for="chkSinIgnorarVersao" accesskey="" class="infraLabelCheckbox">Ignorar Versão</label>
  </div>
  
  <?
  PaginaInfra::getInstance()->fecharAreaDados();
  PaginaInfra::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaInfra::getInstance()->montarAreaDebug();
  PaginaInfra::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaInfra::getInstance()->fecharBody();
PaginaInfra::getInstance()->fecharHtml();
?>