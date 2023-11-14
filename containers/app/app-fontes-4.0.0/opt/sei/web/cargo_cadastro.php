<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/12/2007 - criado por fbv
*
* Versão do Gerador de Código: 1.10.1
*
* Versão no CVS: $Id$
*/


try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(false);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->verificarSelecao('cargo_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selTratamento','selVocativo','selTitulo'));

  $objCargoDTO = new CargoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'cargo_cadastrar':
      $strTitulo = 'Novo Cargo';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarCargo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objCargoDTO->setNumIdCargo(null);
      $objCargoDTO->setStrExpressao($_POST['txtExpressao']);

      $numIdTratamento = PaginaSEI::getInstance()->recuperarCampo('selTratamento');
      if ($numIdTratamento!==''){
        $objCargoDTO->setNumIdTratamento($numIdTratamento);
      }else{
        $objCargoDTO->setNumIdTratamento(null);
      }

      $numIdVocativo = PaginaSEI::getInstance()->recuperarCampo('selVocativo');
      if ($numIdVocativo!==''){
        $objCargoDTO->setNumIdVocativo($numIdVocativo);
      }else{
        $objCargoDTO->setNumIdVocativo(null);
      }

      $numIdTitulo = PaginaSEI::getInstance()->recuperarCampo('selTitulo');
      if ($numIdTitulo!==''){
        $objCargoDTO->setNumIdTitulo($numIdTitulo);
      }else{
        $objCargoDTO->setNumIdTitulo(null);
      }

      $objCargoDTO->setStrStaGenero($_POST['rdoStaGenero']);

      $objCargoDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarCargo'])) {
        try{
          $objCargoRN = new CargoRN();
          $objCargoDTO = $objCargoRN->cadastrarRN0299($objCargoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Cargo "'.$objCargoDTO->getStrExpressao().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_cargo='.$objCargoDTO->getNumIdCargo().'#ID-'.$objCargoDTO->getNumIdCargo()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'cargo_alterar':
      $strTitulo = 'Alterar Cargo';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarCargo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_cargo'])){
        $objCargoDTO->setNumIdCargo($_GET['id_cargo']);
        $objCargoDTO->retTodos();
        $objCargoRN = new CargoRN();
        $objCargoDTO = $objCargoRN->consultarRN0301($objCargoDTO);
        if ($objCargoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objCargoDTO->setNumIdCargo($_POST['hdnIdCargo']);
        $objCargoDTO->setStrExpressao($_POST['txtExpressao']);
        $objCargoDTO->setNumIdVocativo($_POST['selVocativo']);
        $objCargoDTO->setNumIdTratamento($_POST['selTratamento']);
        $objCargoDTO->setNumIdTitulo($_POST['selTitulo']);
        $objCargoDTO->setStrStaGenero($_POST['rdoStaGenero']);
        $objCargoDTO->setStrSinAtivo('S');
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$objCargoDTO->getNumIdCargo().'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarCargo'])) {
        try{
          $objCargoRN = new CargoRN();
          $objCargoRN->alterarRN0300($objCargoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Cargo "'.$objCargoDTO->getStrExpressao().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'#ID-'.$objCargoDTO->getNumIdCargo()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'cargo_consultar':
      $strTitulo = "Consultar Cargo";
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$_GET['id_cargo'].'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objCargoDTO->setNumIdCargo($_GET['id_cargo']);
      $objCargoDTO->retTodos();
      $objCargoRN = new CargoRN();
      $objCargoDTO = $objCargoRN->consultarRN0301($objCargoDTO);
      if ($objCargoDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelTratamento = TratamentoINT::montarSelectExpressaoRI0467('null','&nbsp;',$objCargoDTO->getNumIdTratamento());
  $strItensSelTitulo = TituloINT::montarSelectExpressaoAbreviatura('null','&nbsp;',$objCargoDTO->getNumIdTitulo());
  $strItensSelVocativo = VocativoINT::montarSelectExpressaoRI0469('null','&nbsp;',$objCargoDTO->getNumIdVocativo());

  $strLinkNovoTratamento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tratamento_cadastrar&acao_origem=' . $_GET['acao'].'&cargo=1');
  $strLinkNovoTitulo = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=titulo_cadastrar&acao_origem=' . $_GET['acao'].'&cargo=1');
  $strLinkNovoVocativo = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=vocativo_cadastrar&acao_origem=' . $_GET['acao'].'&cargo=1');

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

#lblExpressao {position:absolute;left:0%;top:0%;width:59%;}
#txtExpressao {position:absolute;left:0%;top:6%;width:59%;}

#lblTratamento {position:absolute;left:0%;top:16%;width:60%;}
#selTratamento {position:absolute;left:0%;top:22%;width:60%;}
#imgNovoTratamento {position:absolute;left:61%;top:22.5%;}

#lblVocativo {position:absolute;left:0%;top:32%;width:60%;}
#selVocativo {position:absolute;left:0%;top:38%;width:60%;}
#imgNovoVocativo {position:absolute;left:61%;top:38.5%;}

#lblTitulo {position:absolute;left:0%;top:48%;width:60%;}
#selTitulo {position:absolute;left:0%;top:54%;width:60%;}
#imgNovoTitulo {position:absolute;left:61%;top:54.5%;}

#fldStaGenero {position:absolute;left:0%;top:66%;height:25%;width:21%;}
#divOptFeminino {position:absolute;left:15%;top:30%;}
#divOptMasculino {position:absolute;left:15%;top:60%;}

<?
PaginaSEI::getInstance()->fecharStyle();

if (PaginaSEI::getInstance()->isBolAjustarTopFieldset()){
  PaginaSEI::getInstance()->abrirStyle();
  ?>
  #divOptFeminino {top:10%;}
  #divOptMasculino {top:50%;}
  <?
  PaginaSEI::getInstance()->fecharStyle();
}

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='cargo_cadastrar'){
    document.getElementById('txtExpressao').focus();
  } else if ('<?=$_GET['acao']?>'=='cargo_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
  	document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas();
}

function OnSubmitForm() {
  return validarFormRI0330();
}

function validarFormRI0330() {
  if (infraTrim(document.getElementById('txtExpressao').value)=='') {
    alert('Informe a Expressão.');
    document.getElementById('txtExpressao').focus();
    return false;
  }

  if (!document.getElementById('optFeminino').checked && !document.getElementById('optMasculino').checked) {
    alert('Informe o Gênero.');
    document.getElementById('optFeminino').focus();
    return false;
  }

  return true;
}

function novoTratamento(){
  infraAbrirJanelaModal('<?=$strLinkNovoTratamento?>', 700, 250);
}

function novoTitulo(){
  infraAbrirJanelaModal('<?=$strLinkNovoTitulo?>', 700, 250);
}

function novoVocativo(){
  infraAbrirJanelaModal('<?=$strLinkNovoVocativo?>', 700, 250);
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmCargoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>

  <label id="lblExpressao" for="txtExpressao" accesskey="E" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">E</span>xpressão:</label>
  <input type="text" id="txtExpressao" name="txtExpressao" class="infraText" value="<?=PaginaSEI::tratarHTML($objCargoDTO->getStrExpressao())?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblTratamento" for="selTratamento" class="infraLabelOpcional">Tratamento:</label>
  <select id="selTratamento" name="selTratamento" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelTratamento?>
  </select>
  <img id="imgNovoTratamento" onclick="novoTratamento();" src="<?=PaginaSEI::getInstance()->getIconeMais()?>" alt="Novo Tratamento" title="Novo Tratamento" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>

  <label id="lblVocativo" for="selVocativo" class="infraLabelOpcional">Vocativo:</label>
  <select id="selVocativo" name="selVocativo" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelVocativo?>
  </select>
  <img id="imgNovoVocativo" onclick="novoVocativo();" src="<?=PaginaSEI::getInstance()->getIconeMais()?>" alt="Novo Vocativo" title="Novo Vocativo" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>

  <label id="lblTitulo" for="selTitulo" class="infraLabelOpcional">Título:</label>
  <select id="selTitulo" name="selTitulo" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelTitulo?>
  </select>
  <img id="imgNovoTitulo" onclick="novoTitulo();" src="<?=PaginaSEI::getInstance()->getIconeMais()?>" alt="Novo Título" title="Novo Título" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>


  <fieldset id="fldStaGenero" class="infraFieldset">
    <legend class="infraLegend">Gênero</legend>

    <div id="divOptFeminino" class="infraDivRadio">
      <input type="radio" name="rdoStaGenero" id="optFeminino" value="F" <?=($objCargoDTO->getStrStaGenero()==ContatoRN::$TG_FEMININO?'checked="checked"':'')?> class="infraRadio"/>
      <label id="lblFeminino" for="optFeminino" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Feminino</label>
    </div>

    <div id="divOptMasculino" class="infraDivRadio">
      <input type="radio" name="rdoStaGenero" id="optMasculino" value="M" <?=($objCargoDTO->getStrStaGenero()==ContatoRN::$TG_MASCULINO?'checked="checked"':'')?> class="infraRadio"/>
      <label id="lblMasculino" for="optMasculino" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Masculino</label>
    </div>

  </fieldset>

  <input type="hidden" id="hdnIdCargo" name="hdnIdCargo" value="<?=$objCargoDTO->getNumIdCargo();?>" />
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