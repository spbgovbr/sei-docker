<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/01/2021 - criado por cas84
*
* Versão do Gerador de Código: 1.43.0
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

  PaginaSEI::getInstance()->verificarSelecao('aviso_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  //PaginaSEI::getInstance()->salvarCamposPost(array());

  $objAvisoDTO = new AvisoDTO();

  $strDesabilitar = '';
  $strDisplayArquivo = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'aviso_upload':
      //Trata do campo file que é postado para a mesma ação
      if (isset($_FILES['filArquivo'])){
        PaginaSEI::getInstance()->processarUpload('filArquivo', DIR_SEI_TEMP, false);
      }
      die;

    case 'aviso_cadastrar':
      $strTitulo = 'Novo Aviso';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarAviso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objAvisoDTO->setNumIdAviso(null);
      $strStaAviso = $_POST['selStaAviso'];
      if ($strStaAviso!==''){
        $objAvisoDTO->setStrStaAviso($strStaAviso);
      }else{
        $objAvisoDTO->setStrStaAviso(null);
      }

      $objAvisoDTO->setStrDescricao($_POST['txtDescricao']);
      $objAvisoDTO->setStrLink($_POST['txtLink']);
      $objAvisoDTO->setStrImagem($_POST['hdnImagem']);
      $objAvisoDTO->setStrSinLiberado(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinLiberado']));
      $objAvisoDTO->setDthInicio($_POST['txtInicio']);
      $objAvisoDTO->setDthFim($_POST['txtFim']);
      $objAvisoDTO->setStrNomeArquivo($_POST['hdnNomeArquivo']);

      $arrObjRelAvisoOrgaoDTO = array();
      $arrOrgaos = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnOrgaos']);
      foreach($arrOrgaos as $numIdOrgaoAviso){
        $objRelAvisoOrgaoDTO = new RelAvisoOrgaoDTO();
        $objRelAvisoOrgaoDTO->setNumIdOrgao($numIdOrgaoAviso);
        $arrObjRelAvisoOrgaoDTO[] = $objRelAvisoOrgaoDTO;
      }
      $objAvisoDTO->setArrObjRelAvisoOrgaoDTO($arrObjRelAvisoOrgaoDTO);

      if (isset($_POST['sbmCadastrarAviso'])) {
        try{

          if(!InfraString::isBolVazia($_POST['txtInicio']) ){
            $objAvisoDTO->setDthInicio($_POST['txtInicio'].":00");
          }

          if(!InfraString::isBolVazia($_POST['txtFim']) ){
            $objAvisoDTO->setDthFim($_POST['txtFim'].":00");
          }

          //if(InfraArray::contar($arrNumIdOrgaosSelecionados) == 0 || InfraArray::contar($arrNumIdOrgaosSelecionados) == InfraArray::contar($arrObjOrgaoDTO)){
          //  $objAvisoDTO->setStrOrgaos("");
          //  PaginaSEI::getInstance()->salvarCampo('selOrgao', "");
          //}

          $objAvisoRN = new AvisoRN();
          $objAvisoDTO = $objAvisoRN->cadastrar($objAvisoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Aviso "'.$objAvisoDTO->getNumIdAviso().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_aviso='.$objAvisoDTO->getNumIdAviso().PaginaSEI::getInstance()->montarAncora($objAvisoDTO->getNumIdAviso())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'aviso_alterar':
      $strTitulo = 'Alterar Aviso';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarAviso" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_aviso'])){
        $objAvisoDTO->setNumIdAviso($_GET['id_aviso']);
        $objAvisoDTO->retTodos();
        $objAvisoRN = new AvisoRN();
        $objAvisoDTO = $objAvisoRN->consultar($objAvisoDTO);
        if ($objAvisoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }

      } else {
        $objAvisoDTO->setNumIdAviso($_POST['hdnIdAviso']);
        $objAvisoDTO->setStrStaAviso($_POST['selStaAviso']);
        $objAvisoDTO->setStrDescricao($_POST['txtDescricao']);
        $objAvisoDTO->setStrSinLiberado(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinLiberado']));
        $objAvisoDTO->setStrLink($_POST['txtLink']);
        $objAvisoDTO->setStrImagem($_POST['hdnImagem']);
        $objAvisoDTO->setDthInicio($_POST['txtInicio']);
        $objAvisoDTO->setDthFim($_POST['txtFim']);
        $objAvisoDTO->setStrNomeArquivo($_POST['hdnNomeArquivo']);

        $arrSelOrgaos = array();
        if (isset($_POST['selOrgaos']) && $_POST['selOrgaos']!=''){
          $arrSelOrgaos = is_array($_POST['selOrgaos']) ? $_POST['selOrgaos'] : array($_POST['selOrgaos']);
        }
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objAvisoDTO->getNumIdAviso())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $arrObjRelAvisoOrgaoDTO = array();
      $arrOrgaos = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnOrgaos']);
      foreach($arrOrgaos as $numIdOrgaoAviso){
        $objRelAvisoOrgaoDTO = new RelAvisoOrgaoDTO();
        $objRelAvisoOrgaoDTO->setNumIdOrgao($numIdOrgaoAviso);
        $arrObjRelAvisoOrgaoDTO[] = $objRelAvisoOrgaoDTO;
      }
      $objAvisoDTO->setArrObjRelAvisoOrgaoDTO($arrObjRelAvisoOrgaoDTO);

      if (isset($_POST['sbmAlterarAviso'])) {
        try{

          if(!InfraString::isBolVazia($_POST['txtInicio']) ){
            $objAvisoDTO->setDthInicio($_POST['txtInicio'].":00");
          }
          if(!InfraString::isBolVazia($_POST['txtFim']) ){
            $objAvisoDTO->setDthFim($_POST['txtFim'].":00");
          }

          //if(InfraArray::contar($arrNumIdOrgaosSelecionados) == 0 || InfraArray::contar($arrNumIdOrgaosSelecionados) == InfraArray::contar($arrObjOrgaoDTO)){
          //  $objAvisoDTO->setStrOrgaos("");
          //  PaginaSEI::getInstance()->salvarCampo('selOrgao', "");
          //}

          $objAvisoRN = new AvisoRN();
          $objAvisoRN->alterar($objAvisoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Aviso "'.$objAvisoDTO->getNumIdAviso().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objAvisoDTO->getNumIdAviso())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'aviso_consultar':
      $strTitulo = 'Consultar Aviso';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_aviso'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

      $strDisplayArquivo = 'display:none';

      $objAvisoDTO->setNumIdAviso($_GET['id_aviso']);
      $objAvisoDTO->setBolExclusaoLogica(false);
      $objAvisoDTO->retTodos();
      $objAvisoRN = new AvisoRN();
      $objAvisoDTO = $objAvisoRN->consultar($objAvisoDTO);
      if ($objAvisoDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strLinkUpload = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=aviso_upload&acao_origem='.$_GET['acao']);
  $strItensSelStaAviso = AvisoINT::montarSelectStaAviso('null','&nbsp;',$objAvisoDTO->getStrStaAviso());
  $strLinkOrgaosSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=orgao_selecionar&tipo_selecao=2&id_object=objLupaOrgaos');

  if (!isset($_POST['hdnOrgaos'])){
    $strItensSelOrgaos = RelAvisoOrgaoINT::montarSelectOrgao($objAvisoDTO->getNumIdAviso());
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
<?if(0){?><style><?}?>
#lblStaAviso {position:absolute;left:0%;top:0%;width:25%;}
#selStaAviso {position:absolute;left:0%;top:40%;width:25%;}
#divSinLiberado {position:absolute;left:26%;top:50%;}

#lblInicio {position:absolute;left:0%;top:0%;width:25%;}
#txtInicio {position:absolute;left:0%;top:40%;width:25%;}
#imgCalInicio {position:absolute;left:26%;top:40%;}

#lblFim {position:absolute;left:0%;top:0%;width:25%;}
#txtFim {position:absolute;left:0%;top:40%;width:25%;}
#imgCalFim {position:absolute;left:26%;top:40%;}

#lblOrgaos {position:absolute;left:0%;top:0%;width:50%;}
#selOrgaos {position:absolute;left:0%;top:18%;width:50%;}
#imgLupaOrgaos {position:absolute;left:51%;top:18%;}
#imgExcluirOrgaos {position:absolute;left:51%;top:38%;}

#lblDescricao {position:absolute;left:0%;top:0%;width:95%;}
#txtDescricao {position:absolute;left:0%;top:40%;width:95%;}

#lblLink {position:absolute;left:0%;top:0%;width:95%;}
#txtLink {position:absolute;left:0%;top:40%;width:95%;}

#lblImagem {position:absolute;left:0%;top:0%;width:25%;}
#txtImagem {position:absolute;left:0%;top:40%;width:25%;}

#frmUpload {margin: .5em 0 0 0;border:0;padding:0;}
#divArquivo {height:3em;width:90%;<?=$strDisplayArquivo?>}
#lblArquivo {position:absolute;left:0;top:0;width:70%;}
#filArquivo {position:absolute;left:0;top:50%;width:70%;}

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

var objLupaOrgaos = null;


function inicializar(){
  if ('<?=$_GET['acao']?>'=='aviso_cadastrar'){
    document.getElementById('selStaAviso').focus();
  } else if ('<?=$_GET['acao']?>'=='aviso_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }

  objLupaOrgaos = new infraLupaSelect('selOrgaos','hdnOrgaos','<?=$strLinkOrgaosSelecao?>');

  objUpload = new infraUpload('frmUpload','<?=$strLinkUpload?>');
  objUpload.validar = function() {
    nomeArquivo=document.getElementById('filArquivo').value;
    if (nomeArquivo.toLowerCase().substr(nomeArquivo.length-4,4)!='.png' && nomeArquivo.toLowerCase().substr(nomeArquivo.length-5,5)!='.jpeg' && nomeArquivo.toLowerCase().substr(nomeArquivo.length-4,4)!='.jpg' ) {
      alert ("Imagem do aviso deve ser no formato PNG ou JPG/JPEG.");
      return false;
    } else return true;
  }
  objUpload.finalizou = function(arr){
    if (arr!=null){
      $("#imgAviso").attr("src","");
      document.getElementById('hdnNomeArquivo').value = arr['nome_upload'];
    }
  }

  infraEfeitoTabelas(true);
}

function validarCadastro() {
  if (!infraSelectSelecionado('selStaAviso')) {
    alert('Selecione um Tipo.');
    document.getElementById('selStaAviso').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtInicio').value)=='') {
    alert('Informe a Data/Hora Início.');
    document.getElementById('txtInicio').focus();
    return false;
  }

  if (!infraValidarDataHora(document.getElementById('txtInicio'))){
    return false;
  }

  if (infraTrim(document.getElementById('txtFim').value)=='') {
    alert('Informe a Data/Hora Fim.');
    document.getElementById('txtFim').focus();
    return false;
  }

  if (!infraValidarDataHora(document.getElementById('txtFim'))){
    return false;
  }

  if (document.getElementById('hdnOrgaos').value=='') {
    alert('Nenhum órgão informado.');
    document.getElementById('selOrgaos').focus();
    return false;
  }

  if (infraTrim(document.getElementById('hdnNomeArquivo').value)=='' && infraTrim(document.getElementById('hdnImagem').value)=='') {
    alert('Informe a Imagem.');
    return false;
  }

  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAvisoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('5em');
?>
  <label id="lblStaAviso" for="selStaAviso" accesskey="" class="infraLabelObrigatorio">Tipo:</label>
  <select id="selStaAviso" name="selStaAviso" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelStaAviso?>
  </select>
  <div id="divSinLiberado" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinLiberado" name="chkSinLiberado" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objAvisoDTO->getStrSinLiberado())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <label id="lblSinLiberado" for="chkSinLiberado" accesskey="" class="infraLabelCheckbox">Liberado</label>
  </div>
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('5em');
?>
  <label id="lblInicio" for="txtInicio" accesskey="" class="infraLabelObrigatorio">Data/Hora Início:</label>
  <input type="text" id="txtInicio" name="txtInicio" onkeypress="return infraMascara(this, event, '##/##/#### ##:##')" class="infraText" value="<?=(!InfraString::isBolVazia($objAvisoDTO->getDthInicio()) && strlen($objAvisoDTO->getDthInicio()) == 19 ? substr(PaginaSEI::tratarHTML($objAvisoDTO->getDthInicio()),0,16) : PaginaSEI::tratarHTML($objAvisoDTO->getDthInicio()))?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <img id="imgCalInicio" title="Selecionar Data/Hora Início" alt="Selecionar Data/Hora Início" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg" onclick="infraCalendario('txtInicio',this, true,'<?=InfraData::getStrDataAtual().' 00:00'?>');" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('5em');
?>
  <label id="lblFim" for="txtFim" accesskey="" class="infraLabelObrigatorio">Data/Hora Fim:</label>
  <input type="text" id="txtFim" name="txtFim" onkeypress="return infraMascara(this, event, '##/##/#### ##:##')" class="infraText" value="<?=(!InfraString::isBolVazia($objAvisoDTO->getDthFim()) && strlen($objAvisoDTO->getDthFim()) == 19 ? substr(PaginaSEI::tratarHTML($objAvisoDTO->getDthFim()),0,16) : PaginaSEI::tratarHTML($objAvisoDTO->getDthFim()))?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <img id="imgCalFim" title="Selecionar Data/Hora Fim" alt="Selecionar Data/Hora Fim" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg" onclick="infraCalendario('txtFim',this, true,'<?=InfraData::getStrDataAtual().' 23:59'?>');" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('11em');
?>
  <label id="lblOrgaos" for="selOrgaos" accesskey="" class="infraLabelObrigatorio">Órgãos:</label>
  <select id="selOrgaos" name="selOrgaos" size="5" multiple="multiple" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelOrgaos?>
  </select>
  <img id="imgLupaOrgaos" onclick="objLupaOrgaos.selecionar(800,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Localizar Órgão" title="Localizar Órgão" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <img id="imgExcluirOrgaos" onclick="objLupaOrgaos.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Órgãos" title="Remover Órgãos" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <input type="hidden" id="hdnOrgaos" name="hdnOrgaos" value="<?=$_POST['hdnOrgaos']?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('5em');
?>
  <label id="lblDescricao" for="txtDescricao" accesskey="" class="infraLabelOpcional">Descrição:</label>
  <input type="text" id="txtDescricao" name="txtDescricao" class="infraText" value="<?=PaginaSEI::tratarHTML($objAvisoDTO->getStrDescricao());?>" onkeypress="return infraMascaraTexto(this,event,500);" maxlength="500" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('5em');
?>
  <label id="lblLink" for="txtLink" accesskey="" class="infraLabelOpcional">Link:</label>
  <input type="text" id="txtLink" name="txtLink" class="infraText" value="<?=PaginaSEI::tratarHTML($objAvisoDTO->getStrLink());?>" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
?>
  <input type="hidden" id="txtImagem" name="txtImagem" class="infraText" value="<?=PaginaSEI::tratarHTML($objAvisoDTO->getStrImagem());?>" onkeypress="return infraMascaraTexto(this,event);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />


  <input type="hidden" id="hdnIdAviso" name="hdnIdAviso" value="<?=$objAvisoDTO->getNumIdAviso();?>" />
  <?
  //PaginaSEI::getInstance()->montarAreaDebug();
 // PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>

  <input type="hidden" id="hdnNomeArquivo" name="hdnNomeArquivo" value="" />
  <input type="hidden" id="hdnImagem" name="hdnImagem" value="<?=$objAvisoDTO->getStrImagem();?>" />

</form>
  <form id="frmUpload">
    <div id="divArquivo" class="infraAreaDados">
      <label id="lblArquivo" for="filArquivo" accesskey="" class="infraLabelInputFile">Escolher Imagem...</label><br/>
      <input type="file" id="filArquivo" class="infraInputFile" accept="image/png,image/jpg,image/jpeg" style="<?=($_GET['acao']=='aviso_consultar' ? "display:none;height:0;" : "")?>" name="filArquivo" size="50" onchange="objUpload.executar();" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    </div>
    <br>
    <img id="imgAviso" style="border:1px dotted #c0c0c0;" src="<?=(InfraString::isBolVazia($objAvisoDTO->getStrImagem()) ? "" : "data:image/png;base64,".$objAvisoDTO->getStrImagem())?>"  />
  </form>
  <br>
  <br>

<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
