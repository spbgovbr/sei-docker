<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/12/2006 - criado por mga
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

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoUsuario','selOrgaoUnidade','selUnidade','selOrgaoSistema','selSistema'));
  
  $objCoordenadorUnidadeDTO = new CoordenadorUnidadeDTO(true);

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'coordenador_unidade_cadastrar':
      $strTitulo = 'Novo Coordenador de Unidade';
      $arrComandos[] = '<input type="submit" name="sbmCadastrarCoordenadorUnidade" value="Salvar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=coordenador_unidade_listar').'\';" class="infraButton" />';
			
			//ORGAO USUARIO
		  $numIdOrgaoUsuario = PaginaSip::getInstance()->recuperarCampo('selOrgaoUsuario');
			if ($numIdOrgaoUsuario!==''){
				$objCoordenadorUnidadeDTO->setNumIdOrgaoUsuario($numIdOrgaoUsuario);
			}else{
				$objCoordenadorUnidadeDTO->setNumIdOrgaoUsuario(null);
			}
			
			//USUARIO
			$objCoordenadorUnidadeDTO->setNumIdUsuario($_POST['hdnIdUsuario']);
		  $objCoordenadorUnidadeDTO->setStrSiglaUsuario($_POST['txtUsuario']);
		  
			//ORGAO UNIDADE
			$numIdOrgaoUnidade = PaginaSip::getInstance()->recuperarCampo('selOrgaoUnidade');
			if ($numIdOrgaoUnidade!==''){
				$objCoordenadorUnidadeDTO->setNumIdOrgaoUnidade($numIdOrgaoUnidade);
			}else{
				$objCoordenadorUnidadeDTO->setNumIdOrgaoUnidade(null);
			}

			//UNIDADE
			$numIdUnidade = PaginaSip::getInstance()->recuperarCampo('selUnidade');
			if ($numIdUnidade!==''){
				$objCoordenadorUnidadeDTO->setNumIdUnidade($numIdUnidade);
			}else{
				$objCoordenadorUnidadeDTO->setNumIdUnidade(null);
			}
			
			//ORGAO SISTEMA
			$numIdOrgaoSistema = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema');
			if ($numIdOrgaoSistema!==''){
				$objCoordenadorUnidadeDTO->setNumIdOrgaoSistema($numIdOrgaoSistema);
			}else{
				$objCoordenadorUnidadeDTO->setNumIdOrgaoSistema(null);
			}
			
			//SISTEMA
			$numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema');
			if ($numIdSistema!==''){
				$objCoordenadorUnidadeDTO->setNumIdSistema($numIdSistema);
			}else{
				$objCoordenadorUnidadeDTO->setNumIdSistema(null);
			}
			
			
      if (isset($_POST['sbmCadastrarCoordenadorUnidade'])) {
				try{
					$objCoordenadorUnidadeRN = new CoordenadorUnidadeRN();
					$objCoordenadorUnidadeDTO = $objCoordenadorUnidadeRN->cadastrar($objCoordenadorUnidadeDTO);
					header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao=coordenador_unidade_listar'));
					die;
				}catch(Exception $e){
					PaginaSip::getInstance()->processarExcecao($e);
				}
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaAdministrados('null','&nbsp;',$objCoordenadorUnidadeDTO->getNumIdOrgaoSistema());
  $strItensSelSistema = SistemaINT::montarSelectSiglaAdministrados('null','&nbsp;', $objCoordenadorUnidadeDTO->getNumIdSistema(), $objCoordenadorUnidadeDTO->getNumIdOrgaoSistema());

  //$strItensSelOrgaoUnidade = OrgaoINT::montarSelectSigla('null','&nbsp;',$objCoordenadorUnidadeDTO->getNumIdOrgaoUnidade());
  //$strItensSelUnidade = UnidadeINT::montarSelectSigla('null','&nbsp;',$objCoordenadorUnidadeDTO->getNumIdUnidade(), $objCoordenadorUnidadeDTO->getNumIdOrgaoUnidade());
  $strItensSelOrgaoUnidade = OrgaoINT::montarSelectSiglaTodos('null','&nbsp;',$objCoordenadorUnidadeDTO->getNumIdOrgaoUnidade());
  $strItensSelUnidade = UnidadeINT::montarSelectSiglaAutorizadas('null','&nbsp;',$objCoordenadorUnidadeDTO->getNumIdUnidade(), $objCoordenadorUnidadeDTO->getNumIdOrgaoUnidade(), $objCoordenadorUnidadeDTO->getNumIdSistema());

  $strItensSelOrgaoUsuario = OrgaoINT::montarSelectSigla('null','&nbsp;',$objCoordenadorUnidadeDTO->getNumIdOrgaoUsuario());
  //$strItensSelUsuario = UsuarioINT::montarSelectSigla('null','&nbsp;',$objCoordenadorUnidadeDTO->getNumIdUsuario(), $objCoordenadorUnidadeDTO->getNumIdOrgaoUsuario());

  $strLinkAjaxSistemas = SessaoSip::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=sistema_montar_select_sigla_administrados');
  $strLinkAjaxUnidades = SessaoSip::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=unidade_montar_select_sigla_autorizadas');
  $strLinkAjaxUsuario = SessaoSip::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_auto_completar_sigla_nome');   

}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - Usuário com Permissão');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>

#lblOrgaoSistema {position:absolute;left:0%;top:0%;width:25%;}
#selOrgaoSistema {position:absolute;left:0%;top:6%;width:25%;}

#lblSistema {position:absolute;left:0%;top:16%;width:25%;}
#selSistema {position:absolute;left:0%;top:22%;width:25%;}

#lblOrgaoUnidade {position:absolute;left:33%;top:0%;width:25%;}
#selOrgaoUnidade {position:absolute;left:33%;top:6%;width:25%;}

#lblUnidade {position:absolute;left:33%;top:16%;width:25%;}
#selUnidade {position:absolute;left:33%;top:22%;width:25%;}

#lblOrgaoUsuario {position:absolute;left:66%;top:0%;width:25%;}
#selOrgaoUsuario {position:absolute;left:66%;top:6%;width:25%;}

#lblUsuario {position:absolute;left:66%;top:16%;width:25%;}
#txtUsuario {position:absolute;left:66%;top:22%;width:25%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>


var objAjaxUsuario = null;
var objAjaxUnidades = null;
var objAjaxSistemas = null;

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
    //document.getElementById('lblUsuarioNome').innerHTML = complemento;
  };

  //COMBO DE UNIDADES
  objAjaxUnidades = new infraAjaxMontarSelectDependente('selOrgaoUnidade','selUnidade','<?=$strLinkAjaxUnidades?>');
  objAjaxUnidades.prepararExecucao = function(){
    return infraAjaxMontarPostPadraoSelect('null','','') + '&idOrgaoUnidade=' + document.getElementById('selOrgaoUnidade').value + '&idSistema=' + document.getElementById('selSistema').value;
  }
  objAjaxUnidades.processarResultado = function(){
    //alert('Carregou unidades.');
  }

  //COMBO DE SISTEMAS 
  objAjaxSistemas = new infraAjaxMontarSelectDependente('selOrgaoSistema','selSistema','<?=$strLinkAjaxSistemas?>');
  objAjaxSistemas.prepararExecucao = function(){
    return infraAjaxMontarPostPadraoSelect('null','','') + '&idOrgaoSistema='+document.getElementById('selOrgaoSistema').value;
  }
  objAjaxSistemas.processarResultado = function(){
    //alert('Carregou sistemas.');
  }
  
  if ('<?=$_GET['acao']?>'=='coordenador_unidade_cadastrar'){
    document.getElementById('selOrgaoUsuario').focus();
  } else if ('<?=$_GET['acao']?>'=='coordenador_unidade_consultar'){
    infraDesabilitarCamposAreaDados();
  }
}

function OnSubmitForm() {

  if (!infraSelectSelecionado(document.getElementById('selOrgaoSistema'))) {
    alert('Selecione Órgão do Sistema.');
    document.getElementById('selOrgaoSistema').focus();
    return false;
  }

  if (!infraSelectSelecionado(document.getElementById('selSistema'))) {
    alert('Selecione um Sistema.');
    document.getElementById('selSistema').focus();
    return false;
  }

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
	
  if (!infraSelectSelecionado(document.getElementById('selOrgaoUsuario'))) {
    alert('Selecione Órgão do Usuário.');
    document.getElementById('selOrgaoUsuario').focus();
    return false;
  }
	
  if (infraTrim(document.getElementById('hdnIdUsuario').value)=='') {
    alert('Informe um Usuário.');
    document.getElementById('txtUsuario').focus();
    return false;
  }
	
  return true;
}

function trocarOrgaoSistema(obj){
  document.getElementById('selSistema').value='null';
  obj.form.submit();
}

function trocarOrgaoUnidade(obj){
  document.getElementById('selUnidade').value='null';
  obj.form.submit();
}

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmCoordenadorUnidadeCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSip::getInstance()->assinarLink('coordenador_unidade_cadastro.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSip::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSip::getInstance()->montarAreaValidacao();
PaginaSip::getInstance()->abrirAreaDados('30em');
?>

  <label id="lblOrgaoSistema" for="selOrgaoSistema" accesskey="o" class="infraLabelObrigatorio">Ó<span class="infraTeclaAtalho">r</span>gão do Sistema:</label>
  <select id="selOrgaoSistema" name="selOrgaoSistema" onchange="trocarOrgaoSistema(this);" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
  <?=$strItensSelOrgaoSistema?>
  </select>
	
  <label id="lblSistema" for="selSistema" accesskey="S" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">S</span>istema:</label>
  <select id="selSistema" name="selSistema" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
  <?=$strItensSelSistema?>
  </select>

  <label id="lblOrgaoUnidade" for="selOrgaoUnidade" accesskey="o" class="infraLabelObrigatorio">Ór<span class="infraTeclaAtalho">g</span>ão da Unidade:</label>
  <select id="selOrgaoUnidade" name="selOrgaoUnidade" onchange="trocarOrgaoUnidade(this);" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
  <?=$strItensSelOrgaoUnidade?>
  </select>
	
  <label id="lblUnidade" for="selUnidade" accesskey="U" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">U</span>nidade:</label>
  <select id="selUnidade" name="selUnidade" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
  <?=$strItensSelUnidade?>
  </select>

  <label id="lblOrgaoUsuario" for="selOrgaoUsuario" accesskey="o" class="infraLabelObrigatorio">Órgã<span class="infraTeclaAtalho">o</span> do Usuário:</label>
  <select id="selOrgaoUsuario" name="selOrgaoUsuario" onchange="objAjaxUsuario.limpar();" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
  <?=$strItensSelOrgaoUsuario?>
  </select>
	
  <label id="lblUsuario" for="txtUsuario" accesskey="u" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">U</span>suário:</label>
  <input type="text" id="txtUsuario" name="txtUsuario" class="infraText" value="<?=PaginaSip::tratarHTML($objCoordenadorUnidadeDTO->getStrSiglaUsuario())?>" maxlength="100" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?> />

  <input type="hidden" id="hdnIdUsuario" name="hdnIdUsuario" value="<?=$objCoordenadorUnidadeDTO->getNumIdUsuario();?>" />
  
  <?
  PaginaSip::getInstance()->fecharAreaDados();
  PaginaSip::getInstance()->montarAreaDebug();
  //PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>