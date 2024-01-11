<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.13.1
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

  PaginaSEI::getInstance()->verificarSelecao('assunto_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $strParametros = '';
  if(isset($_GET['id_tabela_assuntos'])){
    $strParametros .= '&id_tabela_assuntos='.$_GET['id_tabela_assuntos'];
  }

  if (isset($_POST['hdnAssuntoIdentificador'])) {

    if (!is_numeric($_POST['hdnAssuntoIdentificador'])) {
      throw new InfraException('Identificador de assunto inválido.');
    }

    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

    $objAssuntoDTO = new AssuntoDTO();
    $objAssuntoDTO->setBolExclusaoLogica(false);
    $objAssuntoDTO->retNumIdAssunto();
    $objAssuntoDTO->retNumIdTabelaAssuntos();
    $objAssuntoDTO->setNumIdAssunto($_POST['hdnAssuntoIdentificador']);

    $objAssuntoRN = new AssuntoRN();
    $objAssuntoDTO = $objAssuntoRN->consultarRN0256($objAssuntoDTO);

    if ($objAssuntoDTO == null) {
      throw new InfraException('Assunto não encontrado.');
    }

    $_GET['id_assunto'] = $objAssuntoDTO->getNumIdAssunto();
    $_GET['id_tabela_assuntos'] = $objAssuntoDTO->getNumIdTabelaAssuntos();

  }

  $objAssuntoDTO = new AssuntoDTO();

  $strDesabilitar = '';

  $arrComandos = array();
  
  $strChecked='';

  switch($_GET['acao']){
    case 'assunto_cadastrar':
      $strTitulo = 'Novo Assunto';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarAssunto" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.'#ID-'.$_GET['id_assunto']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objAssuntoDTO->setNumIdAssunto(null);
      $objAssuntoDTO->setNumIdTabelaAssuntos($_GET['id_tabela_assuntos']);
    	$objAssuntoDTO->setStrCodigoEstruturado($_POST['txtCodigoEstruturado']);
      $objAssuntoDTO->setStrDescricao($_POST['txtDescricao']);
      $objAssuntoDTO->setNumPrazoCorrente($_POST['txtPrazoCorrente']);
      $objAssuntoDTO->setNumPrazoIntermediario($_POST['txtPrazoIntermediario']);
      $objAssuntoDTO->setStrStaDestinacao($_POST['rdoDestinacao']);

      //entrando pela primeira vez
      if ($_GET['acao']!=$_GET['acao_origem']){
        $objAssuntoDTO->setStrSinEstrutural('N');
      }else{
        $objAssuntoDTO->setStrSinEstrutural(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinEstrutural']));
      }
      
      $objAssuntoDTO->setStrObservacao($_POST['txaObservacao']);
      $objAssuntoDTO->setStrSinAtivo('S');
                        
      if (isset($_POST['sbmCadastrarAssunto'])) {
        try{
          $objAssuntoRN = new AssuntoRN();
          $objAssuntoDTO = $objAssuntoRN->cadastrarRN0259($objAssuntoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Assunto "'.$objAssuntoDTO->getStrCodigoEstruturado().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_assunto='.$objAssuntoDTO->getNumIdAssunto().$strParametros.'#ID-'.$objAssuntoDTO->getNumIdAssunto()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'assunto_alterar':
      $strTitulo = 'Alterar Assunto';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarAssunto" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_assunto'])){
        $objAssuntoDTO->setNumIdAssunto($_GET['id_assunto']);
        $objAssuntoDTO->setBolExclusaoLogica(false);
        $objAssuntoDTO->retTodos();
        $objAssuntoRN = new AssuntoRN();
        $objAssuntoDTO = $objAssuntoRN->consultarRN0256($objAssuntoDTO);
        if ($objAssuntoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objAssuntoDTO->setNumIdAssunto($_POST['hdnIdAssunto']);
        //$objAssuntoDTO->setNumIdTabelaAssuntos($_GET['id_tabela_assuntos']);
	      $objAssuntoDTO->setStrCodigoEstruturado($_POST['txtCodigoEstruturado']);
	      $objAssuntoDTO->setStrDescricao($_POST['txtDescricao']);
	      $objAssuntoDTO->setNumPrazoCorrente($_POST['txtPrazoCorrente']);
        $objAssuntoDTO->setNumPrazoIntermediario($_POST['txtPrazoIntermediario']);
	      $objAssuntoDTO->setStrStaDestinacao($_POST['rdoDestinacao']);
	      $objAssuntoDTO->setStrSinEstrutural(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinEstrutural']));
	      $objAssuntoDTO->setStrObservacao($_POST['txaObservacao']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros).'#ID-'.$objAssuntoDTO->getNumIdAssunto().'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarAssunto'])) {
        try{
          $objAssuntoRN = new AssuntoRN();
          $objAssuntoRN->alterarRN0260($objAssuntoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Assunto "'.$objAssuntoDTO->getStrCodigoEstruturado().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.'#ID-'.$objAssuntoDTO->getNumIdAssunto()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'assunto_consultar':
      $strTitulo = "Consultar Assunto";

      if ($_POST['hdnAssuntoIdentificador']==null) {
        $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.'#ID-'.$_GET['id_assunto']).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      }

      $objAssuntoDTO->setBolExclusaoLogica(false);
      $objAssuntoDTO->setNumIdAssunto($_GET['id_assunto']);
      $objAssuntoDTO->retTodos();
      
      $objAssuntoRN = new AssuntoRN();
      $objAssuntoDTO = $objAssuntoRN->consultarRN0256($objAssuntoDTO);
      if ($objAssuntoDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strDisplayClassificacao = '';
  if ($objAssuntoDTO->getStrSinEstrutural()=='S'){
    $strDisplayClassificacao = 'display:none;';
  }

  $strChkGuardaPermanente = '';
  $strChkEliminacao = '';
  if ($objAssuntoDTO->getStrStaDestinacao()==AssuntoRN::$TD_ELIMINACAO){
    $strChkGuardaPermanente = '';
    $strChkEliminacao = 'checked="checked"';
  }else if ($objAssuntoDTO->getStrStaDestinacao()==AssuntoRN::$TD_GUARDA_PERMANENTE){
    $strChkGuardaPermanente = 'checked="checked"';
    $strChkEliminacao = '';
  }

  $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
  $strMascaraAssunto = $objInfraParametro->getValor('SEI_MASCARA_ASSUNTO');

  $objTabelaAssuntosDTO = new TabelaAssuntosDTO();
  $objTabelaAssuntosDTO->retStrNome();
  $objTabelaAssuntosDTO->retStrSinAtual();
  $objTabelaAssuntosDTO->setNumIdTabelaAssuntos($_GET['id_tabela_assuntos']);

  $objTabelaAssuntosRN = new TabelaAssuntosRN();
  $objTabelaAssuntosDTO = $objTabelaAssuntosRN->consultar($objTabelaAssuntosDTO);

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

#divGeral {height:17em;}

#lblTabelaAssuntos {position:absolute;left:0%;top:0%;width:40%;}
#txtTabelaAssuntos {position:absolute;left:0%;top:12%;width:40%;}

#lblCodigoEstruturado {position:absolute;left:0%;top:28%;width:20%;}
#txtCodigoEstruturado {position:absolute;left:0%;top:40%;width:20%;}

#lblDescricao {position:absolute;left:0%;top:56%;width:95%;}
#txtDescricao {position:absolute;left:0%;top:68%;width:77%;}

#divSinEstrutural {position:absolute;left:0%;top:85%;}

#divClassificacao {height:15em;<?=$strDisplayClassificacao?>;max-width:700px;}

#fldPrazos {position:absolute;left:0%;top:5%;height:80%;width:35%;}
#lblPrazoCorrente {position:absolute;left:10%;top:19%;width:80%;}
#txtPrazoCorrente {position:absolute;left:10%;top:32%;width:60%;}

#lblPrazoIntermediario {position:absolute;left:10%;top:56%;width:80%;}
#txtPrazoIntermediario {position:absolute;left:10%;top:70%;width:60%;}

#fldDestinacao {position:absolute;left:40%;top:5%;height:80%;width:35%;}
#divOptGuardaPermanente {position:absolute;left:15%;top:32%;}
#divOptDestinacao {position:absolute;left:15%;top:63%;}

#divObservacao {height:10em}
#lblObservacao {position:absolute;left:0%;top:5%;width:20%;}
#txaObservacao {position:absolute;left:0%;top:25%;width:77%;}

<?
if (PaginaSEI::getInstance()->isBolAjustarTopFieldset()){
?>
#lblPrazoCorrente {top:5%;}
#txtPrazoCorrente {top:22%;}

#lblPrazoIntermediario {top:50%;}
#txtPrazoIntermediario {top:67%;}

#divOptGuardaPermanente {top:20%}
#divOptDestinacao {top:55%}
<?
}
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script type="javascript">

function inicializar(){
  if ('<?=$_GET['acao']?>'=='assunto_cadastrar'){
    document.getElementById('txtCodigoEstruturado').focus();
  } else if ('<?=$_GET['acao']?>'=='assunto_consultar'){
    infraDesabilitarCamposAreaDados();
  }
}

function OnSubmitForm() {
  return validarCadastroRI0263();
}

function validarCadastroRI0263() {

  if (infraTrim(document.getElementById('txtCodigoEstruturado').value)=='') {
    alert('Informe o Código do assunto.');
    document.getElementById('txtCodigoEstruturado').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtDescricao').value)=='') {
    alert('Informe a Descrição.');
    document.getElementById('txtDescricao').focus();
    return false;
  }

  if (!document.getElementById('chkSinEstrutural').checked) {

    if (infraTrim(document.getElementById('txtPrazoCorrente').value)=='') {
      alert('Informe o prazo de guarda corrente.');
      document.getElementById('txtPrazoCorrente').focus();
      return false;
    }

    if (infraTrim(document.getElementById('txtPrazoIntermediario').value)=='') {
      alert('Informe o prazo de guarda intermediário.');
      document.getElementById('txtPrazoIntermediario').focus();
      return false;
    }

    if (!document.getElementById('optGuardaPermanente').checked && !document.getElementById('optDestinacao').checked) {
      alert('Selecione a destinação final.');
      document.getElementById('optGuardaPermanente').focus();
      return false;
    }
  }
    
  return true;
}

function trocarItemEstrutural(){
  if (document.getElementById('chkSinEstrutural').checked){
    document.getElementById('divClassificacao').style.display = 'none';
  }else{
    document.getElementById('divClassificacao').style.display = 'block';
  }
}

//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAssuntoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
?>
  <div id="divGeral" class="infraAreaDados">

    <label id="lblTabelaAssuntos" class="infraLabelObrigatorio">Tabela:</label>
    <input type="text" id="txtTabelaAssuntos" name="txtTabelaAssuntos" readonly="readonly" class="infraText infraReadOnly" value=" <?=PaginaSEI::tratarHTML($objTabelaAssuntosDTO->getStrNome())?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    
    <label id="lblCodigoEstruturado" for="txtCodigoEstruturado" accesskey="" class="infraLabelObrigatorio">Código:</label>
    <input type="text" id="txtCodigoEstruturado" name="txtCodigoEstruturado" class="infraText" <?=(InfraString::isBolVazia($strMascaraAssunto)?'onkeypress="return infraLimitarTexto(this,event,50);"':'onkeypress="return infraMascara(this,event,\''.$strMascaraAssunto.'\');"')?> value="<?=PaginaSEI::tratarHTML($objAssuntoDTO->getStrCodigoEstruturado());?>" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <label id="lblDescricao" for="txtDescricao" accesskey="" class="infraLabelObrigatorio">Descrição:</label>
    <input type="text" id="txtDescricao" name="txtDescricao" class="infraText"  onkeypress="return infraLimitarTexto(this,event,250);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" value="<?=PaginaSEI::tratarHTML($objAssuntoDTO->getStrDescricao())?>" />

    <div id="divSinEstrutural" class="infraDivCheckbox">
      <input type="checkbox" id="chkSinEstrutural" name="chkSinEstrutural" onchange="trocarItemEstrutural();" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objAssuntoDTO->getStrSinEstrutural())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?//=$strChecked?>/>
      <label id="lblSinEstrutural" for="chkSinEstrutural" accesskey="" class="infraLabelCheckbox">Item apenas estrutural</label>
    </div>
  </div>

  <div id="divClassificacao" class="infraAreaDados">
    <fieldset id="fldPrazos" class="infraFieldset" >
      <legend class="infraLegend">Prazos de Guarda (anos)</legend>

      <label id="lblPrazoCorrente" for="txtPrazoCorrente" accesskey="" class="infraLabelObrigatorio">Corrente:</label>
      <input type="text" id="txtPrazoCorrente" name="txtPrazoCorrente" onkeypress="return infraMascara(this,event,'###')" class="infraText" value="<?=PaginaSEI::tratarHTML($objAssuntoDTO->getNumPrazoCorrente())?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

      <label id="lblPrazoIntermediario" for="txtPrazoIntermediario" accesskey="" class="infraLabelObrigatorio">Intermediário:</label>
      <input type="text" id="txtPrazoIntermediario" name="txtPrazoIntermediario" onkeypress="return infraMascara(this,event,'###')" class="infraText" value="<?=PaginaSEI::tratarHTML($objAssuntoDTO->getNumPrazoIntermediario())?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    </fieldset>

    <fieldset id="fldDestinacao" class="infraFieldset" >
      <legend class="infraLegend">Destinação Final</legend>

      <div id="divOptGuardaPermanente" class="infraDivRadio">
        <input type="radio" name="rdoDestinacao" id="optGuardaPermanente" value="<?=AssuntoRN::$TD_GUARDA_PERMANENTE?>" class="infraRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=$strChkGuardaPermanente?>/>
        <label id="lblGuardaPermanente" for="optGuardaPermanente" class="infraLabelRadio">Guarda Permanente</label> <br/>
      </div>

      <div id="divOptDestinacao" class="infraDivRadio">
      <input type="radio" name="rdoDestinacao" id="optDestinacao" value="<?=AssuntoRN::$TD_ELIMINACAO?>" class="infraRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=$strChkEliminacao?>/>
      <label id="lblDestinacao" for="optDestinacao" class="infraLabelRadio">Eliminação</label> <br/>
      </div>
    </fieldset>
  </div>

  <div id="divObservacao" class="infraAreaDados">
    <label id="lblObservacao" for="txaObservacao" class="infraLabelOpcional">Observação:</label>
    <textarea id="txaObservacao" name="txaObservacao" rows="<?=PaginaSEI::getInstance()->isBolNavegadorFirefox()?'2':'3'?>" class="infraTextarea" onkeypress="return infraLimitarTexto(this,event,500);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objAssuntoDTO->getStrObservacao())?></textarea>
  </div>

  <input type="hidden" id="hdnIdAssunto" name="hdnIdAssunto" value="<?=$objAssuntoDTO->getNumIdAssunto();?>" />

  <?
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>