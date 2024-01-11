<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/11/2006 - criado por mga
*
*
*/

try {
  require_once dirname(__FILE__).'/Sip.php';

  session_start();
	
  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSip::getInstance()->validarLink();

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'orgao_excluir':
		  try{

        $arrObjOrgaoDTO = array();
        $arrStrId = PaginaSip::getInstance()->getArrStrItensSelecionados();
        for ($i=0;$i<count($arrStrId);$i++){
          $objOrgaoDTO = new OrgaoDTO();
          $objOrgaoDTO->setNumIdOrgao($arrStrId[$i]);
          $arrObjOrgaoDTO[] = $objOrgaoDTO;
        }
        $objOrgaoRN = new OrgaoRN();
        $objOrgaoRN->excluir($arrObjOrgaoDTO);

		  }catch(Exception $e){
				PaginaSip::getInstance()->processarExcecao($e);
			}
      header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'orgao_desativar':
		  try{

        $arrObjOrgaoDTO = array();
        $arrStrId = PaginaSip::getInstance()->getArrStrItensSelecionados();
        for ($i=0;$i<count($arrStrId);$i++){
          $objOrgaoDTO = new OrgaoDTO();
          $objOrgaoDTO->setNumIdOrgao($arrStrId[$i]);
          $arrObjOrgaoDTO[] = $objOrgaoDTO;
        }
        $objOrgaoRN = new OrgaoRN();
        $objOrgaoRN->desativar($arrObjOrgaoDTO);

			}catch(Exception $e){
				PaginaSip::getInstance()->processarExcecao($e);
			}
      header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'orgao_reativar':
		  try{

        $arrObjOrgaoDTO = array();
        $arrStrId = PaginaSip::getInstance()->getArrStrItensSelecionados();
        for ($i=0;$i<count($arrStrId);$i++){
          $objOrgaoDTO = new OrgaoDTO();
          $objOrgaoDTO->setNumIdOrgao($arrStrId[$i]);
          $arrObjOrgaoDTO[] = $objOrgaoDTO;
        }
        $objOrgaoRN = new OrgaoRN();
        $objOrgaoRN->reativar($arrObjOrgaoDTO);

			}catch(Exception $e){
				PaginaSip::getInstance()->processarExcecao($e);
			}
			break;

    case 'orgao_listar':
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }
	$arrComandos = array();
	if (SessaoSip::getInstance()->verificarPermissao('orgao_cadastrar')){
		$arrComandos[] = '<input type="button" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=orgao_cadastrar').'\';" class="infraButton" />';
	}
	$objOrgaoDTO = new OrgaoDTO();
	$objOrgaoDTO->retTodos();
	
  if ($_GET['acao'] == 'orgao_reativar'){
    //Lista somente inativos
    $objOrgaoDTO->setBolExclusaoLogica(false);
    $objOrgaoDTO->setStrSinAtivo('N');
  }
	
  PaginaSip::getInstance()->prepararOrdenacao($objOrgaoDTO, 'Ordem', InfraDTO::$TIPO_ORDENACAO_ASC);
	
	$objOrgaoRN = new OrgaoRN();
	$arrObjOrgaoDTO = $objOrgaoRN->listar($objOrgaoDTO);

	$numRegistros = count($arrObjOrgaoDTO);

	if ($numRegistros > 0){
		
		
    if ($_GET['acao']=='orgao_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('orgao_consultar');
      $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('orgao_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    }else if ($_GET['acao']=='orgao_reativar'){
      $bolAcaoReativar = SessaoSip::getInstance()->verificarPermissao('orgao_reativar');
      $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('orgao_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('orgao_excluir');
      $bolAcaoDesativar = false;
    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('orgao_consultar');
      $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('orgao_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('orgao_excluir');
      $bolAcaoDesativar = SessaoSip::getInstance()->verificarPermissao('orgao_desativar');
    }
		
		//Montar ações múltiplas
		$bolCheck = false;
		if ($bolAcaoExcluir){
			$bolCheck = true;
			//$arrComandos[] = '<input type="button" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton" />';
			$strLinkExcluir = SessaoSip::getInstance()->assinarLink('controlador.php?acao=orgao_excluir&acao_origem='.$_GET['acao']);
		}

		if ($bolAcaoDesativar){
			$bolCheck = true;
			//$arrComandos[] = '<input type="button" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton" />';
			$strLinkDesativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=orgao_desativar&acao_origem='.$_GET['acao']);
		}

		if ($bolAcaoReativar){
			$bolCheck = true;
			//$arrComandos[] = '<input type="button" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton" />';
			$strLinkReativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=orgao_reativar&acao_origem='.$_GET['acao']);
		}
		
		$arrComandos[] = '<input type="button" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton" />';
		
		$strResultado = '';
		$strResultado .= '<table width="99%" class="infraTable" summary="Tabela de Órgãos cadastrados">'."\n";
		$strResultado .= '<caption class="infraCaption">'.PaginaSip::getInstance()->gerarCaptionTabela('Órgãos',$numRegistros).'</caption>';
		$strResultado .= '<tr>';
		if ($bolCheck) {
			$strResultado .= '<th class="infraTh" width="1%">'.PaginaSip::getInstance()->getThCheck().'</th>';
		}
		$strResultado .= '<th class="infraTh" width="10%">'.PaginaSip::getInstance()->getThOrdenacao($objOrgaoDTO,'ID','IdOrgao',$arrObjOrgaoDTO).'</th>';
		$strResultado .= '<th class="infraTh" width="10%">'.PaginaSip::getInstance()->getThOrdenacao($objOrgaoDTO,'Sigla','Sigla',$arrObjOrgaoDTO).'</th>';
		$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objOrgaoDTO,'Descrição','Descricao',$arrObjOrgaoDTO).'</th>';
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSip::getInstance()->getThOrdenacao($objOrgaoDTO,'Autenticar','SinAutenticar',$arrObjOrgaoDTO).'</th>';
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSip::getInstance()->getThOrdenacao($objOrgaoDTO,'Ordem','Ordem',$arrObjOrgaoDTO).'</th>';
		$strResultado .= '<th class="infraTh" width="15%">Ações</th>';
		$strResultado .= '</tr>'."\n";
		for($i = 0;$i < $numRegistros; $i++){
			if ( ($i+2) % 2 ) {
				$strResultado .= '<tr class="infraTrEscura">';
			} else {
				$strResultado .= '<tr class="infraTrClara">';
			}
			if ($bolCheck){
				$strResultado .= '<td valign="top">'.PaginaSip::getInstance()->getTrCheck($i,$arrObjOrgaoDTO[$i]->getNumIdOrgao(),$arrObjOrgaoDTO[$i]->getStrSigla()).'</td>';
			}
			$strResultado .= '<td align="center">'.$arrObjOrgaoDTO[$i]->getNumIdOrgao().'</td>';
			$strResultado .= '<td align="center">'.PaginaSip::tratarHTML($arrObjOrgaoDTO[$i]->getStrSigla()).'</td>';
			$strResultado .= '<td>'.PaginaSip::tratarHTML($arrObjOrgaoDTO[$i]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSip::tratarHTML($arrObjOrgaoDTO[$i]->getStrSinAutenticar()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSip::tratarHTML($arrObjOrgaoDTO[$i]->getNumOrdem()).'</td>';
			
		  $strResultado .= '<td align="center">';
			if ($bolAcaoConsultar){
				$strResultado .= '<a href="'.SessaoSip::getInstance()->assinarLink('controlador.php?acao=orgao_consultar&id_orgao='.$arrObjOrgaoDTO[$i]->getNumIdOrgao()).'" tabindex="'.PaginaSip::getInstance()->getProxTabDados().'"><img src="'.PaginaSip::getInstance()->getIconeConsultar().'" title="Consultar Órgão" alt="Consultar Órgão" class="infraImg" /></a>&nbsp;';
			}

			if ($bolAcaoAlterar){
				$strResultado .= '<a href="'.SessaoSip::getInstance()->assinarLink('controlador.php?acao=orgao_alterar&id_orgao='.$arrObjOrgaoDTO[$i]->getNumIdOrgao()).'" tabindex="'.PaginaSip::getInstance()->getProxTabDados().'"><img src="'.PaginaSip::getInstance()->getIconeAlterar().'" title="Alterar Órgão" alt="Alterar Órgão" class="infraImg" /></a>&nbsp;';
			}

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjOrgaoDTO[$i]->getNumIdOrgao();
        $strDescricao = $arrObjOrgaoDTO[$i]->getStrSigla();
      }

			if ($bolAcaoDesativar){
				$strResultado .= '<a onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSip::getInstance()->getProxTabDados().'"><img src="'.PaginaSip::getInstance()->getIconeDesativar().'" title="Desativar Órgão" alt="Desativar Órgão" class="infraImg" /></a>&nbsp;';
			}
			
			if ($bolAcaoReativar){
				$strResultado .= '<a onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSip::getInstance()->getProxTabDados().'"><img src="'.PaginaSip::getInstance()->getIconeReativar().'" title="Reativar Órgão" alt="Reativar Órgão" class="infraImg" /></a>&nbsp;';
			}
			
			if ($bolAcaoExcluir){
				$strResultado .= '<a onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSip::getInstance()->getProxTabDados().'"><img src="'.PaginaSip::getInstance()->getIconeExcluir().'" title="Excluir Órgão" alt="Excluir Órgão" class="infraImg" /></a>&nbsp;';
			}
			
			$strResultado .= '</td></tr>'."\n";
		}
		$strResultado .= '</table>';
	}
	$arrComandos[] = '<input type="button" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton" />';

}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
} 

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - Órgãos');
PaginaSip::getInstance()->montarStyle();
//PaginaSip::getInstance()->abrirStyle();
//PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='orgao_selecionar'){
    infraReceberSelecao();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoExcluir){ ?>
     function acaoExcluir(id,desc){
       if (confirm("Confirma exclusão do Órgão \""+desc+"\"?")){
         document.getElementById('hdnInfraItensSelecionados').value=id;
         document.getElementById('frmOrgaoLista').action='<?=$strLinkExcluir?>';
         document.getElementById('frmOrgaoLista').submit();
       }
     }

     function acaoExclusaoMultipla(){
       if (document.getElementById('hdnInfraItensSelecionados').value==''){
         alert('Nenhum Órgão selecionado.');
         return;
       }
       if (confirm("Confirma exclusão dos Órgãos selecionados?")){
         document.getElementById('frmOrgaoLista').action='<?=$strLinkExcluir?>';
         document.getElementById('frmOrgaoLista').submit();
       }
     }
<? } ?>

<? if ($bolAcaoDesativar){ ?>
     function acaoDesativar(id,desc){
       if (confirm("Confirma desativação do Órgão \""+desc+"\"?")){
         document.getElementById('hdnInfraItensSelecionados').value=id;
         document.getElementById('frmOrgaoLista').action='<?=$strLinkDesativar?>';
         document.getElementById('frmOrgaoLista').submit();
       }
     }

     function acaoDesativacaoMultipla(){
       if (document.getElementById('hdnInfraItensSelecionados').value==''){
         alert('Nenhum Órgão selecionado.');
         return;
       }
       if (confirm("Confirma desativação dos Órgãos selecionados?")){
         document.getElementById('frmOrgaoLista').action='<?=$strLinkDesativar?>';
         document.getElementById('frmOrgaoLista').submit();
       }
     }
<? } ?>

<? if ($bolAcaoReativar){ ?>
     function acaoReativar(id,desc){
       if (confirm("Confirma reativação do Órgão \""+desc+"\"?")){
         document.getElementById('hdnInfraItensSelecionados').value=id;
         document.getElementById('frmOrgaoLista').action='<?=$strLinkReativar?>';
         document.getElementById('frmOrgaoLista').submit();
       }
     }

     function acaoReativacaoMultipla(){
       if (document.getElementById('hdnInfraItensSelecionados').value==''){
         alert('Nenhum Órgão selecionado.');
         return;
       }
       if (confirm("Confirma reativação dos Órgãos selecionados?")){
         document.getElementById('frmOrgaoLista').action='<?=$strLinkReativar?>';
         document.getElementById('frmOrgaoLista').submit();
       }
     }
<? } ?>

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody('Órgãos','onload="inicializar();"');
?>
<form id="frmOrgaoLista" method="post" action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSip::getInstance()->montarBarraLocalizacao('Órgãos');
  PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSip::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSip::getInstance()->montarAreaDebug();
  PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>