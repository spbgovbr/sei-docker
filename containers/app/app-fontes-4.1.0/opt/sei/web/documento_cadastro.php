<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 01/08/2008 - criado por leandro_db
 *
 * Versão do Gerador de Código: 1.13.1
 *
 * Versão no CVS: $Id$
 */

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  global $SEI_MODULOS;

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->verificarSelecao('documento_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore','id_procedimento', 'id_serie', 'id_plano_trabalho', 'id_etapa_trabalho', 'id_item_etapa', 'id_operacao', 'flag_protocolo', 'ocultar_texto_inicial', 'bloquear_tipo_documento'));

  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  $strSinOcultarTextoInicial = 'N';
  if (isset($_GET['ocultar_texto_inicial']) && $_GET['ocultar_texto_inicial']=='S'){
    $strSinOcultarTextoInicial = 'S';
  }

  $strSinBloquearTipoDocumento = 'N';
  if (isset($_GET['bloquear_tipo_documento']) && $_GET['bloquear_tipo_documento']=='S'){
    $strSinBloquearTipoDocumento = 'S';
  }

  //PaginaSEI::getInstance()->salvarCamposPost(array());

  $objInfraParametro = new InfraParametro(BancoSEI::getInstance());

  $objDocumentoDTO = new DocumentoDTO();

  $arrComandos = array();

  switch($_GET['acao']){

    case 'documento_upload_anexo':
      if (isset($_FILES['filArquivo'])){
        PaginaSEI::getInstance()->processarUpload('filArquivo', DIR_SEI_TEMP, false);
      }
      die;

    case 'documento_gerar':
    case 'documento_receber':

      if ($_GET['acao']=='documento_receber'){
        $strTitulo = 'Registrar Documento Externo';
        $strRotuloData = 'Data do Documento:';
      }elseif ($_GET['acao']=='documento_gerar'){
        $strTitulo = 'Gerar Documento';
        $strRotuloData = 'Data de Elaboração:';
      }

      $arrComandos[] = '<button type="button" onclick="confirmarDados()" accesskey="S" name="btnSalvar" id="btnSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

      if ($_GET['acao_retorno']!='plano_trabalho_detalhar'){
        $strAncora = PaginaSEI::getInstance()->montarAncora($_GET['id_serie']);
      }else{
        $strAncora = PaginaSEI::getInstance()->montarAncora($_GET['id_etapa_trabalho'].'-'.$_GET['id_item_etapa']);
      }
      $arrComandos[] = '<button type="button" accesskey="V" name="btnCancelar" value="Voltar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strAncora).'\';" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>';

      $objDocumentoDTO->setDblIdDocumento(null);
      $objDocumentoDTO->setDblIdProcedimento($_GET['id_procedimento']);

      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->setDblIdProtocolo(null);

      if (isset($_GET['id_serie']) && $_GET['id_serie']!=-1){
        $objDocumentoDTO->setNumIdSerie($_GET['id_serie']);
        $objProtocoloDTO->setNumIdSerieDocumento($_GET['id_serie']);
      }else{
        $objDocumentoDTO->setNumIdSerie($_POST['hdnIdSerie']);
        $objProtocoloDTO->setNumIdSerieDocumento($_POST['hdnIdSerie']);
      }

      $objDocumentoDTO->setNumIdUnidadeGeradoraProtocolo(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objDocumentoDTO->setNumIdUnidadeResponsavel(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objDocumentoDTO->setStrNumero($_POST['txtNumero']);
      $objDocumentoDTO->setStrNomeArvore($_POST['txtNomeArvore']);
      $objDocumentoDTO->setDinValor($_POST['txtDinValor']);
      $objDocumentoDTO->setNumIdTipoConferencia($_POST['selTipoConferencia']);
      $objDocumentoDTO->setStrSinArquivamento(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinArquivamento']));
      $objDocumentoDTO->setStrSinBloqueado('N');

      if ($_GET['acao']=='documento_receber' && $_GET['flag_protocolo']=='S'){
        $arrObjUnidadeDTOReabertura = array();
        $arrUnidadesReabertura = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUnidadesReabertura']);
        for($i=0; $i< count($arrUnidadesReabertura) ;$i++){
          $objUnidadeDTO  = new UnidadeDTO();
          $objUnidadeDTO->setNumIdUnidade($arrUnidadesReabertura[$i]);
          $arrObjUnidadeDTOReabertura[] = $objUnidadeDTO;
        }
        $objDocumentoDTO->setArrObjUnidadeDTO($arrObjUnidadeDTOReabertura);
      }

      if (!isset($_POST['rdoNivelAcesso'])){
        $objProtocoloDTO->setStrStaNivelAcessoLocal(null);
        //$objProtocoloDTO->setNumIdHipoteseLegal(null);
        //$objProtocoloDTO->setStrStaGrauSigilo(null);
      }else{
        $objProtocoloDTO->setStrStaNivelAcessoLocal($_POST['rdoNivelAcesso']);
        $objProtocoloDTO->setNumIdHipoteseLegal($_POST['selHipoteseLegal']);
        $objProtocoloDTO->setStrStaGrauSigilo($_POST['selGrauSigilo']);
      }

      $objProtocoloDTO->setStrDescricao($_POST['txtDescricao']);

      if ($_GET['acao']=='documento_gerar'){
        $objProtocoloDTO->setDtaGeracao(InfraData::getStrDataAtual());
      }else{
        $objProtocoloDTO->setDtaGeracao($_POST['txtDataElaboracao']);
      }

      $arrAssuntos = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnAssuntos']);
      $arrObjAssuntosDTO = array();
      for($x = 0;$x<count($arrAssuntos);$x++){
        $objRelProtocoloAssuntoDTO = new RelProtocoloAssuntoDTO();
        $objRelProtocoloAssuntoDTO->setNumIdAssunto($arrAssuntos[$x]);
        $objRelProtocoloAssuntoDTO->setNumSequencia($x);
        $arrObjAssuntosDTO[$x] = $objRelProtocoloAssuntoDTO;
      }
      $objProtocoloDTO->setArrObjRelProtocoloAssuntoDTO($arrObjAssuntosDTO);

      $arrObjParticipantesDTO = array();

      //INTERESSADO
      $arrParticipantes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnInteressados']);
      for($i=0; $i< count($arrParticipantes) ;$i++){
        $objParticipante  = new ParticipanteDTO();
        $objParticipante->setNumIdContato($arrParticipantes[$i]);
        $objParticipante->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);
        $objParticipante->setNumSequencia($i);
        $arrObjParticipantesDTO[] = $objParticipante;
      }

      //REMETENTE
      if ($_POST['hdnIdRemetente']!=''){
        $objParticipante  = new ParticipanteDTO();
        $objParticipante->setNumIdContato($_POST['hdnIdRemetente']);
        $objParticipante->setStrStaParticipacao(ParticipanteRN::$TP_REMETENTE);
        $objParticipante->setNumSequencia(0);
        $arrObjParticipantesDTO[] = $objParticipante;
      }

      //DESTINATARIO
      $arrParticipantes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnDestinatarios']);
      for($i=0; $i< count($arrParticipantes) ;$i++){
        $objParticipante  = new ParticipanteDTO();
        $objParticipante->setNumIdContato($arrParticipantes[$i]);
        $objParticipante->setStrStaParticipacao(ParticipanteRN::$TP_DESTINATARIO);
        $objParticipante->setNumSequencia($i);
        $arrObjParticipantesDTO[] = $objParticipante;
      }
      $objProtocoloDTO->setArrObjParticipanteDTO($arrObjParticipantesDTO);

      //OBSERVACOES
      $objObservacaoDTO  = new ObservacaoDTO();
      $objObservacaoDTO->setStrDescricao($_POST['txaObservacoes']);
      $objProtocoloDTO->setArrObjObservacaoDTO(array($objObservacaoDTO));

      //ANEXOS
      $objProtocoloDTO->setArrObjAnexoDTO(AnexoINT::processarRI0872($_POST['hdnAnexos']));

      $objDocumentoDTO->setObjProtocoloDTO($objProtocoloDTO);
      $objDocumentoDTO->setNumIdTextoPadraoInterno($_POST['hdnIdTextoPadrao']);
      $objDocumentoDTO->setStrProtocoloDocumentoTextoBase($_POST['txtProtocoloDocumentoTextoBase']);

      if ($_GET['acao']=='documento_gerar' ){
        $objDocumentoDTO->setStrStaDocumento(DocumentoRN::$TD_EDITOR_INTERNO);
      }else	if ($_GET['acao']=='documento_receber' ){
        $objDocumentoDTO->setStrStaDocumento(DocumentoRN::$TD_EXTERNO);
      }

      if (isset($_GET['id_plano_trabalho'])) {
        $objDocumentoDTO->setNumIdPlanoTrabalho($_GET['id_plano_trabalho']);
      }

      if (isset($_GET['id_etapa_trabalho'])) {
        $objDocumentoDTO->setNumIdEtapaTrabalho($_GET['id_etapa_trabalho']);
      }

      if (isset($_GET['id_item_etapa'])) {
        $objDocumentoDTO->setNumIdItemEtapa($_GET['id_item_etapa']);
      }

      if (isset($_GET['id_operacao'])) {
        $objDocumentoDTO->setStrIdOperacao($_GET['id_operacao']);
      }

      $bolValidacaoEscolha = false;
      try {
        DocumentoINT::validarEscolhaTipoDocumento($objDocumentoDTO);
      }catch(Exception $e){
        $bolValidacaoEscolha = true;
        PaginaSEI::getInstance()->processarExcecao($e);
      }

      if ($_POST['hdnFlagDocumentoCadastro']=='2' && !$bolValidacaoEscolha){

        try{

          $objDocumentoRN = new DocumentoRN();
          $objDocumentoDTO = $objDocumentoRN->cadastrarRN0003($objDocumentoDTO);

          //PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arvore_visualizar&acao_origem='.$_GET['acao'].'&acao_retorno='.PaginaSEI::getInstance()->getAcaoRetorno().'&id_procedimento='.$objProtocoloDTO->getDblIdProcedimento().'&id_documento='.$objDocumentoDTO->getDblIdDocumento().'&atualizar_arvore=1'));
          die;

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'documento_alterar':
    case 'documento_alterar_recebido':
    case 'publicacao_gerar_relacionada':

      if ($_GET['acao']=='documento_alterar_recebido'){
        $strTitulo = 'Alterar Registro de Documento Externo';
        $strRotuloData = 'Data do Documento:';
      }else if ($_GET['acao']=='documento_alterar'){
        $strTitulo = 'Alterar Documento';
        $strRotuloData = 'Data de Elaboração:';
      }else if ($_GET['acao']=='publicacao_gerar_relacionada'){
        $strTitulo = 'Gerar Publicação Relacionada';
        $strRotuloData = 'Data de Elaboração:';
      }

      $arrComandos[] = '<button type="button" onclick="confirmarDados()" accesskey="S" name="btnSalvar" id="btnSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

      $objProtocoloDTO = new ProtocoloDTO();

      $strObservacao = '';

      if (!isset($_POST['hdnIdDocumento'])){

        $objDocumentoDTO = new DocumentoDTO();

        $objDocumentoDTO->retStrDescricaoProtocolo();
        $objDocumentoDTO->retDblIdProcedimento();
        $objDocumentoDTO->retDblIdDocumento();
        $objDocumentoDTO->retStrStaNivelAcessoLocalProtocolo();
        $objDocumentoDTO->retNumIdHipoteseLegalProtocolo();
        $objDocumentoDTO->retStrStaGrauSigiloProtocolo();
        $objDocumentoDTO->retDtaGeracaoProtocolo();
        $objDocumentoDTO->retNumIdSerie();
        $objDocumentoDTO->retStrStaDocumento();
        $objDocumentoDTO->retNumIdTipoConferencia();
        $objDocumentoDTO->retStrSinArquivamento();
        $objDocumentoDTO->retNumIdUnidadeGeradoraProtocolo();
        $objDocumentoDTO->retStrSinBloqueado();
        $objDocumentoDTO->retStrNumero();
        $objDocumentoDTO->retStrNomeArvore();
        $objDocumentoDTO->retDinValor();


        $objDocumentoDTO->setDblIdDocumento($_GET['id_documento']);
        $objDocumentoRN = new DocumentoRN();
        $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);
        if ($objDocumentoDTO==null){
          throw new InfraException("Registro não encontrado.", null, null, false);
        }

        $objProtocoloDTO->setStrDescricao($objDocumentoDTO->getStrDescricaoProtocolo());
        $objProtocoloDTO->setStrStaNivelAcessoLocal($objDocumentoDTO->getStrStaNivelAcessoLocalProtocolo());
        $objProtocoloDTO->setNumIdHipoteseLegal($objDocumentoDTO->getNumIdHipoteseLegalProtocolo());
        $objProtocoloDTO->setStrStaGrauSigilo($objDocumentoDTO->getStrStaGrauSigiloProtocolo());
        $objProtocoloDTO->setDtaGeracao($objDocumentoDTO->getDtaGeracaoProtocolo());

        //observação buscar
        $objObservacaoDTO  = new ObservacaoDTO();
        $objObservacaoDTO->retStrDescricao();
        $objObservacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objObservacaoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());

        $objObservacaoRN = new ObservacaoRN();
        $objObservacaoDTO = $objObservacaoRN->consultarRN0221($objObservacaoDTO);

        if ($objObservacaoDTO!=null){
          $strObservacao = $objObservacaoDTO->getStrDescricao();
        }

      }else{

        $objDocumentoDTO->setDblIdDocumento($_POST['hdnIdDocumento']);
        $objDocumentoDTO->setDblIdProcedimento($_POST['hdnIdProcedimento']);
        $objDocumentoDTO->setStrStaDocumento($_POST['hdnStaDocumento']);
        $objDocumentoDTO->setStrSinBloqueado($_POST['hdnSinBloqueado']);
        $objDocumentoDTO->setNumIdUnidadeGeradoraProtocolo($_POST['hdnIdUnidadeGeradoraProtocolo']);
        $objProtocoloDTO->setStrDescricao($_POST['txtDescricao']);

        if (!isset($_POST['rdoNivelAcesso'])){
          $objProtocoloDTO->setStrStaNivelAcessoLocal($_POST['hdnStaNivelAcessoLocal']);
          $objProtocoloDTO->setNumIdHipoteseLegal($_POST['hdnIdHipoteseLegal']);
          $objProtocoloDTO->setStrStaGrauSigilo($_POST['hdnStaGrauSigilo']);
        }else{
          $objProtocoloDTO->setStrStaNivelAcessoLocal($_POST['rdoNivelAcesso']);
          $objProtocoloDTO->setNumIdHipoteseLegal($_POST['selHipoteseLegal']);
          $objProtocoloDTO->setStrStaGrauSigilo($_POST['selGrauSigilo']);
        }

        //$objDocumentoDTO->setNumIdUnidadeResponsavel(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

        if ($_GET['acao']=='documento_alterar' || $_GET['acao']=='publicacao_gerar_relacionada'){
          $objDocumentoDTO->setNumIdSerie($_POST['hdnIdSerie']);
          $objProtocoloDTO->setNumIdSerieDocumento($_POST['hdnIdSerie']);
        }else{
          $objDocumentoDTO->setNumIdSerie($_POST['selSerie']);
          $objProtocoloDTO->setNumIdSerieDocumento($_POST['selSerie']);
        }

        $objDocumentoDTO->setStrNumero($_POST['txtNumero']);
        $objDocumentoDTO->setStrNomeArvore($_POST['txtNomeArvore']);
        $objDocumentoDTO->setDinValor($_POST['txtDinValor']);

        if (isset($_POST['selTipoConferencia'])) {
          $objDocumentoDTO->setNumIdTipoConferencia($_POST['selTipoConferencia']);
          $objDocumentoDTO->setStrSinArquivamento(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinArquivamento']));
        }else{
          $objDocumentoDTO->setNumIdTipoConferencia($_POST['hdnIdTipoConferencia']);
          $objDocumentoDTO->setStrSinArquivamento($_POST['hdnSinArquivamento']);
        }

        $objProtocoloDTO->setDtaGeracao($_POST['txtDataElaboracao']);

        //observação buscar
        $strObservacao = $_POST['txaObservacoes'];
      }

      $objProtocoloDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());

      $arrAssuntos = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnAssuntos']);
      $arrObjAssuntosDTO = array();
      for($x = 0;$x<count($arrAssuntos);$x++){
        $objRelProtocoloAssuntoDTO = new RelProtocoloAssuntoDTO();
        $objRelProtocoloAssuntoDTO->setNumIdAssunto($arrAssuntos[$x]);
        $objRelProtocoloAssuntoDTO->setNumSequencia($x);
        $arrObjAssuntosDTO[$x] = $objRelProtocoloAssuntoDTO;
      }
      $objProtocoloDTO->setArrObjRelProtocoloAssuntoDTO($arrObjAssuntosDTO);


      $arrObjParticipantesDTO = array();

      //REMETENTE
      if ($_POST['hdnIdRemetente']){
        $objParticipante  = new ParticipanteDTO();
        $objParticipante->setNumIdContato($_POST['hdnIdRemetente']);
        $objParticipante->setStrStaParticipacao(ParticipanteRN::$TP_REMETENTE);
        $objParticipante->setNumSequencia(0);
        $arrObjParticipantesDTO[] = $objParticipante;
      }

      //INTERESSADO
      $arrParticipantes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnInteressados']);
      for($i=0; $i< count($arrParticipantes) ;$i++){
        $objParticipante  = new ParticipanteDTO();
        $objParticipante->setNumIdContato($arrParticipantes[$i]);
        $objParticipante->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);
        $objParticipante->setNumSequencia($i);
        $arrObjParticipantesDTO[] = $objParticipante;
      }

      //DESTINATARIO
      $arrParticipantes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnDestinatarios']);
      for($i=0; $i< count($arrParticipantes) ;$i++){
        $objParticipante  = new ParticipanteDTO();
        $objParticipante->setNumIdContato($arrParticipantes[$i]);
        $objParticipante->setStrStaParticipacao(ParticipanteRN::$TP_DESTINATARIO);
        $objParticipante->setNumSequencia($i);
        $arrObjParticipantesDTO[] = $objParticipante;
      }
      $objProtocoloDTO->setArrObjParticipanteDTO($arrObjParticipantesDTO);

      //OBSERVACOES
      $objObservacaoDTO  = new ObservacaoDTO();
      $objObservacaoDTO->setStrDescricao($strObservacao);
      $objProtocoloDTO->setArrObjObservacaoDTO(array($objObservacaoDTO));

      //ANEXOS
      $objProtocoloDTO->setArrObjAnexoDTO(AnexoINT::processarRI0872($_POST['hdnAnexos']));

      $objDocumentoDTO->setObjProtocoloDTO($objProtocoloDTO);

      //$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objDocumentoDTO->getDblIdDocumento()))).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $bolValidacaoEscolha = false;
      try {
        DocumentoINT::validarEscolhaTipoDocumento($objDocumentoDTO);
      }catch(Exception $e){
        $bolValidacaoEscolha = true;
        PaginaSEI::getInstance()->processarExcecao($e);
      }

      if ($_POST['hdnFlagDocumentoCadastro']=='2' && !$bolValidacaoEscolha ){
        try{

          $objDocumentoRN = new DocumentoRN();

          if ($_GET['acao']=='documento_alterar' || $_GET['acao']=='documento_alterar_recebido'){
            $objDocumentoRN->alterarRN0004($objDocumentoDTO);
          }else if ($_GET['acao']=='publicacao_gerar_relacionada'){
            $objDocumentoDTO = $objDocumentoRN->gerarPublicacaoRelacionadaRN1207($objDocumentoDTO);
          }

          //PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arvore_visualizar&acao_origem='.$_GET['acao'].'&id_documento='.$objDocumentoDTO->getDblIdDocumento().'&atualizar_arvore=1'));
          die;

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;


    case 'documento_consultar':
    case 'documento_consultar_recebido':

      if ($_GET['acao']=='documento_consultar_recebido'){
        $strTitulo = 'Consultar Registro de Documento Externo';
        $strRotuloData = 'Data do Documento:';
      }else{
        $strTitulo = "Consultar Documento";
        $strRotuloData = 'Data de Elaboração:';
      }

      $strAncora = '';
      $strParametros = '&id_documento='.$_GET['id_documento'];

      $objDocumentoDTO->retStrDescricaoProtocolo();
      $objDocumentoDTO->retDblIdProcedimento();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retStrStaNivelAcessoLocalProtocolo();
      $objDocumentoDTO->retNumIdHipoteseLegalProtocolo();
      $objDocumentoDTO->retStrStaGrauSigiloProtocolo();
      $objDocumentoDTO->retDtaGeracaoProtocolo();
      $objDocumentoDTO->retNumIdSerie();
      $objDocumentoDTO->retStrStaDocumento();
      $objDocumentoDTO->retNumIdTipoConferencia();
      $objDocumentoDTO->retStrSinArquivamento();
      $objDocumentoDTO->retNumIdUnidadeGeradoraProtocolo();
      $objDocumentoDTO->retStrSinBloqueado();
      $objDocumentoDTO->retStrNumero();
      $objDocumentoDTO->retStrNomeArvore();
      $objDocumentoDTO->retDinValor();

      $objDocumentoDTO->setDblIdDocumento($_GET['id_documento']);

      $objDocumentoRN = new DocumentoRN();
      $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);
      if ($objDocumentoDTO==null){
        throw new InfraException("Registro não encontrado.");
      }

      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->setStrDescricao($objDocumentoDTO->getStrDescricaoProtocolo());
      $objProtocoloDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());
      $objProtocoloDTO->setStrStaNivelAcessoLocal($objDocumentoDTO->getStrStaNivelAcessoLocalProtocolo());
      $objProtocoloDTO->setNumIdHipoteseLegal($objDocumentoDTO->getNumIdHipoteseLegalProtocolo());
      $objProtocoloDTO->setStrStaGrauSigilo($objDocumentoDTO->getStrStaGrauSigiloProtocolo());
      $objProtocoloDTO->setDtaGeracao($objDocumentoDTO->getDtaGeracaoProtocolo());

      //observação buscar
      $objObservacaoDTO  = new ObservacaoDTO();
      $objObservacaoDTO->retStrDescricao();
      $objObservacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objObservacaoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());

      $objObservacaoRN = new ObservacaoRN();
      $objObservacaoDTO = $objObservacaoRN->consultarRN0221($objObservacaoDTO);

      if ($objObservacaoDTO==null){
        $objObservacaoDTO  = new ObservacaoDTO();
        $objObservacaoDTO->setStrDescricao('');
      }

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strNomeSerie = '';
  if ($objDocumentoDTO->getNumIdSerie()!=null) {
    //BUSCA DADOS DA SERIE
    $objSerieDTO = new SerieDTO();
    $objSerieDTO->setBolExclusaoLogica(false);
    $objSerieDTO->retNumIdSerie();
    $objSerieDTO->retStrStaNumeracao();
    $objSerieDTO->retStrStaAplicabilidade();
    $objSerieDTO->retStrNome();
    $objSerieDTO->retStrSinInteressado();
    $objSerieDTO->retStrSinDestinatario();
    $objSerieDTO->retStrSinValorMonetario();
    $objSerieDTO->retNumIdModelo();
    $objSerieDTO->setNumIdSerie($objDocumentoDTO->getNumIdSerie());

    $objSerieRN = new SerieRN();
    $objSerieDTO = $objSerieRN->consultarRN0644($objSerieDTO);

    if ($objSerieDTO == null) {
      throw new InfraException('Registro de Tipo de Documento não encontrado ['.$objDocumentoDTO->getNumIdSerie().'].');
    }

    $strNomeSerie = $objSerieDTO->getStrNome();
  }

  //ASSUNTOS
  $strAssuntosNegados = 'var arrAssuntosNegados = Array();'."\n";
  $numAssuntos = 0;
  if (!isset($_POST['hdnFlagDocumentoCadastro'])){
    if ($_GET['acao']=='documento_gerar' || $_GET['acao']=='documento_receber'){
      $strItensSelRelProtocoloAssunto = SerieINT::montarSelectSugestaoAssuntos($objDocumentoDTO->getNumIdSerie());
    }else{

      $strItensSelRelProtocoloAssunto = RelProtocoloAssuntoINT::conjuntoPorCodigoDescricaoRI0510($objDocumentoDTO->getDblIdDocumento());

      if ($_GET['acao']=='documento_alterar' || $_GET['acao']=='documento_alterar_recebido'){
        $objRelProtocoloAssuntoDTO = new RelProtocoloAssuntoDTO();
        $objRelProtocoloAssuntoDTO->setDistinct(true);
        $objRelProtocoloAssuntoDTO->retNumIdAssunto();
        $objRelProtocoloAssuntoDTO->retStrSiglaUnidade();
        $objRelProtocoloAssuntoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());
        $objRelProtocoloAssuntoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual(), InfraDTO::$OPER_DIFERENTE);
        $objRelProtocoloAssuntoRN = new RelProtocoloAssuntoRN();
        $arrObjRelProtocoloAssuntoDTO = $objRelProtocoloAssuntoRN->listarRN0188($objRelProtocoloAssuntoDTO);

        foreach($arrObjRelProtocoloAssuntoDTO as $objRelProtocoloAssuntoDTO){
          $strAssuntosNegados .= 'arrAssuntosNegados['.$numAssuntos++.'] = {id_assunto:\''.$objRelProtocoloAssuntoDTO->getNumIdAssunto().'\',sigla_unidade:\''.$objRelProtocoloAssuntoDTO->getStrSiglaUnidade().'\'};'."\n";;
        }

      }
    }
  }else if ($_POST['hdnFlagDocumentoCadastro']=='1'){
    $_POST['hdnAssuntos'] = '';
    $strItensSelRelProtocoloAssunto = AssuntoINT::montarSelectTrocaSerie($objDocumentoDTO->getNumIdSerie(), $arrAssuntos);
  }

  $strDisplayTextoInicial = 'display:none';
  if ($_GET['acao']=='documento_gerar' && $strSinOcultarTextoInicial=='N'){
    $strDisplayTextoInicial = 'display:block';
  }

  if (($_GET['acao']=='documento_gerar' || $_GET['acao']=='documento_receber') && isset($_GET['id_procedimento']) && !isset($_POST['hdnIdDocumento'])){
    $dblProtocoloInicializacao = $_GET['id_procedimento'];
  }else{
    $dblProtocoloInicializacao = $objProtocoloDTO->getDblIdProtocolo();
  }

  $strDisplayAssuntos = 'display:block';
  $strDisplayInteressados = 'display:block';
  $strDisplayDestinatarios = 'display:block';
  $strDisplayUnidadesReabertura = 'display:none';
  $strDisplaySerieData = 'display:none;';
  $strDisplayNumero = 'display:none;';
  $strDisplayValorMonetario = 'display:none;';
  $strDisplayDivFormato = 'display:none;';
  $strDisplayOpcoesDigitalizado = 'display:none;';
  $strDisplayDescricao = 'display:none;';
  $strDisplaySerieTitulo = 'display:block;';
  $strDisplayAnexos = 'display:none;';
  $strClassLabelNumero = 'class="infraLabelOpcional"';
  $strItensSelSerie = '';
  $strItensSelTipoConferencia = '';
  $strFormatoNatoChecked = '';
  $strFormatoDigitalizadoChecked = '';
  $strFormatoNatoDisabled = '';
  $strFormatoDigitalizadoDisabled = '';
  $strOpcoesDigitalizadoDisabled = '';

  if ($_GET['acao']=='documento_gerar' ||
      $_GET['acao']=='documento_alterar' ||
      $_GET['acao']=='documento_consultar' ||
      $_GET['acao']=='publicacao_gerar_relacionada'){

    if ($objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_FORMULARIO_AUTOMATICO || $objDocumentoDTO->getStrStaDocumento() == DocumentoRN::$TD_FORMULARIO_GERADO){
      $strDisplayAssuntos = 'display:none';
    }

    if ($objSerieDTO->getStrSinInteressado()=='N'){
      $strDisplayInteressados = 'display:none';
    }

    if ($objSerieDTO->getStrSinDestinatario()=='N'){
      $strDisplayDestinatarios = 'display:none';
    }

    if ($objSerieDTO->getStrStaNumeracao()==SerieRN::$TN_INFORMADA){
      $strClassLabelNumero = 'class="infraLabelObrigatorio"';
      $strDisplayNumero = '';
    }

    $strDisplayDescricao = 'display:block;';

    if (($objSerieDTO->getStrStaAplicabilidade()==SerieRN::$TA_INTERNO || $objSerieDTO->getStrStaAplicabilidade()==SerieRN::$TA_INTERNO_EXTERNO) &&  $objSerieDTO->getNumIdModelo()==null){
      throw new InfraException('Tipo de documento "'.$objSerieDTO->getStrNome().'" não possui modelo associado.');
    }

  }else{

    if ($_GET['acao']=='documento_receber' && $_GET['flag_protocolo']=='S'){
      $strDisplayUnidadesReabertura = 'display:block;';
    }

    $strDisplaySerieData = 'display:block;';
    $strDisplayDivFormato = 'display:block;';
    $strDisplayNumero = '';
    $strDisplaySerieTitulo = 'display:none;';
    $strDisplayAnexos = 'display:block';

    if ($strSinBloquearTipoDocumento=='S'){
      $strItensSelSerie = SerieINT::montarSelectNomeUnico($objDocumentoDTO->getNumIdSerie());
    }else{
      $strItensSelSerie = SerieINT::montarSelectNomeExternos('null','&nbsp;',$objDocumentoDTO->getNumIdSerie());
    }

    $strItensSelTipoConferencia = TipoConferenciaINT::montarSelectDescricao('null','&nbsp;',$objDocumentoDTO->getNumIdTipoConferencia());

    if (isset($_POST['rdoFormato'])){
      if ($_POST['rdoFormato']=='N'){
        $strFormatoNatoChecked = ' checked="checked" ';
        $strDisplayOpcoesDigitalizado = 'display:none;';
      }else if ($_POST['rdoFormato']=='D'){
        $strFormatoDigitalizadoChecked = ' checked="checked" ';
        $strDisplayOpcoesDigitalizado = 'display:block;';
      }
    }else if ($_GET['acao']=='documento_alterar_recebido' || $_GET['acao']=='documento_consultar_recebido'){
      if ($objDocumentoDTO->getNumIdTipoConferencia() == null){
        $strFormatoNatoChecked = ' checked="checked" ';
        $strDisplayOpcoesDigitalizado = 'display:none;';
      }else if ($objDocumentoDTO->getNumIdTipoConferencia() != null){
        $strFormatoDigitalizadoChecked = ' checked="checked" ';
        $strDisplayOpcoesDigitalizado = 'display:block;';
      }

      if ($objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo()!=SessaoSEI::getInstance()->getNumIdUnidadeAtual()){
        $strFormatoNatoDisabled = 'disabled="disabled"';
        $strFormatoDigitalizadoDisabled = 'disabled="disabled"';
        $strOpcoesDigitalizadoDisabled = 'disabled="disabled"';
      }
    }
  }

  if ($objSerieDTO!=null && $objSerieDTO->getStrSinValorMonetario()=='S'){
    $strDisplayValorMonetario = '';
  }

  //busca somente ao entrar na tela ou vindo da escolha do clone
  if (!isset($_POST['hdnIdDocumento'])){

    //REMETENTE
    $objRemetente = new ParticipanteDTO();
    $objRemetente->retNumIdContato();
    $objRemetente->retStrNomeContato();
    $objRemetente->retStrSiglaContato();
    $objRemetente->setDblIdProtocolo($dblProtocoloInicializacao);
    $objRemetente->setStrStaParticipacao(ParticipanteRN::$TP_REMETENTE);
    $objParticipanteRN = new ParticipanteRN();
    $objRemetente = $objParticipanteRN->consultarRN1008($objRemetente);

    if ($objRemetente!=null){
      $strIdRemetente = $objRemetente->getNumIdContato();
      $strNomeRemetente = ContatoINT::formatarNomeSiglaRI1224($objRemetente->getStrNomeContato(),$objRemetente->getStrSiglaContato());
    }
  }else{
    $strIdRemetente = $_POST['hdnIdRemetente'];
    $strNomeRemetente = $_POST['txtRemetente'];
  }

  $strInteressadosNegados = 'var arrInteressadosNegados = Array();'."\n";
  $strDestinatariosNegados = 'var arrDestinatariosNegados = Array();'."\n";
  $numInteressados = 0;
  $numDestinatarios = 0;
  if ($_GET['acao']=='documento_alterar' || $_GET['acao']=='documento_alterar_recebido' || $_GET['acao']=='publicacao_gerar_relacionada'){
    $objParticipanteDTO = new ParticipanteDTO();
    $objParticipanteDTO->retNumIdContato();
    $objParticipanteDTO->retStrStaParticipacao();
    $objParticipanteDTO->retStrSiglaUnidade();
    $objParticipanteDTO->setDblIdProtocolo($dblProtocoloInicializacao);
    $objParticipanteDTO->setStrStaParticipacao(array(ParticipanteRN::$TP_INTERESSADO,ParticipanteRN::$TP_DESTINATARIO),InfraDTO::$OPER_IN);
    $objParticipanteDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual(),InfraDTO::$OPER_DIFERENTE);
    $objParticipanteRN = new ParticipanteRN();
    $arrObjParticipanteDTO = $objParticipanteRN->listarRN0189($objParticipanteDTO);

    foreach($arrObjParticipanteDTO as $objParticipanteDTO){
      if ($objParticipanteDTO->getStrStaParticipacao()==ParticipanteRN::$TP_INTERESSADO){
        $strInteressadosNegados .= 'arrInteressadosNegados['.$numInteressados++.'] = {id_contato: \''.$objParticipanteDTO->getNumIdContato().'\', sigla_unidade: \''.$objParticipanteDTO->getStrSiglaUnidade().'\'};'."\n";
      }else if ($objParticipanteDTO->getStrStaParticipacao()==ParticipanteRN::$TP_DESTINATARIO){
        $strDestinatariosNegados .= 'arrDestinatariosNegados['.$numDestinatarios++.'] = {id_contato: \''.$objParticipanteDTO->getNumIdContato().'\', sigla_unidade: \''.$objParticipanteDTO->getStrSiglaUnidade().'\'};'."\n";
      }
    }
  }

  //INTERESSADOS
  $strItensSelInteressado = ParticipanteINT::conjuntoPorParticipacaoRI0513($dblProtocoloInicializacao,array(ParticipanteRN::$TP_INTERESSADO));

  //DESTINATARIO
  $strItensSelDestinatario = ParticipanteINT::conjuntoPorParticipacaoRI0513($dblProtocoloInicializacao,array(ParticipanteRN::$TP_DESTINATARIO));

  //OBSERVACOES
  $strTabObservacoes = ObservacaoINT::tabelaObservacoesOutrasUnidades($dblProtocoloInicializacao);

  $objProcedimentoDTO = new ProcedimentoDTO();
  $objProcedimentoDTO->retNumIdTipoProcedimento();
  $objProcedimentoDTO->retStrStaEstadoProtocolo();
  $objProcedimentoDTO->setDblIdProcedimento($objDocumentoDTO->getDblIdProcedimento());

  $objProcedimentoRN = new ProcedimentoRN();
  $objProcedimentoDTO = $objProcedimentoRN->consultarRN0201($objProcedimentoDTO);
  $numIdTipoProcedimento = $objProcedimentoDTO->getNumIdTipoProcedimento();

  $bolPermitirAlteracaoNivelAcesso = $objInfraParametro->getValor('SEI_ALTERACAO_NIVEL_ACESSO_DOCUMENTO',false);

  ProtocoloINT::montarNivelAcesso(array($numIdTipoProcedimento),
      $objProtocoloDTO,
      (($_GET['acao']=='documento_consultar' || $_GET['acao']=='documento_consultar_recebido') || ($objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo()!=SessaoSEI::getInstance()->getNumIdUnidadeAtual() && $bolPermitirAlteracaoNivelAcesso!='1')),
      $strCssNivelAcesso,
      $strHtmlNivelAcesso,
      $strJsGlobalNivelAcesso,
      $strJsInicializarNivelAcesso,
      $strJsValidacoesNivelAcesso);

  //ANEXOS
  $bolAlteracaoAnexoPermitida = $objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo()==SessaoSEI::getInstance()->getNumIdUnidadeAtual() &&
                                $objDocumentoDTO->getStrSinBloqueado()=='N' &&
                                $_GET['acao']!='documento_consultar' &&
                                $_GET['acao']!='documento_consultar_recebido' &&
                                $objProcedimentoDTO->getStrStaEstadoProtocolo()!=ProtocoloRN::$TE_PROCEDIMENTO_ANEXADO;

  $strDisplayAnexarArquivo = '';
  if (!$bolAlteracaoAnexoPermitida){
    $strDisplayAnexarArquivo = 'display:none;';
  }

  $bolAcaoUpload = SessaoSEI::getInstance()->verificarPermissao('documento_upload_anexo');
  $bolAcaoDownload = SessaoSEI::getInstance()->verificarPermissao('documento_download_anexo');
  $bolAcaoRemoverAnexo = (SessaoSEI::getInstance()->verificarPermissao('documento_remover_anexo') && $bolAlteracaoAnexoPermitida);




  $arrIdAnexos = null;
  if ($objProtocoloDTO->getDblIdProtocolo()!=null) {
    //Itens da tabela de anexos
    $objAnexoDTO = new AnexoDTO();
    $objAnexoDTO->retNumIdAnexo();
    $objAnexoDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());

    $objAnexoRN = new AnexoRN();
    $arrIdAnexos = InfraArray::converterArrInfraDTO($objAnexoRN->listarRN0218($objAnexoDTO),'IdAnexo');
  }

  $_POST['hdnAnexos'] = AnexoINT::montarAnexos($arrIdAnexos,
                                               $bolAcaoDownload,
                               'documento_download_anexo',
                                               $arrAcoesDownload,
                                               $bolAcaoRemoverAnexo,
                                               $arrAcoesRemover);

  //Links para uso com AJAX
  $strLinkAjaxTextoPadrao = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=texto_padrao_auto_completar');
  $strLinkTextoPadraoSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=texto_padrao_interno_selecionar&tipo_selecao=1&id_object=objLupaTextoPadrao');
  $strLinkDocumentoTextoBaseSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_modelo_selecionar&tipo_selecao=1&id_object=objLupaDocumentoTextoBase');
  $strLinkAjaxAssuntoRI1223 = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=assunto_auto_completar_RI1223');
  $strLinkAssuntosSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assunto_selecionar&tipo_selecao=2&id_object=objLupaAssuntos');
  $strLinkAjaxContatos = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=contato_auto_completar_contexto_RI1225');
  $strLinkAjaxCadastroAutomatico = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=contato_cadastro_contexto_temporario');
  $strLinkAjaxDocumentoRecebidoDuplicado = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=documento_recebido_duplicado');
  $strLinkInteressados = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=contato_selecionar&tipo_selecao=2&id_object=objLupaInteressados');
  $strLinkDestinatarios = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=contato_selecionar&tipo_selecao=2&id_object=objLupaDestinatarios');
  $strLinkRemetente = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=contato_selecionar&tipo_selecao=1&id_object=objLupaRemetente');
  $strLinkUnidadesReabertura = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_selecionar_reabertura_processo&tipo_selecao=2&id_object=objLupaUnidadesReabertura&id_procedimento='.$_GET['id_procedimento']);
  $strLinkAlterarContato = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=contato_alterar&acao_origem='.$_GET['acao'].'&arvore='.$_GET['arvore']);
  $strLinkConsultarContato = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=contato_consultar&acao_origem='.$_GET['acao'].'&arvore='.$_GET['arvore']);
  $strLinkConsultarAssunto = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assunto_consultar&acao_origem='.$_GET['acao']);

  $strLinkAnexos = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_upload_anexo');

  $strCheckedTextoPadrao = '';
  $strCheckedProtocoloDocumentoTextoBase = '';
  $strCheckedNenhum = '';

  if ($_POST['rdoTextoInicial']=='D'){
    $strCheckedProtocoloDocumentoTextoBase = 'checked="checked"';
  }else if ($_POST['rdoTextoInicial']=='T'){
    $strCheckedTextoPadrao = 'checked="checked"';
  }else{
    $strCheckedNenhum = 'checked="checked"';
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

  #divSerieTitulo {<?=$strDisplaySerieTitulo?>;width:85%;}

  #lblDescricao {position:absolute;left:0;top:0;width:50%;}
  #txtDescricao {position:absolute;left:0;top:40%;width:85%;}

  #divUnidadesReabertura {<?=$strDisplayUnidadesReabertura?>}
  #lblUnidadesReabertura {position:absolute;left:0;top:0;}
  #selUnidadesReabertura {position:absolute;left:0;top:25%;width:90%;}
  #divOpcoesUnidadesReabertura {position:absolute;left:91%;top:25%;}

  #divSerieDataElaboracao {<?=$strDisplaySerieData?>}
  #lblSerie {position:absolute;left:0;top:0;}
  #selSerie {position:absolute;left:0;top:36%;width:50%;}

  #lblDataElaboracao {position:absolute;left:52%;top:0;}
  #txtDataElaboracao {position:absolute;left:52%;top:36%;width:14%;}
  #imgDataElaboracao {position:absolute;left:67%;top:41%;}

  #divTextoInicial {<?=$strDisplayTextoInicial?>}
  #fldTextoInicial {position:absolute;left:0;top:0;height:85%;width:85%;}

  #divOptProtocoloDocumentoTextoBase {position:absolute;left:13%;top:22%;}
  #txtProtocoloDocumentoTextoBase {position:absolute;left:40%;top:22%;width:15%;visibility:hidden;}
  #lblOuModeloFavorito {position:absolute;left:57%;top:25%;visibility:hidden;}
  #btnEscolherDocumentoTextoBase {position:absolute;left:60.5%;top:18%;visibility:hidden;}

  #divOptTextoPadrao {position:absolute;left:13%;top:47%;}
  #txtTextoPadrao {position:absolute;left:40%;top:45%;width:55%;visibility:hidden;}
  #imgPesquisarTextoPadrao {position:absolute;left:96%;top:45%;visibility:hidden;}

  #divOptNenhum   {position:absolute;left:13%;top:72%;}

  #divNumeroNomeArvore {}
  #lblNumero {position:absolute;left:0;top:0;<?=$strDisplayNumero?>}
  #txtNumero {position:absolute;left:0;top:36%;width:14%;<?=$strDisplayNumero?>}
  #lblNomeArvore {position:absolute;left:<?=($strDisplayNumero==''?'16%':'0')?>;top:0;}
  #txtNomeArvore {position:absolute;left:<?=($strDisplayNumero==''?'16%':'0')?>;top:36%;width:<?=($strDisplayNumero==''?'34%':'50%')?>;}
  #lblDinValor {position:absolute;left:52%;top:0;<?=$strDisplayValorMonetario?>}
  #txtDinValor {position:absolute;left:52%;top:36%;width:14%;<?=$strDisplayValorMonetario?>}

  #divFormato {<?=$strDisplayDivFormato?>;}
  #fldFormato {position:absolute;left:0%;top:0%;height:95%;width:300px;}
  #divOptNato {position:absolute;left:15%;top:<?=(PaginaSEI::getInstance()->isBolAjustarTopFieldset()?'10%':'35%');?>}
  #divOptDigitalizado {position:absolute;left:15%;top:<?=(PaginaSEI::getInstance()->isBolAjustarTopFieldset()?'50%':'65%');?>}
  #ancAjudaFormato {position:absolute;left:305px;top:15%;}

  #lblTipoConferencia {position:absolute;left:52%;top:10%;width:33%;<?=$strDisplayOpcoesDigitalizado?>}
  #selTipoConferencia {position:absolute;left:52%;top:34%;width:33%;<?=$strDisplayOpcoesDigitalizado?>}

  #divSinArquivamento {position:absolute;left:52%;top:73%;<?=$strDisplayOpcoesDigitalizado?>}
  #ancAjudaArquivamento > img {vertical-align:top;padding-left:.3em;}

  #divDescricao {<?=$strDisplayDescricao?>}

  #lblRemetente {position:absolute;left:0;top:5%;}
  #txtRemetente {position:absolute;left:0;top:41%;width:85%;}
  #divOpcoesRemetente {position:absolute;left:86%;top:41%;width:80px;}

  #divInteressados {<?=$strDisplayInteressados?>}
  #lblInteressados {position:absolute;left:0;top:0;}
  #txtInteressado {position:absolute;left:0;top:18%;width:50%;}
  #selInteressados {position:absolute;left:0;top:43%;width:85%;height:50%;}
  #divOpcoesInteressados {position:absolute;left:86%;top:43%;width:80px;}

  #divDestinatarios {<?=$strDisplayDestinatarios?>}
  #lblDestinatarios {position:absolute;left:0;top:0;}
  #txtDestinatario {position:absolute;left:0;top:18%;width:50%;}
  #selDestinatarios {position:absolute;left:0;top:43%;width:85%;height:50%;}
  #divOpcoesDestinatarios {position:absolute;left:86%;top:43%;}

  #divAssuntos {<?=$strDisplayAssuntos?>}
  #lblAssuntos {position:absolute;left:0;top:0;}
  #txtAssunto {position:absolute;left:0;top:18%;width:50%;}
  #selAssuntos {position:absolute;left:0;top:43%;width:85%;height:50%;}
  #divOpcoesAssuntos {position:absolute;left:86%;top:43%;width:80px;}

  #lblObservacoes {position:absolute;left:0;top:0;width:50%;}
  #txaObservacoes {position:absolute;left:0;top:27%;width:85%;}

  /* #divObservacoesOutras {display:none;} */

<?=$strCssNivelAcesso?>

  #frmAnexos {margin: .5em 0 0 0;border:0;padding:0;<?=$strDisplayAnexos?>}
  #divArquivo {height:3em;<?=$strDisplayAnexarArquivo?>;width:80%;}
  #lblArquivo {position:absolute;left:0;top:0;width:70%;}
  #filArquivo {position:absolute;left:0;top:50%;width:70%;}
  #imgAdicionarArquivo {position:absolute;left:50%;top:40%;}

<?if (PaginaSEI::getInstance()->isBolAjustarTopFieldset()) {?>

  #divOptProtocoloDocumentoTextoBase {top:5%;}
  #txtProtocoloDocumentoTextoBase {top:5%;}
  #lblOuModeloFavorito {top:10%;}
  #btnEscolherDocumentoTextoBase {top:2%;}

  #divOptTextoPadrao {top:35%;}
  #txtTextoPadrao {top:30%;}
  #imgPesquisarTextoPadrao{top:30%}

  #divOptNenhum {top:65%;}

<?
}
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

  var objLupaUnidadesReabertura = null;
  var objTabelaAnexos = null;
  var objAutoCompletarTextoPadrao = null;
  var objLupaTextoPadrao = null;
  var objLupaDocumentoTextoBase = null;
  var objAutoCompletarAssuntoRI1223 = null;
  var objLupaAssuntos = null;
  var objLupaInteressados = null;
  var objAutoCompletarInteressadoRI1225 = null;
  var objLupaRemetente = null;
  var objAutoCompletarRemetenteRI1226 = null;
  var objLupaDestinatarios = null;
  var objAutoCompletarDestinatarioRI1226 = null;
  var objContatoCadastroAutomatico = null;
  var objUpload = null;


  <?=$strJsGlobalNivelAcesso?>

  <?=$strAssuntosNegados?>

  <?=$strInteressadosNegados?>

  <?=$strDestinatariosNegados?>

  function inicializar(){

    objLupaUnidadesReabertura = new infraLupaSelect('selUnidadesReabertura','hdnUnidadesReabertura','<?=$strLinkUnidadesReabertura?>');

    <?if ($_GET['acao']=='documento_gerar'){?>
      configurarTextoInicial();

      objLupaDocumentoTextoBase = new infraLupaText('txtProtocoloDocumentoTextoBase','hdnIdDocumentoTextoBase','<?=$strLinkDocumentoTextoBaseSelecao?>');

      objAutoCompletarTextoPadrao = new infraAjaxAutoCompletar('hdnIdTextoPadrao','txtTextoPadrao','<?=$strLinkAjaxTextoPadrao?>');
      objAutoCompletarTextoPadrao.limparCampo = false;

      objAutoCompletarTextoPadrao.prepararExecucao = function(){
        return 'palavras_pesquisa='+encodeURIComponent(document.getElementById('txtTextoPadrao').value);
      };

      objAutoCompletarTextoPadrao.processarResultado = function(id,descricao,complemento){
        if (id!=''){
          document.getElementById('hdnIdTextoPadrao').value = id;
          document.getElementById('txtTextoPadrao').value = descricao;
        }
      }
      objAutoCompletarTextoPadrao.selecionar('<?=$_POST['hdnIdTextoPadrao']?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript($_POST['txtTextoPadrao'],false);?>');

      objLupaTextoPadrao = new infraLupaText('txtTextoPadrao','hdnIdTextoPadrao','<?=$strLinkTextoPadraoSelecao?>');
      objLupaTextoPadrao.finalizarSelecao = function(){
        objAutoCompletarTextoPadrao.selecionar(document.getElementById('hdnIdTextoPadrao').value,document.getElementById('txtTextoPadrao').value);
      }

    <?}?>


    if ('<?=$_GET['acao']?>'=='documento_gerar' || '<?=$_GET['acao']?>'=='documento_alterar' || '<?=$_GET['acao']?>'=='publicacao_gerar_relacionada' ||	'<?=$_GET['acao']?>'=='documento_consultar'){
      document.getElementById('divRemetente').style.display='none';
      document.getElementById('divDestinatarios').style.display='';
    }else{
      document.getElementById('divRemetente').style.display='';
      document.getElementById('divDestinatarios').style.display='none';
    }

    funcaoConclusao = function(arr){
      desabilitarBotaoSalvar(true);
      objTabelaAnexos.limpar();
      objTabelaAnexos.adicionar([arr['nome_upload'],arr['nome'],arr['data_hora'],arr['tamanho'],infraFormatarTamanhoBytes(arr['tamanho']),'<?=PaginaSEI::getInstance()->formatarParametrosJavaScript(SessaoSEI::getInstance()->getStrSiglaUsuario())?>' ,'<?=PaginaSEI::getInstance()->formatarParametrosJavaScript(SessaoSEI::getInstance()->getStrSiglaUnidadeAtual())?>']);
      objTabelaAnexos.adicionarAcoes(arr['nome_upload'],'',false,true);
      desabilitarBotaoSalvar(false);
    };

    <?=DocumentoINT::montarUpload('frmAnexos',$strLinkAnexos,'filArquivo','objUpload', 'funcaoConclusao','objTabelaAnexos','tblAnexos','hdnAnexos')?>



    //Monta ações de download
    <? if (InfraArray::contar($arrAcoesDownload)>0){
      foreach(array_keys($arrAcoesDownload) as $id) { ?>
    objTabelaAnexos.adicionarAcoes('<?=$id?>','<?=$arrAcoesDownload[$id]?>');
    <?   }
  } ?>

    //Monta ações para remover anexos
    <? if (InfraArray::contar($arrAcoesRemover)>0){
      foreach(array_keys($arrAcoesRemover) as $id) { ?>
    objTabelaAnexos.adicionarAcoes('<?=$id?>','',false,true);
    <?   }
  } ?>

    //Se consultando desabilita campos e não monta ações para remover anexos
    if ('<?=$_GET['acao']?>'=='documento_consultar' || '<?=$_GET['acao']?>'=='documento_consultar_recebido'){
      infraDesabilitarCamposDiv(document.getElementById('divSerieDataElaboracao'));
      infraDesabilitarCamposDiv(document.getElementById('divDescricao'));
      infraDesabilitarCamposDiv(document.getElementById('divNumeroNomeArvore'));
      infraDesabilitarCamposDiv(document.getElementById('divFormato'));
      document.getElementById('ancAjudaFormato').style.display = 'none';
      infraDesabilitarCamposDiv(document.getElementById('divAssuntos'));
      document.getElementById('selAssuntos').ondblclick = function(e){
        if (this.selectedIndex!=-1) {
          seiCadastroAssunto(this.options[this.selectedIndex].value, 'selAssuntos', 'frmDocumentoCadastro', '<?=$strLinkConsultarAssunto?>');
        }
      };


      infraDesabilitarCamposDiv(document.getElementById('divRemetente'));
      infraDesabilitarCamposDiv(document.getElementById('divInteressados'));
      document.getElementById('selInteressados').ondblclick = function(e){
        if (this.selectedIndex!=-1) {
          seiCadastroContato(this.options[this.selectedIndex].value, 'selInteressados', 'frmDocumentoCadastro','<?=$strLinkConsultarContato?>');
        }
      };

      infraDesabilitarCamposDiv(document.getElementById('divDestinatarios'));
      document.getElementById('selDestinatarios').ondblclick = function(e){
        if (this.selectedIndex!=-1) {
          seiCadastroContato(this.options[this.selectedIndex].value, 'selDestinatarios', 'frmDocumentoCadastro','<?=$strLinkConsultarContato?>');
        }
      };

      infraDesabilitarCamposDiv(document.getElementById('divObservacoes'));
      infraDesabilitarCamposDiv(document.getElementById('divNivelAcesso'));
      document.getElementById('divArquivo').style.display = 'none';
      return;
    }

    objAutoCompletarAssuntoRI1223 = new infraAjaxAutoCompletar('hdnIdAssunto','txtAssunto','<?=$strLinkAjaxAssuntoRI1223?>');
    //objAutoCompletarAssuntoRI1223.maiusculas = true;
    //objAutoCompletarAssuntoRI1223.mostrarAviso = true;
    //objAutoCompletarAssuntoRI1223.tempoAviso = 1000;
    //objAutoCompletarAssuntoRI1223.tamanhoMinimo = 3;
    objAutoCompletarAssuntoRI1223.limparCampo = true;
    //objAutoCompletarAssuntoRI1223.bolExecucaoAutomatica = false;

    objAutoCompletarAssuntoRI1223.prepararExecucao = function(){
      return 'palavras_pesquisa='+document.getElementById('txtAssunto').value;
    };


    objAutoCompletarAssuntoRI1223.processarResultado = function(id,descricao,complemento){
      if (id!=''){
        objLupaAssuntos.adicionar(id,descricao,document.getElementById('txtAssunto'));
      }
    };


    //Inicializa campos hidden com valores das listas
    objLupaAssuntos = new infraLupaSelect('selAssuntos','hdnAssuntos','<?=$strLinkAssuntosSelecao?>');

    <? if ($_GET['acao']=='documento_alterar' || $_GET['acao']=='documento_alterar_recebido'){?>
      objLupaAssuntos.processarRemocao = function(itens){
        for(var i=0;i < itens.length;i++){
          for(var j=0;j < arrAssuntosNegados.length; j++){
            if (itens[i].value == arrAssuntosNegados[j].id_assunto){
              alert('Assunto \"' + itens[i].text + '\" não pode ser removido porque foi adicionado pela unidade ' + arrAssuntosNegados[j].sigla_unidade + '.');
              return false;
            }
          }
        }
        return true;
      }
    <?}?>

    objLupaAssuntos.processarAlteracao = function (pos, texto, valor){
      seiCadastroAssunto(valor, 'selAssuntos','frmDocumentoCadastro','<?=$strLinkConsultarAssunto?>');
    }


    document.getElementById('selAssuntos').ondblclick = function(e){
      objLupaAssuntos.alterar();
    };

    objAutoCompletarInteressadoRI1225 = new infraAjaxAutoCompletar('hdnIdInteressado','txtInteressado','<?=$strLinkAjaxContatos?>');
    //objAutoCompletarInteressadoRI1225.maiusculas = true;
    //objAutoCompletarInteressadoRI1225.mostrarAviso = true;
    //objAutoCompletarInteressadoRI1225.tempoAviso = 1000;
    //objAutoCompletarInteressadoRI1225.tamanhoMinimo = 3;
    objAutoCompletarInteressadoRI1225.limparCampo = false;
    //objAutoCompletarInteressadoRI1225.bolExecucaoAutomatica = false;

    objAutoCompletarInteressadoRI1225.prepararExecucao = function(){
      return 'palavras_pesquisa='+encodeURIComponent(document.getElementById('txtInteressado').value);
    };

    objAutoCompletarInteressadoRI1225.processarResultado = function(id,descricao,complemento){
      if (id!=''){
        objLupaInteressados.adicionar(id,descricao,document.getElementById('txtInteressado'));
      }
    };

    infraAdicionarEvento(document.getElementById('txtInteressado'),'keyup',tratarEnterInteressado);

    objLupaInteressados = new infraLupaSelect('selInteressados','hdnInteressados','<?=$strLinkInteressados?>');

    objLupaInteressados.processarAlteracao = function (pos, texto, valor){
      seiCadastroContato(valor, 'selInteressados','frmDocumentoCadastro','<?=$strLinkAlterarContato?>');
    }

    objLupaInteressados.processarRemocao = function(itens){
      for(var i=0;i < itens.length;i++){
        for(var j=0;j < arrInteressadosNegados.length; j++){
          if (itens[i].value == arrInteressadosNegados[j].id_contato) {
            alert('Interessado \"' + itens[i].text + '\" não pode ser removido porque foi adicionado pela unidade ' + arrInteressadosNegados[j].sigla_unidade + '.');
            return false;
          }
        }
      }
      return true;
    }

    document.getElementById('selInteressados').ondblclick = function(e){
      objLupaInteressados.alterar();
    };

    objLupaRemetente = new infraLupaText('txtRemetente','hdnIdRemetente','<?=$strLinkRemetente?>');

    objLupaRemetente.finalizarSelecao = function(){
      objAutoCompletarRemetenteRI1226.selecionar(document.getElementById('hdnIdRemetente').value,document.getElementById('txtRemetente').value);
    }

    objLupaRemetente.processarAlteracao = function (id, texto){
      seiCadastroContato(id, 'txtRemetente', 'frmDocumentoCadastro','<?=$strLinkAlterarContato?>');
    }

    objAutoCompletarRemetenteRI1226 = new infraAjaxAutoCompletar('hdnIdRemetente','txtRemetente','<?=$strLinkAjaxContatos?>');
    //objAutoCompletarRemetenteRI1226.maiusculas = true;
    //objAutoCompletarRemetenteRI1226.mostrarAviso = true;
    //objAutoCompletarRemetenteRI1226.tempoAviso = 1000;
    //objAutoCompletarRemetenteRI1226.tamanhoMinimo = 3;
    objAutoCompletarRemetenteRI1226.limparCampo = false;
    //objAutoCompletarRemetenteRI1226.bolExecucaoAutomatica = false;

    objAutoCompletarRemetenteRI1226.prepararExecucao = function(){
      return 'palavras_pesquisa='+encodeURIComponent(document.getElementById('txtRemetente').value);
    };

    objAutoCompletarRemetenteRI1226.processarResultado = function(id,descricao,complemento){
      if (id!=''){
        document.getElementById('hdnIdRemetente').value = id;
        document.getElementById('txtRemetente').value = descricao;
      }
    }
    objAutoCompletarRemetenteRI1226.selecionar('<?=$strIdRemetente?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeRemetente,false);?>');

    infraAdicionarEvento(document.getElementById('txtRemetente'),'keyup',tratarEnterRemetente);

    objAutoCompletarDestinatarioRI1226 = new infraAjaxAutoCompletar('hdnIdDestinatario','txtDestinatario','<?=$strLinkAjaxContatos?>');
    //objAutoCompletarDestinatarioRI1226.maiusculas = true;
    //objAutoCompletarDestinatarioRI1226.mostrarAviso = true;
    //objAutoCompletarDestinatarioRI1226.tempoAviso = 1000;
    //objAutoCompletarDestinatarioRI1226.tamanhoMinimo = 3;
    objAutoCompletarDestinatarioRI1226.limparCampo = false;
    //objAutoCompletarDestinatarioRI1226.permitirSelecaoGrupo = true;
    //objAutoCompletarDestinatarioRI1226.bolExecucaoAutomatica = false;

    objAutoCompletarDestinatarioRI1226.prepararExecucao = function(){
      return 'palavras_pesquisa='+encodeURIComponent(document.getElementById('txtDestinatario').value);
    };


    objAutoCompletarDestinatarioRI1226.processarResultado = function(id,descricao,complemento){
      if (id!=''){
        objLupaDestinatarios.adicionar(id,descricao,document.getElementById('txtDestinatario'));
      }
    };

    infraAdicionarEvento(document.getElementById('txtDestinatario'),'keyup',tratarEnterDestinatario);

    objLupaDestinatarios = new infraLupaSelect('selDestinatarios','hdnDestinatarios','<?=$strLinkDestinatarios?>');

    objLupaDestinatarios.processarAlteracao = function (pos, texto, valor){
      seiCadastroContato(valor, 'selDestinatarios', 'frmDocumentoCadastro','<?=$strLinkAlterarContato?>');
    }

    objLupaDestinatarios.processarRemocao = function(itens){
      for(var i=0;i < itens.length;i++){
        for(var j=0;j < arrDestinatariosNegados.length; j++){
          if (itens[i].value == arrDestinatariosNegados[j].id_contato) {
            alert('Destinatário \"' + itens[i].text + '\" não pode ser removido porque foi adicionado pela unidade ' + arrDestinatariosNegados[j].sigla_unidade + '.');
            return false;
          }
        }
      }
      return true;
    }

    document.getElementById('selDestinatarios').ondblclick = function(e){
      objLupaDestinatarios.alterar();
    };



    objContatoCadastroAutomatico = new infraAjaxComplementar(null,'<?=$strLinkAjaxCadastroAutomatico?>');
    objContatoCadastroAutomatico.tipo = null;
    //objContatoCadastroAutomatico.mostrarAviso = false;
    //objContatoCadastroAutomatico.tempoAviso = 3000;
    //objContatoCadastroAutomatico.limparCampo = false;

    objContatoCadastroAutomatico.prepararExecucao = function(){
      if (this.tipo=='I'){
        return 'nome='+encodeURIComponent(document.getElementById('txtInteressado').value);
      }else if (this.tipo=='R'){
        return 'nome='+encodeURIComponent(document.getElementById('txtRemetente').value);
      }else if (this.tipo=='D'){
        return 'nome='+encodeURIComponent(document.getElementById('txtDestinatario').value);
      }
    };

    objContatoCadastroAutomatico.processarResultado = function(arr){
      if (arr!=null){
        if (this.tipo=='I'){
          objAutoCompletarInteressadoRI1225.processarResultado(arr['IdContato'], document.getElementById('txtInteressado').value, null);
        }else if (this.tipo=='R'){
          objAutoCompletarRemetenteRI1226.selecionar(arr['IdContato'],document.getElementById('txtRemetente').value);
        }else if (this.tipo=='D'){
          objAutoCompletarDestinatarioRI1226.processarResultado(arr['IdContato'], document.getElementById('txtDestinatario').value, null);
        }
      }
    };

    <?=$strJsInicializarNivelAcesso?>

    selecionarFormatoDigitalizado();

    infraEfeitoTabelas();
  }

  function confirmarDados(){
    if (OnSubmitForm()){
      submeter();
    }
  }

  function submeter(){
    desabilitarBotaoSalvar(true);
    document.getElementById('hdnFlagDocumentoCadastro').value = '2';
    document.getElementById('frmDocumentoCadastro').submit();
  }

  function OnSubmitForm() {
    return validarCadastroRI0881();
  }

  function validarCadastroRI0881() {

    if ('<?=$_GET['id_serie']?>'=='-1'){
      if (document.getElementById('hdnIdSerie').value==''){
        alert('Escolha um Tipo de Documento.');
        document.getElementById('selSerie').focus();
        return false;
      }
    }

    if ('<?=$_GET['acao']?>'=='documento_receber' || '<?=$_GET['acao']?>'=='documento_alterar_recebido'){

      if (document.getElementById('hdnIdRemetente').value=='' && infraTrim(document.getElementById('txtRemetente').value!='')) {
        alert('Remetente não cadastrado.');
        document.getElementById('txtRemetente').focus();
        return false;
      }

      if (document.getElementById('txtDataElaboracao').value=='') {
        alert('Informe a Data do Documento.');
        document.getElementById('txtDataElaboracao').focus();
        return false;
      }

      if (!infraValidarData(document.getElementById('txtDataElaboracao'))){
        return false;
      }

      if (document.getElementById("optDigitalizado").checked == false && document.getElementById("optNato").checked == false) {
        alert('Informe o Formato do documento externo.');
        return false;
      }

      if (document.getElementById("optDigitalizado").checked == true && !infraSelectSelecionado(document.getElementById("selTipoConferencia"))) {
        alert('Informe o Tipo de Conferência');
        document.getElementById('selTipoConferencia').focus();
        return false;
      }
    }


    if (document.getElementById('lblNumero').className == 'infraLabelObrigatorio' && infraTrim(document.getElementById('txtNumero').value)==''){
      alert('Informe o Número.');
      document.getElementById('txtNumero').focus();
      return false;
    }

    <?=$strJsValidacoesNivelAcesso?>

    <?if ($_GET['acao'] == 'documento_receber') {?>

    /*
     if (document.getElementById('filArquivo').value==''){
     alert('Anexo não informado.');
     document.getElementById('filArquivo').focus();
     return false;
     }
     */

    var objDocumentoRecebidoDuplicado = new infraAjaxComplementar(null,'<?=$strLinkAjaxDocumentoRecebidoDuplicado?>');
    //objDocumentoRecebidoDuplicado.mostrarAviso = false;
    //objDocumentoRecebidoDuplicado.tempoAviso = 3000;
    //objDocumentoRecebidoDuplicado.limparCampo = false;

    objDocumentoRecebidoDuplicado.prepararExecucao = function(){
      return 'dta_elaboracao='+document.getElementById('txtDataElaboracao').value + '&id_serie=' + document.getElementById('selSerie').value + '&numero=' + document.getElementById('txtNumero').value;
    };

    objDocumentoRecebidoDuplicado.processarResultado = function(arr){
      if (arr!=null){
        if (!confirm('Já existe um documento (' + arr['ProtocoloDocumentoFormatado'] + ') cadastrado com estas características.\n\nDeseja continuar?')){
          return;
        }
      }
      submeter();
    };

    objDocumentoRecebidoDuplicado.executar();

    return false;

    <?}else{?>

    return true;

    <?}?>
  }

  function tratarEnterInteressado(ev){
    var key = infraGetCodigoTecla(ev);

    if (key == 13 && document.getElementById('hdnIdInteressado').value=='' && infraTrim(document.getElementById('txtInteressado').value)!=''){
      if (confirm('Nome inexistente. Deseja incluir?')){
        objContatoCadastroAutomatico.tipo = 'I';
        objContatoCadastroAutomatico.executar();
      }
    }
  }

  function tratarEnterRemetente(ev){
    var key = infraGetCodigoTecla(ev);

    if (key == 13 && document.getElementById('hdnIdRemetente').value=='' && infraTrim(document.getElementById('txtRemetente').value)!=''){
      if (confirm('Nome inexistente. Deseja incluir?')){
        objContatoCadastroAutomatico.tipo = 'R';
        objContatoCadastroAutomatico.executar();
      }
    }
  }

  function tratarEnterDestinatario(ev){
    var key = infraGetCodigoTecla(ev);

    if (key == 13 && document.getElementById('hdnIdDestinatario').value=='' && infraTrim(document.getElementById('txtDestinatario').value)!=''){
      if (confirm('Nome inexistente. Deseja incluir?')){
        objContatoCadastroAutomatico.tipo = 'D';
        objContatoCadastroAutomatico.executar();
      }
    }
  }

  function configurarTextoInicial(){
    if (document.getElementById('optTextoPadrao').checked){
      document.getElementById('txtTextoPadrao').style.visibility = 'visible';
      document.getElementById('imgPesquisarTextoPadrao').style.visibility = 'visible';
      document.getElementById('txtTextoPadrao').focus();
      document.getElementById('txtProtocoloDocumentoTextoBase').style.visibility = 'hidden';
      document.getElementById('lblOuModeloFavorito').style.visibility = 'hidden';
      document.getElementById('btnEscolherDocumentoTextoBase').style.visibility = 'hidden';
      document.getElementById('txtProtocoloDocumentoTextoBase').value = '';
    }else if (document.getElementById('optProtocoloDocumentoTextoBase').checked){
      document.getElementById('txtTextoPadrao').style.visibility = 'hidden';
      document.getElementById('imgPesquisarTextoPadrao').style.visibility = 'hidden';
      document.getElementById('txtTextoPadrao').value = '';
      document.getElementById('txtProtocoloDocumentoTextoBase').style.visibility = 'visible';
      document.getElementById('lblOuModeloFavorito').style.visibility = 'visible';
      document.getElementById('btnEscolherDocumentoTextoBase').style.visibility = 'visible';
      document.getElementById('txtProtocoloDocumentoTextoBase').focus();
    }else{
      document.getElementById('txtTextoPadrao').value = '';
      document.getElementById('txtProtocoloDocumentoTextoBase').value = '';
      document.getElementById('txtTextoPadrao').style.visibility = 'hidden';
      document.getElementById('imgPesquisarTextoPadrao').style.visibility = 'hidden';
      document.getElementById('txtProtocoloDocumentoTextoBase').style.visibility = 'hidden';
      document.getElementById('lblOuModeloFavorito').style.visibility = 'hidden';
      document.getElementById('btnEscolherDocumentoTextoBase').style.visibility = 'hidden';
    }
  }

  function trocarSerie(){
    document.getElementById('hdnIdSerie').value = document.getElementById('selSerie').value;
    document.getElementById('frmDocumentoCadastro').submit();
  }

  function desabilitarBotaoSalvar(estado){
    var arrBotoesSalvar = document.getElementsByName('btnSalvar');
    for(var i=0; i < arrBotoesSalvar.length; i++){
      arrBotoesSalvar[i].disabled = estado;
    }
  }

  function selecionarFormatoDigitalizado() {

    if (document.getElementById('optDigitalizado').checked){
      document.getElementById("lblTipoConferencia").style.display = "block";
      document.getElementById("selTipoConferencia").style.display = "block";
      document.getElementById("divSinArquivamento").style.display = "block";
    } else {
      document.getElementById("lblTipoConferencia").style.display = "none";
      document.getElementById("selTipoConferencia").style.display = "none";
      document.getElementById("divSinArquivamento").style.display = "none";
      document.getElementById("selTipoConferencia").selectedIndex = 0;
    }
  }


<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmDocumentoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>" style="display:inline;">
    <?
    //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    ?>
    <div id="divSerieTitulo" class="tituloProcessoDocumento">
      <label id="lblSerieTitulo"><?=PaginaSEI::tratarHTML($strNomeSerie)?></label>
    </div>

    <div id="divUnidadesReabertura" class="infraAreaDados" style="height:7em;">
      <label id="lblUnidadesReabertura" for="selUnidadesReabertura" class="infraLabelOpcional">Reabrir processo nas unidades:</label>
      <select id="selUnidadesReabertura" name="selUnidadesReabertura" size="3" multiple="multiple" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      </select>
      <div id="divOpcoesUnidadesReabertura">
        <img id="imgLupaUnidadesReabertura" onclick="objLupaUnidadesReabertura.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Unidade" title="Selecionar Unidade" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <img id="imgExcluirUnidadesReabertura" onclick="objLupaUnidadesReabertura.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Unidades Selecionadas" title="Remover Unidades Selecionadas" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      </div>
    </div>

    <div id="divSerieDataElaboracao" class="infraAreaDados" style="height:5em;">
      <label id="lblSerie" for="selSerie" accesskey="" class="infraLabelObrigatorio">Tipo do Documento:</label>
      <select id="selSerie" name="selSerie" onchange="trocarSerie();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
        <?=$strItensSelSerie?>
      </select>

      <label id="lblDataElaboracao" for="txtDataElaboracao" class="infraLabelObrigatorio"><?=$strRotuloData;?></label>
      <input type="text" id="txtDataElaboracao" name="txtDataElaboracao" onkeypress="return infraMascaraData(this, event)" class="infraText" value="<?=PaginaSEI::tratarHTML($objProtocoloDTO->getDtaGeracao())?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
      <img id="imgDataElaboracao" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" alt="Selecionar Data" onclick="infraCalendario('txtDataElaboracao',this);" title="Selecionar Data" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    </div>

    <div id="divTextoInicial" class="infraAreaDados" style="height:10em;">
      <fieldset id="fldTextoInicial" class="infraFieldset">
        <legend class="infraLegend">Texto Inicial</legend>

        <div id="divOptProtocoloDocumentoTextoBase" class="infraDivRadio">
          <input type="radio" <?=$strCheckedProtocoloDocumentoTextoBase?> onclick="configurarTextoInicial();" name="rdoTextoInicial" id="optProtocoloDocumentoTextoBase" value="D" class="infraRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
          <span id="spnProtocoloDocumentoTextoBase"><label id="lblProtocoloDocumentoTextoBase" for="optProtocoloDocumentoTextoBase" class="infraLabelRadio">Documento Modelo</label></span>
        </div>

        <input type="text" id="txtProtocoloDocumentoTextoBase" name="txtProtocoloDocumentoTextoBase" onkeypress="return infraMascaraNumero(this, event)" maxlength="<?=DIGITOS_DOCUMENTO?>" class="infraText" value="<?=PaginaSEI::tratarHTML($_POST['txtProtocoloDocumentoTextoBase'])?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <label id="lblOuModeloFavorito">ou</label>
        <button type="button" id="btnEscolherDocumentoTextoBase" name="btnEscolherDocumentoTextoBase" onclick="objLupaDocumentoTextoBase.selecionar(800,500);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" class="infraButton">Selecionar nos Favoritos</button>

        <div id="divOptTextoPadrao" class="infraDivRadio">
          <input type="radio" <?=$strCheckedTextoPadrao?> onclick="configurarTextoInicial();" name="rdoTextoInicial" id="optTextoPadrao" value="T" class="infraRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
          <span id="spnTextoPadrao"><label id="lblTextoPadrao" for="optTextoPadrao" class="infraLabelRadio">Texto Padrão</label></span>
        </div>

        <input type="text" id="txtTextoPadrao" name="txtTextoPadrao" class="infraText" value="" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <input type="hidden" id="hdnIdTextoPadrao" name="hdnIdTextoPadrao" value="" />
        <img id="imgPesquisarTextoPadrao" onclick="objLupaTextoPadrao.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Texto Padrão" title="Selecionar Texto Padrão" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

        <div id="divOptNenhum" class="infraDivRadio">
          <input type="radio" <?=$strCheckedNenhum?> onclick="configurarTextoInicial();" name="rdoTextoInicial" id="optNenhum" value="N" class="infraRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
          <span id="spnNenhum"><label id="lblNenhum" for="optNenhum" class="infraLabelRadio">Nenhum</label></span>
        </div>

        <input type="hidden" id="hdnIdDocumentoTextoBase" name="hdnIdDocumentoTextoBase" value="<?=$_POST['hdnIdDocumentoTextoBase']?>" />

      </fieldset>
    </div>

    <div id="divDescricao" class="infraAreaDados" style="height:5em;">
      <label id="lblDescricao" for="txtDescricao" accesskey="" class="infraLabelOpcional">Descrição:</label>
      <input type="text" id="txtDescricao" name="txtDescricao" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" class="infraText" value="<?=PaginaSEI::tratarHTML($objProtocoloDTO->getStrDescricao())?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    </div>

    <div id="divNumeroNomeArvore" class="infraAreaDados" style="height:5em;">
      <label id="lblNumero" for="txtNumero" <?=$strClassLabelNumero?>>Número:</label>
      <input type="text" id="txtNumero" onkeypress="return infraLimitarTexto(this,event,50);" maxlength="50" name="txtNumero" class="infraText" value="<?=PaginaSEI::tratarHTML($objDocumentoDTO->getStrNumero())?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
      <label id="lblNomeArvore" for="txtNomeArvore" class="infraLabelOpcional" >Nome na Árvore:</label>
      <input type="text" id="txtNomeArvore" onkeypress="return infraLimitarTexto(this,event,50);" maxlength="50" name="txtNomeArvore" class="infraText" value="<?=PaginaSEI::tratarHTML($objDocumentoDTO->getStrNomeArvore())?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
      <label id="lblDinValor" for="txtDinValor" accesskey="" class="infraLabelOpcional">Valor (R$):</label>
      <input type="text" id="txtDinValor" name="txtDinValor" onkeydown="return infraMascaraDinheiro(this, event, 2, 15)" class="infraText" value="<?=PaginaSEI::tratarHTML($objDocumentoDTO->getDinValor());?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    </div>

    <div id="divFormato" class="infraAreaDados" style="height:8em;">

      <fieldset id="fldFormato" class="infraFieldset">
        <legend class="infraLegend">Formato</legend>

        <div id="divOptNato" class="infraDivRadio">
          <input type="radio" name="rdoFormato" id="optNato" value="N" class="infraRadio" <?=$strFormatoNatoChecked?> <?=$strFormatoNatoDisabled?> onclick="selecionarFormatoDigitalizado();" />
          <span id="spnNato"><label id="lblNato" for="optNato" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Nato-digital</label></span>
        </div>

        <div id="divOptDigitalizado" class="infraDivRadio">
          <input type="radio" name="rdoFormato" id="optDigitalizado" value="D" class="infraRadio" <?=$strFormatoDigitalizadoChecked?> <?=$strFormatoDigitalizadoDisabled?> onclick="selecionarFormatoDigitalizado();" />
          <span id="spnDigitalizado"><label id="lblDigitalizado" for="optDigitalizado" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Digitalizado nesta Unidade</label></span>
        </div>

      </fieldset>
      <a href="javascript:void(0);" id="ancAjudaFormato" <?=PaginaSEI::montarTitleTooltip('Selecione a opção "Nato-digital" se o arquivo a ser registrado foi criado ou recebido por meio eletrônico.'."\n\n\n".'Selecione a opção "Digitalizado nesta Unidade" somente se o arquivo a ser registrado foi produzido a partir da digitalização de um documento em papel.')?>><img src="<?=PaginaSEI::getInstance()->getIconeAjuda()?>" class="infraImg"/></a>

      <label id="lblTipoConferencia" name="lblTipoConferencia" for="selTipoConferencia" accesskey="" class="infraLabelObrigatorio">Tipo de Conferência: </label>
      <select id="selTipoConferencia" name="selTipoConferencia" <?=$strOpcoesDigitalizadoDisabled?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" class="infraSelect">
        <?=$strItensSelTipoConferencia?>
      </select>

      <div id="divSinArquivamento" class="infraDivCheckbox infraAreaDados" style="height:3em;">
        <input type="checkbox" id="chkSinArquivamento" name="chkSinArquivamento" <?=$strOpcoesDigitalizadoDisabled?> class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objDocumentoDTO->getStrSinArquivamento())?>   />
        <label id="lblSinArquivamento" for="chkSinArquivamento" accesskey="" class="infraLabelCheckbox">Para arquivamento</label>
        <a href="javascript:void(0);" id="ancAjudaArquivamento" <?=PaginaSEI::montarTitleTooltip('Após o cadastramento encaminhar o documento para a unidade de arquivo com o número SEI nele registrado.')?>><img src="<?=PaginaSEI::getInstance()->getIconeAjuda()?>" class="infraImg"/></a>
      </div>

    </div>

    <div id="divRemetente" class="infraAreaDados" style="height:5em;">
      <label id="lblRemetente" for="txtRemetente" accesskey="R" class="infraLabelOpcional"><span class="infraTeclaAtalho">R</span>emetente:</label>
      <input type="text" id="txtRemetente" name="txtRemetente" class="infraText" value="<?=PaginaSEI::tratarHTML($strNomeRemetente)?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <input type="hidden" id="hdnIdRemetente" name="hdnIdRemetente" value="<?=$strIdRemetente?>" />
      <div id="divOpcoesRemetente">
        <img id="imgPesquisarRemetente" onclick="objLupaRemetente.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Remetente" title="Selecionar Remetente" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <img id="imgAlterarRemetente" onclick="objLupaRemetente.alterar();" src="<?=PaginaSEI::getInstance()->getIconeAlterar()?>" alt="Consultar/Alterar Dados do Remetente Selecionado" title="Consultar/Alterar Dados do Remetente Selecionado" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
      </div>
    </div>

    <div id="divInteressados" class="infraAreaDados" style="height:10em;">
      <label id="lblInteressados" for="txtInteressado" accesskey="I" class="infraLabelOpcional"><span class="infraTeclaAtalho">I</span>nteressados:</label>
      <input type="text" id="txtInteressado" name="txtInteressado" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <input type="hidden" id="hdnIdInteressado" name="hdnIdInteressado" class="infraText" value="" />
      <select id="selInteressados" name="selInteressados" class="infraSelect" multiple="multiple" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"  >
        <?=$strItensSelInteressado?>
      </select>
      <div id="divOpcoesInteressados">
        <img id="imgSelecionarGrupo" onclick="objLupaInteressados.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" title="Selecionar Contatos para Interessados" alt="Selecionar Contatos para Interessados" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <img id="imgAlterarInteressado" onclick="objLupaInteressados.alterar();" src="<?=PaginaSEI::getInstance()->getIconeAlterar()?>" alt="Consultar/Alterar Dados do Interessado Selecionado" title="Consultar/Alterar Dados do Interessado Selecionado" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
        <img id="imgRemoverInteressados" onclick="objLupaInteressados.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Interessados Selecionados" title="Remover Interessados Selecionados" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
        <br />
        <img id="imgInteressadosAcima" onclick="objLupaInteressados.moverAcima();" src="<?=PaginaSEI::getInstance()->getIconeMoverAcima()?>" alt="Mover Acima Interessado Selecionado" title="Mover Acima Interessado Selecionado" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <img id="imgInteressadosAbaixo" onclick="objLupaInteressados.moverAbaixo();" src="<?=PaginaSEI::getInstance()->getIconeMoverAbaixo()?>" alt="Mover Abaixo Interessado Selecionado" title="Mover Abaixo Interessado Selecionado" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      </div>
    </div>

    <div id="divDestinatarios" class="infraAreaDados" style="height:10em;">
      <label id="lblDestinatarios" for="txtDestinatario" accesskey="e" class="infraLabelOpcional">D<span class="infraTeclaAtalho">e</span>stinatários:</label>
      <input type="text" id="txtDestinatario" name="txtDestinatario" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <input type="hidden" id="hdnIdDestinatario" name="hdnIdDestinatario" class="infraText" value="" />
      <select id="selDestinatarios" name="selDestinatarios" class="infraSelect" multiple="multiple" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"  >
        <?=$strItensSelDestinatario?>
      </select>

      <div id="divOpcoesDestinatarios">
        <img id="imgSelecionarGrupo" onclick="objLupaDestinatarios.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" title="Selecionar Contatos para Destinatários" alt="Selecionar Contatos para Destinatários" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <img id="imgAlterarDestinatario" onclick="objLupaDestinatarios.alterar();" src="<?=PaginaSEI::getInstance()->getIconeAlterar()?>" alt="Consultar/Alterar Dados do Destinatário Selecionado" title="Consultar/Alterar Dados do Destinatário Selecionado" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
        <img id="imgRemoverDestinatarios" onclick="objLupaDestinatarios.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Destinatários Selecionados" title="Remover Destinatários Selecionados" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
        <br />
        <img id="imgDestinatariosAcima" onclick="objLupaDestinatarios.moverAcima();" src="<?=PaginaSEI::getInstance()->getIconeMoverAcima()?>" alt="Mover Acima Destinatário Selecionado" title="Mover Acima Destinatário Selecionado" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <img id="imgDestinatariosAbaixo" onclick="objLupaDestinatarios.moverAbaixo();" src="<?=PaginaSEI::getInstance()->getIconeMoverAbaixo()?>" alt="Mover Abaixo Destinatário Selecionado" title="Mover Abaixo Destinatário Selecionado" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      </div>
    </div>

    <div id="divAssuntos" class="infraAreaDados" style="height:10em;">
      <label id="lblAssuntos" for="txtAssunto" accesskey="u" class="infraLabelOpcional">Classificação por Ass<span class="infraTeclaAtalho">u</span>ntos:</label>
      <input type="text" id="txtAssunto" name="txtAssunto" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <input type="hidden" id="hdnIdAssunto" name="hdnIdAssunto" class="infraText" value="" />
      <select id="selAssuntos" name="selAssuntos" class="infraSelect" multiple="multiple" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
        <?=$strItensSelRelProtocoloAssunto?>
      </select>
      <div id="divOpcoesAssuntos">
        <img id="imgPesquisarAssuntos" onclick="objLupaAssuntos.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Pesquisa de Assuntos" title="Pesquisa de Assuntos" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <img id="imgRemoverAssuntos" onclick="objLupaAssuntos.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Assuntos Selecionados" title="Remover Assuntos Selecionados" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <br />
        <img id="imgAssuntosAcima" onclick="objLupaAssuntos.moverAcima();" src="<?=PaginaSEI::getInstance()->getIconeMoverAcima()?>" alt="Mover Acima Assunto Selecionado" title="Mover Acima Assunto Selecionado" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <img id="imgAssuntosAbaixo" onclick="objLupaAssuntos.moverAbaixo();" src="<?=PaginaSEI::getInstance()->getIconeMoverAbaixo()?>" alt="Mover Abaixo Assunto Selecionado" title="Mover Abaixo Assunto Selecionado" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      </div>
    </div>

    <div id="divObservacoes" class="infraAreaDados" style="height:7em;">
      <label id="lblObservacoes" for="txaObservacoes" accesskey="O" class="infraLabelOpcional"><span class="infraTeclaAtalho">O</span>bservações desta unidade:</label>
      <textarea id="txaObservacoes" name="txaObservacoes" class="infraTextarea" rows="2" onkeypress="return infraLimitarTexto(this,event,1000);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" ><?=PaginaSEI::tratarHTML($objObservacaoDTO->getStrDescricao())?></textarea>
    </div>

    <?if ($strTabObservacoes!=''){?>
      <div id="divObservacoesOutras" class="infraAreaTabela" style="padding-bottom: 2em;">
        <?=$strTabObservacoes?>
      </div>
    <?}?>

    <?=$strHtmlNivelAcesso?>

    <input type="hidden" id="hdnFlagDocumentoCadastro" name="hdnFlagDocumentoCadastro" value="1"/>
    <input type="hidden" id="hdnAssuntos" name="hdnAssuntos" value="<?=$_POST['hdnAssuntos']?>" />
    <input type="hidden" id="hdnInteressados" name="hdnInteressados" value="<?=PaginaSEI::tratarHTML($_POST['hdnInteressados'])?>" />
    <input type="hidden" id="hdnDestinatarios" name="hdnDestinatarios" value="<?=PaginaSEI::tratarHTML($_POST['hdnDestinatarios'])?>" />
    <input type="hidden" id="hdnIdSerie" name="hdnIdSerie" value="<?=$objDocumentoDTO->getNumIdSerie()?>" />
    <input type="hidden" id="hdnIdUnidadeGeradoraProtocolo" name="hdnIdUnidadeGeradoraProtocolo" value="<?=$objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo()?>" />
    <input type="hidden" id="hdnStaDocumento" name="hdnStaDocumento" value="<?=$objDocumentoDTO->getStrStaDocumento()?>" />
    <input type="hidden" id="hdnIdTipoConferencia" name="hdnIdTipoConferencia" value="<?=$objDocumentoDTO->getNumIdTipoConferencia()?>" />
    <input type="hidden" id="hdnSinArquivamento" name="hdnSinArquivamento" value="<?=$objDocumentoDTO->getStrSinArquivamento()?>" />
    <input type="hidden" id="hdnStaNivelAcessoLocal" name="hdnStaNivelAcessoLocal" value="<?=$objProtocoloDTO->getStrStaNivelAcessoLocal()?>" />
    <input type="hidden" id="hdnIdHipoteseLegal" name="hdnIdHipoteseLegal" value="<?=$objProtocoloDTO->getNumIdHipoteseLegal()?>" />
    <input type="hidden" id="hdnStaGrauSigilo" name="hdnStaGrauSigilo" value="<?=$objProtocoloDTO->getStrStaGrauSigilo()?>" />
    <input type="hidden" id="hdnIdDocumento" name="hdnIdDocumento" value="<?=$objDocumentoDTO->getDblIdDocumento()?>" />
    <input type="hidden" id="hdnIdProcedimento" name="hdnIdProcedimento" value="<?=$objDocumentoDTO->getDblIdProcedimento()?>" />
    <input type="hidden" id="hdnAnexos" name="hdnAnexos" value="<?=$_POST['hdnAnexos']?>"/>
    <input type="hidden" id="hdnIdHipoteseLegalSugestao" name="hdnIdHipoteseLegalSugestao" value="" />
    <input type="hidden" id="hdnIdTipoProcedimento" name="hdnIdTipoProcedimento" value="<?=$numIdTipoProcedimento?>" />
    <input type="hidden" id="hdnUnidadesReabertura" name="hdnUnidadesReabertura" value="<?=$_POST['hdnUnidadesReabertura']?>" />
    <input type="hidden" id="hdnSinBloqueado" name="hdnSinBloqueado" value="<?=$objDocumentoDTO->getStrSinBloqueado()?>" />
    <input type="hidden" id="hdnContatoObject" name="hdnContatoObject" value="" />
    <input type="hidden" id="hdnContatoIdentificador" name="hdnContatoIdentificador" value="" />
    <input type="hidden" id="hdnAssuntoIdentificador" name="hdnAssuntoIdentificador" value="" />

  </form>

  <form id="frmAnexos">
    <div id="divArquivo" class="infraAreaDados">
      <label id="lblArquivo" for="filArquivo" accesskey="" class="infraLabelInputFile">Anexar Arquivo...</label>
      <input type="file" id="filArquivo" class="infraInputFile" name="filArquivo" size="50" onchange="objUpload.executar();" tabindex="1000"/><br />
    </div>

    <div id="divAnexos" class="infraAreaDadosDinamica" style="width:85%;margin-left:0px;" >
      <table id="tblAnexos" name="tblAnexos" class="infraTable" style="width:100%">
        <caption class="infraCaption"><?=PaginaSEI::getInstance()->gerarCaptionTabela("Anexos",0)?></caption>

        <tr>
          <th width="1%" style="display:none;">ID</th>
          <th class="infraTh">Nome</th>
          <th width="22%" class="infraTh" align="center">Data</th>
          <th width="1%" style="display:none;">Bytes</th>
          <th width="13%" class="infraTh" align="center">Tamanho</th>
          <th width="10%" class="infraTh" align="center">Usuário</th>
          <th width="10%" class="infraTh" align="center">Unidade</th>
          <th width="10%" class="infraTh">Ações</th>
        </tr>
      </table>
      <!-- campo hidden correspondente (hdnAnexos) deve ficar no outro form -->
    </div>
  </form>
<?
PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>