<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 14/03/2007 - criado por mga
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

  //SessaoSip::getInstance()->validarSessao();
  SessaoSip::getInstance()->validarLink();

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

	PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoUsuario'));
	
  $arrComandos = array();

  switch($_GET['acao']){
    case 'permissao_delegar':
      $strTitulo = 'Delegar Permissões';
      $arrComandos[] = '<input type="submit" name="sbmDelegarPermissao" value="Delegar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=permissao_listar_pessoais').'\';" class="infraButton" />';
			
			//ORGAO USUARIO
			$numIdOrgaoUsuario = $_POST['selOrgaoUsuario'];
			if ($numIdOrgaoUsuario===''){
				$numIdOrgaoUsuario = null;
			}
			
			//USUARIO
			$numIdUsuario = $_POST['hdnIdUsuario'];
		  $strSiglaUsuario = $_POST['txtUsuario'];
			
			if (isset($_POST['hdnInfraItensSelecionados'])){
				$strIdPermissoes = implode(',',PaginaSip::getInstance()->getArrStrItensSelecionados());
			}else{
				$strIdPermissoes = $_POST['hdnPermissoes'];
			}
			
      if (isset($_POST['sbmDelegarPermissao'])) {
        
				try{
					$objPermissaoDelegarDTO = new PermissaoDelegarDTO();
					$objPermissaoDelegarDTO->setNumIdUsuario($numIdUsuario);
					 
					if($_POST['hdnPermissoes']!=''){
						$arrObjPermissaoDTO = array();
						$arrStrId = explode(',',$_POST['hdnPermissoes']);
						for ($i=0;$i<count($arrStrId);$i++){
							$arrStrIdComposto = explode('-',$arrStrId[$i]);
							$objPermissaoDTO = new PermissaoDTO(true);
							$objPermissaoDTO->setNumIdPerfil($arrStrIdComposto[0]);
							$objPermissaoDTO->setNumIdSistema($arrStrIdComposto[1]);
							$objPermissaoDTO->setNumIdUsuario($arrStrIdComposto[2]);
							$objPermissaoDTO->setNumIdUnidade($arrStrIdComposto[3]);
							$arrObjPermissaoDTO[] = $objPermissaoDTO;
						}
					}
					$objPermissaoDelegarDTO->setArrObjPermissaoDTO($arrObjPermissaoDTO);
					$objPermissaoRN = new PermissaoRN();
					$objPermissaoRN->delegar($objPermissaoDelegarDTO);
					header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao=permissao_listar_pessoais'));
					die;
					
				}catch(Exception $e){
					PaginaSip::getInstance()->processarExcecao($e);
				}
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

	
	//Monta tabela com permissoes que serão delegadas
	$objPermissaoDTO = new PermissaoDTO(true);
	$objPermissaoDTO->retTodos();

	$objPermissaoRN = new PermissaoRN();
	$arrObjPermissaoDTO = array();
	
	$flagDescartouPermissao = false;
	
	//Armazena somente os Ids que podem ser delegados
	$strIdsFiltrados = '';
	$strSeparador = '';

	if (!InfraString::isBolVazia($strIdPermissoes)){
		$arrStrId = explode(',',$strIdPermissoes);
		for ($i=0;$i<count($arrStrId);$i++){
			$arrStrIdComposto = explode('-',$arrStrId[$i]);
			$objPermissaoDTO->setNumIdPerfil($arrStrIdComposto[0]);
			$objPermissaoDTO->setNumIdSistema($arrStrIdComposto[1]);
			$objPermissaoDTO->setNumIdUsuario($arrStrIdComposto[2]);
			$objPermissaoDTO->setNumIdUnidade($arrStrIdComposto[3]);
			$dto = $objPermissaoRN->consultar($objPermissaoDTO);
			if ($dto->getNumIdTipoPermissao()!=PermissaoRN::$TIPO_NAO_DELEGAVEL){
			  $arrObjPermissaoDTO[] = $dto;
				$strIdsFiltrados .= $strSeparador.$arrStrId[$i];
				$strSeparador = ',';
			} else {
			  $flagDescartouPermissao = true;	
			}
		}
	}
	
	//Na primeira vez que postar mostra o aviso
	if ($flagDescartouPermissao){
		$strIdPermissoes = $strIdsFiltrados;
		PaginaSip::getInstance()->adicionarMensagem('Uma ou mais permissões selecionadas não podem ser delegadas e foram removidas da lista para delegação.');
	}
	
	$numRegistros = count($arrObjPermissaoDTO);

	if ($numRegistros > 0){
		$strResultado = '';
		$strResultado .= '<table width="99%" class="infraTable" summary="Tabela de Permissões que serão Delegadas">'."\n";
		$strResultado .= '<caption class="infraCaption">'.PaginaSip::getInstance()->gerarCaptionTabela('Permissões para Delegação',$numRegistros).'</caption>';
		$strResultado .= '<tr>';
		$strResultado .= '<th class="infraTh">Sistema</th>';
		$strResultado .= '<th class="infraTh">Usuário</th>';
		$strResultado .= '<th class="infraTh">Unidade</th>';
		$strResultado .= '<th class="infraTh">Perfil</th>';
		$strResultado .= '</tr>'."\n";
		for($i = 0;$i < $numRegistros; $i++){
			if ( ($i+2) % 2 ) {
				$strResultado .= '<tr class="infraTrEscura">';
			} else {
				$strResultado .= '<tr class="infraTrClara">';
			}
			$strResultado .= '<td align="center">'.$arrObjPermissaoDTO[$i]->getStrSiglaSistema().' / '.$arrObjPermissaoDTO[$i]->getStrSiglaOrgaoSistema().'</td>';
			$strResultado .= '<td align="center">'.$arrObjPermissaoDTO[$i]->getStrSiglaUsuario().' / '.$arrObjPermissaoDTO[$i]->getStrSiglaOrgaoUsuario().'</td>';
			$strResultado .= '<td align="center">'.$arrObjPermissaoDTO[$i]->getStrSiglaUnidade().' / '.$arrObjPermissaoDTO[$i]->getStrSiglaOrgaoUnidade().'</td>';
			$strResultado .= '<td>'.$arrObjPermissaoDTO[$i]->getStrNomePerfil().'</td>';
			$strResultado .= '</tr>'."\n";
		}
		$strResultado .= '</table>';
	}

  $strItensSelOrgaoUsuario = OrgaoINT::montarSelectSigla('null','&nbsp;',$numIdOrgaoUsuario);	
  
	$strLinkAjaxUsuario = SessaoSip::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_auto_completar_sigla_nome');   

}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - Permissão');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
#lblOrgaoUsuario {position:absolute;left:0%;top:0%;width:25%;}
#selOrgaoUsuario {position:absolute;left:0%;top:20%;width:25%;}

#lblUsuario {position:absolute;left:0%;top:50%;width:25%;}
#txtUsuario {position:absolute;left:0%;top:70%;width:25%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>
var objAjaxUsuario = null;

function inicializar(){

  //AUTO COMPLETAR USUARIO
  objAjaxUsuario = new infraAjaxAutoCompletar('hdnIdUsuario','txtUsuario','<?=$strLinkAjaxUsuario?>');
  objAjaxUsuario.prepararExecucao = function(){
    if (!infraSelectSelecionado('selOrgaoUsuario')){
      alert('Selecione Órgão do Usuário.');
      document.getElementById('selOrgaoUsuario').focus();
      return false;
    }
    return 'sigla='+document.getElementById('txtUsuario').value + '&idOrgao='+document.getElementById('selOrgaoUsuario').value;
  };
  objAjaxUsuario.processarResultado = function(id,descricao,complemento){
    //
  };

  if ('<?=$_GET['acao']?>'=='permissao_delegar'){
    document.getElementById('selOrgaoUsuario').focus();
  }
}

function OnSubmitForm() {
	
	if (!validarForm()){
		return false;
	}
  
	return true;
}

function validarForm(){
	
  if (!infraSelectSelecionado(document.getElementById('selOrgaoUsuario'))) {
    alert('Selecione Órgão do Usuário.');
    document.getElementById('selOrgaoUsuario').focus();
    return false;
  }
	
  if (infraTrim(document.getElementById('txtUsuario').value)=='') {
    alert('Informe um Usuário.');
    document.getElementById('txtUsuario').focus();
    return false;
  }
	
  return true;
}


<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmPermissaoDelegar" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSip::getInstance()->assinarLink('permissao_delegar.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSip::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSip::getInstance()->montarAreaValidacao();
PaginaSip::getInstance()->abrirAreaDados('10em');
?>
  <label id="lblOrgaoUsuario" for="selOrgaoUsuario" accesskey="o" class="infraLabelObrigatorio">Órgã<span class="infraTeclaAtalho">o</span> do Usuário:</label>
  <select id="selOrgaoUsuario" name="selOrgaoUsuario" onchange="objAjaxUsuario.limpar();" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
  <?=$strItensSelOrgaoUsuario?>
  </select>
	
  <label id="lblUsuario" for="txtUsuario" accesskey="u" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">U</span>suário:</label>
  <input type="text" id="txtUsuario" name="txtUsuario" class="infraText" value="<?=PaginaSip::tratarHTML($strSiglaUsuario)?>" maxlength="100" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?> />

  <input type="hidden" id="hdnPermissoes" name="hdnPermissoes" value="<?=$strIdPermissoes;?>" />
  <input type="hidden" id="hdnIdUsuario" name="hdnIdUsuario" value="<?=$strIdUsuario;?>" />

  <?
	
  PaginaSip::getInstance()->fecharAreaDados();
	//PaginaSip::getInstance()->montarAreaTabela($strResultado,$numRegistros);
	PaginaSip::getInstance()->abrirAreaTabela();
	echo $strResultado;
	PaginaSip::getInstance()->fecharAreaTabela();
  //PaginaSip::getInstance()->montarAreaDebug();
  //PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>