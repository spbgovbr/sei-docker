<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 17/06/2010 - criado por fazenda_db
 *
 * Versão do Gerador de Código: 1.29.1
 *
 * Versão no CVS: $Id$
 */
try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  PaginaSEIExterna::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  //SessaoSEIExterna::getInstance()->validarLink();

  $arrComandos = array();

  $objDocumentoDTO = null;

  switch($_GET['acao']){

    case 'usuario_externo_upload_documento':
      if (isset($_FILES['filArquivo'])){
        PaginaSEIExterna::getInstance()->processarUpload('filArquivo', DIR_SEI_TEMP, false);
      }
      die;

    case 'usuario_externo_incluir_documento':

      $strTitulo = 'Inclusão de Documento';
      $arrComandos[] = '<button id="sbmIncluirDocumento" name="sbmIncluirDocumento" type="submit" accesskey="S" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      //$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.$strLinkCancelar.'\'" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmIncluirDocumento'])) {
        try{
          //abaixo segue a estrutura para cadastro/upload de anexos de um acesso externo
          // -AcessoExternoDTO
          // --ArrObjDocumentoDTO - o AcessoExternoDTO tem um array de documentos... cada documento corresponde a um anexo.. no caso, é apenas um documento
          // ---ProtocoloDTO - mas DocumentoDTO não tem um atributo/relacionamento direto com AnexoDTO... são 'ligados' pelo ProtocoloDTO de cada documento
          // ---- ArrObjAnexoDTO - sendo que ProtocoloDTO tem um array de anexos... nesse caso, esse array terá sempre um elemento, que é o anexo referente ao documento
          $objAcessoExternoDTO =  new AcessoExternoDTO();
          $objAcessoExternoDTO->setNumIdAcessoExterno($_GET['id_acesso_externo']);
          //anexos
          $objAnexoDTO = new AnexoDTO();
          $objAnexoDTO->setStrNome($_POST['hdnNome']);
          $objAnexoDTO->setNumIdAnexo($_POST['hdnNomeUpload']);
          $objAnexoDTO->setDthInclusao($_POST['hdnDataHora']);
          $objAnexoDTO->setNumTamanho($_POST['hdnTamanho']);
          //documento referente a um anexo
          $objDocumentoDTO = new DocumentoDTO();
          //serie do documento
          $objDocumentoDTO->setNumIdSerie($_POST['hdnIdSerie']);
          //protocolo referente a cada documento
          $objProtocoloDTO = new ProtocoloDTO();
          //anexo, que é o documento... apesar do atributo no ProtocoloDTO ser um array, terá sempre apenas um objeto
          $objProtocoloDTO->setArrObjAnexoDTO(array($objAnexoDTO));
          $objDocumentoDTO->setObjProtocoloDTO($objProtocoloDTO);
          $objAcessoExternoDTO->setObjDocumentoDTO($objDocumentoDTO);

          $objAcessoExternoRN = new AcessoExternoRN();
          $objDocumentoDTO = $objAcessoExternoRN->incluirDocumento($objAcessoExternoDTO);

          $bolSalvouOk=true;

        }catch(Exception $e){
          PaginaSEIExterna::getInstance()->processarExcecao($e);
        }
      }

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strLinkAnexos = SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao=usuario_externo_upload_documento&id_acesso_externo='.$_GET['id_acesso_externo']);

  $strItensSelSerie = SerieINT::montarSelectUsuarioExterno('null','&nbsp;',$_POST['selSeriePesquisa'], $_GET['id_acesso_externo']);

  $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
  $numTamMbDocExterno = $objInfraParametro->getValor('SEI_TAM_MB_DOC_EXTERNO');
  if (InfraString::isBolVazia($numTamMbDocExterno) || !is_numeric($numTamMbDocExterno)){
    throw new InfraException('Valor do parâmetro SEI_TAM_MB_DOC_EXTERNO inválido.');
  }
}catch(Exception $e){
  PaginaSEIExterna::getInstance()->processarExcecao($e);
}

PaginaSEIExterna::getInstance()->montarDocType();
PaginaSEIExterna::getInstance()->abrirHtml();
PaginaSEIExterna::getInstance()->abrirHead();
PaginaSEIExterna::getInstance()->montarMeta();
PaginaSEIExterna::getInstance()->montarTitle(PaginaSEIExterna::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEIExterna::getInstance()->montarStyle();
PaginaSEIExterna::getInstance()->abrirStyle();
?>

//  #frmAnexos {display: none}

  #lblSeriePesquisa {position:absolute;left:0%;top:0%;width:12%;}
  #selSeriePesquisa {position:absolute;left:13%;top:0%;width:60%;}

  #lblArquivo {position:absolute;left:0%;top:0%;width:12%;}
  #filArquivo {position:absolute;left:13%;top:0%;width:80%;}



<?
PaginaSEIExterna::getInstance()->fecharStyle();
PaginaSEIExterna::getInstance()->montarJavaScript();
PaginaSEIExterna::getInstance()->abrirJavaScript();
?>
  var objUpload = null;
  var objTabelaAnexos = null;
  var bolAnexos = false;

  function inicializar(){

  <? if ($bolSalvouOk){ ?>
    alert('Documento incluído no processo com número de protocolo <?=$objDocumentoDTO->getStrProtocoloDocumentoFormatado()?>.');
    infraFecharJanelaModal();
  <? } ?>

  //Anexos

  funcaoConclusao = function(arr){
    strTipoDocumento = $("#selSeriePesquisa option:selected").text();
    $("#hdnNomeUpload").val(arr['nome_upload']);
    $("#hdnNome").val(arr['nome']);
    $("#hdnDataHora").val(arr['data_hora']);
    $("#hdnTamanho").val(arr['tamanho']);
    bolAnexos = true;
  }

    <?=DocumentoINT::montarUpload('frmAnexos',$strLinkAnexos,'filArquivo','objUpload','funcaoConclusao')?>

   document.getElementById('selSeriePesquisa').focus();

  }



  function validarDocumentos() {
    if (!infraSelectSelecionado('selSeriePesquisa')) {
      alert('Informe o tipo do documento.');
      return false;
    }

    if (!bolAnexos) {
      alert('Informe o documento para inclusão.');
      return false;
    }

    $("#hdnIdSerie").val($("#selSeriePesquisa").val());

    return true;
  }


<?
PaginaSEIExterna::getInstance()->fecharJavaScript();
PaginaSEIExterna::getInstance()->fecharHead();
PaginaSEIExterna::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>

  <form id="frmUploadDocumentos" method="post" onsubmit="return validarDocumentos();" action="<?=SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao='.$_GET['acao'].'&id_acesso_externo='.$_GET['id_acesso_externo'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    ?>
    <div class="infraAreaDados" style="height: 4em;">
      <label id="lblSeriePesquisa" for="selSeriePesquisa" accesskey="" class="infraLabelObrigatorio">Tipo:</label>
      <select id="selSeriePesquisa" name="selSeriePesquisa" class="infraSelect"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
        <?=$strItensSelSerie?>
      </select>
      <input type="hidden" id="hdnIdSerie" name="hdnIdSerie" value=""/>
      <input type="hidden" id="hdnNomeUpload" name="hdnNomeUpload" value=""/>
      <input type="hidden" id="hdnNome" name="hdnNome" value=""/>
      <input type="hidden" id="hdnDataHora" name="hdnDataHora" value=""/>
      <input type="hidden" id="hdnTamanho" name="hdnTamanho" value=""/>
    </div>


  </form>
  <form id="frmAnexos" style="margin:0;border:0;padding:0;">
    <div class="infraAreaDados" style="height: 3em;">
      <label id="lblArquivo" for="filArquivo" accesskey="" class="infraLabelInputFile">Escolher Arquivo...</label>
      <input type="file" id="filArquivo" name="filArquivo" class="infraInputFile" size="50" onchange="objUpload.executar();" tabindex="1000"/><br />
    </div>
      <!-- campo hidden correspondente (hdnAnexos) deve ficar no outro form -->
  </form>


<?
PaginaSEIExterna::getInstance()->montarAreaDebug();
PaginaSEIExterna::getInstance()->fecharBody();
PaginaSEIExterna::getInstance()->fecharHtml();
?>