<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/10/2013 - criado por mga
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->verificarSelecao('hipotese_legal_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objHipoteseLegalDTO = new HipoteseLegalDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'hipotese_legal_cadastrar':
      $strTitulo = 'Nova Hipótese Legal';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarHipoteseLegal" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objHipoteseLegalDTO->setNumIdHipoteseLegal(null);
      $objHipoteseLegalDTO->setStrStaNivelAcesso($_POST['rdoNivelAcesso']);
      $objHipoteseLegalDTO->setStrNome($_POST['txtNome']);
      $objHipoteseLegalDTO->setStrBaseLegal($_POST['txtBaseLegal']);
      $objHipoteseLegalDTO->setStrDescricao($_POST['txaDescricao']);
      $objHipoteseLegalDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarHipoteseLegal'])) {
        try{
          $objHipoteseLegalRN = new HipoteseLegalRN();
          $objHipoteseLegalDTO = $objHipoteseLegalRN->cadastrar($objHipoteseLegalDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Hipótese Legal "'.$objHipoteseLegalDTO->getStrNome().'" cadastrada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_hipotese_legal='.$objHipoteseLegalDTO->getNumIdHipoteseLegal().PaginaSEI::getInstance()->montarAncora($objHipoteseLegalDTO->getNumIdHipoteseLegal())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'hipotese_legal_alterar':
      $strTitulo = 'Alterar Hipótese Legal';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarHipoteseLegal" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_hipotese_legal'])){
        $objHipoteseLegalDTO->setNumIdHipoteseLegal($_GET['id_hipotese_legal']);
        $objHipoteseLegalDTO->retTodos();
        $objHipoteseLegalRN = new HipoteseLegalRN();
        $objHipoteseLegalDTO = $objHipoteseLegalRN->consultar($objHipoteseLegalDTO);
        if ($objHipoteseLegalDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objHipoteseLegalDTO->setNumIdHipoteseLegal($_POST['hdnIdHipoteseLegal']);
        $objHipoteseLegalDTO->setStrStaNivelAcesso($_POST['rdoNivelAcesso']);
        $objHipoteseLegalDTO->setStrNome($_POST['txtNome']);
        $objHipoteseLegalDTO->setStrBaseLegal($_POST['txtBaseLegal']);
        $objHipoteseLegalDTO->setStrDescricao($_POST['txaDescricao']);
        $objHipoteseLegalDTO->setStrSinAtivo('S');
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objHipoteseLegalDTO->getNumIdHipoteseLegal())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarHipoteseLegal'])) {
        try{
          $objHipoteseLegalRN = new HipoteseLegalRN();
          $objHipoteseLegalRN->alterar($objHipoteseLegalDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Hipótese Legal "'.$objHipoteseLegalDTO->getStrNome().'" alterada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objHipoteseLegalDTO->getNumIdHipoteseLegal())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'hipotese_legal_consultar':
      $strTitulo = 'Consultar Hipótese Legal';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_hipotese_legal'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objHipoteseLegalDTO->setNumIdHipoteseLegal($_GET['id_hipotese_legal']);
      $objHipoteseLegalDTO->setBolExclusaoLogica(false);
      $objHipoteseLegalDTO->retTodos();
      $objHipoteseLegalRN = new HipoteseLegalRN();
      $objHipoteseLegalDTO = $objHipoteseLegalRN->consultar($objHipoteseLegalDTO);
      if ($objHipoteseLegalDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  
  $bolHabilitarSigiloso = true;
  $bolHabilitarRestrito = true;
  if ($_GET['acao']=='hipotese_legal_consultar'){
    $bolHabilitarSigiloso = false;
    $bolHabilitarRestrito = false;
  }
  
  $bolMarcarSigiloso = false;
  $bolMarcarRestrito = false;
  if ($objHipoteseLegalDTO->getStrStaNivelAcesso()==ProtocoloRN::$NA_SIGILOSO){
    $bolMarcarSigiloso = true;
  }else if ($objHipoteseLegalDTO->getStrStaNivelAcesso()==ProtocoloRN::$NA_RESTRITO){
    $bolMarcarRestrito = true;
  }
  
  
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

#fldNivelAcesso {position:absolute;left:0%;top:0%;height:20%;width:50%;}
#divOptSigiloso  {position:absolute;left:20%;top:45%;}
#divOptRestrito {position:absolute;left:60%;top:45%;}

#lblNome {position:absolute;left:0%;top:32%;width:50%;}
#txtNome {position:absolute;left:0%;top:38%;width:50%;}

#lblBaseLegal {position:absolute;left:0%;top:48%;width:50%;}
#txtBaseLegal {position:absolute;left:0%;top:54%;width:50%;}

#lblDescricao {position:absolute;left:0%;top:64%;width:80%;}
#txaDescricao {position:absolute;left:0%;top:70%;width:80%;}

<?
if (PaginaSEI::getInstance()->isBolAjustarTopFieldset()){
?>

#divOptSigiloso {top:30%;}
#divOptRestrito {top:30%;}

<?
}
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='hipotese_legal_cadastrar'){
    document.getElementById('txtNome').focus();
  } else if ('<?=$_GET['acao']?>'=='hipotese_legal_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas();
}

function validarCadastro() {
  if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('Informe o Nome.');
    document.getElementById('txtNome').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtBaseLegal').value)=='') {
    alert('Informe a Base Legal.');
    document.getElementById('txtBaseLegal').focus();
    return false;
  }

  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmHipoteseLegalCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>

  <fieldset id="fldNivelAcesso" class="infraFieldset">
  	<legend class="infraLegend">Nível de Restrição de Acesso</legend>
  	
      <div id="divOptSigiloso" class="infraDivRadio"> 
  			<input <?=$bolHabilitarSigiloso?'':'disabled="disabled"'?> type="radio" name="rdoNivelAcesso" id="optSigiloso" value="<?=ProtocoloRN::$NA_SIGILOSO?>" <?=($bolMarcarSigiloso?'checked="checked"':'')?> class="infraRadio"/>
  	    <span <?=$bolHabilitarSigiloso?'':'disabled="disabled"'?> id="spnSigiloso"><label id="lblSigiloso" for="optSigiloso" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Sigiloso</label><label>&nbsp;</label></span>
	    </div>
  	  
  	  <div id="divOptRestrito" class="infraDivRadio">
  			<input <?=$bolHabilitarRestrito?'':'disabled="disabled"'?> type="radio" name="rdoNivelAcesso" id="optRestrito" value="<?=ProtocoloRN::$NA_RESTRITO?>" <?=($bolMarcarRestrito?'checked="checked"':'')?> class="infraRadio"/>
  	    <span <?=$bolHabilitarRestrito?'':'disabled="disabled"'?> id="spnRestrito"><label id="lblRestrito" for="optRestrito" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Restrito</label></span>
	    </div>
	    
  </fieldset>       

  <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objHipoteseLegalDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblBaseLegal" for="txtBaseLegal" accesskey="B" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">B</span>ase Legal:</label>
  <input type="text" id="txtBaseLegal" name="txtBaseLegal" class="infraText" value="<?=PaginaSEI::tratarHTML($objHipoteseLegalDTO->getStrBaseLegal());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblDescricao" for="txaDescricao" accesskey="D" class="infraLabelOpcional"><span class="infraTeclaAtalho">D</span>escrição:</label>
  <textarea id="txaDescricao" name="txaDescricao" rows="4" class="infraTextarea" onkeypress="return infraMascaraTexto(this,event,500);" maxlength="500" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objHipoteseLegalDTO->getStrDescricao());?></textarea>

  <input type="hidden" id="hdnIdHipoteseLegal" name="hdnIdHipoteseLegal" value="<?=$objHipoteseLegalDTO->getNumIdHipoteseLegal();?>" />
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>