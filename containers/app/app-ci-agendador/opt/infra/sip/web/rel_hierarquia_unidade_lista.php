<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/12/2006 - criado por mga
*
*
*/

try {
  require_once dirname(__FILE__).'/Sip.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  //SessaoSip::getInstance()->validarSessao();
  SessaoSip::getInstance()->validarLink();

  PaginaSip::getInstance()->prepararSelecao('rel_hierarquia_unidade_selecionar');
  
  SessaoSip::getInstance()->validarPermissao($_GET['acao']);
	
  PaginaSip::getInstance()->salvarCamposPost(array('selHierarquia','hdnIdUnidadeRamificacao','txtUnidadeRamificacao'));
  
  switch($_GET['acao']){
    case 'rel_hierarquia_unidade_excluir':
      try{
        $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
        $arrObjRelHierarquiaUnidadeDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $arrId = explode('-',$arrStrIds[$i]);
					$objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
					$objRelHierarquiaUnidadeDTO->setNumIdUnidade($arrId[0]);
					$objRelHierarquiaUnidadeDTO->setNumIdHierarquia($arrId[1]);
					$arrObjRelHierarquiaUnidadeDTO[] = $objRelHierarquiaUnidadeDTO;
        }
				$objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
				$objRelHierarquiaUnidadeRN->excluir($arrObjRelHierarquiaUnidadeDTO);
        PaginaSip::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSip::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;


    case 'rel_hierarquia_unidade_desativar':
      try{
        $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
        $arrObjRelHierarquiaUnidadeDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $arrId = explode('-',$arrStrIds[$i]);
					$objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
					$objRelHierarquiaUnidadeDTO->setNumIdUnidade($arrId[0]);
					$objRelHierarquiaUnidadeDTO->setNumIdHierarquia($arrId[1]);
					$arrObjRelHierarquiaUnidadeDTO[] = $objRelHierarquiaUnidadeDTO;
        }
				$objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
				$objRelHierarquiaUnidadeRN->desativar($arrObjRelHierarquiaUnidadeDTO);
        PaginaSip::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSip::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;
      
    case 'rel_hierarquia_unidade_reativar':
      try{
        $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
        $arrObjRelHierarquiaUnidadeDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $arrId = explode('-',$arrStrIds[$i]);
					$objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
					$objRelHierarquiaUnidadeDTO->setNumIdUnidade($arrId[0]);
					$objRelHierarquiaUnidadeDTO->setNumIdHierarquia($arrId[1]);
					$arrObjRelHierarquiaUnidadeDTO[] = $objRelHierarquiaUnidadeDTO;
        }
				$objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
				$objRelHierarquiaUnidadeRN->reativar($arrObjRelHierarquiaUnidadeDTO);
        PaginaSip::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSip::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;
      break;
      
    case 'rel_hierarquia_unidade_selecionar':
      $strTitulo = PaginaGedoc::getInstance()->getTituloSelecao('Selecionar Unidade da Hierarquia','Selecionar Unidades da Hierarquia');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='rel_hierarquia_unidade_cadastrar'){
        if (isset($_GET['id_unidade']) && isset($_GET['id_hierarquia'])){
          PaginaGedoc::getInstance()->adicionarSelecionado($_GET['id_unidade'].'-'.$_GET['id_hierarquia']);
        }
      }
      break;

    case 'rel_hierarquia_unidade_listar':
      $strTitulo = 'Montar Hierarquia';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  
  $arrComandos[] = '<input type="submit" id="sbmPesquisar" value="Pesquisar" class="infraButton" />';
  
  $bolAcaoCadastrar = SessaoSip::getInstance()->verificarPermissao('rel_hierarquia_unidade_cadastrar');
  if ($bolAcaoCadastrar){
		$arrComandos[] = '<input type="button" id="btnNova" value="Adicionar Unidade" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=rel_hierarquia_unidade_cadastrar&acao_origem='.$_GET['acao']).'\';" class="infraButton" />';
  }
	
  $objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
  $objRelHierarquiaUnidadeDTO->retStrRamificacao();
  $objRelHierarquiaUnidadeDTO->retStrSiglaUnidade();
	$objRelHierarquiaUnidadeDTO->retNumIdOrgaoUnidade();
	//$objRelHierarquiaUnidadeDTO->retArrUnidadesSuperiores();
	//$objRelHierarquiaUnidadeDTO->retArrUnidadesInferiores();
	$objRelHierarquiaUnidadeDTO->retStrSinAtivo();
	
	$numIdHierarquia = PaginaSip::getInstance()->recuperarCampo('selHierarquia','null');
  $objRelHierarquiaUnidadeDTO->setNumIdHierarquia($numIdHierarquia);
  
  //$strRamificacaoPesquisa = trim(PaginaSip::getInstance()->recuperarCampo('txtRamificacao'));
  //if (trim($strRamificacaoPesquisa)!=''){
  //	$objRelHierarquiaUnidadeDTO->setStrRamificacao($strRamificacaoPesquisa);
  //}

  $numIdUnidadeRamificacao = PaginaSip::getInstance()->recuperarCampo('hdnIdUnidadeRamificacao');
  $strUnidadeRamificacao = PaginaSip::getInstance()->recuperarCampo('txtUnidadeRamificacao');

  if (!InfraString::isBolVazia($numIdUnidadeRamificacao)) {
    $objRelHierarquiaUnidadeDTO->setNumIdUnidade($numIdUnidadeRamificacao);
  }

  $objRelHierarquiaUnidadeDTO->setBolExclusaoLogica(false);
  
  PaginaSip::getInstance()->prepararOrdenacao($objRelHierarquiaUnidadeDTO, 'Ramificacao', InfraDTO::$TIPO_ORDENACAO_ASC);
	
	PaginaSip::getInstance()->prepararPaginacao($objRelHierarquiaUnidadeDTO);
	
  $objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
  $arrObjRelHierarquiaUnidadeDTO = $objRelHierarquiaUnidadeRN->listarHierarquia($objRelHierarquiaUnidadeDTO);
  
  PaginaSip::getInstance()->processarPaginacao($objRelHierarquiaUnidadeDTO);
	
  $numRegistros = count($arrObjRelHierarquiaUnidadeDTO);
	
  if ($numRegistros > 0){
    

    $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('rel_hierarquia_unidade_alterar');
    $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('rel_hierarquia_unidade_excluir');
    $bolAcaoDesativar = SessaoSip::getInstance()->verificarPermissao('rel_hierarquia_unidade_desativar');
    $bolAcaoReativar = SessaoSip::getInstance()->verificarPermissao('rel_hierarquia_unidade_reativar');

    //Montar ações múltiplas
    $bolCheck = true;
    
    
    if ($bolAcaoExcluir){
      //$arrComandos[] = '<input type="button" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton" />';
      $strLinkExcluir = SessaoSip::getInstance()->assinarLink('controlador.php?acao=rel_hierarquia_unidade_excluir&acao_origem='.$_GET['acao'].'');
    }

    if ($bolAcaoDesativar){
      //$arrComandos[] = '<input type="button" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton" />';
      $strLinkDesativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=rel_hierarquia_unidade_desativar&acao_origem='.$_GET['acao'].'');
    }

    if ($bolAcaoReativar){
      //$arrComandos[] = '<input type="button" id="btnDesativar" value="Desativar" onclick="acaoReativacaoMultipla();" class="infraButton" />';
      $strLinkReativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=rel_hierarquia_unidade_reativar&acao_origem='.$_GET['acao'].'');
    }
    
		$arrComandos[] = '<input type="button" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton" />';
    
    $strResultado = '';
    $strResultado .= '<table width="99%" class="infraTable" summary="Tabela de Unidades na Hierarquia">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSip::getInstance()->gerarCaptionTabela('Unidades na Hierarquia',$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSip::getInstance()->getThCheck().'</th>';
    }
    $strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objRelHierarquiaUnidadeDTO,'Ramificação','Ramificacao',$arrObjRelHierarquiaUnidadeDTO).'</th>';
    $strResultado .= '<th class="infraTh" width="15%">'.PaginaSip::getInstance()->getThOrdenacao($objRelHierarquiaUnidadeDTO,'Unidade','SiglaUnidade',$arrObjRelHierarquiaUnidadeDTO).'</th>';
    $strResultado .= '<th class="infraTh" width="15%">'.PaginaSip::getInstance()->getThOrdenacao($objRelHierarquiaUnidadeDTO,'Órgão','SiglaOrgaoUnidade',$arrObjRelHierarquiaUnidadeDTO).'</th>';
    
    //$strResultado .= '<th class="infraTh">Superiores</th>';    
    //$strResultado .= '<th class="infraTh">Inferiores</th>';    
    //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objRelHierarquiaUnidadeDTO,'Data Início','DataInicio',$arrObjRelHierarquiaUnidadeDTO).'</th>';
    //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objRelHierarquiaUnidadeDTO,'Data Fim','DataFim',$arrObjRelHierarquiaUnidadeDTO).'</th>';
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>';
    $strResultado .= '</tr>'."\n";
    for($i = 0;$i < $numRegistros; $i++){

      if ($arrObjRelHierarquiaUnidadeDTO[$i]->getStrSinAtivo()=='S'){
        if ( ($i+2) % 2 ) {
          $strResultado .= '<tr class="infraTrEscura">';
        } else {
          $strResultado .= '<tr class="infraTrClara">';
        }
      }else{
        $strResultado .= '<tr class="trVermelha">';
      }
      
      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSip::getInstance()->getTrCheck($i,$arrObjRelHierarquiaUnidadeDTO[$i]->getNumIdUnidade().'-'.$arrObjRelHierarquiaUnidadeDTO[$i]->getNumIdHierarquia(),$arrObjRelHierarquiaUnidadeDTO[$i]->getStrSiglaUnidade()).'</td>';
      }
      $strResultado .= '<td valign="top">'.PaginaSip::tratarHTML($arrObjRelHierarquiaUnidadeDTO[$i]->getStrRamificacao()).'</td>';
      /*
      $strResultado .= '<td valign="top" align="center">'.PaginaSip::tratarHTML($arrObjRelHierarquiaUnidadeDTO[$i]->getStrSiglaUnidade()).'</td>';
      $strResultado .= '<td valign="top" align="center">'.PaginaSip::tratarHTML($arrObjRelHierarquiaUnidadeDTO[$i]->getStrSiglaOrgaoUnidade()).'</td>';
      */
      
			$strResultado .= '<td align="center">';
			$strResultado .= '<a alt="'.PaginaSip::tratarHTML($arrObjRelHierarquiaUnidadeDTO[$i]->getStrDescricaoUnidade()).'" title="'.PaginaSip::tratarHTML($arrObjRelHierarquiaUnidadeDTO[$i]->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSip::tratarHTML($arrObjRelHierarquiaUnidadeDTO[$i]->getStrSiglaUnidade()).'</a>';
			$strResultado .= '</td>';
			
			$strResultado .= '<td align="center">';
			$strResultado .= '<a alt="'.PaginaSip::tratarHTML($arrObjRelHierarquiaUnidadeDTO[$i]->getStrDescricaoOrgaoUnidade()).'" title="'.PaginaSip::tratarHTML($arrObjRelHierarquiaUnidadeDTO[$i]->getStrDescricaoOrgaoUnidade()).'" class="ancoraSigla">'.PaginaSip::tratarHTML($arrObjRelHierarquiaUnidadeDTO[$i]->getStrSiglaOrgaoUnidade()).'</a>';
			$strResultado .= '</td>';
      

      /*
      $strResultado .= '<td valign="top">';
      
      $arrUnidadesSuperiores = $arrObjRelHierarquiaUnidadeDTO[$i]->getArrUnidadesSuperiores();
      for($j=0;$j<count($arrUnidadesSuperiores);$j++){
        if ($j>0){
          $strResultado .= '<br />';
        }
        $strResultado .= $arrUnidadesSuperiores[$j][1];
      }
      
      $strResultado .= '&nbsp;</td>';
      
      
      $strResultado .= '<td valign="top">';
      
      $arrUnidadesInferiores = $arrObjRelHierarquiaUnidadeDTO[$i]->getArrUnidadesInferiores();
      for($j=0;$j<count($arrUnidadesInferiores);$j++){ 
        if ($j>0){
          $strResultado .= '<br />';
        }
        $strResultado .= $arrUnidadesInferiores[$j][1];
      }
      
      $strResultado .= '&nbsp;</td>';
      */
      
      //$strResultado .= '<td align="center">'.$arrObjRelHierarquiaUnidadeDTO[$i]->getDtaDataInicio().'</td>';
      //$strResultado .= '<td align="center">'.$arrObjRelHierarquiaUnidadeDTO[$i]->getDtaDataFim().'</td>';
      $strResultado .= '<td valign="top" align="center">';
      
      if ($bolAcaoCadastrar && $arrObjRelHierarquiaUnidadeDTO[$i]->getStrSinAtivo()=='S'){
        $strResultado .= '<a href="'.SessaoSip::getInstance()->assinarLink('controlador.php?acao=rel_hierarquia_unidade_cadastrar&acao_origem='.$_GET['acao'].'&id_hierarquia_superior='.$arrObjRelHierarquiaUnidadeDTO[$i]->getNumIdHierarquia().'&id_unidade_superior='.$arrObjRelHierarquiaUnidadeDTO[$i]->getNumIdUnidade()).'" tabindex="'.PaginaSip::getInstance()->getProxTabDados().'"><img src="'.PaginaSip::getInstance()->getIconeMais().'" title="Adicionar Subunidade" alt="Adicionar Subunidade" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar && $arrObjRelHierarquiaUnidadeDTO[$i]->getStrSinAtivo()=='S'){
        $strResultado .= '<a href="'.SessaoSip::getInstance()->assinarLink('controlador.php?acao=rel_hierarquia_unidade_alterar&acao_origem='.$_GET['acao'].'&id_hierarquia='.$arrObjRelHierarquiaUnidadeDTO[$i]->getNumIdHierarquia().'&id_unidade='.$arrObjRelHierarquiaUnidadeDTO[$i]->getNumIdUnidade()).'" tabindex="'.PaginaSip::getInstance()->getProxTabDados().'"><img src="'.PaginaSip::getInstance()->getIconeAlterar().'" title="Alterar Unidade na Hierarquia" alt="Alterar Unidade na Hierarquia" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjRelHierarquiaUnidadeDTO[$i]->getNumIdUnidade().'-'.$arrObjRelHierarquiaUnidadeDTO[$i]->getNumIdHierarquia();
        $strDescricao = PaginaSip::formatarParametrosJavaScript($arrObjRelHierarquiaUnidadeDTO[$i]->getStrSiglaUnidade());
      }

      if ($bolAcaoDesativar && $arrObjRelHierarquiaUnidadeDTO[$i]->getStrSinAtivo()=='S'){
        $strResultado .= '<a onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSip::getInstance()->getProxTabDados().'"><img src="'.PaginaSip::getInstance()->getIconeDesativar().'" title="Desativar Unidade na Hierarquia" alt="Desativar Unidade na Hierarquia" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar && $arrObjRelHierarquiaUnidadeDTO[$i]->getStrSinAtivo()=='N'){
        $strResultado .= '<a onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSip::getInstance()->getProxTabDados().'"><img src="'.PaginaSip::getInstance()->getIconeReativar().'" title="Reativar Unidade na Hierarquia" alt="Reativar Unidade na Hierarquia" class="infraImg" /></a>&nbsp;';
      }
      
      if ($bolAcaoExcluir /* && count($arrObjRelHierarquiaUnidadeDTO[$i]->getArrUnidadesInferiores())==0 */){
        $strResultado .= '<a onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSip::getInstance()->getProxTabDados().'"><img src="'.PaginaSip::getInstance()->getIconeExcluir().'" title="Excluir Unidade na Hierarquia" alt="Excluir Unidade na Hierarquia" class="infraImg" /></a>&nbsp;';
      }
      
      
      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  $arrComandos[] = '<input type="button" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton" />';
  
	$strItensSelHierarquia = HierarquiaINT::montarSelectNome('null','&nbsp;',$numIdHierarquia);

  $strLinkAjaxUnidadeRamificacao = SessaoSip::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=unidade_ramificacao_auto_completar');
	
}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
} 

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - Montar Hierarquia');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
#lblHierarquia {position:absolute;left:0%;top:0%;width:40%;}
#selHierarquia {position:absolute;left:0%;top:20%;width:40%;}

#lblUnidadeRamificacao {position:absolute;left:0%;top:50%;width:60%;}
#txtUnidadeRamificacao {position:absolute;left:0%;top:70%;width:60%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

var objAjaxUnidadeRamificacao = null;

function inicializar(){
  if ('<?=$_GET['acao']?>'=='rel_hierarquia_unidade_selecionar'){
    infraReceberSelecao();
  }

  objAjaxUnidadeRamificacao = new infraAjaxAutoCompletar('hdnIdUnidadeRamificacao','txtUnidadeRamificacao','<?=$strLinkAjaxUnidadeRamificacao?>');
  objAjaxUnidadeRamificacao.carregando = true;
  objAjaxUnidadeRamificacao.prepararExecucao = function(){
    if (!infraSelectSelecionado('selHierarquia')){
      alert('Selecione a Hierarquia.');
      document.getElementById('selHierarquia').focus();
      return false;
    }
    return 'sigla='+document.getElementById('txtUnidadeRamificacao').value + '&id_hierarquia='+document.getElementById('selHierarquia').value;
  };

  objAjaxUnidadeRamificacao.processarResultado = function(id,descricao,complemento){
    if (id!='' && !objAjaxUnidadeRamificacao.carregando){
      document.getElementById('frmRelHierarquiaUnidadeLista').submit();
    }
  };

  objAjaxUnidadeRamificacao.selecionar('<?=$numIdUnidadeRamificacao;?>','<?=PaginaSip::getInstance()->formatarParametrosJavascript($strUnidadeRamificacao,false)?>');
  objAjaxUnidadeRamificacao.carregando = false;

  infraEfeitoTabelas();
}

<? if ($bolAcaoExcluir){ ?>
     function acaoExcluir(id,desc){
       if (confirm("Confirma exclusão da unidade \""+desc+"\" na hierarquia?")){
         document.getElementById('hdnInfraItemId').value=id;
         document.getElementById('frmRelHierarquiaUnidadeLista').action='<?=$strLinkExcluir?>';
         document.getElementById('frmRelHierarquiaUnidadeLista').submit();
       }
     }

     function acaoExclusaoMultipla(){
       if (document.getElementById('hdnInfraItensSelecionados').value==''){
         alert('Nenhuma unidade selecionada.');
         return;
       }
       if (confirm("Confirma exclusão das unidades selecionadas na hierarquia?")){
         document.getElementById('hdnInfraItemId').value='';
         document.getElementById('frmRelHierarquiaUnidadeLista').action='<?=$strLinkExcluir?>';
         document.getElementById('frmRelHierarquiaUnidadeLista').submit();
       }
     }
<? } ?>

<? if ($bolAcaoDesativar){ ?>
     function acaoDesativar(id,desc){
       if (confirm("Confirma desativação da unidade \""+desc+"\" na hierarquia?")){
         document.getElementById('hdnInfraItemId').value=id;
         document.getElementById('frmRelHierarquiaUnidadeLista').action='<?=$strLinkDesativar?>';
         document.getElementById('frmRelHierarquiaUnidadeLista').submit();
       }
     }

     function acaoDesativacaoMultipla(){
       if (document.getElementById('hdnInfraItensSelecionados').value==''){
         alert('Nenhuma unidade selecionada.');
         return;
       }
       if (confirm("Confirma desativação das unidades selecionadas na hierarquia?")){
         document.getElementById('hdnInfraItemId').value='';
         document.getElementById('frmRelHierarquiaUnidadeLista').action='<?=$strLinkDesativar?>';
         document.getElementById('frmRelHierarquiaUnidadeLista').submit();
       }
     }
<? } ?>

<? if ($bolAcaoReativar){ ?>
     function acaoReativar(id,desc){
       if (confirm("Confirma reativação da unidade \""+desc+"\" na hierarquia?")){
         document.getElementById('hdnInfraItemId').value=id;
         document.getElementById('frmRelHierarquiaUnidadeLista').action='<?=$strLinkReativar?>';
         document.getElementById('frmRelHierarquiaUnidadeLista').submit();
       }
     }

     function acaoReativacaoMultipla(){
       if (document.getElementById('hdnInfraItensSelecionados').value==''){
         alert('Nenhuma unidade selecionada.');
         return;
       }
       if (confirm("Confirma reativação das unidades selecionadas na hierarquia?")){
         document.getElementById('hdnInfraItemId').value='';
         document.getElementById('frmRelHierarquiaUnidadeLista').action='<?=$strLinkReativar?>';
         document.getElementById('frmRelHierarquiaUnidadeLista').submit();
       }
     }
<? } ?>

function trocarHierarquia(){
  objAjaxUnidadeRamificacao.limpar();
  document.getElementById('frmRelHierarquiaUnidadeLista').submit();
}

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody('Montar Hierarquia','onload="inicializar();"');
?>
<form id="frmRelHierarquiaUnidadeLista" method="post" action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSip::getInstance()->montarBarraLocalizacao('Montar Hierarquia');
  PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSip::getInstance()->abrirAreaDados('10em');
  ?>
	
  <label id="lblHierarquia" for="selHierarquia" accesskey="H" class="infraLabelOpcional"><span class="infraTeclaAtalho">H</span>ierarquia:</label>
  <select id="selHierarquia" name="selHierarquia" class="infraSelect" onchange="trocarHierarquia()" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" >
  <?=$strItensSelHierarquia?>
  </select>
	
  <label id="lblUnidadeRamificacao" for="txtUnidadeRamificacao" class="infraLabelOpcional">Unidade da Ramificação:</label>
  <input type="text" id="txtUnidadeRamificacao" name="txtUnidadeRamificacao" class="infraText" value="<?=PaginaSip::tratarHTML($strUnidadeRamificacao)?>" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />
  <input type="hidden" id="hdnIdUnidadeRamificacao" name="hdnIdUnidadeRamificacao" value="<?=PaginaSip::tratarHTML($numIdUnidadeRamificacao)?>" />
	
  <?
  PaginaSip::getInstance()->fecharAreaDados();
  PaginaSip::getInstance()->montarAreaTabela($strResultado,$numRegistros,true);
  //PaginaSip::getInstance()->montarAreaDebug();
  PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>