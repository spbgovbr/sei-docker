<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 13/10/2009 - criado por mga
*
* Versão do Gerador de Código: 1.29.1
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

  PaginaSEI::getInstance()->verificarSelecao('orgao_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objOrgaoDTO = new OrgaoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'orgao_upload':
      //Trata do campo file que é postado para a mesma ação
      if (isset($_FILES['filArquivo'])){
        PaginaSEI::getInstance()->processarUpload('filArquivo', DIR_SEI_TEMP, false);
      }
      die;

    case 'orgao_alterar':
      $strTitulo = 'Alterar Órgão';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarOrgao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_orgao'])){
        $objOrgaoDTO->setNumIdOrgao($_GET['id_orgao']);
        $objOrgaoDTO->retTodos();
        $objOrgaoRN = new OrgaoRN();
        $objOrgaoDTO = $objOrgaoRN->consultarRN1352($objOrgaoDTO);
        if ($objOrgaoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objOrgaoDTO->setNumIdOrgao($_GET['id_orgao_alteracao']);
        $objOrgaoDTO->setNumIdContato($_GET['id_contato']);
        $objOrgaoDTO->setStrSigla($_POST['txtSiglaContatoAssociado']);
        $objOrgaoDTO->setStrDescricao($_POST['txtNomeContatoAssociado']);
	      $objOrgaoDTO->setStrNumeracao($_POST['txtNumeracao']);
	      $objOrgaoDTO->setStrCodigoSei($_POST['txtCodigoSei']);
	      $objOrgaoDTO->setStrServidorCorretorOrtografico($_POST['txtServidorCorretorOrtografico']);
	      $objOrgaoDTO->setStrTimbre($_POST['hdnTimbre']);
	      $objOrgaoDTO->setStrNomeArquivo($_POST['hdnNomeArquivo']);
	      $objOrgaoDTO->setStrSinEnvioProcesso(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinEnvioProcesso']));
        $objOrgaoDTO->setStrSinFederacaoEnvio(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinFederacaoEnvio']));
        $objOrgaoDTO->setNumIdUnidade($_POST['selUnidadeFederacao']);
        $objOrgaoDTO->setStrSinFederacaoRecebimento(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinFederacaoRecebimento']));
        $objOrgaoDTO->setStrSinPublicacao(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPublicacao']));
        $objOrgaoDTO->setStrStaCorretorOrtografico($_POST['rdoCorretor']);
        $objOrgaoDTO->setStrSinAtivo('S');
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objOrgaoDTO->getNumIdOrgao())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarOrgao'])) {
        try{
          $objOrgaoRN = new OrgaoRN();
          $objOrgaoRN->alterarRN1350($objOrgaoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Órgão "'.$objOrgaoDTO->getStrSigla().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objOrgaoDTO->getNumIdOrgao())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'orgao_consultar':
      $strTitulo = 'Consultar Órgão';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_orgao'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objOrgaoDTO->setNumIdOrgao($_GET['id_orgao']);
      $objOrgaoDTO->setBolExclusaoLogica(false);
      $objOrgaoDTO->retTodos();
      $objOrgaoRN = new OrgaoRN();
      $objOrgaoDTO = $objOrgaoRN->consultarRN1352($objOrgaoDTO);
      if ($objOrgaoDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

	$strLinkUpload = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=orgao_upload&acao_origem='.$_GET['acao']);

  $strDisplayRemover = '';
  if ($_GET['acao']=='orgao_consultar' || InfraString::isBolVazia($objOrgaoDTO->getStrTimbre())){
    $strDisplayRemover = 'display:none;';
  }
  
  $strDisplayTimbre = '';
  if (InfraString::isBolVazia($objOrgaoDTO->getStrTimbre())){
    $strDisplayTimbre = 'display:none;';
  }

  $strItensSelUnidadeFederacao = UnidadeINT::montarSelectSiglaDescricao('null','&nbsp', $objOrgaoDTO->getNumIdUnidade());

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
#lblNumeracao {position:absolute;left:0%;top:0%;}
#txtNumeracao {position:absolute;left:0%;top:5%;width:94%;font-family: Courier, Courier New, monospace;}
#ancAjuda {position:absolute;left:95%;top:5%;}

#divSinEnvioProcesso {position:absolute;left:0%;top:15%;}
#divSinPublicacao {position:absolute;left:0%;top:21%;}

#fldCorretor {height: 28%;left: 0;position: absolute;top:32%;width:95%;max-width:800px;}
#divOptNenhum {left: 2%;position: absolute;top: 25%;}
#divOptNativo {left: 2%;position: absolute;top: 50%;}
#divOptLicenciado {left: 2%;position: absolute;top: 75%;}
#lblServidorCorretorOrtografico {position:absolute;left:30%;top:73%;width:14%;text-align:right;visibility:hidden;}
#txtServidorCorretorOrtografico {position:absolute;left:45%;top:68%;width:50%;font-family: Courier, Courier New, monospace;visibility:hidden;}

#fldFederacao {height: 24%;left: 0;position: absolute;top:68%;width:95%;max-width:800px;}
#divSinFederacaoEnvio {position:absolute;left:2%;top:30%;}
#divSinFederacaoRecebimento {position:absolute;left:2%;top:60%;}
#lblUnidadeFederacao {position:absolute;left:24%;top:62%;width:14%;text-align:right;visibility:hidden;}
#selUnidadeFederacao {position:absolute;left:39%;top:56%;width:58%;visibility:hidden;}

#lblArquivo {position:absolute;left:0%;top:0%;}
#filArquivo {position:absolute;left:0%;top:40%;}
#imgRemover {width:1.6em; height:1.6em}

<?
if (PaginaSEI::getInstance()->isBolAjustarTopFieldset()){
?>

#divOptNenhum {top:10%;}
#divOptNativo {top:40%;}
#divOptLicenciado {top:70%;}
#lblServidorCorretorOrtografico {top:65%;}
#txtServidorCorretorOrtografico {top:60%;}

#divSinFederacaoEnvio {top:20%;}
#divSinFederacaoRecebimento {top:60%;}
#lblUnidadeFederacao {top:55%;}
#selUnidadeFederacao {top:50%;}

  <?
}
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){

  configurarCorretor();

  configurarFederacao();

  if ('<?=$_GET['acao']?>'=='orgao_consultar'){
    infraDesabilitarCamposAreaDados();
    document.getElementById('imgRemover').style.display="none";
  }else{
    document.getElementById('btnCancelar').focus();
  }
  
  if ('<?=$_GET['acao']?>'!='orgao_consultar'){
    document.getElementById('btnCancelar').focus();
    objUpload = new infraUpload('frmUpload','<?=$strLinkUpload?>');
    objUpload.validar = function() {
      nomeArquivo=document.getElementById('filArquivo').value;
      if (nomeArquivo.substr(nomeArquivo.length-4,4)!='.png') {
        alert ("Imagem do timbre deve ser no formato PNG.");
        return false;
      } else return true;
    }
    objUpload.finalizou = function(arr){
      removerTimbre();
      if (arr!=null){
        document.getElementById('hdnNomeArquivo').value = arr['nome_upload'];
      }
    }
  }
  infraEfeitoTabelas();
}

function removerTimbre(){
  document.getElementById('hdnNomeArquivo').value="*REMOVER*";
  document.getElementById('imgTimbre').style.display='none';
  document.getElementById('imgRemover').style.display='none';
}

function validarCadastroRI1359() {

  if (document.getElementById('optLicenciado').checked && infraTrim(document.getElementById('txtServidorCorretorOrtografico').value)=='') {
    alert('Informe o caminho do servidor de correção ortográfica.');
    document.getElementById('txtServidorCorretorOrtografico').focus();
    return false;
  }

  if (document.getElementById('chkSinFederacaoRecebimento').checked && !infraSelectSelecionado(document.getElementById('selUnidadeFederacao'))) {
    alert('Selecione uma unidade padrão para recebimento de processos do SEI Federação.');
    document.getElementById('selUnidadeFederacao').focus();
    return false;
  }

  return true;
}

function configurarCorretor(){
  if (document.getElementById('optNenhum').checked || document.getElementById('optNativo').checked){
    document.getElementById('lblServidorCorretorOrtografico').style.visibility = 'hidden';
    document.getElementById('txtServidorCorretorOrtografico').style.visibility = 'hidden';    
  }else{
    document.getElementById('lblServidorCorretorOrtografico').style.visibility = 'visible';
    document.getElementById('txtServidorCorretorOrtografico').style.visibility = 'visible';
   <? if ($_GET['acao']!='orgao_consultar') { ?>
    document.getElementById('txtServidorCorretorOrtografico').focus();
   <? } ?>    
  }
}

function configurarFederacao(){
  if (!document.getElementById('chkSinFederacaoRecebimento').checked){
    document.getElementById('lblUnidadeFederacao').style.visibility = 'hidden';
    document.getElementById('selUnidadeFederacao').style.visibility = 'hidden';
  }else{
    document.getElementById('lblUnidadeFederacao').style.visibility = 'visible';
    document.getElementById('selUnidadeFederacao').style.visibility = 'visible';
    <? if ($_GET['acao']!='orgao_consultar') { ?>
      document.getElementById('selUnidadeFederacao').focus();
    <? } ?>
  }
}

function OnSubmitForm() {
  return validarCadastroRI1359();
}

function exibirAjuda(){
  alert('Variáveis disponíveis:' + "\n\n" +
        '@cod_orgao_sip@, @cod_orgao_sip_02d@, ... , @cod_orgao_sip_05d@' + "\n" +
        '@seq_anual_cod_orgao_sip_05d@, @seq_anual_cod_orgao_sip_06d@, ... , @seq_anual_cod_orgao_sip_010d@' + "\n\n\n" +
        '@cod_orgao_sei@, @cod_orgao_sei_02d@, ... , @cod_orgao_sei_05d@' + "\n" +
        '@seq_anual_cod_orgao_sei_05d@, @seq_anual_cod_orgao_sei_06d@, ... , @seq_anual_cod_orgao_sei_010d@' + "\n\n\n" +
        '@cod_unidade_sip@, @cod_unidade_sip_02d@, ... , @cod_unidade_sip_010d@' + "\n" +
        '@seq_anual_cod_unidade_sip_05d@, @seq_anual_cod_unidade_sip_06d@, ... , @seq_anual_cod_unidade_sip_010d@' + "\n\n\n" +
        '@cod_unidade_sei@, @cod_unidade_sei_02d@, ... , @cod_unidade_sei_010d@' + "\n" +
        '@seq_anual_cod_unidade_sei_05d@, @seq_anual_cod_unidade_sei_06d@, ... , @seq_anual_cod_unidade_sei_010d@' + "\n\n\n" +
        '@ano_2d@, @ano_4d@' + "\n\n\n" +
        '@dv_mod97_base10_cnj_2d@, @dv_mod11_1d@, @dv_mod11_executivo_federal_2d@, @dv_mod97_base10_cnmp_2d@, @dv_mod97_base10_executivo_federal_2d@');
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmOrgaoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&id_orgao_alteracao='.$objOrgaoDTO->getNumIdOrgao().'&id_contato='.$objOrgaoDTO->getNumIdContato())?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
  ContatoINT::montarContatoAssociado(true, $objOrgaoDTO->getNumIdOrgao(), true, $objOrgaoDTO->getStrCodigoSei(), false, null, false, $objOrgaoDTO->getNumIdContato(), $objOrgaoDTO->getStrSigla(), $objOrgaoDTO->getStrDescricao(), null, true, 'frmOrgaoCadastro');
  PaginaSEI::getInstance()->abrirAreaDados('35em');
?>

  <label id="lblNumeracao" for="txtNumeracao" accesskey="" class="infraLabelOpcional">Formato da Numeração:</label>
  <input type="text" id="txtNumeracao" name="txtNumeracao" class="infraText" value="<?=PaginaSEI::tratarHTML($objOrgaoDTO->getStrNumeracao());?>" onkeypress="infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <a id="ancAjuda" onclick="exibirAjuda();" title="Ajuda" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><img src="<?=PaginaSEI::getInstance()->getIconeAjuda()?>" class="infraImg"/></a>

  <div id="divSinEnvioProcesso" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinEnvioProcesso" name="chkSinEnvioProcesso" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objOrgaoDTO->getStrSinEnvioProcesso())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    <label id="lblSinEnvioProcesso" for="chkSinEnvioProcesso" accesskey="" class="infraLabelCheckbox">As unidades deste órgão podem receber processos</label>
  </div>

  <div id="divSinPublicacao" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinPublicacao" name="chkSinPublicacao" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objOrgaoDTO->getStrSinPublicacao())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    <label id="lblSinPublicacao" for="chkSinPublicacao" accesskey="" class="infraLabelCheckbox">As unidades deste órgão podem publicar documentos</label>
  </div>
  
  <fieldset id="fldCorretor" class="infraFieldset">
  <legend class="infraLegend">Corretor Ortográfico</legend>
  	
  	  <div id="divOptNenhum" class="infraDivRadio">
			<input type="radio" name="rdoCorretor" id="optNenhum" value="<?=OrgaoRN::$TCO_NENHUM?>" onclick="configurarCorretor();" <?=($objOrgaoDTO->getStrStaCorretorOrtografico()==OrgaoRN::$TCO_NENHUM?'checked="checked"':'')?> class="infraRadio"/>
	    <span id="spnNenhum"><label id="lblNenhum" for="optNenhum" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Nenhum</label></span>
	    </div>

    <div id="divOptNativo" class="infraDivRadio">
      <input type="radio" name="rdoCorretor" id="optNativo" value="<?=OrgaoRN::$TCO_NATIVO_NAVEGADOR?>" onclick="configurarCorretor();" <?=($objOrgaoDTO->getStrStaCorretorOrtografico()==OrgaoRN::$TCO_NATIVO_NAVEGADOR || ($objOrgaoDTO->getStrStaCorretorOrtografico()!=OrgaoRN::$TCO_NENHUM && $objOrgaoDTO->getStrStaCorretorOrtografico()!=OrgaoRN::$TCO_LICENCIADO)?'checked="checked"':'')?> class="infraRadio"/>
      <span id="spnNativo"><label id="lblNativo" for="optNativo" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Nativo do Navegador</label></span>
    </div>
  	
    <div id="divOptLicenciado" class="infraDivRadio">
			<input type="radio" name="rdoCorretor" id="optLicenciado" value="<?=OrgaoRN::$TCO_LICENCIADO?>" onclick="configurarCorretor();" <?=($objOrgaoDTO->getStrStaCorretorOrtografico()==OrgaoRN::$TCO_LICENCIADO?'checked="checked"':'')?> class="infraRadio"/>
	    <span id="spnLicenciado"><label id="lblLicenciado" for="optLicenciado" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Licenciado (em descontinuidade)</label></span>
    </div>

    <label id="lblServidorCorretorOrtografico" for="txtServidorCorretorOrtografico" accesskey="" class="infraLabelOpcional">Servidor:</label>
    <input type="text" id="txtServidorCorretorOrtografico" name="txtServidorCorretorOrtografico" class="infraText" value="<?=PaginaSEI::tratarHTML($objOrgaoDTO->getStrServidorCorretorOrtografico());?>" onkeypress="infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
	    
  </fieldset>


  <fieldset id="fldFederacao" class="infraFieldset">
    <legend class="infraLegend">SEI Federação</legend>

    <div id="divSinFederacaoEnvio" class="infraDivCheckbox">
      <input type="checkbox" id="chkSinFederacaoEnvio" name="chkSinFederacaoEnvio" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objOrgaoDTO->getStrSinFederacaoEnvio())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
      <label id="lblSinFederacaoEnvio" for="chkSinFederacaoEnvio" accesskey="" class="infraLabelCheckbox">Pode enviar processos</label>
    </div>

    <div id="divSinFederacaoRecebimento" class="infraDivCheckbox">
      <input type="checkbox" id="chkSinFederacaoRecebimento" name="chkSinFederacaoRecebimento" onclick="configurarFederacao();" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objOrgaoDTO->getStrSinFederacaoRecebimento())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
      <label id="lblSinFederacaoRecebimento" for="chkSinFederacaoRecebimento" accesskey="" class="infraLabelCheckbox">Pode receber processos</label>
    </div>

    <label id="lblUnidadeFederacao" for="selUnidadeFederacao" accesskey="" class="infraLabelOpcional">Unidade:</label>
    <select id="selUnidadeFederacao" name="selUnidadeFederacao" class="infraSelect"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?=$strItensSelUnidadeFederacao?>
    </select>


  </fieldset>

  <input type="hidden" id="hdnNomeArquivo" name="hdnNomeArquivo" value="" />
  <input type="hidden" id="hdnTimbre" name="hdnTimbre" value="<?=$objOrgaoDTO->getStrTimbre();?>" />

  <?
  PaginaSEI::getInstance()->fecharAreaDados();

  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>

<form id="frmUpload">
  <div id="divUpload" class="infraAreaDados" style="height:5em">
    <label id="lblArquivo" for="filArquivo" accesskey="" class="infraLabelOpcional">Timbre:</label>
<? if ($_GET['acao']=='orgao_cadastrar' || $_GET['acao']=='orgao_alterar') { ?>    
    <input type="file" id="filArquivo" accept="image/png" name="filArquivo" size="50" onchange="objUpload.executar();" /><br />
<? }
?>
    </div>

  <img id="imgTimbre" style="border:1px dotted #c0c0c0;float:left;<?=$strDisplayTimbre?>" src="data:image/png;base64,<?=$objOrgaoDTO->getStrTimbre();?>" />
  <img id="imgRemover" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Timbre" title="Remover Timbre" style="<?=$strDisplayRemover?>" class="infraImg" onclick="removerTimbre();" />
    
</form>
<?  
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>