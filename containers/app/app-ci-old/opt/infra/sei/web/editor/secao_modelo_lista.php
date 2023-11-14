<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/11/2011 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id: secao_modelo_lista.php 10161 2015-07-24 13:58:56Z mga $
*/

try {
  require_once dirname(__FILE__).'/../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('secao_modelo_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $strParametros = '';
  if(isset($_GET['id_modelo'])){
    $strParametros .= '&id_modelo='.$_GET['id_modelo'];
  }

  //PaginaSEI::getInstance()->salvarCamposPost(array('selModelo'));

  switch($_GET['acao']){
    case 'secao_modelo_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjSecaoModeloDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objSecaoModeloDTO = new SecaoModeloDTO();
          $objSecaoModeloDTO->setNumIdSecaoModelo($arrStrIds[$i]);
          $objSecaoModeloDTO->setBolExclusaoLogica(false);
          $arrObjSecaoModeloDTO[] = $objSecaoModeloDTO;
        }
        $objSecaoModeloRN = new SecaoModeloRN();
        $objSecaoModeloRN->excluir($arrObjSecaoModeloDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].$strParametros));
      die;


    case 'secao_modelo_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjSecaoModeloDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objSecaoModeloDTO = new SecaoModeloDTO();
          $objSecaoModeloDTO->setNumIdSecaoModelo($arrStrIds[$i]);
          $arrObjSecaoModeloDTO[] = $objSecaoModeloDTO;
        }
        $objSecaoModeloRN = new SecaoModeloRN();
        $objSecaoModeloRN->desativar($arrObjSecaoModeloDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].$strParametros));
      die;

    case 'secao_modelo_reativar':
      $strTitulo = 'Reativar Seções';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjSecaoModeloDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objSecaoModeloDTO = new SecaoModeloDTO();
            $objSecaoModeloDTO->setNumIdSecaoModelo($arrStrIds[$i]);
            $arrObjSecaoModeloDTO[] = $objSecaoModeloDTO;
          }
          $objSecaoModeloRN = new SecaoModeloRN();
          $objSecaoModeloRN->reativar($arrObjSecaoModeloDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].$strParametros));
        die;
      }
      break;


    case 'secao_modelo_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Seção','Selecionar Seções');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='secao_modelo_cadastrar'){
        if (isset($_GET['id_secao_modelo'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_secao_modelo']);
        }
      }
      break;

    case 'secao_modelo_listar':
      $strTitulo = 'Seções';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'secao_modelo_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'secao_modelo_listar' || $_GET['acao'] == 'secao_modelo_selecionar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('secao_modelo_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNova" value="Nova" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=secao_modelo_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].$strParametros).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ova</button>';
    }
  /* } */
  if(SessaoSEI::getInstance()->verificarPermissao('editor_simular')){
    $arrComandos[] = '<button type="button" accesskey="V" id="btnVisualizar" value="Visualizar" onclick="infraAbrirJanela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=editor_simular&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].$strParametros).'\',\'Teste de modelo\',800,600);" class="infraButton"><span class="infraTeclaAtalho">V</span>isualizar Modelo</button>';
  }


  $objSecaoModeloDTO = new SecaoModeloDTO();
  $objSecaoModeloDTO->retNumIdSecaoModelo();
  $objSecaoModeloDTO->retStrNome();
  //$objSecaoModeloDTO->retStrConteudo();
  $objSecaoModeloDTO->retNumOrdem();
  $objSecaoModeloDTO->retStrSinSomenteLeitura();
  $objSecaoModeloDTO->retStrSinAssinatura();
  $objSecaoModeloDTO->retStrSinPrincipal();
  $objSecaoModeloDTO->retStrSinDinamica();
  $objSecaoModeloDTO->retStrSinCabecalho();
  $objSecaoModeloDTO->retStrSinRodape();
  $objSecaoModeloDTO->retStrSinHtml();
  $objSecaoModeloDTO->retStrNomeModelo();
  $numIdModelo = $_GET['id_modelo'];
  if ($numIdModelo!==''){
    $objSecaoModeloDTO->setNumIdModelo($numIdModelo);
  }
  $objModeloDTO = new ModeloDTO();
  $objModeloDTO->setBolExclusaoLogica(false);
  $objModeloDTO->retStrNome();
  $objModeloDTO->setNumIdModelo($numIdModelo);
  $objModeloRN = new ModeloRN();
  $objModeloDTO = $objModeloRN->consultar($objModeloDTO);
  $strModelo = $objModeloDTO->getStrNome();
  //$objModeloDTO->montar();


  $objSecaoModeloDTO->setBolExclusaoLogica(false);
  $objSecaoModeloDTO->retStrSinAtivo();

  $objSecaoModeloDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);

  //PaginaSEI::getInstance()->prepararPaginacao($objSecaoModeloDTO);

  $objSecaoModeloRN = new SecaoModeloRN();
  $arrObjSecaoModeloDTO = $objSecaoModeloRN->listar($objSecaoModeloDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objSecaoModeloDTO);
  $numRegistros = count($arrObjSecaoModeloDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='secao_modelo_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('secao_modelo_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('secao_modelo_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
      //$bolAcaoRelSecaoModeloEstiloLupas=false;
     }else if ($_GET['acao']=='secao_modelo_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('secao_modelo_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('secao_modelo_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('secao_modelo_excluir');
      $bolAcaoDesativar = false;
     }else{
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('secao_modelo_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('secao_modelo_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('secao_modelo_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('secao_modelo_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('secao_modelo_desativar');
    }


    if ($bolAcaoDesativar){
      $bolCheck = true;
//      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=secao_modelo_desativar&acao_origem='.$_GET['acao'].$strParametros);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
//      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=secao_modelo_reativar&acao_origem='.$_GET['acao'].$strParametros.'&acao_confirmada=sim');
    }


    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=secao_modelo_excluir&acao_origem='.$_GET['acao'].$strParametros);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela')).'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    /* if ($_GET['acao']!='secao_modelo_reativar'){ */
      $strSumarioTabela = 'Tabela de Seções.';
      $strCaptionTabela = 'Seções';
    /* }else{
      $strSumarioTabela = 'Tabela de Seções Inativas.';
      $strCaptionTabela = 'Seções Inativas';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">Nome</th>'."\n";
    //$strResultado .= '<th class="infraTh">Conteúdo</th>'."\n";
    $strResultado .= '<th class="infraTh" width="8%">Ordem</th>'."\n";
    $strResultado .= '<th class="infraTh" width="8%">Cabeçalho</th>'."\n";
    $strResultado .= '<th class="infraTh" width="8%">Rodapé</th>'."\n";
    $strResultado .= '<th class="infraTh" width="8%">Principal</th>'."\n";
    $strResultado .= '<th class="infraTh" width="8%">Assinatura</th>'."\n";
    $strResultado .= '<th class="infraTh" width="8%">Somente Leitura</th>'."\n";
    $strResultado .= '<th class="infraTh" width="8%">Dinâmica</th>'."\n";
    $strResultado .= '<th class="infraTh" width="8%">HTML</th>'."\n";
    //$strResultado .= '<th class="infraTh">Modelo</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){
    	if ($arrObjSecaoModeloDTO[$i]->getStrSinAtivo()=='S'){
      	$strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
    	} else {
      	$strCssTr = '<tr class="trVermelha">';
    	}
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="middle">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjSecaoModeloDTO[$i]->getNumIdSecaoModelo(),$arrObjSecaoModeloDTO[$i]->getNumOrdem()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjSecaoModeloDTO[$i]->getStrNome()).'</td>';
      //$strResultado .= '<td>'.$arrObjSecaoModeloDTO[$i]->getStrConteudo().'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjSecaoModeloDTO[$i]->getNumOrdem()).'</td>';
      $strResultado .= '<td align="center">'.($arrObjSecaoModeloDTO[$i]->getStrSinCabecalho()=='S'?'X':'&nbsp;').'</td>';
      $strResultado .= '<td align="center">'.($arrObjSecaoModeloDTO[$i]->getStrSinRodape()=='S'?'X':'&nbsp;').'</td>';
      $strResultado .= '<td align="center">'.($arrObjSecaoModeloDTO[$i]->getStrSinPrincipal()=='S'?'X':'&nbsp;').'</td>';
      $strResultado .= '<td align="center">'.($arrObjSecaoModeloDTO[$i]->getStrSinAssinatura()=='S'?'X':'&nbsp;').'</td>';
      $strResultado .= '<td align="center">'.($arrObjSecaoModeloDTO[$i]->getStrSinSomenteLeitura()=='S'?'X':'&nbsp;').'</td>';
      $strResultado .= '<td align="center">'.($arrObjSecaoModeloDTO[$i]->getStrSinDinamica()=='S'?'X':'&nbsp;').'</td>';
      $strResultado .= '<td align="center">'.($arrObjSecaoModeloDTO[$i]->getStrSinHtml()=='S'?'X':'&nbsp;').'</td>';
      //$strResultado .= '<td align="center">'.$arrObjSecaoModeloDTO[$i]->getStrNomeModelo().'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjSecaoModeloDTO[$i]->getNumIdSecaoModelo());
      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
      	$strId = $arrObjSecaoModeloDTO[$i]->getNumIdSecaoModelo();
      	$strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjSecaoModeloDTO[$i]->getStrNome());
      }
      if ($arrObjSecaoModeloDTO[$i]->getStrSinAtivo()=='S'){

	      if ($bolAcaoConsultar){
	        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=secao_modelo_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_secao_modelo='.$arrObjSecaoModeloDTO[$i]->getNumIdSecaoModelo().$strParametros).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Seção" alt="Consultar Seção" class="infraImg" /></a>&nbsp;';
	      }

	      if ($bolAcaoAlterar){
	        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=secao_modelo_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_secao_modelo='.$arrObjSecaoModeloDTO[$i]->getNumIdSecaoModelo().$strParametros).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Seção" alt="Alterar Seção" class="infraImg" /></a>&nbsp;';
	      }

	      if ($bolAcaoReativar){
	        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Seção" alt="Desativar Seção" class="infraImg" /></a>&nbsp;';
	      }
      } else {
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Seção" alt="Reativar Seção" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Seção" alt="Excluir Seção" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'secao_modelo_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::getInstance()->montarAncora($_GET['id_modelo'])).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  //$strItensSelModelo = ModeloINT::montarSelectNome('','Todos',$numIdModelo);
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
#lblModelo {position:absolute;left:0%;top:0%;width:50%;}
#txtModelo {position:absolute;left:0%;top:40%;width:50%;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='secao_modelo_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação da Seção \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmSecaoModeloLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmSecaoModeloLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Seção selecionada.');
    return;
  }
  if (confirm("Confirma desativação das Seções selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmSecaoModeloLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmSecaoModeloLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação da Seção \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmSecaoModeloLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmSecaoModeloLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Seção selecionada.');
    return;
  }
  if (confirm("Confirma reativação das Seções selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmSecaoModeloLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmSecaoModeloLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão da Seção \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmSecaoModeloLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmSecaoModeloLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Seção selecionada.');
    return;
  }
  if (confirm("Confirma exclusão das Seções selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmSecaoModeloLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmSecaoModeloLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmSecaoModeloLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>
  <label id="lblModelo" class="infraLabelObrigatorio">Modelo:</label>
  <input type="text" id="txtModelo" name="txtModelo" readonly="readonly" class="infraText infraReadOnly" value=" <?=PaginaSEI::tratarHTML($strModelo)?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>