<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/08/2013 - criado por mga
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
	
  
  SessaoSip::getInstance()->validarLink();

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistema','selSistema'));
  
  if (isset($_POST['hdnFlag'])){
    PaginaSip::getInstance()->salvarCampo('chkCoordenadoPeloUsuario',(isset($_POST['chkCoordenadoPeloUsuario']) ? $_POST['chkCoordenadoPeloUsuario'] : ''));
  }
  
  switch($_GET['acao']){
		  					
    case 'perfil_listar_coordenados':
      $strTitulo = 'Perfis Coordenados';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }
	
	$arrComandos = array();
	
	$objPerfilDTO = new PerfilDTO();
	$objPerfilDTO->retTodos(true);
	
	//ORGAO
	$numIdOrgao = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema',SessaoSip::getInstance()->getNumIdOrgaoSistema());
	if ($numIdOrgao!==''){
		$objPerfilDTO->setNumIdOrgaoSistema($numIdOrgao);
	}
	
	//SISTEMA
	$numIdSistema = '';
	if ($numIdOrgao!==''){
		$numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema','null');
		if ($numIdSistema!==''){
			$objPerfilDTO->setNumIdSistema($numIdSistema);
		}
	} else {
		//Para todos os orgãos os sistemas podem se repetir então não possibilita
		//escolha (desabilita combo)
	  $objPerfilDTO->setNumIdSistema(null);
		$strDesabilitar = 'disabled="disabled"';
	}
	
	$objPerfilDTO->setStrSinCoordenadoPeloUsuario(PaginaSip::getInstance()->getCheckBox(PaginaSip::getInstance()->recuperarCampo('chkCoordenadoPeloUsuario')));
	
	
	PaginaSip::getInstance()->prepararOrdenacao($objPerfilDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);			

	$objPerfilRN = new PerfilRN();
	$arrObjPerfilDTO = $objPerfilRN->listarCoordenados($objPerfilDTO);

	$numRegistros = count($arrObjPerfilDTO);

	if ($numRegistros > 0){
	  
	  
	  $bolAcaoCoordenadorPerfilListarSimples = SessaoSip::getInstance()->verificarPermissao('coordenador_perfil_listar_simples');
 		$bolAcaoItemMenuListarCoordenados = SessaoSip::getInstance()->verificarPermissao('item_menu_listar_perfil');
 		
 		
	  
		//Montar ações múltiplas
		$bolCheck = true;
		
		$arrComandos[] = '<input type="button" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton" />';
		
    $strSumarioTabela = 'Tabela de Perfis Coordenados.';
    $strCaptionTabela = 'Perfis Coordenados';

    $strResultado = '';
    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSip::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
		
		$strResultado .= '<tr>';
		if ($bolCheck) {
			$strResultado .= '<th class="infraTh" width="1%">'.PaginaSip::getInstance()->getThCheck().'</th>';
		}
		$strResultado .= '<th class="infraTh" width="20%">'.PaginaSip::getInstance()->getThOrdenacao($objPerfilDTO,'Nome','Nome',$arrObjPerfilDTO).'</th>';
		$strResultado .= '<th class="infraTh">Descrição</th>';
		$strResultado .= '<th class="infraTh" width="10%">Coordenado</th>';
		//$strResultado .= '<th class="infraTh" width="20%">'.PaginaSip::getInstance()->getThOrdenacao($objPerfilDTO,'Sistema','SiglaSistema',$arrObjPerfilDTO).'</th>';
		$strResultado .= '<th class="infraTh" width="10%">Ações</th>';
		$strResultado .= '</tr>'."\n";
		for($i = 0;$i < $numRegistros; $i++){
			if ( ($i+2) % 2 ) {
				$strResultado .= '<tr class="infraTrEscura">';
			} else {
				$strResultado .= '<tr class="infraTrClara">';
			}
			if ($bolCheck){
				$strResultado .= '<td>'.PaginaSip::getInstance()->getTrCheck($i,$arrObjPerfilDTO[$i]->getNumIdPerfil().'-'.$arrObjPerfilDTO[$i]->getNumIdSistema(),$arrObjPerfilDTO[$i]->getStrNome()).'</td>';
			}
			$strResultado .= '<td width="25%">'.PaginaSip::tratarHTML($arrObjPerfilDTO[$i]->getStrNome()).'</td>';
			$strResultado .= '<td>'.PaginaSip::tratarHTML($arrObjPerfilDTO[$i]->getStrDescricao()).'</td>';
			$strResultado .= '<td align="center">'.($arrObjPerfilDTO[$i]->getStrSinCoordenadoPeloUsuario()=='S'?'Sim':'&nbsp;').'</td>';
			
			//$strResultado .= '<td align="center">'.$arrObjPerfilDTO[$i]->getStrSiglaSistema().' / '.$arrObjPerfilDTO[$i]->getStrSiglaOrgaoSistema().'</td>';
			
			/*
			$strResultado .= '<td align="center">';
			$strResultado .= '<a alt="'.$arrObjPerfilDTO[$i]->getStrDescricaoSistema().'" title="'.$arrObjPerfilDTO[$i]->getStrDescricaoSistema().'" class="ancoraSigla">'.$arrObjPerfilDTO[$i]->getStrSiglaSistema().'</a>';
			$strResultado .= '&nbsp;/&nbsp;';
			$strResultado .= '<a alt="'.$arrObjPerfilDTO[$i]->getStrDescricaoOrgaoSistema().'" title="'.$arrObjPerfilDTO[$i]->getStrDescricaoOrgaoSistema().'" class="ancoraSigla">'.$arrObjPerfilDTO[$i]->getStrSiglaOrgaoSistema().'</a>';
			$strResultado .= '</td>';
			*/
			
			
			$strResultado .= '<td align="center">';
			
			if ($bolAcaoCoordenadorPerfilListarSimples && $arrObjPerfilDTO[$i]->getStrSinCoordenadoPorAlgumUsuario()=='S'){
			  $strResultado .= '<a href="'.SessaoSip::getInstance()->assinarLink('controlador.php?acao=coordenador_perfil_listar_simples&acao_retorno='.$_GET['acao'].'&id_perfil='.$arrObjPerfilDTO[$i]->getNumIdPerfil().'&id_orgao_sistema='.$arrObjPerfilDTO[$i]->getNumIdOrgaoSistema().'&id_sistema='.$arrObjPerfilDTO[$i]->getNumIdSistema()).'" tabindex="'.PaginaSip::getInstance()->getProxTabTabela().'"><img src="'.PaginaSip::getInstance()->getIconeGrupo().'" title="Coordenadores do Perfil" alt="Coordenadores do Perfil" class="infraImg" /></a>&nbsp;';
			}
			
			if ($bolAcaoItemMenuListarCoordenados){
				$strResultado .= '<a href="'.SessaoSip::getInstance()->assinarLink('controlador.php?acao=item_menu_listar_perfil&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_perfil='.$arrObjPerfilDTO[$i]->getNumIdPerfil().'&id_sistema='.$arrObjPerfilDTO[$i]->getNumIdSistema()).'" tabindex="'.PaginaSip::getInstance()->getProxTabDados().'"><img src="'.PaginaSip::getInstance()->getDiretorioSvgLocal().'/menu.svg" title="Itens de Menu do Perfil" alt="Itens de Menu do Perfil" class="infraImg" /></a>&nbsp;';
			}
			
			$strResultado .= '</td>'."\n";
			
			$strResultado .= '</tr>'."\n";
		}
		$strResultado .= '</table>';
	}
	$arrComandos[] = '<input type="button" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton" />';

  $strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaCoordenados('null','&nbsp;', $numIdOrgao);
  $strItensSelSistema = SistemaINT::montarSelectSiglaCoordenados('null','&nbsp;', $numIdSistema, $numIdOrgao);
}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
} 

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - Perfis');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
#lblOrgaoSistema {position:absolute;left:0%;top:0%;width:20%;}
#selOrgaoSistema {position:absolute;left:0%;top:20%;width:20%;}

#lblSistema {position:absolute;left:0%;top:50%;width:20%;}
#selSistema {position:absolute;left:0%;top:70%;width:20%;}

#divSinCoordenadoPeloUsuario {position:absolute;left:33%;top:70%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

function inicializar(){
  infraEfeitoTabelas();
}

function trocarOrgaoSistema(obj){
	document.getElementById('selSistema').value='null';
	obj.form.submit();
}

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmPerfilListaCoordenados" method="post" action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSip::getInstance()->montarBarraLocalizacao('Perfis');
  PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSip::getInstance()->abrirAreaDados('10em');
  ?>
	
  <label id="lblOrgaoSistema" for="selOrgaoSistema" accesskey="o" class="infraLabelOpcional">Órgã<span class="infraTeclaAtalho">o</span> do Sistema:</label>
  <select id="selOrgaoSistema" name="selOrgaoSistema" onchange="trocarOrgaoSistema(this);" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" >
  <?=$strItensSelOrgaoSistema?>
  </select>
	
  <label id="lblSistema" for="selSistema" accesskey="S" class="infraLabelOpcional"><span class="infraTeclaAtalho">S</span>istema:</label>
  <select id="selSistema" name="selSistema" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?> >
  <?=$strItensSelSistema?>
  </select>

  <div id="divSinCoordenadoPeloUsuario" class="infraDivCheckbox">
    <input type="checkbox" id="chkCoordenadoPeloUsuario" name="chkCoordenadoPeloUsuario" onclick='this.form.submit();' class="infraCheckbox" <?=PaginaSip::getInstance()->setCheckBox($objPerfilDTO->getStrSinCoordenadoPeloUsuario())?> <?=$strDesabilitar?>/>
  	<label id="lblCoordenadoPeloUsuario" for="chkCoordenadoPeloUsuario" class="infraLabelCheckbox">Visualizar somente coordenados por mim</label>
	</div>
  
  <?
  PaginaSip::getInstance()->fecharAreaDados();
  PaginaSip::getInstance()->montarAreaTabela($strResultado,$numRegistros,true);
  PaginaSip::getInstance()->montarAreaDebug();
  PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
  
  <input type="hidden" id="hdnFlag" name="hdnFlag" value="1" />
  
</form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>