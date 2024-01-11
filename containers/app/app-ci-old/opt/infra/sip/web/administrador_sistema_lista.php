<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 30/11/2006 - criado por marcio_DB
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

  if (isset($_GET['id_orgao_sistema']) && isset($_GET['id_sistema'])){
    PaginaSip::getInstance()->salvarCampo('selOrgaoSistema',$_GET['id_orgao_sistema']);
    PaginaSip::getInstance()->salvarCampo('selSistema',$_GET['id_sistema']);
  }else{
    PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistema','selSistema'));
  }
  
  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'administrador_sistema_excluir':
			try{
        $arrObjAdministradorSistemaDTO = array();
        $arrStrId = PaginaSip::getInstance()->getArrStrItensSelecionados();
        for ($i=0;$i<count($arrStrId);$i++){
          $arrIdComposto = explode('-',$arrStrId[$i]);
          $objAdministradorSistemaDTO = new AdministradorSistemaDTO();
          $objAdministradorSistemaDTO->setNumIdSistema($arrIdComposto[0]);
          $objAdministradorSistemaDTO->setNumIdUsuario($arrIdComposto[1]);
          $arrObjAdministradorSistemaDTO[] = $objAdministradorSistemaDTO;
        }
        $objAdministradorSistemaRN = new AdministradorSistemaRN();
        $objAdministradorSistemaRN->excluir($arrObjAdministradorSistemaDTO);
			}catch(Exception $e){
				PaginaSip::getInstance()->processarExcecao($e);
			}
			break;
			
    case 'administrador_sistema_listar':
		  break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }
	
	$arrComandos = array();
	if (SessaoSip::getInstance()->verificarPermissao('administrador_sistema_cadastrar')){
		$arrComandos[] = '<input type="button" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=administrador_sistema_cadastrar').'\';" class="infraButton" />';
	}
	$objAdministradorSistemaDTO = new AdministradorSistemaDTO(true);
	$objAdministradorSistemaDTO->retTodos();
	
	//ORGAO
	$numIdOrgao = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema');
	$objAdministradorSistemaDTO->setNumIdOrgaoSistema($numIdOrgao);
	
	//SISTEMA
	$numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema');
	$objAdministradorSistemaDTO->setNumIdSistema($numIdSistema);
	
	PaginaSip::getInstance()->prepararOrdenacao($objAdministradorSistemaDTO, 'SiglaUsuario', InfraDTO::$TIPO_ORDENACAO_ASC);
	
	$objAdministradorSistemaRN = new AdministradorSistemaRN();
	$arrObjAdministradorSistemaDTO = $objAdministradorSistemaRN->listar($objAdministradorSistemaDTO);

	$numRegistros = count($arrObjAdministradorSistemaDTO);

	if ($numRegistros > 0){
		//$bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('administrador_sistema_consultar');
		//$bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('administrador_sistema_alterar');
		$bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('administrador_sistema_excluir');
		//$bolAcaoDesativar = SessaoSip::getInstance()->verificarPermissao('administrador_sistema_desativar');

		//Montar ações múltiplas
		$bolCheck = false;
		if ($bolAcaoExcluir){
			$bolCheck = true;
			$arrComandos[] = '<input type="button" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton" />';
			$strLinkExcluir = SessaoSip::getInstance()->assinarLink('administrador_sistema_lista.php?acao=administrador_sistema_excluir');
		}

		$arrComandos[] = '<input type="button" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton" />';
		
		$strResultado = '';
		$strResultado .= '<table width="50%" class="infraTable" summary="Tabela de Administradores cadastrados">'."\n";
		$strResultado .= '<caption class="infraCaption">'.PaginaSip::getInstance()->gerarCaptionTabela('Administradores',$numRegistros).'</caption>';
		$strResultado .= '<tr>';
		if ($bolCheck) {
			$strResultado .= '<th class="infraTh" width="1%">'.PaginaSip::getInstance()->getThCheck().'</th>';
		}
		//$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objAdministradorSistemaDTO,'Sistema', 'SiglaSistema',$arrObjAdministradorSistemaDTO).'</th>';
		$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objAdministradorSistemaDTO,'Usuário', 'SiglaUsuario',$arrObjAdministradorSistemaDTO).'</th>';
		$strResultado .= '<th class="infraTh">Ações</th>';
		$strResultado .= '</tr>'."\n";
		for($i = 0;$i < $numRegistros; $i++){
			if ( ($i+2) % 2 ) {
				$strResultado .= '<tr class="infraTrEscura">';
			} else {
				$strResultado .= '<tr class="infraTrClara">';
			}
			if ($bolCheck){
				$strResultado .= '<td valign="top">'.PaginaSip::getInstance()->getTrCheck($i,$arrObjAdministradorSistemaDTO[$i]->getNumIdSistema().'-'.$arrObjAdministradorSistemaDTO[$i]->getNumIdUsuario(), $arrObjAdministradorSistemaDTO[$i]->getStrSiglaSistema()).'</td>';
			}
			//$strResultado .= '<td align="center">'.$arrObjAdministradorSistemaDTO[$i]->getStrSiglaSistema().' / '.$arrObjAdministradorSistemaDTO[$i]->getStrSiglaOrgaoSistema().'</td>';
			//$strResultado .= '<td align="center">'.$arrObjAdministradorSistemaDTO[$i]->getStrSiglaUsuario().' / '.$arrObjAdministradorSistemaDTO[$i]->getStrSiglaOrgaoUsuario().'</td>';

      $strNomeUsuario = PaginaSip::tratarHTML($arrObjAdministradorSistemaDTO[$i]->getStrNomeUsuario());
      $strSiglaUsuario = PaginaSip::tratarHTML($arrObjAdministradorSistemaDTO[$i]->getStrSiglaUsuario());
      $strDescricaoOrgaoUsuario = PaginaSip::tratarHTML($arrObjAdministradorSistemaDTO[$i]->getStrDescricaoOrgaoUsuario());
      $strSiglaOrgaoUsuario = PaginaSip::tratarHTML($arrObjAdministradorSistemaDTO[$i]->getStrSiglaOrgaoUsuario());
      $strSiglaSistema = PaginaSip::tratarHTML(PaginaSip::getInstance()->formatarParametrosJavaScript($arrObjAdministradorSistemaDTO[$i]->getStrSiglaSistema()));

      $strResultado .= '<td align="center">';
			$strResultado .= '<a alt="'.$strNomeUsuario.'" title="'.$strNomeUsuario.'" class="ancoraSigla">'.$strSiglaUsuario.'</a>';
			$strResultado .= '&nbsp;/&nbsp;';
			$strResultado .= '<a alt="'.$strDescricaoOrgaoUsuario.'" title="'.$strDescricaoOrgaoUsuario.'" class="ancoraSigla">'.$strSiglaOrgaoUsuario.'</a>';
			$strResultado .= '</td>';
			
		          
			$strResultado .= '<td align="center" width="20%">';
			
			if ($bolAcaoExcluir){
				$strResultado .= '<a onclick="acaoExcluir(\''.$arrObjAdministradorSistemaDTO[$i]->getNumIdSistema().'-'.$arrObjAdministradorSistemaDTO[$i]->getNumIdUsuario().'\',\''.PaginaSip::formatarParametrosJavaScript($arrObjAdministradorSistemaDTO[$i]->getStrSiglaSistema().'/'.$arrObjAdministradorSistemaDTO[$i]->getStrSiglaUsuario()).'\');" tabindex="'.PaginaSip::getInstance()->getProxTabDados().'"><img src="'.PaginaSip::getInstance()->getIconeExcluir().'" title="Excluir Administrador" alt="Excluir Administrador" class="infraImg" /></a>&nbsp;';
			}

			$strResultado .= '</td></tr>'."\n";
		}
		$strResultado .= '</table>';
	}
	$arrComandos[] = '<input type="button" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton" />';
	
  $strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaTodos('null','&nbsp;', $numIdOrgao);
  $strItensSelSistema = SistemaINT::montarSelectSiglaSip('null','&nbsp;', $numIdSistema, $numIdOrgao);

  
}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
} 

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - Administradores');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
#lblOrgaoSistema {position:absolute;left:0%;top:0%;width:20%;}
#selOrgaoSistema {position:absolute;left:0%;top:20%;width:20%;}

#lblSistema {position:absolute;left:0%;top:50%;width:20%;}
#selSistema {position:absolute;left:0%;top:70%;width:20%;}
<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

<? if ($bolAcaoExcluir){ ?>
     function acaoExcluir(id,desc){
       if (confirm("Confirma exclusão do Administrador \""+desc+"\"?")){
         document.getElementById('hdnInfraItensSelecionados').value=id;
         document.getElementById('frmAdministradorSistemaLista').action='<?=$strLinkExcluir?>';
         document.getElementById('frmAdministradorSistemaLista').submit();
       }
     }

     function acaoExclusaoMultipla(){
       if (document.getElementById('hdnInfraItensSelecionados').value==''){
         alert('Nenhum Administrador selecionado.');
         return;
       }
       if (confirm("Confirma exclusão dos Administradores selecionados?")){
         document.getElementById('frmAdministradorSistemaLista').action='<?=$strLinkExcluir?>';
         document.getElementById('frmAdministradorSistemaLista').submit();
       }
     }
<? } ?>

function trocarOrgaoSistema(obj){
	document.getElementById('selSistema').value='null';
	obj.form.submit();
}

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody();
?>
<form id="frmAdministradorSistemaLista" method="post" action="<?=SessaoSip::getInstance()->assinarLink('administrador_sistema_lista.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSip::getInstance()->montarBarraLocalizacao('Administradores');
  PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSip::getInstance()->abrirAreaDados('10em');
  ?>
  
  <label id="lblOrgaoSistema" for="selOrgaoSistema" accesskey="o" class="infraLabelOpcional">Órgã<span class="infraTeclaAtalho">o</span> do Sistema:</label>
  <select id="selOrgaoSistema" name="selOrgaoSistema" onchange="trocarOrgaoSistema(this);" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" >
  <?=$strItensSelOrgaoSistema?>
  </select>
	
  <label id="lblSistema" for="selSistema" accesskey="S" class="infraLabelOpcional"><span class="infraTeclaAtalho">S</span>istema:</label>
  <select id="selSistema" name="selSistema" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
  <?=$strItensSelSistema?>
  </select>
  
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