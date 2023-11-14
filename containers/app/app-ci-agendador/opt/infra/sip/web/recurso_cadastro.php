<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/05/2009 - criado por mga
*
* Versão do Gerador de Código: 1.26.0
*
* Versão no CVS: $Id$
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

  PaginaSip::getInstance()->verificarSelecao('recurso_selecionar');

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistema','selSistema'));

  $objRecursoDTO = new RecursoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'recurso_cadastrar':
      $strTitulo = 'Novo Recurso';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarRecurso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objRecursoDTO->setNumIdRecurso(null);
      
			//ORGAO
			$numIdOrgao = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema',SessaoSip::getInstance()->getNumIdOrgaoSistema());
			if ($numIdOrgao!==''){
				$objRecursoDTO->setNumIdOrgaoSistema($numIdOrgao);
			}else{
				$objRecursoDTO->setNumIdOrgaoSistema(null);
			}
			
			//SISTEMA
			$numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema');
			if ($numIdSistema!==''){
				$objRecursoDTO->setNumIdSistema($numIdSistema);
			}else{
				$objRecursoDTO->setNumIdSistema(null);
			}

      $objRecursoDTO->setStrNome($_POST['txtNome']);
      $objRecursoDTO->setStrDescricao($_POST['txtDescricao']);
      $objRecursoDTO->setStrCaminho($_POST['txtCaminho']);
      $objRecursoDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarRecurso'])) {
        try{
          $objRecursoRN = new RecursoRN();
          $objRecursoDTO = $objRecursoRN->cadastrar($objRecursoDTO);
          PaginaSip::getInstance()->setStrMensagem('Recurso "'.$objRecursoDTO->getStrNome().'" cadastrado com sucesso.');
          header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_sistema='.$objRecursoDTO->getNumIdSistema().'&id_recurso='.$objRecursoDTO->getNumIdRecurso().'#ID-'.$objRecursoDTO->getNumIdSistema().'-'.$objRecursoDTO->getNumIdRecurso()));
          die;
        }catch(Exception $e){
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'recurso_alterar':
      $strTitulo = 'Alterar Recurso';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarRecurso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

			if (isset($_GET['id_recurso']) && isset($_GET['id_sistema'])){
        $objRecursoDTO->setNumIdRecurso($_GET['id_recurso']);
        $objRecursoDTO->setNumIdSistema($_GET['id_sistema']);
        $objRecursoDTO->retTodos(true);
        $objRecursoRN = new RecursoRN();
        $objRecursoDTO = $objRecursoRN->consultar($objRecursoDTO);
        if ($objRecursoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
			} else {
				$objRecursoDTO->setNumIdRecurso($_POST['hdnIdRecurso']);
				$objRecursoDTO->setNumIdOrgaoSistema($_POST['hdnIdOrgaoSistema']);
				$objRecursoDTO->setNumIdSistema($_POST['hdnIdSistema']);
				$objRecursoDTO->setStrNome($_POST['txtNome']);
				$objRecursoDTO->setStrDescricao($_POST['txtDescricao']);
				$objRecursoDTO->setStrCaminho($_POST['txtCaminho']);
				$objRecursoDTO->setStrSinAtivo("S");
			}

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$objRecursoDTO->getNumIdSistema().'-'.$objRecursoDTO->getNumIdRecurso().'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarRecurso'])) {
        try{
          $objRecursoRN = new RecursoRN();
          $objRecursoRN->alterar($objRecursoDTO);
          PaginaSip::getInstance()->setStrMensagem('Recurso "'.$objRecursoDTO->getStrNome().'" alterado com sucesso.');
          header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'#ID-'.$objRecursoDTO->getNumIdSistema().'-'.$objRecursoDTO->getNumIdRecurso()));
          die;
        }catch(Exception $e){
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'recurso_consultar':
      $strTitulo = 'Consultar Recurso';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$_GET['id_sistema'].'-'.$_GET['id_recurso'].'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objRecursoDTO->setNumIdSistema($_GET['id_sistema']);
      $objRecursoDTO->setNumIdRecurso($_GET['id_recurso']);
      $objRecursoDTO->setBolExclusaoLogica(false);
      $objRecursoDTO->retTodos(true);
      $objRecursoRN = new RecursoRN();
      $objRecursoDTO = $objRecursoRN->consultar($objRecursoDTO);
      if ($objRecursoDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaAdministrados('null','&nbsp;',$objRecursoDTO->getNumIdOrgaoSistema());	
  $strItensSelSistema = SistemaINT::montarSelectSiglaAdministrados('null','&nbsp;',$objRecursoDTO->getNumIdSistema(),$objRecursoDTO->getNumIdOrgaoSistema());
  
  if ($_GET['acao']!='recurso_cadastrar'){
    $strItensSelPerfil = RelPerfilRecursoINT::montarSelectPerfisRecurso($objRecursoDTO->getNumIdSistema(), $objRecursoDTO->getNumIdRecurso());
  }
  
}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>

#lblOrgaoSistema {position:absolute;left:0%;top:0%;width:20%;}
#selOrgaoSistema {position:absolute;left:0%;top:4%;width:20%;}

#lblSistema {position:absolute;left:0%;top:11%;width:20%;}
#selSistema {position:absolute;left:0%;top:15%;width:20%;}

#lblNome {position:absolute;left:0%;top:22%;width:50%;}
#txtNome {position:absolute;left:0%;top:26%;width:50%;}

#lblDescricao {position:absolute;left:0%;top:33%;width:80%;}
#txtDescricao {position:absolute;left:0%;top:37%;width:80%;}

#lblCaminho {position:absolute;left:0%;top:44%;width:50%;}
#txtCaminho {position:absolute;left:0%;top:48%;width:50%;}

#lblPerfil {position:absolute;left:0%;top:55%;width:50%;}
#selPerfil {position:absolute;left:0%;top:59%;width:50%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='recurso_cadastrar'){
    document.getElementById('selSistema').focus();
    document.getElementById('lblPerfil').style.visibility='hidden';
    document.getElementById('selPerfil').style.visibility='hidden';
  } else if ('<?=$_GET['acao']?>'=='recurso_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas();
}

function validarCadastro() {
  if (!infraSelectSelecionado(document.getElementById('selOrgaoSistema'))) {
    alert('Selecione um Órgão do Sistema.');
    document.getElementById('selOrgaoSistema').focus();
    return false;
  }

  if (!infraSelectSelecionado('selSistema')) {
    alert('Selecione um Sistema.');
    document.getElementById('selSistema').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('Informe o Nome.');
    document.getElementById('txtNome').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtCaminho').value)=='') {
    alert('Informe o Caminho.');
    document.getElementById('txtCaminho').focus();
    return false;
  }

  return true;
}

function OnSubmitForm() {
  return validarCadastro();
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
<form id="frmRecursoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSip::getInstance()->montarAreaValidacao();
PaginaSip::getInstance()->abrirAreaDados('47em');
?>
  <label id="lblOrgaoSistema" for="selOrgaoSistema" accesskey="r" class="infraLabelObrigatorio">Ó<span class="infraTeclaAtalho">r</span>gão do Sistema:</label>
  <select id="selOrgaoSistema" name="selOrgaoSistema" onchange="trocarOrgaoSistema(this);" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?> >
  <?=$strItensSelOrgaoSistema?>
  </select>

  <label id="lblSistema" for="selSistema" accesskey="S" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">S</span>istema:</label>
  <select id="selSistema" name="selSistema" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
  <?=$strItensSelSistema?>
  </select>

  <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSip::tratarHTML($objRecursoDTO->getStrNome());?>" maxlength="50" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />
  
  <label id="lblDescricao" for="txtDescricao" accesskey="D" class="infraLabelOpcional"><span class="infraTeclaAtalho">D</span>escrição:</label>
  <input type="text" id="txtDescricao" name="txtDescricao" class="infraText" value="<?=PaginaSip::tratarHTML($objRecursoDTO->getStrDescricao());?>" maxlength="200" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />

  <label id="lblCaminho" for="txtCaminho" accesskey="C" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">C</span>aminho:</label>
  <input type="text" id="txtCaminho" name="txtCaminho" class="infraText" value="<?=PaginaSip::tratarHTML($objRecursoDTO->getStrCaminho());?>" maxlength="255" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />

  <label id="lblPerfil" for="selPerfil" accesskey="S" class="infraLabelOpcional">Perfis:</label>
  <select id="selPerfil" name="selPerfil" class="infraSelect" size="10" multiple="multiple" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
  <?=$strItensSelPerfil?>
  </select>
  
  <input type="hidden" name="hdnIdRecurso" value="<?=$objRecursoDTO->getNumIdRecurso();?>" />
  <input type="hidden" name="hdnIdOrgaoSistema" value="<?=$objRecursoDTO->getNumIdOrgaoSistema();?>" />
  <input type="hidden" name="hdnIdSistema" value="<?=$objRecursoDTO->getNumIdSistema();?>" />
  <?
  PaginaSip::getInstance()->fecharAreaDados();
  //PaginaSip::getInstance()->montarAreaDebug();
  //PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>