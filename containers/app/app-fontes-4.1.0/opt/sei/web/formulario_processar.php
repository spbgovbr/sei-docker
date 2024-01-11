<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 23/07/2015 - criado por mga
 *
 */

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  if ($_GET['acao']=='tipo_formulario_visualizar'){
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  }

  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }

  if (isset($_GET['id_procedimento'])){
    $strParametros .= '&id_procedimento='.$_GET['id_procedimento'];
  }

  if (isset($_GET['id_serie'])){
    $strParametros .= '&id_serie='.$_GET['id_serie'];
  }

  if (isset($_GET['id_tipo_formulario'])){
    $strParametros .= '&id_tipo_formulario='.$_GET['id_tipo_formulario'];
  }


  //PaginaSEI::getInstance()->salvarCamposPost(array());

  $objDocumentoDTO = new DocumentoDTO();

  $arrComandos = array();

  switch($_GET['acao']) {

    case 'formulario_gerar':

      $strTitulo = 'Gerar Formulário';

      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmFormularioProcessar" id="sbmFormularioProcessar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="V" name="btnCancelar" value="Voltar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . $strParametros . PaginaSEI::getInstance()->montarAncora($_GET['id_serie'])) . '\';" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>';

      $objDocumentoDTO->setDblIdDocumento(null);
      $objDocumentoDTO->setDblIdProcedimento($_GET['id_procedimento']);

      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->setDblIdProtocolo(null);

      if (isset($_GET['id_serie']) && $_GET['id_serie'] != -1) {
        $objDocumentoDTO->setNumIdSerie($_GET['id_serie']);
      } else {
        $objDocumentoDTO->setNumIdSerie($_POST['hdnIdSerie']);
      }

      //BUSCA DADOS DA SERIE
      $objSerieDTO = new SerieDTO();
      $objSerieDTO->setBolExclusaoLogica(false);
      $objSerieDTO->retNumIdTipoFormulario();
      $objSerieDTO->retStrNome();
      $objSerieDTO->setNumIdSerie($objDocumentoDTO->getNumIdSerie());

      $objSerieRN = new SerieRN();
      $objSerieDTO = $objSerieRN->consultarRN0644($objSerieDTO);

      if ($objSerieDTO==null){
        throw new InfraException("Registro de Tipo de Documento não encontrado.");
      }

      $objDocumentoDTO->setStrNomeSerie($objSerieDTO->getStrNome());
      $objDocumentoDTO->setNumIdTipoFormulario($objSerieDTO->getNumIdTipoFormulario());

      $objDocumentoDTO->setNumIdUnidadeResponsavel(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objDocumentoDTO->setStrSinBloqueado('N');

      $objProtocoloDTO->setStrStaNivelAcessoLocal(ProtocoloRN::$NA_PUBLICO);
      $objProtocoloDTO->setNumIdHipoteseLegal(null);
      $objProtocoloDTO->setStrStaGrauSigilo(null);

      $objProtocoloDTO->setStrDescricao(null);
      $objProtocoloDTO->setDtaGeracao(InfraData::getStrDataAtual());

      $objProtocoloDTO->setArrObjRelProtocoloAssuntoDTO(array());
      $objProtocoloDTO->setArrObjParticipanteDTO(array());
      $objProtocoloDTO->setArrObjObservacaoDTO(array());

      $objProtocoloDTO->setArrObjRelProtocoloAtributoDTO(AtributoINT::processar(null, $objDocumentoDTO->getNumIdTipoFormulario()));

      //ANEXOS
      $objProtocoloDTO->setArrObjAnexoDTO(array());

      $objDocumentoDTO->setObjProtocoloDTO($objProtocoloDTO);
      $objDocumentoDTO->setNumIdTipoConferencia(null);
      $objDocumentoDTO->setStrNumero(null);
      $objDocumentoDTO->setStrStaDocumento(DocumentoRN::$TD_FORMULARIO_GERADO);
      $objDocumentoDTO->setNumIdTextoPadraoInterno(null);
      $objDocumentoDTO->setStrProtocoloDocumentoTextoBase(null);

      if (isset($_POST['sbmFormularioProcessar'])){

        try{

          $objDocumentoRN = new DocumentoRN();
          $objDocumentoDTO = $objDocumentoRN->cadastrarRN0003($objDocumentoDTO);

          //PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
          echo "<script>parent.location.href='".SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arvore_visualizar&acao_origem='.$_GET['acao'].'&acao_retorno='.PaginaSEI::getInstance()->getAcaoRetorno().'&id_procedimento='.$_GET['id_procedimento'].'&id_documento='.$objDocumentoDTO->getDblIdDocumento().'&atualizar_arvore=1'.$strParametros)."';</script>";

          die;

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'formulario_alterar':

      $strTitulo = 'Alterar Formulário';

      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmFormularioProcessar" id="sbmFormularioProcessar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

      $strObservacao = '';

      if (!isset($_POST['hdnIdDocumento'])){

        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->retDblIdDocumento();
        $objDocumentoDTO->retDblIdProcedimento();
        $objDocumentoDTO->retNumIdTipoFormulario();
        $objDocumentoDTO->retNumIdSerie();
        $objDocumentoDTO->retStrNomeSerie();
        $objDocumentoDTO->setDblIdDocumento($_GET['id_documento']);
        $objDocumentoRN = new DocumentoRN();
        $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);
        if ($objDocumentoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }

      }else{

        $objDocumentoDTO->setDblIdDocumento($_POST['hdnIdDocumento']);
        $objDocumentoDTO->setDblIdProcedimento($_POST['hdnIdProcedimento']);
        $objDocumentoDTO->setNumIdSerie($_POST['hdnIdSerie']);
        $objDocumentoDTO->setNumIdTipoFormulario($_POST['hdnIdTipoFormulario']);
        $objDocumentoDTO->setStrNomeSerie($_POST['hdnNomeSerie']);
      }

      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->setArrObjRelProtocoloAtributoDTO(AtributoINT::processar($objDocumentoDTO->getDblIdDocumento(),$objDocumentoDTO->getNumIdTipoFormulario()));
      $objDocumentoDTO->setObjProtocoloDTO($objProtocoloDTO);

      //$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::getInstance()->montarAncora($objDocumentoDTO->getDblIdDocumento()))).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmFormularioProcessar'])){
        try{

          $objDocumentoRN = new DocumentoRN();
          $objDocumentoRN->atualizarConteudoRN1205($objDocumentoDTO);

          //PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arvore_visualizar&acao_origem='.$_GET['acao'].'&id_documento='.$objDocumentoDTO->getDblIdDocumento().'&atualizar_arvore=1'.$strParametros));
          die;

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'formulario_consultar':

      $strTitulo = "Consultar Formulário";

      $strParametros = '&id_procedimento='.$_GET['id_procedimento'].'&id_documento='.$_GET['id_documento'];

      $objDocumentoDTO->retTodos(true);
      $objDocumentoDTO->setDblIdDocumento($_GET['id_documento']);

      $objDocumentoRN = new DocumentoRN();
      $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

      if ($objDocumentoDTO==null){
        throw new InfraException("Registro não encontrado.");
      }

      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());

      break;

    case 'tipo_formulario_visualizar':

      $strTitulo = "Visualizar Tipo de Formulário";

      $arrComandos[] = '<button type="submit" accesskey="" name="sbmFormularioProcessar" id="sbmFormularioProcessar" value="Testar Confirmação de Dados" class="infraButton">Testar Confirmação de Dados</button>';

      $objDocumentoDTO->setDblIdDocumento(null);
      $objDocumentoDTO->setDblIdProcedimento(null);
      $objDocumentoDTO->setNumIdSerie(null);

      $objDocumentoDTO->setNumIdTipoFormulario($_GET['id_tipo_formulario']);
      $objDocumentoDTO->setStrNomeSerie('Tipo do Documento');

      if (isset($_POST['sbmFormularioProcessar'])){
        try{

          $objRelProtocoloAtributoRN = new RelProtocoloAtributoRN();
          $objRelProtocoloAtributoRN->validarValores(AtributoINT::processar(null, $_GET['id_tipo_formulario']));
          PaginaSEI::getInstance()->setStrMensagem('Os dados informados são válidos.',InfraPagina::$TIPO_MSG_AVISO);

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  AtributoINT::montar($objDocumentoDTO->getDblIdDocumento(),$objDocumentoDTO->getNumIdTipoFormulario(),$strHtmlAtributos,$strJavascriptAtributos);

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
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
  //<script>

  function inicializar(){
    if ('<?=$_GET['acao']?>'=='formulario_consultar'){
      infraDesabilitarCamposAreaDados();
    }
  }

  function OnSubmitForm() {
    return validarCadastroRI0881();
  }

  function validarCadastroRI0881() {

    <?=$strJavascriptAtributos?>

    return true;
  }

  //</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody(($_GET['acao']!='tipo_formulario_visualizar'?$strTitulo:''),'onload="inicializar();"');
?>
  <form id="frmFormularioCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>" style="display:inline;">
    <br>
    <?
    //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    ?>
    <div id="divSerieTitulo" class="tituloProcessoDocumento">
      <label id="lblSerieTitulo"><?=PaginaSEI::tratarHTML($objDocumentoDTO->getStrNomeSerie())?></label>
    </div>

    <?=$strHtmlAtributos?>

    <input type="hidden" id="hdnIdSerie" name="hdnIdSerie" class="infraText" value="<?=$objDocumentoDTO->getNumIdSerie()?>" />
    <input type="hidden" id="hdnIdTipoFormulario" name="hdnIdTipoFormulario" class="infraText" value="<?=$objDocumentoDTO->getNumIdTipoFormulario()?>" />
    <input type="hidden" id="hdnNomeSerie" name="hdnNomeSerie" class="infraText" value="<?=PaginaSEI::tratarHTML($objDocumentoDTO->getStrNomeSerie())?>" />
    <input type="hidden" id="hdnIdDocumento" name="hdnIdDocumento" value="<?=$objDocumentoDTO->getDblIdDocumento()?>" />
    <input type="hidden" id="hdnIdProcedimento" name="hdnIdProcedimento" value="<?=$objDocumentoDTO->getDblIdProcedimento()?>" />
    <?
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>