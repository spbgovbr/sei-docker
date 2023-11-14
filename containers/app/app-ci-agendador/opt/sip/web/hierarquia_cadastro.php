<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/01/2007 - criado por mga
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

  $objHierarquiaDTO = new HierarquiaDTO();


  $arrComandos = array();

  switch($_GET['acao']){
    case 'hierarquia_cadastrar':
      $strTitulo = 'Nova Hierarquia';
      $arrComandos[] = '<input type="submit" name="sbmCadastrarHierarquia" value="Salvar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno()).'\';" class="infraButton" />';
			
			$objHierarquiaDTO->setNumIdHierarquia(null);
			$objHierarquiaDTO->setStrNome($_POST['txtNome']);
			$objHierarquiaDTO->setStrDescricao($_POST['txtDescricao']);
			$objHierarquiaDTO->setDtaDataInicio($_POST['txtDataInicio']);
			$objHierarquiaDTO->setDtaDataFim($_POST['txtDataFim']);
			$objHierarquiaDTO->setStrSinAtivo("S");
			
      if (isset($_POST['sbmCadastrarHierarquia'])) {
        try{
          $objHierarquiaRN = new HierarquiaRN();
          $objHierarquiaDTO = $objHierarquiaRN->cadastrar($objHierarquiaDTO);
          header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao=hierarquia_listar'.PaginaSip::getInstance()->montarAncora($objHierarquiaDTO->getNumIdHierarquia())));
          die;
        }catch(Exception $e){
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }

      break;

    case 'hierarquia_alterar':
      $strTitulo = 'Alterar Hierarquia';
      $arrComandos[] = '<input type="submit" name="sbmAlterarHierarquia" value="Salvar" class="infraButton" />';

			if (isset($_GET['id_hierarquia'])){
        $objHierarquiaDTO->setNumIdHierarquia($_GET['id_hierarquia']);
        $objHierarquiaDTO->retTodos();
        $objHierarquiaRN = new HierarquiaRN();
        $objHierarquiaDTO = $objHierarquiaRN->consultar($objHierarquiaDTO);
        if ($objHierarquiaDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
			} else {
				$objHierarquiaDTO->setNumIdHierarquia($_POST['hdnIdHierarquia']);
				$objHierarquiaDTO->setStrNome($_POST['txtNome']);
				$objHierarquiaDTO->setStrDescricao($_POST['txtDescricao']);
				$objHierarquiaDTO->setDtaDataInicio($_POST['txtDataInicio']);
				$objHierarquiaDTO->setDtaDataFim($_POST['txtDataFim']);
				$objHierarquiaDTO->setStrSinAtivo("S");
			}

      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().PaginaSip::getInstance()->montarAncora($objHierarquiaDTO->getNumIdHierarquia())).'\';" class="infraButton" />';

      if (isset($_POST['sbmAlterarHierarquia'])) {
        try{
          $objHierarquiaRN = new HierarquiaRN();
          $objHierarquiaRN->alterar($objHierarquiaDTO);
          header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao=hierarquia_listar'.PaginaSip::getInstance()->montarAncora($objHierarquiaDTO->getNumIdHierarquia())));
          die;
        }catch(Exception $e){
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'hierarquia_consultar':
      $strTitulo = "Consultar Hierarquia";
      $arrComandos[] = '<input type="button" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().PaginaSip::getInstance()->montarAncora($_GET['id_hierarquia'])).'\';" class="infraButton" />';
      $objHierarquiaDTO->setNumIdHierarquia($_GET['id_hierarquia']);
      $objHierarquiaDTO->setBolExclusaoLogica(false);
      $objHierarquiaDTO->retTodos();
      $objHierarquiaRN = new HierarquiaRN();
      $objHierarquiaDTO = $objHierarquiaRN->consultar($objHierarquiaDTO);
      if ($objHierarquiaDTO==null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }


}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - Hierarquia');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
#lblNome {position:absolute;left:0%;top:0%;width:50%;}
#txtNome {position:absolute;left:0%;top:6%;width:50%;}

#lblDescricao {position:absolute;left:0%;top:16%;width:80%;}
#txtDescricao {position:absolute;left:0%;top:22%;width:80%;}

#lblDataInicio {position:absolute;left:0%;top:32%;width:20%;}
#txtDataInicio {position:absolute;left:0%;top:38%;width:20%;}
#imgCalDataInicio {position:absolute;left:21%;top:38%;}

#lblDataFim {position:absolute;left:0%;top:48%;width:20%;}
#txtDataFim {position:absolute;left:0%;top:54%;width:20%;}
#imgCalDataFim {position:absolute;left:21%;top:54%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='hierarquia_cadastrar'){
    document.getElementById('txtNome').focus();
  } else if ('<?=$_GET['acao']?>'=='hierarquia_consultar'){
    infraDesabilitarCamposAreaDados();
  }
}

function OnSubmitForm() {
  return validarForm();
}

function validarForm() {
	
  if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('Informe Nome.');
    document.getElementById('txtNome').focus();
    return false;
  }
  
  if (infraTrim(document.getElementById('txtDataInicio').value)=='') {
    alert('Informe Data Inicial.');
    document.getElementById('txtDataInicio').focus();
    return false;
  }

  if (!infraValidaData(document.getElementById('txtDataInicio'))){
    return false;
  }

  if (!infraValidaData(document.getElementById('txtDataFim'))){
    return false;
  }

	/*
	if (infraCompararDatas(infraDataAtual(),document.getElementById('txtDataInicio').value)<0){
		alert('Data Inicial não pode estar no passado.');
		document.getElementById('txtDataInicio').focus();
		return false;
	}
	*/

	if (infraCompararDatas(infraDataAtual(),document.getElementById('txtDataFim').value)<0){
		alert('Data Final não pode estar no passado.');
		document.getElementById('txtDataFim').focus();
		return false;
	}
	
	if(infraCompararDatas(document.getElementById('txtDataInicio').value,document.getElementById('txtDataFim').value)<0){
		alert('Data Final deve ser igual ou superior a Data Inicial.');
		document.getElementById('txtDataFim').focus();
		return false;
	}
	
  return true;
}
<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmHierarquiaCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSip::getInstance()->assinarLink(basename(__FILE__).'?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSip::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSip::getInstance()->montarAreaValidacao();
PaginaSip::getInstance()->abrirAreaDados('30em');
?>
  <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSip::tratarHTML($objHierarquiaDTO->getStrNome());?>" maxlength="50" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />

  <label id="lblDescricao" for="txtDescricao" accesskey="D" class="infraLabelOpcional"><span class="infraTeclaAtalho">D</span>escrição:</label>
  <input type="text" id="txtDescricao" name="txtDescricao" class="infraText" value="<?=PaginaSip::tratarHTML($objHierarquiaDTO->getStrDescricao());?>" maxlength="200" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />
  
  <label id="lblDataInicio" for="txtDataInicio" accesskey="I" class="infraLabelObrigatorio">Data <span class="infraTeclaAtalho">I</span>nicial:</label>
  <input type="text" id="txtDataInicio" name="txtDataInicio" onkeypress="return infraMascaraData(this, event)" class="infraText" value="<?=PaginaSip::tratarHTML($objHierarquiaDTO->getDtaDataInicio());?>" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />
  <img src="<?=PaginaSip::getInstance()->getIconeCalendario()?>" id="imgCalDataInicio" title="Selecionar Data Inicial" alt="Selecionar Data Inicial" class="infraImg" onclick="infraCalendario('txtDataInicio',this);"  tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />
  
  <label id="lblDataFim" for="txtDataFim" accesskey="F" class="infraLabelOpcional">Data <span class="infraTeclaAtalho">F</span>inal:</label>
  <input type="text" id="txtDataFim" name="txtDataFim" onkeypress="return infraMascaraData(this, event)" class="infraText" value="<?=PaginaSip::tratarHTML($objHierarquiaDTO->getDtaDataFim());?>" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />
  <img src="<?=PaginaSip::getInstance()->getIconeCalendario()?>" id="imgCalDataFim" title="Selecionar Data Final" alt="Selecionar Data Final" class="infraImg" onclick="infraCalendario('txtDataFim',this);"  tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />

  <input type="hidden" id="hdnIdHierarquia" name="hdnIdHierarquia" value="<?=$objHierarquiaDTO->getNumIdHierarquia();?>" />
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