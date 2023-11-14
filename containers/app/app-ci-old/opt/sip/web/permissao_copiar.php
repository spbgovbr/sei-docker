<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/12/2006 - criado por mga
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

	PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoUnidade','selOrgaoUsuario','txtUsuarioCopia','hdnIdUsuarioCopia'));
  
  $arrComandos = array();

	switch($_GET['acao']){
    case 'permissao_copiar':
      $strTitulo = 'Copiar Permissões';
      
      $arrComandos[] = '<input type="submit" name="sbmSalvarPermissao" value="Salvar" class="infraButton" />';
			
			//ORGAO USUARIO
			$numIdOrgaoUsuario = $_POST['selOrgaoUsuario'];
			if ($numIdOrgaoUsuario===''){
				$numIdOrgaoUsuario = null;
			}
			
			//USUARIO
      $numIdUsuario = PaginaSip::getInstance()->recuperarCampo('hdnIdUsuarioCopia');
      $strSiglaUsuario = PaginaSip::getInstance()->recuperarCampo('txtUsuarioCopia');
			
			//ORGAO UNIDADE
			$numIdOrgaoUnidade = $_POST['selOrgaoUnidade'];
			if ($numIdOrgaoUnidade===''){
				$numIdOrgaoUnidade = null;
			}

      //UNIDADE
			if ($_POST['rdoTipoUnidadeDestino']=='M'){
				$numIdUnidade = null;
			}else {
				$numIdUnidade = $_POST['selUnidade'];
				if ($numIdUnidade === '') {
					$numIdUnidade = null;
				}
			}

			if (isset($_POST['selSistema'])){
				$numIdSistema = $_POST['selSistema'];
			}else{
				$numIdSistema = $_POST['hdnIdSistema'];
			}

			$arrIdPermissoesSelecionadas = PaginaSip::getInstance()->getArrStrItensSelecionados();

			$strAncora = PaginaSip::getInstance()->montarAncora(implode(',',$arrIdPermissoesSelecionadas));
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=permissao_listar_administradas&acao_origem='.$_GET['acao'].$strAncora).'\';" class="infraButton" />';

      if (isset($_POST['sbmSalvarPermissao'])) {
        
				try{
					$objPermissaoCopiarDTO = new PermissaoCopiarDTO();
					$objPermissaoCopiarDTO->setNumIdUsuario($numIdUsuario);
					$objPermissaoCopiarDTO->setNumIdUnidade($numIdUnidade);

					$arrObjPermissaoDTO = array();
					foreach($arrIdPermissoesSelecionadas as $permissao){
						$arrStrIdComposto = explode('-',$permissao);
						$objPermissaoDTO = new PermissaoDTO();
						$objPermissaoDTO->setNumIdPerfil($arrStrIdComposto[0]);
						$objPermissaoDTO->setNumIdSistema($arrStrIdComposto[1]);
						$objPermissaoDTO->setNumIdUsuario($arrStrIdComposto[2]);
						$objPermissaoDTO->setNumIdUnidade($arrStrIdComposto[3]);
						$arrObjPermissaoDTO[] = $objPermissaoDTO;
					}

					//die('@'.count($arrObjPermissaoDTO));

					$objPermissaoCopiarDTO->setArrObjPermissaoDTO($arrObjPermissaoDTO);
					$objPermissaoRN = new PermissaoRN();
					$arrObjPermissaoDTO = $objPermissaoRN->copiar($objPermissaoCopiarDTO);
					
					PaginaSip::getInstance()->setStrMensagem('Cópia realizada com sucesso.');
					header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao=permissao_listar_administradas&acao_origem='.$_GET['acao'].$strAncora));
					die;
					
				}catch(Exception $e){
					PaginaSip::getInstance()->processarExcecao($e);
				}
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

	//Monta tabela com permissoes que serão copiadas
	$objPermissaoDTO = new PermissaoDTO();
	$objPermissaoDTO->retNumIdPerfil();
	$objPermissaoDTO->retNumIdSistema();
	$objPermissaoDTO->retNumIdUsuario();
	$objPermissaoDTO->retNumIdUnidade();
	$objPermissaoDTO->retStrSiglaSistema();
	$objPermissaoDTO->retStrDescricaoSistema();
	$objPermissaoDTO->retStrDescricaoOrgaoSistema();
	$objPermissaoDTO->retStrSiglaOrgaoSistema();
	$objPermissaoDTO->retStrNomeUsuario();
	$objPermissaoDTO->retStrSiglaUsuario();
	$objPermissaoDTO->retStrSiglaOrgaoUsuario();
	$objPermissaoDTO->retStrDescricaoOrgaoUsuario();
	$objPermissaoDTO->retStrSiglaSistema();
	$objPermissaoDTO->retStrSiglaOrgaoSistema();
	$objPermissaoDTO->retStrSiglaUnidade();
	$objPermissaoDTO->retStrDescricaoUnidade();
	$objPermissaoDTO->retStrSiglaOrgaoUnidade();
	$objPermissaoDTO->retStrDescricaoOrgaoUnidade();
	$objPermissaoDTO->retStrNomePerfil();
  
	$objPermissaoRN = new PermissaoRN();
	$arrObjPermissaoDTO = array();

	if ($_GET['acao_origem']=='permissao_listar_administradas') {
		$arrIdPermissoes = $arrIdPermissoesSelecionadas;
	}else{
		$arrIdPermissoes = PaginaSip::getInstance()->getArrStrItens();
	}

	foreach($arrIdPermissoes as $permissao){
		$arrStrIdComposto = explode('-',$permissao);
		$objPermissaoDTO->setNumIdPerfil($arrStrIdComposto[0]);
		$objPermissaoDTO->setNumIdSistema($arrStrIdComposto[1]);
		$objPermissaoDTO->setNumIdUsuario($arrStrIdComposto[2]);
		$objPermissaoDTO->setNumIdUnidade($arrStrIdComposto[3]);
		$arrObjPermissaoDTO[] = $objPermissaoRN->consultar($objPermissaoDTO);
	}
	
	$numRegistros = count($arrObjPermissaoDTO);

	if ($numRegistros > 0){
		$strResultado = '<br />';
		$strResultado .= '<table width="99%" class="infraTable" summary="Tabela de Permissões que serão Copiadas">'."\n";
		$strResultado .= '<caption class="infraCaption">'.PaginaSip::getInstance()->gerarCaptionTabela('Permissões para Cópia',$numRegistros).'</caption>';
		$strResultado .= '<tr>';
		$strResultado .= '<th class="infraTh" width="1%">'.PaginaSip::getInstance()->getThCheck().'</th>';
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

			$strResultado .= '<td valign="top">'.PaginaSip::getInstance()->getTrCheck($n++,$arrObjPermissaoDTO[$i]->getNumIdPerfil().'-'.$arrObjPermissaoDTO[$i]->getNumIdSistema().'-'.$arrObjPermissaoDTO[$i]->getNumIdUsuario().'-'.$arrObjPermissaoDTO[$i]->getNumIdUnidade(),$arrObjPermissaoDTO[$i]->getStrSiglaSistema().'/'.$arrObjPermissaoDTO[$i]->getStrSiglaOrgaoSistema().' - '.$arrObjPermissaoDTO[$i]->getStrSiglaUsuario().'/'.$arrObjPermissaoDTO[$i]->getStrSiglaOrgaoUsuario().' - '.$arrObjPermissaoDTO[$i]->getStrSiglaUnidade().'/'.$arrObjPermissaoDTO[$i]->getStrSiglaOrgaoUnidade().' - '.$arrObjPermissaoDTO[$i]->getStrNomePerfil()).'</td>';

			$strResultado .= '<td align="center">';
			$strResultado .= '<a alt="'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoSistema()).'" title="'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoSistema()).'" class="ancoraSigla">'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrSiglaSistema()).'</a>';
			$strResultado .= '&nbsp;/&nbsp;';
			$strResultado .= '<a alt="'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoOrgaoSistema()).'" title="'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoOrgaoSistema()).'" class="ancoraSigla">'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrSiglaOrgaoSistema()).'</a>';
			$strResultado .= '</td>';
			
			$strResultado .= '<td align="center">';
			$strResultado .= '<a alt="'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrNomeUsuario()).'" title="'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrNomeUsuario()).'" class="ancoraSigla">'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrSiglaUsuario()).'</a>';
			$strResultado .= '&nbsp;/&nbsp;';
			$strResultado .= '<a alt="'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoOrgaoUsuario()).'" title="'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoOrgaoUsuario()).'" class="ancoraSigla">'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrSiglaOrgaoUsuario()).'</a>';
			$strResultado .= '</td>';

			$strResultado .= '<td align="center">';
			$strResultado .= '<a alt="'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoUnidade()).'" title="'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrSiglaUnidade()).'</a>';
			$strResultado .= '&nbsp;/&nbsp;';
			$strResultado .= '<a alt="'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoOrgaoUnidade()).'" title="'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoOrgaoUnidade()).'" class="ancoraSigla">'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrSiglaOrgaoUnidade()).'</a>';
			$strResultado .= '</td>';
			
			$strResultado .= '<td align="center">'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrNomePerfil()).'</td>';
			$strResultado .= '</tr>'."\n";
		}
		$strResultado .= '</table>';
	}

  $strItensSelOrgaoUsuario = OrgaoINT::montarSelectSiglaTodos('null','&nbsp;',$numIdOrgaoUsuario);	
  $strItensSelOrgaoUnidade = OrgaoINT::montarSelectSiglaTodos('null','&nbsp;',$numIdOrgaoUnidade);	
  
  //$strItensSelUnidade = UnidadeINT::montarSelectSigla('null','&nbsp;',$numIdUnidade, $numIdOrgaoUnidade);
  $strItensSelUnidade = UnidadeINT::montarSelectSiglaAutorizadas('null','&nbsp;',$numIdUnidade, $numIdOrgaoUnidade, $numIdSistema);
  
	$strLinkAjaxUsuario = SessaoSip::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_auto_completar');
  $strLinkAjaxUnidades = SessaoSip::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=unidade_montar_select_sigla');  
	
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

#lblOrgaoUsuario {position:absolute;left:0%;top:0%;width:35%;}
#selOrgaoUsuario {position:absolute;left:0%;top:20%;width:25%;}

#lblUsuarioCopia {position:absolute;left:0%;top:50%;width:35%;}
#txtUsuarioCopia {position:absolute;left:0%;top:70%;width:35%;}

#fldUnidade {position:absolute;left:40%;top:5%;height:80%;width:30%;}
#divOptMesmaUnidade {position:absolute;left:5%;top:<?=PaginaSip::getInstance()->isBolAjustarTopFieldset()?'20%':'30%'?>;}
#divOptUnidadeInformada {position:absolute;left:5%;top:<?=PaginaSip::getInstance()->isBolAjustarTopFieldset()?'50%':'65%'?>;}

#lblOrgaoUnidade {position:absolute;left:70%;top:0%;width:25%;visibility:hidden;}
#selOrgaoUnidade {position:absolute;left:70%;top:20%;width:25%;visibility:hidden;}

#lblUnidade {position:absolute;left:70%;top:50%;width:25%;visibility:hidden;}
#selUnidade {position:absolute;left:70%;top:70%;width:25%;visibility:hidden;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

var objAjaxUnidades = null;
var objAjaxUsuario = null;

function inicializar(){

  //COMBO DE UNIDADES
  objAjaxUnidades = new infraAjaxMontarSelectDependente('selOrgaoUnidade','selUnidade','<?=$strLinkAjaxUnidades?>');
  objAjaxUnidades.prepararExecucao = function(){
    return infraAjaxMontarPostPadraoSelect('null','','') + '&idOrgaoUnidade='+document.getElementById('selOrgaoUnidade').value;
  }
  objAjaxUnidades.processarResultado = function(){
    //alert('Carregou unidades.');
  }

  //AUTO COMPLETAR USUARIO
  objAjaxUsuario = new infraAjaxAutoCompletar('hdnIdUsuarioCopia','txtUsuarioCopia','<?=$strLinkAjaxUsuario?>');
  objAjaxUsuario.prepararExecucao = function(){
    if (!infraSelectSelecionado('selOrgaoUsuario')){
      alert('Selecione Órgão do Usuário.');
      document.getElementById('selOrgaoUsuario').focus();
      return false;
    }
    return 'sigla='+document.getElementById('txtUsuarioCopia').value + '&idOrgao='+document.getElementById('selOrgaoUsuario').value;
  };

  objAjaxUsuario.selecionar('<?=$numIdUsuario;?>','<?=PaginaSip::getInstance()->formatarParametrosJavascript($strSiglaUsuario,false);?>');

  if ('<?=$_GET['acao']?>'=='permissao_copiar'){
    document.getElementById('selOrgaoUsuario').focus();
  }

	tipoUnidadeDestino();

  infraEfeitoTabelas();
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
	
  if (infraTrim(document.getElementById('txtUsuarioCopia').value)=='') {
    alert('Informe um Usuário.');
    document.getElementById('txtUsuarioCopia').focus();
    return false;
  }

	if (!document.getElementById('optMesmaUnidade').checked && !document.getElementById('optUnidadeInformada').checked){
		alert('Informe tipo da Unidade Destino.');
		document.getElementById('optMesmaUnidade').focus();
		return false;
  }

	if (document.getElementById('optUnidadeInformada').checked){
		if (!infraSelectSelecionado(document.getElementById('selOrgaoUnidade'))) {
			alert('Selecione Órgão da Unidade.');
			document.getElementById('selOrgaoUnidade').focus();
			return false;
		}

		if (!infraSelectSelecionado(document.getElementById('selUnidade'))) {
			alert('Selecione uma Unidade.');
			document.getElementById('selUnidade').focus();
			return false;
		}
  }

	if (document.getElementById('hdnInfraItensSelecionados').value==''){
		alert('Nenhuma Permissão selecionada.');
		return false;
	}

  return true;
}

function tipoUnidadeDestino(){
  if (document.getElementById('optMesmaUnidade').checked){
    document.getElementById('lblOrgaoUnidade').style.visibility = 'hidden';
	  document.getElementById('selOrgaoUnidade').style.visibility = 'hidden';
		document.getElementById('lblUnidade').style.visibility = 'hidden';
		document.getElementById('selUnidade').style.visibility = 'hidden';
  }else if (document.getElementById('optUnidadeInformada').checked){
		document.getElementById('lblOrgaoUnidade').style.visibility = 'visible';
		document.getElementById('selOrgaoUnidade').style.visibility = 'visible';
		document.getElementById('lblUnidade').style.visibility = 'visible';
		document.getElementById('selUnidade').style.visibility = 'visible';
  }
}

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmPermissaoCopiar" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSip::getInstance()->assinarLink('permissao_copiar.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSip::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSip::getInstance()->montarAreaValidacao();
PaginaSip::getInstance()->abrirAreaDados('10em');
?>

  <label id="lblOrgaoUsuario" for="selOrgaoUsuario" class="infraLabelObrigatorio">Órgão do Usuário:</label>
  <select id="selOrgaoUsuario" name="selOrgaoUsuario" onchange="objAjaxUsuario.limpar();" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
  <?=$strItensSelOrgaoUsuario?>
  </select>

  <label id="lblUsuarioCopia" for="txtUsuarioCopia" class="infraLabelObrigatorio">Usuário:</label>
  <input type="text" id="txtUsuarioCopia" name="txtUsuarioCopia" class="infraText" value="<?=PaginaSip::tratarHTML($strSiglaUsuario)?>" maxlength="100" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?> />

	<fieldset id="fldUnidade" class="infraFieldset" >
		<legend class="infraLegend">&nbsp;Unidade Destino&nbsp;</legend>

		<div id="divOptMesmaUnidade" class="infraDivRadio">
			<input type="radio" name="rdoTipoUnidadeDestino" id="optMesmaUnidade" value="M" onclick="tipoUnidadeDestino()" class="infraRadio" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$_POST['rdoTipoUnidadeDestino']=='M'?'checked=checked':''?> />
			<label id="lblMesmaUnidade" for="optMesmaUnidade" class="infraLabelRadio">Mesma da permissão original</label> <br/>
		</div>

		<div id="divOptUnidadeInformada" class="infraDivRadio">
			<input type="radio" name="rdoTipoUnidadeDestino" id="optUnidadeInformada" value="I" onclick="tipoUnidadeDestino()" class="infraRadio" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$_POST['rdoTipoUnidadeDestino']=='I'?'checked=checked':''?> />
			<label id="lblUnidadeInformada" for="optUnidadeInformada" class="infraLabelRadio">Escolher</label> <br/>
		</div>

	</fieldset>

	<label id="lblOrgaoUnidade" for="selOrgaoUnidade" class="infraLabelObrigatorio">Órgão da Unidade:</label>
	<select id="selOrgaoUnidade" name="selOrgaoUnidade" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
		<?=$strItensSelOrgaoUnidade?>
	</select>

	<label id="lblUnidade" for="selUnidade" class="infraLabelObrigatorio">Unidade:</label>
	<select id="selUnidade" name="selUnidade" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
		<?=$strItensSelUnidade?>
	</select>

  <input type="hidden" id="hdnIdSistema" name="hdnIdSistema" value="<?=$numIdSistema;?>" />
  <input type="hidden" id="hdnIdUsuarioCopia" name="hdnIdUsuarioCopia" value="<?=$strIdUsuario;?>" />
  <?
	
  PaginaSip::getInstance()->fecharAreaDados();
	//PaginaSip::getInstance()->montarAreaTabela($strResultado,$numRegistros);
	PaginaSip::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSip::getInstance()->montarAreaDebug();
  //PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>