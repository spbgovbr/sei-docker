<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 05/09/2008 - criado por mga
 *
 * 26/10/2012 - modificado por mkr
 *
 * Versão do Gerador de Código: 1.23.0
 *
 * Versão no CVS: $Id$
 */

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  InfraDebug::getInstance()->setBolLigado(true);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->limpar();

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $arrNumIdOrgao = array();

  $bolAcessoNegado = false;

  $strRestritoNegado = null;

  $strPesquisaRapida = null;

  $arrComandos = array();

  $strLinkVisualizarPublicado = '';

  $bolPesquisarTodos = false;

  if (isset($_POST['hdnInicio'])){

    PaginaSEI::getInstance()->salvarCampo('rdoPesquisarEm', $_POST['rdoPesquisarEm']);
    PaginaSEI::getInstance()->salvarCampo('chkSinDocumentosGerados', $_POST['chkSinDocumentosGerados']);
    PaginaSEI::getInstance()->salvarCampo('chkSinDocumentosRecebidos', $_POST['chkSinDocumentosRecebidos']);
    PaginaSEI::getInstance()->salvarCampo('chkSinConsiderarDocumentos', $_POST['chkSinConsiderarDocumentos']);
    PaginaSEI::getInstance()->salvarCampo('chkSinTramitacao', $_POST['chkSinTramitacao']);

    if(isset($_POST['selOrgaoPesquisa'])){
      $arrNumIdOrgao = $_POST['selOrgaoPesquisa'];
      if (!is_array($arrNumIdOrgao)){
        $arrNumIdOrgao = array($arrNumIdOrgao);
      }
    }

    PaginaSEI::getInstance()->salvarCampo('selOrgaoPesquisa', implode(',',$arrNumIdOrgao));
    PaginaSEI::getInstance()->salvarCampo('chkSinRestringirOrgao', $_POST['chkSinRestringirOrgao']);

    PaginaSEI::getInstance()->salvarCampo('chkSinInteressado', $_POST['chkSinInteressado']);
    PaginaSEI::getInstance()->salvarCampo('chkSinRemetente', $_POST['chkSinRemetente']);
    PaginaSEI::getInstance()->salvarCampo('chkSinDestinatario', $_POST['chkSinDestinatario']);

    PaginaSEI::getInstance()->salvarCampo('selData', $_POST['selData']);

    PaginaSEI::getInstance()->salvarCamposPost(array('q',
      'txtContato',
      'hdnIdContato',
      'txtAssinante',
      'hdnIdAssinante',
      'txtDescricaoPesquisa',
      'txtObservacaoPesquisa',
      'txtAssunto',
      'hdnIdAssunto',
      'txtUnidade',
      'hdnIdUnidade',
      'txtProtocoloPesquisa',
      'selTipoProcedimentoPesquisa',
      'selSeriePesquisa',
      'txtNumeroDocumentoPesquisa',
      'txtNomeArvoreDocumentoPesquisa',
      'selData',
      'txtDataInicio',
      'txtDataFim',
      'txtUsuarioGerador1',
      'hdnIdUsuarioGerador1',
      'txtUsuarioGerador2',
      'hdnIdUsuarioGerador2',
      'txtUsuarioGerador3',
      'hdnIdUsuarioGerador3'
    ));

    //se informou apenas o numero SEI e nao esta paginando executa pesquisa rapida
    if ($_POST['hdnInicio'] == '0' &&
        trim($_POST['txtProtocoloPesquisa'])!='' &&
        trim($_POST['q'])=='' &&
        InfraArray::contar($arrNumIdOrgao) == 0 &&
        trim($_POST['hdnIdUnidade'])=='' &&
        trim($_POST['hdnIdAssunto'])=='' &&
        trim($_POST['hdnIdAssinante'])=='' &&
        trim($_POST['hdnIdContato'])=='' &&
        trim($_POST['txtDescricaoPesquisa'])=='' &&
        trim($_POST['txtObservacaoPesquisa'])=='' &&
        trim($_POST['selTipoProcedimentoPesquisa'])=='' &&
        trim($_POST['selSeriePesquisa'])=='' &&
        trim($_POST['txtNumeroDocumentoPesquisa'])=='' &&
        trim($_POST['txtNomeArvoreDocumentoPesquisa'])=='' &&
        trim($_POST['hdnIdUsuarioGerador1'])=='' &&
        trim($_POST['hdnIdUsuarioGerador2'])=='' &&
        trim($_POST['hdnIdUsuarioGerador3'])=='' &&
        trim($_POST['txtDataInicio'])=='' &&
        trim($_POST['txtDataFim'])==''){
      $strPesquisaRapida = $_POST['txtProtocoloPesquisa'];
    }

  }else{

    PaginaSEI::getInstance()->salvarCampo('q', '');
    PaginaSEI::getInstance()->salvarCampo('selOrgaoPesquisa', '');
    PaginaSEI::getInstance()->salvarCampo('chkSinRestringirOrgao', 'N');
    PaginaSEI::getInstance()->salvarCampo('rdoPesquisarEm', 'D');
    PaginaSEI::getInstance()->salvarCampo('chkSinDocumentosGerados', 'S');
    PaginaSEI::getInstance()->salvarCampo('chkSinDocumentosRecebidos', 'S');
    PaginaSEI::getInstance()->salvarCampo('chkSinConsiderarDocumentos', 'N');
    PaginaSEI::getInstance()->salvarCampo('chkSinTramitacao', 'N');
    PaginaSEI::getInstance()->salvarCampo('txtContato', '');
    PaginaSEI::getInstance()->salvarCampo('hdnIdContato', '');
    PaginaSEI::getInstance()->salvarCampo('chkSinInteressado', 'S');
    PaginaSEI::getInstance()->salvarCampo('chkSinRemetente', 'S');
    PaginaSEI::getInstance()->salvarCampo('chkSinDestinatario', 'S');
    PaginaSEI::getInstance()->salvarCampo('txtAssinante', '');
    PaginaSEI::getInstance()->salvarCampo('hdnIdAssinante', '');
    PaginaSEI::getInstance()->salvarCampo('txtDescricaoPesquisa', '');
    PaginaSEI::getInstance()->salvarCampo('txtObservacaoPesquisa', '');
    PaginaSEI::getInstance()->salvarCampo('txtAssunto', '');
    PaginaSEI::getInstance()->salvarCampo('hdnIdAssunto', '');
    PaginaSEI::getInstance()->salvarCampo('txtUnidade', '');
    PaginaSEI::getInstance()->salvarCampo('hdnIdUnidade', '');
    PaginaSEI::getInstance()->salvarCampo('txtProtocoloPesquisa', '');
    PaginaSEI::getInstance()->salvarCampo('selTipoProcedimentoPesquisa', '');
    PaginaSEI::getInstance()->salvarCampo('selSeriePesquisa', '');
    PaginaSEI::getInstance()->salvarCampo('txtNumeroDocumentoPesquisa', '');
    PaginaSEI::getInstance()->salvarCampo('txtNomeArvoreDocumentoPesquisa', '');
    PaginaSEI::getInstance()->salvarCampo('selData', 'I');
    PaginaSEI::getInstance()->salvarCampo('txtDataInicio', '');
    PaginaSEI::getInstance()->salvarCampo('txtDataFim', '');

    PaginaSEI::getInstance()->salvarCampo('txtUsuarioGerador1', '');
    PaginaSEI::getInstance()->salvarCampo('hdnIdUsuarioGerador1', '');
    PaginaSEI::getInstance()->salvarCampo('txtUsuarioGerador2', '');
    PaginaSEI::getInstance()->salvarCampo('hdnIdUsuarioGerador2', '');
    PaginaSEI::getInstance()->salvarCampo('txtUsuarioGerador3', '');
    PaginaSEI::getInstance()->salvarCampo('hdnIdUsuarioGerador3', '');

    if (isset($_POST['txtPesquisaRapida'])){
      $strPesquisaRapida = $_POST['txtPesquisaRapida'];
    }

  }

  if ($strPesquisaRapida != null){

    if (!isset($_POST['hdnInicio'])) {
      PaginaSEI::getInstance()->salvarCampo('q', $strPesquisaRapida);
    }

    //verifica se contém número removendo caracteres especiais e letras
    if (is_numeric(InfraUtil::retirarFormatacao($strPesquisaRapida))){

      $objProtocoloRN = new ProtocoloRN();
      //busca pelo numero do processo
      $objProtocoloDTOPesquisa = new ProtocoloDTO();

      //pesquisa incluindo letras devido a formatos de protocolo contendo números e letras
      $objProtocoloDTOPesquisa->setStrProtocoloFormatadoPesquisa(InfraUtil::retirarFormatacao($strPesquisaRapida,false));
      $arrObjProtocoloDTOPesquisado = $objProtocoloRN->pesquisarProtocoloFormatado($objProtocoloDTOPesquisa);

      if (count($arrObjProtocoloDTOPesquisado)==1) {

        $objProtocoloDTO = $arrObjProtocoloDTOPesquisado[0];

        if ($objProtocoloDTO->getStrStaNivelAcessoGlobal() == ProtocoloRN::$NA_SIGILOSO || $objProtocoloDTO->getStrStaNivelAcessoGlobal() == ProtocoloRN::$NA_RESTRITO) {

          //verifica permissão de acesso ao documento
          $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();

          if ($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_PROCEDIMENTO) {
            $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_PROCEDIMENTOS);
          } else if ($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_DOCUMENTO_GERADO) {
            $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_DOCUMENTOS_GERADOS);
          } else if ($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO) {
            $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_DOCUMENTOS_RECEBIDOS);
          } else {
            $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_TODOS);
          }

          $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_TODOS);
          $objPesquisaProtocoloDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());

          $objProtocoloRN = new ProtocoloRN();
          $arrObjProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);

          if (count($arrObjProtocoloDTO) == 0 || ($arrObjProtocoloDTO[0]->getNumCodigoAcesso() < 0 && $arrObjProtocoloDTO[0]->getNumCodigoAcesso()!=ProtocoloRN::$CA_DOCUMENTO_CANCELADO)) {

            if ($objProtocoloDTO->getStrStaNivelAcessoGlobal() == ProtocoloRN::$NA_SIGILOSO) {

              $bolAcessoNegado = true;

            } else {

              $objUnidadeDTO = new UnidadeDTO();
              $objUnidadeDTO->retStrSinProtocolo();
              $objUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

              $objUnidadeRN = new UnidadeRN();
              $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);


              $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
              $numTipoPesquisaRestrito = $objInfraParametro->getValor('SEI_EXIBIR_ARVORE_RESTRITO_SEM_ACESSO', false);

              if ($objUnidadeDTO->getStrSinProtocolo() == 'N' && $numTipoPesquisaRestrito != '1') {
                $strRestritoNegado = 'Unidade atual não possui acesso ao '.($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_PROCEDIMENTO ? 'processo' : 'documento').' restrito '.$objProtocoloDTO->getStrProtocoloFormatado().'.';
                $bolAcessoNegado = true;
              }

            }
          } else {

            $objProtocoloDTO = $arrObjProtocoloDTO[0];

            //acesso exclusivo devido a publicacao abre em janela separada
            if ($objProtocoloDTO->getNumCodigoAcesso() == ProtocoloRN::$CA_DOCUMENTO_PUBLICADO) {
              $strLinkVisualizarPublicado = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&acao_origem='.$_GET['acao'].'&id_documento='.$objProtocoloDTO->getDblIdProtocolo());
              PaginaSEI::getInstance()->salvarCampo('q', '');
              $bolAcessoNegado = true;
            }
          }
        }

        /////////////////////////////////////////
        //die(nl2br(InfraDebug::getStrDebug()));
        /////////////////////////////////////////

        if (!$bolAcessoNegado) {
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&id_protocolo='.$objProtocoloDTO->getDblIdProtocolo()));
          die;
        }

      }else if (count($arrObjProtocoloDTOPesquisado) > 1){
        PaginaSEI::getInstance()->salvarCampo('q', '');
        PaginaSEI::getInstance()->salvarCampo('txtProtocoloPesquisa', $strPesquisaRapida);
        $bolPesquisarTodos = true;
      }
    }

  }else{
    if ($_GET['sugestao']=='1'){
      PaginaSEI::getInstance()->salvarCampo('q', $_GET['q']);
    }
  }

  $objOrgaoDTO = new OrgaoDTO();
  $objOrgaoDTO->retNumIdOrgao();
  $objOrgaoDTO->retStrSigla();
  $objOrgaoDTO->retStrDescricao();
  $objOrgaoDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

  $objOrgaoRN = new OrgaoRN();
  $arrObjOrgaoDTO = $objOrgaoRN->listarRN1353($objOrgaoDTO);

  $numOrgaos = count($arrObjOrgaoDTO);

  switch($_GET['acao']){

    case 'protocolo_pesquisar':
    case 'protocolo_pesquisa_rapida':

      if ($_GET['acao_origem']!='protocolo_pesquisar'){
        $strTitulo = 'Pesquisa';
      }else{
        $strTitulo = 'Resultado da Pesquisa';
      }

      $strPesquisarEm = PaginaSEI::getInstance()->recuperarCampo('rdoPesquisarEm');
      $strSinDocumentosGerados = PaginaSEI::getInstance()->recuperarCampo('chkSinDocumentosGerados');
      $strSinDocumentosRecebidos = PaginaSEI::getInstance()->recuperarCampo('chkSinDocumentosRecebidos');
      $strSinConsiderarDocumentos = PaginaSEI::getInstance()->recuperarCampo('chkSinConsiderarDocumentos');
      $strSinTramitacao = PaginaSEI::getInstance()->recuperarCampo('chkSinTramitacao');

      $strPalavrasPesquisa = PaginaSEI::getInstance()->recuperarCampo('q');

      if (PaginaSEI::getInstance()->recuperarCampo('selOrgaoPesquisa')!='') {
        $arrNumIdOrgaosSelecionados = explode(',', PaginaSEI::getInstance()->recuperarCampo('selOrgaoPesquisa'));
      }else{
        $arrNumIdOrgaosSelecionados = array();
      }

      $strSinRestringirOrgao = PaginaSEI::getInstance()->recuperarCampo('chkSinRestringirOrgao');

      $strIdContato = PaginaSEI::getInstance()->recuperarCampo('hdnIdContato');
      $strNomeContato = PaginaSEI::getInstance()->recuperarCampo('txtContato');
      $strSinInteressado = PaginaSEI::getInstance()->recuperarCampo('chkSinInteressado');
      $strSinRemetente = PaginaSEI::getInstance()->recuperarCampo('chkSinRemetente');
      $strSinDestinatario = PaginaSEI::getInstance()->recuperarCampo('chkSinDestinatario');
      $strIdAssinante = PaginaSEI::getInstance()->recuperarCampo('hdnIdAssinante');
      $strNomeAssinante = PaginaSEI::getInstance()->recuperarCampo('txtAssinante');
      $strDescricaoPesquisa = PaginaSEI::getInstance()->recuperarCampo('txtDescricaoPesquisa');
      $strObservacaoPesquisa = PaginaSEI::getInstance()->recuperarCampo('txtObservacaoPesquisa');
      $strIdAssunto = PaginaSEI::getInstance()->recuperarCampo('hdnIdAssunto');
      $strDescricaoAssunto = PaginaSEI::getInstance()->recuperarCampo('txtAssunto');
      $numIdUnidade = PaginaSEI::getInstance()->recuperarCampo('hdnIdUnidade');
      $strDescricaoUnidade = PaginaSEI::getInstance()->recuperarCampo('txtUnidade');
      $strProtocoloPesquisa = PaginaSEI::getInstance()->recuperarCampo('txtProtocoloPesquisa');
      $numIdTipoProcedimento = PaginaSEI::getInstance()->recuperarCampo('selTipoProcedimentoPesquisa','null');
      $numIdSerie = PaginaSEI::getInstance()->recuperarCampo('selSeriePesquisa','null');
      $strNumeroDocumentoPesquisa = PaginaSEI::getInstance()->recuperarCampo('txtNumeroDocumentoPesquisa');
      $strNomeArvoreDocumentoPesquisa = PaginaSEI::getInstance()->recuperarCampo('txtNomeArvoreDocumentoPesquisa');
      $strStaData = PaginaSEI::getInstance()->recuperarCampo('selData','I');
      $strDataInicio = PaginaSEI::getInstance()->recuperarCampo('txtDataInicio');
      $strDataFim = PaginaSEI::getInstance()->recuperarCampo('txtDataFim');
      $strUsuarioGerador1 = PaginaSEI::getInstance()->recuperarCampo('txtUsuarioGerador1');
      $numIdUsuarioGerador1 = PaginaSEI::getInstance()->recuperarCampo('hdnIdUsuarioGerador1');
      $strUsuarioGerador2 = PaginaSEI::getInstance()->recuperarCampo('txtUsuarioGerador2');
      $numIdUsuarioGerador2 = PaginaSEI::getInstance()->recuperarCampo('hdnIdUsuarioGerador2');
      $strUsuarioGerador3 = PaginaSEI::getInstance()->recuperarCampo('txtUsuarioGerador3');
      $numIdUsuarioGerador3 = PaginaSEI::getInstance()->recuperarCampo('hdnIdUsuarioGerador3');

      //print_r($_POST);die;

      $strItensSelTipoProcedimento 	= TipoProcedimentoINT::montarSelectNome('null','&nbsp;',$numIdTipoProcedimento);
      $strItensSelSerie = SerieINT::montarSelectNomeRI0802('null','&nbsp;',$numIdSerie);

      $strLinkAjaxContatos = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=contato_auto_completar_pesquisa');
      $strLinkAjaxAssinantes = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=contato_auto_completar_usuario_pesquisa');
      $strLinkAjaxUsuarios = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_auto_completar_sigla');
      $strLinkAjaxAssuntoRI1223 = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=assunto_auto_completar_RI1223');
      $strLinkAjaxUnidade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=unidade_auto_completar_todas');

      $strLinkAjuda = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=pesquisa_solr_ajuda&acao_origem='.$_GET['acao']);

      $q = PaginaSEI::getInstance()->recuperarCampo('q');

      $strResultado = '';


      if (!$bolAcessoNegado &&
          ($strPesquisarEm=='P' || $strSinDocumentosGerados=='S' || $strSinDocumentosRecebidos=='S') &&
        (!InfraString::isBolVazia($q) ||
          InfraArray::contar($arrNumIdOrgaosSelecionados) ||
          !InfraString::isBolVazia($strIdContato) ||
          !InfraString::isBolVazia($strIdAssinante) ||
          !InfraString::isBolVazia($strDescricaoPesquisa) ||
          !InfraString::isBolVazia($strObservacaoPesquisa) ||
          !InfraString::isBolVazia($strIdAssunto) ||
          !InfraString::isBolVazia($numIdUnidade) ||
          !InfraString::isBolVazia($strProtocoloPesquisa) ||
          !InfraString::isBolVazia($numIdTipoProcedimento) ||
          !InfraString::isBolVazia($numIdSerie) ||
          !InfraString::isBolVazia($strNumeroDocumentoPesquisa) ||
          !InfraString::isBolVazia($strNomeArvoreDocumentoPesquisa) ||
          !InfraString::isBolVazia($strDataInicio) ||
          !InfraString::isBolVazia($strDataFim) ||
          !InfraString::isBolVazia($numIdUsuarioGerador1) ||
          !InfraString::isBolVazia($numIdUsuarioGerador2) ||
          !InfraString::isBolVazia($numIdUsuarioGerador3))){

        try {

          $objPesquisaProtocoloSolrDTO = new PesquisaProtocoloSolrDTO();
          $objPesquisaProtocoloSolrDTO->setStrPalavrasChave($strPalavrasPesquisa);
          $objPesquisaProtocoloSolrDTO->setStrSinConsiderarDocumentos($strSinConsiderarDocumentos);

          if ($bolPesquisarTodos){
            $objPesquisaProtocoloSolrDTO->setStrSinProcessos('S');
            $objPesquisaProtocoloSolrDTO->setStrSinDocumentosGerados('S');
            $objPesquisaProtocoloSolrDTO->setStrSinDocumentosRecebidos('S');
          }else{
            $objPesquisaProtocoloSolrDTO->setStrSinProcessos($strPesquisarEm=='P'?'S':'N');

            if ($objPesquisaProtocoloSolrDTO->getStrSinProcessos()=='S'){
              $objPesquisaProtocoloSolrDTO->setStrSinDocumentosGerados('S');
              $objPesquisaProtocoloSolrDTO->setStrSinDocumentosRecebidos('S');
            }else {
              $objPesquisaProtocoloSolrDTO->setStrSinDocumentosGerados($strSinDocumentosGerados);
              $objPesquisaProtocoloSolrDTO->setStrSinDocumentosRecebidos($strSinDocumentosRecebidos);
            }
          }

          $objPesquisaProtocoloSolrDTO->setStrSinTramitacao($strSinTramitacao);

          if (count($arrNumIdOrgaosSelecionados) != $numOrgaos){
            $objPesquisaProtocoloSolrDTO->setArrNumIdOrgao($arrNumIdOrgaosSelecionados);
          }else{
            $objPesquisaProtocoloSolrDTO->setArrNumIdOrgao(array());
          }

          $objPesquisaProtocoloSolrDTO->setNumIdContato($strIdContato);
          $objPesquisaProtocoloSolrDTO->setStrSinInteressado($strSinInteressado);
          $objPesquisaProtocoloSolrDTO->setStrSinRemetente($strSinRemetente);
          $objPesquisaProtocoloSolrDTO->setStrSinDestinatario($strSinDestinatario);
          $objPesquisaProtocoloSolrDTO->setNumIdAssinante($strIdAssinante);
          $objPesquisaProtocoloSolrDTO->setStrDescricao($strDescricaoPesquisa);
          $objPesquisaProtocoloSolrDTO->setStrObservacao($strObservacaoPesquisa);
          $objPesquisaProtocoloSolrDTO->setNumIdAssunto($strIdAssunto);
          $objPesquisaProtocoloSolrDTO->setNumIdUnidadeGeradora($numIdUnidade);
          $objPesquisaProtocoloSolrDTO->setStrProtocoloPesquisa($strProtocoloPesquisa);
          $objPesquisaProtocoloSolrDTO->setNumIdTipoProcedimento($numIdTipoProcedimento);
          $objPesquisaProtocoloSolrDTO->setNumIdSerie($numIdSerie);
          $objPesquisaProtocoloSolrDTO->setStrNumero($strNumeroDocumentoPesquisa);
          $objPesquisaProtocoloSolrDTO->setStrNomeArvore($strNomeArvoreDocumentoPesquisa);
          $objPesquisaProtocoloSolrDTO->setDtaInicio($strDataInicio);
          $objPesquisaProtocoloSolrDTO->setDtaFim($strDataFim);
          $objPesquisaProtocoloSolrDTO->setNumIdUsuarioGerador1($numIdUsuarioGerador1);
          $objPesquisaProtocoloSolrDTO->setNumIdUsuarioGerador2($numIdUsuarioGerador2);
          $objPesquisaProtocoloSolrDTO->setNumIdUsuarioGerador3($numIdUsuarioGerador3);
          $objPesquisaProtocoloSolrDTO->setNumInicioPaginacao($_POST['hdnInicio']);
          $objPesquisaProtocoloSolrDTO->setDblIdProcedimento(null);
          $objPesquisaProtocoloSolrDTO->setBolArvore(false);
          $objPesquisaProtocoloSolrDTO->setStrStaTipoData($strStaData);

          SolrProtocolo::executar($objPesquisaProtocoloSolrDTO);

          $strResultado = $objPesquisaProtocoloSolrDTO->getStrResultadoPesquisa();
          $strLinkVisualizarPublicado = $objPesquisaProtocoloSolrDTO->getStrLinkPublicacao();

        } catch (Exception $e) {
          SeiSolrUtil::tratarErroPesquisa(PaginaSEI::getInstance(), $e);
        }
      }

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }



  $strOptionsOrgaos='';
  foreach($arrObjOrgaoDTO as $objOrgaoDTO){
    $strOptionsOrgaos.='<option value="'.$objOrgaoDTO->getNumIdOrgao().'"';
    if (isset($_POST['selOrgaoPesquisa'])){
      if (in_array($objOrgaoDTO->getNumIdOrgao(), $arrNumIdOrgao)) {
        $strOptionsOrgaos .= ' selected="selected"';
      }
    }else{
      $strOptionsOrgaos .= ' selected="selected"';
    }
    $strOptionsOrgaos.='>'.PaginaPublicacoes::tratarHTML($objOrgaoDTO->getStrSigla()).'</option>'."\n";
  }

  $arrComandos[] = '<button type="submit" id="sbmPesquisar" name="sbmPesquisar" value="Pesquisar" class="infraButton">Pesquisar</button>';
  $arrComandos[] = '<button type="button" onclick="limparForm()" id="sbmReset" name="sbmReset" value="Limpar" class="infraButton">Limpar</button>';

  $bolAcessoListarPesquisa  = SessaoSEI::getInstance()->verificarPermissao("pesquisa_listar");
  if($bolAcessoListarPesquisa){
    $strLinkPesquisaSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=pesquisa_selecionar&tipo_selecao=1');
    $arrComandos[] = '<button  type="button" onclick="infraAbrirJanelaModal(\''.$strLinkPesquisaSelecao.'\',700,550)"   value="Minhas Pesquisas" class="infraButton">Minhas Pesquisas</button>';
  }

  $bolAcessoCadastrarPesquisa  = SessaoSEI::getInstance()->verificarPermissao("pesquisa_cadastrar");
  if($bolAcessoCadastrarPesquisa &&    isset($_POST['hdnInicio'])){

    $strLinkPesquisaCadastro = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=pesquisa_cadastrar');
    $arrComandos[] = '<button   type="button" onclick="infraAbrirJanelaModal(\''.$strLinkPesquisaCadastro.'\',500,250)" id="sbmPesquisarSalvar" name="sbmPesquisarSalvar" value="Salvar Pesquisar" class="infraButton">Salvar Pesquisa</button>';
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
if(0){?><style><?}
?>

  .infraCheckboxDiv{
    margin-left:0px;
  }

  #frmPesquisaProtocolo .infraImg{
    width: 24px;
    height: 24px;
    margin-top: 2px;
  }

  .w-30{
    width: 30% !important;
  }

  #frmPesquisaProtocolo{max-width: 1200px;}

  #lblPesquisarEm {position:absolute;left:0%;top:30%;width:20%;}
  #fldPesquisarEm {position:relative;width: 350px;margin-left: 17%;border: .1em solid #666;-moz-border-radius: 4px;border-radius: 4px;-webkit-border-radius: 4px;}
  #divOptProcessos {position:absolute;left:8%;top:35%;}
  #divOptDocumentos {position:absolute;left:8%;top:65%;}
  #chkSinDocumentosGerados {margin-left:2em;}
  #chkSinDocumentosRecebidos {margin-left:1.5em;}
  #divSinTramitacao {}


  #txtUnidade {position:relative;}
  #txtAssunto {position:relative;}
  #txtAssinante {position:relative;}
  #txtContato {position:relative;}
  #txtUsuarioGerador1 {position:relative;}
  #txtUsuarioGerador2 {position:relative;}
  #txtUsuarioGerador3 {position:relative;}

  td.pesquisaTituloEsquerda img.arvore {
    margin: 0px 5px -3px 0px;
    vertical-align: sub;
  }

  #divInfraAreaTabela tr.infraTrClara td {padding:.3em;}
  #divInfraAreaTabela table.infraTable {border-spacing:0;}

  <?
  if(0){?></style><?}
if (PaginaSEI::getInstance()->isBolAjustarTopFieldset()){
?>
  #divOptProcessos {top:15%;}
  #divOptDocumentos {top:55%;}
<?
}
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
if(0){?><script><?}
  ?>

  var objAutoCompletarInteressadoRI1225 = null;
  var objAutoCompletarUsuario = null;
  var objAutoCompletarAssuntoRI1223 = null;
  var objAutoCompletarUnidade = null;
  var objAutoCompletarUsuarioGerador1 = null;
  var objAutoCompletarUsuarioGerador2 = null;
  var objAutoCompletarUsuarioGerador3 = null;

  function inicializar(){

    <?if ($strRestritoNegado!=null){ ?>
    return;
    <?}?>

    infraOcultarMenuSistemaEsquema();

    $("#frmPesquisaProtocolo input, #frmPesquisaProtocolo label, #frmPesquisaProtocolo select, #frmPesquisaProtocolo img, #frmPesquisaProtocolo button").not("#divInfraBarraComandosSuperior button").click(function (){$("#sbmPesquisarSalvar").hide()});
    $("#frmPesquisaProtocolo input, #frmPesquisaProtocolo label, #frmPesquisaProtocolo select, #frmPesquisaProtocolo img, #frmPesquisaProtocolo button").not("#divInfraBarraComandosSuperior button").keyup(function (){$("#sbmPesquisarSalvar").hide()});


    objAutoCompletarInteressadoRI1225 = new infraAjaxAutoCompletar('hdnIdContato','txtContato','<?=$strLinkAjaxContatos?>');
    objAutoCompletarInteressadoRI1225.limparCampo = true;
    objAutoCompletarInteressadoRI1225.prepararExecucao = function(){
      return 'palavras_pesquisa='+document.getElementById('txtContato').value;
    };
    objAutoCompletarInteressadoRI1225.selecionar('<?=$strIdContato;?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeContato,false)?>');


    objAutoCompletarUsuario = new infraAjaxAutoCompletar('hdnIdAssinante','txtAssinante','<?=$strLinkAjaxAssinantes?>');
    objAutoCompletarUsuario.limparCampo = true;
    objAutoCompletarUsuario.prepararExecucao = function(){
      return 'palavras_pesquisa='+document.getElementById('txtAssinante').value + '&sin_usuario_interno=S&sin_usuario_externo=S';
    };
    objAutoCompletarUsuario.selecionar('<?=$strIdAssinante?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeAssinante,false)?>');


    objAutoCompletarAssuntoRI1223 = new infraAjaxAutoCompletar('hdnIdAssunto','txtAssunto','<?=$strLinkAjaxAssuntoRI1223?>');
    objAutoCompletarAssuntoRI1223.limparCampo = true;
    objAutoCompletarAssuntoRI1223.prepararExecucao = function(){
      return 'palavras_pesquisa='+document.getElementById('txtAssunto').value;
    };
    objAutoCompletarAssuntoRI1223.selecionar('<?=$strIdAssunto;?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript($strDescricaoAssunto,false)?>');

    objAutoCompletarUnidade = new infraAjaxAutoCompletar('hdnIdUnidade','txtUnidade','<?=$strLinkAjaxUnidade?>');
    objAutoCompletarUnidade.limparCampo = true;
    objAutoCompletarUnidade.prepararExecucao = function(){
      return 'palavras_pesquisa='+document.getElementById('txtUnidade').value+'&id_orgao=' + obterOrgaosSelecionados();
    };
    objAutoCompletarUnidade.selecionar('<?=$numIdUnidade;?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript($strDescricaoUnidade,false)?>');

    objAutoCompletarUsuarioGerador1 = new infraAjaxAutoCompletar('hdnIdUsuarioGerador1','txtUsuarioGerador1','<?=$strLinkAjaxUsuarios?>');
    objAutoCompletarUsuarioGerador1.limparCampo = true;
    objAutoCompletarUsuarioGerador1.prepararExecucao = function(){
      return 'palavras_pesquisa='+document.getElementById('txtUsuarioGerador1').value + '&inativos=1';
    };
    objAutoCompletarUsuarioGerador1.selecionar('<?=$numIdUsuarioGerador1?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript($strUsuarioGerador1,false)?>');
    objAutoCompletarUsuarioGerador1.processarResultado = function(id, descricao, complemento){
      if (id!=''){
        document.getElementById('hdnIdUsuarioGerador1').value = id;
        document.getElementById('txtUsuarioGerador1').value = complemento;
      }
    };

    objAutoCompletarUsuarioGerador2 = new infraAjaxAutoCompletar('hdnIdUsuarioGerador2','txtUsuarioGerador2','<?=$strLinkAjaxUsuarios?>');
    objAutoCompletarUsuarioGerador2.limparCampo = true;
    objAutoCompletarUsuarioGerador2.prepararExecucao = function(){
      return 'palavras_pesquisa='+document.getElementById('txtUsuarioGerador2').value + '&inativos=1';
    };
    objAutoCompletarUsuarioGerador2.selecionar('<?=$numIdUsuarioGerador2?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript($strUsuarioGerador2,false)?>');
    objAutoCompletarUsuarioGerador2.processarResultado = function(id, descricao, complemento){
      if (id!=''){
        document.getElementById('hdnIdUsuarioGerador2').value = id;
        document.getElementById('txtUsuarioGerador2').value = complemento;
      }
    };

    objAutoCompletarUsuarioGerador3 = new infraAjaxAutoCompletar('hdnIdUsuarioGerador3','txtUsuarioGerador3','<?=$strLinkAjaxUsuarios?>');
    objAutoCompletarUsuarioGerador3.limparCampo = true;
    objAutoCompletarUsuarioGerador3.prepararExecucao = function(){
      return 'palavras_pesquisa='+document.getElementById('txtUsuarioGerador3').value + '&inativos=1';
    };
    objAutoCompletarUsuarioGerador3.selecionar('<?=$numIdUsuarioGerador3?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript($strUsuarioGerador3,false)?>');
    objAutoCompletarUsuarioGerador3.processarResultado = function(id, descricao, complemento){
      if (id!=''){
        document.getElementById('hdnIdUsuarioGerador3').value = id;
        document.getElementById('txtUsuarioGerador3').value = complemento;
      }
    };

    //remover a string null dos combos
    document.getElementById('selTipoProcedimentoPesquisa').options[0].value='';
    document.getElementById('selSeriePesquisa').options[0].value='';

    infraProcessarResize();

    <? if ($strLinkVisualizarPublicado != ''){ ?>
    infraAbrirJanela('<?=$strLinkVisualizarPublicado?>','janelaPesquisaPublicado',750,550,'location=0,status=1,resizable=1,scrollbars=1',false);
    <? } ?>

    document.getElementById('q').focus();

    //tratarTipoPesquisa();
    //tratarCheckboxData();
    //tratarPeriodo();
    tratarCamposDocumento();
  }


  function sugerirUsuarioGerador(){
    objAutoCompletarUsuarioGerador1.selecionar('<?=SessaoSEI::getInstance()->getNumIdUsuario()?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript(SessaoSEI::getInstance()->getStrNomeUsuario())?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript(SessaoSEI::getInstance()->getStrSiglaUsuario(),false)?>');
  }

  function onSubmitForm(){

    if (!document.getElementById('optProcessos').checked && !document.getElementById('optDocumentos').checked){
      alert('Selecione uma opção para pesquisa: Processos ou Documentos.');
      return false;
    }

    if (document.getElementById('optDocumentos').checked && !document.getElementById('chkSinDocumentosGerados').checked && !document.getElementById('chkSinDocumentosRecebidos').checked){
      alert('Selecione pelo menos uma das opções para pesquisa em documentos: Gerados e/ou Externos.');
      return false;
    }

    if ($("#selOrgaoPesquisa").multipleSelect("getSelects").length==0) {
      alert('Nenhum Órgão Gerador selecionado.');
      return false;
    }

    if (infraTrim(document.getElementById('txtContato').value)!='' && !document.getElementById('chkSinInteressado').checked && !document.getElementById('chkSinRemetente').checked && !document.getElementById('chkSinDestinatario').checked){
      alert('Selecione pelo menos umas das opções para pesquisa do contato "'+ document.getElementById('txtContato').value + '" (Interessado, Remetente ou Destinatário).');
      return false;
    }

    if (infraTrim(document.getElementById('txtDataInicio').value)!='') {
      if (!infraValidarData(document.getElementById('txtDataInicio'))) {
        return false;
      }
      if (infraTrim(document.getElementById('txtDataFim').value)!='') {
        if (!infraValidarData(document.getElementById('txtDataFim'))) {
          return false;
        }
      }

    }else if (infraTrim(document.getElementById('txtDataFim').value)!=''){
      alert("Data inicial deve ser informada.");
      return false;
    }

    return true;
  }

  function navegar(inicio) {
    document.getElementById('hdnInicio').value = inicio;
    if (typeof(window.onSubmitForm)=='function' && !window.onSubmitForm()) {
      return;
    }
    document.getElementById('frmPesquisaProtocolo').submit();
  }

  function tratarSelecaoOrgao(){
    objAutoCompletarUnidade.limpar();
  }

  function obterOrgaosSelecionados(){
    return $("#selOrgaoPesquisa").multipleSelect("getSelects");
  }

  function trocarFiltroUsuario(){
    objAutoCompletarInteressadoRI1225.limpar();
  }


  function tratarTipoPesquisa(){
    if (document.getElementById('optProcessos').checked){
      document.getElementById('chkSinDocumentosGerados').checked = true;
      document.getElementById('chkSinDocumentosRecebidos').checked = true;

      $("#divSinDocumentosGeradosRecebidos").hide();
      $("#divSinConsiderarDocumentos").css("display", "inline");


    }else {
      document.getElementById('chkSinConsiderarDocumentos').checked = false;

      $("#divSinDocumentosGeradosRecebidos").css("display", "inline");
      $("#divSinConsiderarDocumentos").hide();
    }

    tratarCamposDocumento();
  }

  function tratarCamposDocumento() {

    if (!document.getElementById('optProcessos').checked || (document.getElementById('optProcessos').checked && document.getElementById('chkSinConsiderarDocumentos').checked)) {
      $("#divPalavrasPesquisa").show();

      $("#divAssinante").show();

      $("#lblDescricaoPesquisa").html("Especificação / Descrição:");

      $("#lblProtocoloPesquisaComplemento").show();

      $("#divSeriePesquisa").show();

      $("#divNumeroDocumentoPesquisa").show();

      $("#divNomeArvoreDocumentoPesquisa").show();
    } else {
      $("#divPalavrasPesquisa").hide();
      $("#q").val("");

      $("#divAssinante").hide();
      $("#txtAssinante").val("");
      $("#hdnIdAssinante").val("");

      $("#lblDescricaoPesquisa").html("Especificação:");

      $("#lblProtocoloPesquisaComplemento").hide();

      $("#divSeriePesquisa").hide();
      $("#selSeriePesquisa").val("");

      $("#divNumeroDocumentoPesquisa").hide();
      $("#txtNumeroDocumentoPesquisa").val("");

      $("#divNomeArvoreDocumentoPesquisa").hide();
      $("#txtNomeArvoreDocumentoPesquisa").val("");

    }
  }


  function limparForm(){
    window.location = "<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=protocolo_pesquisar') ?>";
  }

  $( document ).ready(function() {
    $("#selOrgaoPesquisa").multipleSelect({
      filter: false,
      minimumCountSelected: 1,
      selectAll: true,
      onClick: function (view) { document.getElementById('chkSinRestringirOrgao').checked = false; },
      onCheckAll: function () { document.getElementById('chkSinRestringirOrgao').checked = false; },
      onUncheckAll: function () { document.getElementById('chkSinRestringirOrgao').checked = false; }
    });
  });

  function restringirOrgao(){
    if (document.getElementById('chkSinRestringirOrgao').checked){
      $("#selOrgaoPesquisa").multipleSelect('uncheckAll');
      document.getElementById('chkSinRestringirOrgao').checked = true;
      $("#selOrgaoPesquisa").multipleSelect('check', <?=SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual()?>);
    }
  }

  <?
  if(0){?></script><?}
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<?if ($strRestritoNegado!=null){?>

  <div id="divMensagem" class="infraAreaDados">
    <br />
    <label style="font-size:1.4em"><?=$strRestritoNegado?></label>
  </div>

<?}else{?>

  <form id="frmPesquisaProtocolo" name="frmPesquisaProtocolo" method="post" onsubmit="return onSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    ?>
    <div id="divPesquisarEm" class="d-flex flex-column flex-md-row mb-2" style="height:9em">
        <!-- <label id="lblPesquisarEm" for="" accesskey="" class="infraLabelObrigatorio">Pesquisar:</label> -->
        <div style="width: 17%;" class="d-none d-md-inline-block"></div>
        <fieldset id="fldPesquisarEm" class="infraFieldset h-100 m-0">

          <legend class="infraLegend">Pesquisar</legend>

          <div id="divOptProcessos" class="infraDivRadio">
            <input type="radio" id="optProcessos" name="rdoPesquisarEm" value="P" onclick="tratarTipoPesquisa()" class="infraRadio" <?=($strPesquisarEm=='P'?'checked="checked"':'')?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
            <label id="lblProcessos" for="optProcessos" accesskey="" class="infraLabelRadio" >Processos</label>

            <div id="divSinConsiderarDocumentos" style="display: <?=($strPesquisarEm=='P'?'inline':'none')?>;padding-left: 12px;">
              <input type="checkbox" id="chkSinConsiderarDocumentos" onclick="tratarCamposDocumento()" name="chkSinConsiderarDocumentos"  value="S" class="infraCheckbox" <?=($strSinConsiderarDocumentos=='S'?'checked="checked"':'')?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
              <label id="lblSinConsiderarDocumentos" for="chkSinConsiderarDocumentos" accesskey=""  class="infraLabelCheckbox" >Considerar Documentos</label>
            </div>
          </div>

          <div id="divOptDocumentos" class="infraDivRadio">
            <input type="radio" id="optDocumentos" name="rdoPesquisarEm" value="D" onclick="tratarTipoPesquisa()" class="infraRadio" <?=($strPesquisarEm=='D'?'checked="checked"':'')?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
            <label id="lblDocumentos" for="optDocumentos" accesskey="" class="infraLabelRadio" >Documentos</label>

            <div id="divSinDocumentosGeradosRecebidos" style="display: <?=($strPesquisarEm=='D'?'inline':'none')?>;">
              <input type="checkbox" id="chkSinDocumentosGerados" name="chkSinDocumentosGerados" value="S" class="infraCheckbox" <?=($strSinDocumentosGerados=='S'?'checked="checked"':'')?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
              <label id="lblSinDocumentosGerados" for="chkSinDocumentosGerados" accesskey="" class="infraLabelCheckbox" >Gerados</label>

              <input type="checkbox" id="chkSinDocumentosRecebidos" name="chkSinDocumentosRecebidos" value="S" class="infraCheckbox" <?=($strSinDocumentosRecebidos=='S'?'checked="checked"':'')?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
              <label id="lblSinDocumentosRecebidos" for="chkSinDocumentosRecebidos" accesskey="" class="infraLabelCheckbox" >Externos</label>
            </div>
          </div>
        </fieldset>

        <div id="divSinTramitacao" class="infraDivCheckbox my-1 mt-md-5 ml-md-5 ">
          <input type="checkbox" id="chkSinTramitacao" name="chkSinTramitacao" value="S" class="infraCheckbox" <?=($strSinTramitacao=='S'?'checked="checked"':'')?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
          <label id="lblSinTramitacao" for="chkSinTramitacao" accesskey="" class="infraLabelCheckbox" >Com Tramitação na Unidade</label>
        </div>
    </div>

    <div id="divPalavrasPesquisa" class="infraAreaDados row" style="height: 3em">
      <div class="col-5 col-md-2 mx-0 pr-0 pt-2">
       <label id="lblPalavrasPesquisa" for="q" accesskey=""  class="infraLabelOpcional">Texto para Pesquisa:</label>
      </div>
      <div class="col-7 col-md-9 media">
        <input type="text" id="q" name="q" class="infraText w-75" maxlength="250" onkeypress="return infraLimitarTexto(this,event,250);" value="<?=PaginaSEI::tratarHTML($strPalavrasPesquisa)?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <a id="ancAjuda" href="<?=$strLinkAjuda?>" target="janAjuda" class="ml-1" title="Ajuda para Pesquisa" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><img src="<?=PaginaSEI::getInstance()->getIconeAjuda()?>" class="infraImg"/></a>
      </div>
    </div>

    <div id="divOrgao" class="d-flex flex-column flex-md-row mb-2" >
      <div class="col-5 col-md-2 mx-0 px-0 pt-2">
      <label id="lblOrgaoPesquisa" for="selOrgaoPesquisa" accesskey="" class="infraLabelOpcional">Órgão Gerador:</label>
      </div>
      <div class="pl-0 pl-md-1" style=" width: 354px;">
      <select style="display: none" multiple id="selOrgaoPesquisa" name="selOrgaoPesquisa[]" onchange="tratarSelecaoOrgao()" class=" w-100 infraSelect multipleSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
        <?=$strOptionsOrgaos;?>
      </select>
      </div>

      <div id="divSinRestringirOrgao" class="infraDivCheckbox my-1 my-md-0 ml-md-5 ">
        <input type="checkbox" id="chkSinRestringirOrgao" name="chkSinRestringirOrgao" value="S" class="infraCheckbox" onchange="restringirOrgao()" <?=($strSinRestringirOrgao=='S'?'checked="checked"':'')?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <label id="lblSinRestringirOrgao" for="chkSinRestringirOrgao" accesskey="" class="infraLabelCheckbox" >Restringir ao Órgão da Unidade</label>
      </div>

    </div>

    <div id="divUnidadeGeradora" class="infraAreaDados row" style="height: 3em">
      <div class="col-5 col-md-2 mx-0 pr-0 pt-2">
      <label id="lblUnidade" for="txtUnidade" class="infraLabelOpcional">Unidade Geradora:</label>
      </div>
      <div class="col-7 col-md-9 media">
      <input type="text" id="txtUnidade" name="txtUnidade" class="infraText  w-75" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" value="<?=PaginaSEI::tratarHTML($strDescricaoUnidade)?>" />
      <input type="hidden" id="hdnIdUnidade" name="hdnIdUnidade" class="infraText" value="<?=$numIdUnidade?>" />
      </div>
    </div>

    <div id="divAssunto" class="infraAreaDados row" style="height: 3em">
      <div class="col-5 col-md-2 mx-0 pr-0 pt-2">
      <label id="lblAssunto" for="txtAssunto" class="infraLabelOpcional">Assunto:</label>
      </div>
      <div class="col-7 col-md-9 media">
      <input type="text" id="txtAssunto" name="txtAssunto" class="infraText  w-75" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" value="<?=PaginaSEI::tratarHTML($strDescricaoAssunto)?>" />
      <input type="hidden" id="hdnIdAssunto" name="hdnIdAssunto" class="infraText" value="<?=$strIdAssunto?>" />
      </div>
    </div>

    <div id="divAssinante" class="infraAreaDados row" style="height: 3em">
      <div class="col-5 col-md-2 mx-0 pr-0 pt-2">
      <label id="lblAssinante" for="txtAssinante" accesskey=""  class="infraLabelOpcional">Assinatura / Autenticação:</label>
      </div>
      <div class="col-7 col-md-9 media">
      <input type="text" id="txtAssinante" name="txtAssinante" class="infraText w-75" value="<?=PaginaSEI::tratarHTML($strNomeAssinante);?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <input type="hidden" id="hdnIdAssinante" name="hdnIdAssinante" class="infraText" value="<?=$strIdAssinante?>" />
      </div>
    </div>

    <div id="divContatoSelecao" class="infraAreaDados row" style="height: 3em">
      <div class="col-5 col-md-2 mx-0 pr-0 pt-2">
      <label id="lblContato" for="txtContato" accesskey=""  class="infraLabelOpcional">Contato:</label>
      </div>
      <div class="col-7 col-md-9 media">
      <input type="text" id="txtContato" name="txtContato" class="infraText w-75" value="<?=PaginaSEI::tratarHTML($strNomeContato);?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <input type="hidden" id="hdnIdContato" name="hdnIdContato" class="infraText" value="<?=$strIdContato?>" />
      </div>
    </div>

    <div id="divContatoOpcoes" class="infraAreaDados row" style="height: 3em">
      <div class="col-2 d-none d-md-inline-block"></div>
      <div id="divSinInteressado" class="infraDivCheckbox col-md-2 col-4">
        <input type="checkbox" id="chkSinInteressado" name="chkSinInteressado" value="S" class="infraCheckbox" <?=($strSinInteressado=='S'?'checked="checked"':'')?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <label id="lblSinInteressado" for="chkSinInteressado" accesskey="" class="infraLabelCheckbox" >Interessado</label>
      </div>

      <div id="divSinRemetente" class="infraDivCheckbox col-md-2 col-4">
        <input type="checkbox" id="chkSinRemetente" name="chkSinRemetente" value="S" class="infraCheckbox" <?=($strSinRemetente=='S'?'checked="checked"':'')?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <label id="lblSinRemetente" for="chkSinRemetente" accesskey="" class="infraLabelCheckbox" >Remetente</label>
      </div>

      <div id="divSinDestinatario" class="infraDivCheckbox col-md-2 col-4">
        <input type="checkbox" id="chkSinDestinatario" name="chkSinDestinatario" value="S" class="infraCheckbox" <?=($strSinDestinatario=='S'?'checked="checked"':'')?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <label id="lblSinDestinatario" for="chkSinDestinatario" accesskey="" class="infraLabelCheckbox" >Destinatário</label>
      </div>
    </div>

    <div id="divDescricaoPesquisa" class="infraAreaDados row" style="height: 3em">
      <div class="col-5 col-md-2 mx-0 pr-0 pt-2">
      <label id="lblDescricaoPesquisa" for="txtDescricaoPesquisa" accesskey="" class="infraLabelOpcional">Especificação / Descrição:</label>
      </div>
      <div class="col-7 col-md-9 media">

      <input type="text" id="txtDescricaoPesquisa" name="txtDescricaoPesquisa" class="infraText w-75" maxlength="250" onkeypress="return infraLimitarTexto(this,event,250);" value="<?=PaginaSEI::tratarHTML($strDescricaoPesquisa);?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <a id="ancAjudaDescricao" href="<?=$strLinkAjuda?>" class="ml-1" target="janAjuda" title="Ajuda para Pesquisa" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><img src="<?=PaginaSEI::getInstance()->getIconeAjuda()?>" class="infraImg"/></a>
      </div>
    </div>

    <div id="divObservacaoPesquisa" class="infraAreaDados row" style="height: 3em">
      <div class="col-5 col-md-2 mx-0 pr-0 pt-2">

      <label id="lblObservacaoPesquisa" for="txtObservacaoPesquisa" accesskey="" class="infraLabelOpcional">Obs. desta Unidade:</label>
      </div>
      <div class="col-7 col-md-9 media">

      <input type="text" id="txtObservacaoPesquisa" name="txtObservacaoPesquisa" class="infraText w-75" maxlength="250" onkeypress="return infraLimitarTexto(this,event,250);" value="<?=PaginaSEI::tratarHTML($strObservacaoPesquisa);?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <a id="ancAjudaObservacao" href="<?=$strLinkAjuda?>" target="janAjuda" class="ml-1"  title="Ajuda para Pesquisa" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><img src="<?=PaginaSEI::getInstance()->getIconeAjuda()?>" class="infraImg"/></a>
    </div>
    </div>

    <div id="divProtocoloPesquisa" class="infraAreaDados row" style="height: 3em">
      <div class="col-5 col-md-2 mx-0 pr-0 pt-2">

      <label id="lblProtocoloPesquisa" for="txtProtocoloPesquisa" accesskey="" class="infraLabelOpcional">Nº SEI:</label>
      </div>
      <div class="col-7 col-md-9 media">

      <input type="text" id="txtProtocoloPesquisa" name="txtProtocoloPesquisa" class="infraText w-50" maxlength="50" onkeypress="return infraLimitarTexto(this,event,50);" value="<?=PaginaSEI::tratarHTML($strProtocoloPesquisa);?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblProtocoloPesquisaComplemento" class=" ml-1 infraLabelOpcional">(Processo/Documento)</label>
      </div>
    </div>

    <div id="divTipoProcedimentoPesquisa" class="infraAreaDados row" style="height: 3em">
      <div class="col-5 col-md-2 mx-0 pr-0 pt-2">
      <label id="lblTipoProcedimentoPesquisa" for="selTipoProcedimentoPesquisa" accesskey="" class="infraLabelOpcional">Tipo do Processo:</label>
      </div>
      <div class="col-7 col-md-9 media">

      <select id="selTipoProcedimentoPesquisa" name="selTipoProcedimentoPesquisa" class="infraSelect w-75" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
        <?=$strItensSelTipoProcedimento?>
      </select>
      </div>
    </div>

    <div id="divSeriePesquisa" class="infraAreaDados row" style="height: 3em">
      <div class="col-5 col-md-2 mx-0 pr-0 pt-2">

      <label id="lblSeriePesquisa" for="selSeriePesquisa" accesskey="" class="infraLabelOpcional">Tipo do Documento:</label>
      </div>
      <div class="col-7 col-md-9 media">
      <select id="selSeriePesquisa" name="selSeriePesquisa" class="infraSelect w-75" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
        <?=$strItensSelSerie?>
      </select>
      </div>
    </div>

    <div id="divNumeroDocumentoPesquisa" class="infraAreaDados row" style="height: 3em">
      <div class="col-5 col-md-2 mx-0 pr-0 pt-2">

      <label id="lblNumeroDocumentoPesquisa" for="txtNumeroDocumentoPesquisa" accesskey="" class="infraLabelOpcional">Número:</label>
      </div>
      <div class="col-7 col-md-9 media">
      <input type="text" id="txtNumeroDocumentoPesquisa" name="txtNumeroDocumentoPesquisa" class="infraText w-75" maxlength="50" onkeypress="return infraLimitarTexto(this,event,50);" value="<?=PaginaSEI::tratarHTML($strNumeroDocumentoPesquisa);?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      </div>
      </div>

    <div id="divNomeArvoreDocumentoPesquisa" class="infraAreaDados row" style="height: 3em">
      <div class="col-5 col-md-2 mx-0 pr-0 pt-2">
      <label id="lblNomeArvoreDocumentoPesquisa" for="txtNomeArvoreDocumentoPesquisa" accesskey="" class="infraLabelOpcional">Nome na Árvore:</label>
      </div>
      <div class="col-7 col-md-9 media">
        <input type="text" id="txtNomeArvoreDocumentoPesquisa" name="txtNomeArvoreDocumentoPesquisa" class="w-75 infraText" maxlength="50" onkeypress="return infraLimitarTexto(this,event,50);" value="<?=PaginaSEI::tratarHTML($strNomeArvoreDocumentoPesquisa);?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    </div>
    </div>

    <div id="divUsuarioGerador" class="infraAreaDados d-flex flex-row" style="height:3em;">
      <div class="col-4 col-md-2 mx-0 px-0 pt-2">

      <label id="lblUsuarioGerador" accesskey="" class="infraLabelOpcional">Usuário Gerador:</label>
      </div>
      <div class="d-flex col-md-7 col-8 pl-0 pr-0 pl-md-1  pr-md-4 justify-content-between">

      <input type="text" id="txtUsuarioGerador1" name="txtUsuarioGerador1" class="infraText w-30" onfocus="sugerirUsuarioGerador();" value="<?=PaginaSEI::tratarHTML($strUsuarioGerador1);?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <input type="hidden" id="hdnIdUsuarioGerador1" name="hdnIdUsuarioGerador1" class="infraText" value="<?=$numIdUsuarioGerador1?>" />

      <input type="text" id="txtUsuarioGerador2" name="txtUsuarioGerador2" class="infraText w-30" value="<?=PaginaSEI::tratarHTML($strUsuarioGerador2);?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <input type="hidden" id="hdnIdUsuarioGerador2" name="hdnIdUsuarioGerador2" class="infraText" value="<?=$numIdUsuarioGerador2?>" />

      <input type="text" id="txtUsuarioGerador3" name="txtUsuarioGerador3" class="infraText w-30" value="<?=PaginaSEI::tratarHTML($strUsuarioGerador3);?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <input type="hidden" id="hdnIdUsuarioGerador3" name="hdnIdUsuarioGerador3" class="infraText" value="<?=$numIdUsuarioGerador3?>" />
      </div>
      </div>

    <div id="divData" class="infraAreaDados d-flex " style="height:3em;">
      <div class="col-3 col-md-2 mx-0 px-0 pt-2">
      <label id="lblData" class="infraLabelOpcional">Data entre:</label>
      </div>
      <div class="d-flex col-md-7 col-9 pl-0 pr-0 pl-md-1  pr-md-4 ">

        <input type="text" id="txtDataInicio" name="txtDataInicio" onkeypress="return infraMascaraData(this, event)" class="w-25 infraText" value="<?=PaginaSEI::tratarHTML($strDataInicio);?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <img id="imgDataInicio" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" onclick="infraCalendario('txtDataInicio',this);" alt="Selecionar Data Inicial" title="Selecionar Data Inicial" class="infraImg mx-1" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblDataE" for="txtDataE" accesskey="" class="infraLabelOpcional mx-0 ml-md-3 mr-md-2 pt-2">e</label>
        <input type="text" id="txtDataFim" name="txtDataFim" onkeypress="return infraMascaraData(this, event)" class="w-25 ml-1 infraText" value="<?=PaginaSEI::tratarHTML($strDataFim);?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <img id="imgDataFim" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" onclick="infraCalendario('txtDataFim',this);" alt="Selecionar Data Final" title="Selecionar Data Final" class="infraImg mx-1" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <select id="selData" name="selData" class="infraSelect w-30 ml-auto" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
        <option value="I" <?=$strStaData == 'I' ? 'selected="selected"' : ''?>>Data de Inclusão no SEI</option>
        <option value="G" <?=$strStaData == 'G' ? 'selected="selected"' : ''?>>Data do Processo / Documento</option>
      </select>
      </div>
    </div>



    <?
    if($strResultado == ''){
      echo "<div style='height: 130px;'></div>";
    }
    echo '<div id="conteudo" >';
    echo $strResultado;
    echo '</div>';
    PaginaSEI::getInstance()->montarAreaDebug();
    ?>
    <input type="hidden" id="hdnInicio" name="hdnInicio" value="0" />
  </form>

<?}?>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>