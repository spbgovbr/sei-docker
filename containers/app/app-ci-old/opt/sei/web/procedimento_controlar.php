<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4º REGIÃO
 *
 * Versão do Gerador de Código: 1.13.1
 *
 * Versão no CVS: $Id$
 */
try {
  require_once dirname(__FILE__) . '/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  //PaginaSEI::getInstance()->prepararSelecao('procedimento_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  //$numSeg = InfraUtil::verificarTempoProcessamento();

  global $SEI_MODULOS;

  $strIdHdnMarcador = 'hdnIdMarcador'.SessaoSEI::getInstance()->getNumIdUnidadeAtual();
  $strIdHdnTipoProcedimento = 'hdnIdTipoProcedimento'.SessaoSEI::getInstance()->getNumIdUnidadeAtual();

  if ($_GET['acao_origem'] == 'painel_controle_visualizar'){
    PaginaSEI::getInstance()->salvarCampo('hdnTipoVisualizacao', ProcedimentoINT::$TV_DETALHADA);
    PaginaSEI::getInstance()->salvarCampo('hdnMeusProcessos', AtividadeRN::$TA_TODAS);
    PaginaSEI::getInstance()->salvarCampo($strIdHdnMarcador, null);
    PaginaSEI::getInstance()->salvarCampo($strIdHdnTipoProcedimento, null);
  }else{
    PaginaSEI::getInstance()->salvarCamposPost(array('hdnTipoVisualizacao', 'hdnMeusProcessos', $strIdHdnMarcador, $strIdHdnTipoProcedimento));
  }
  PaginaSEI::getInstance()->salvarCamposPost(array('hdnExibirRecebidos', 'hdnExibirGerados'));
  $strExibirGerados = PaginaSEI::getInstance()->recuperarCampo('hdnExibirGerados', "false");
  $strExibirRecebidos = PaginaSEI::getInstance()->recuperarCampo('hdnExibirRecebidos', "false");

  $arrComandos = array();
  $arrIdProcessosSigilosos = array();
  $arrIdProcessosComMarcador = array();

  switch ($_GET['acao']) {

    case 'procedimento_concluir':

      $objProcedimentoRN = new ProcedimentoRN();

      $arr = array_merge(PaginaSEI::getInstance()->getArrStrItensSelecionados('Gerados'), PaginaSEI::getInstance()->getArrStrItensSelecionados('Recebidos'), PaginaSEI::getInstance()->getArrStrItensSelecionados('Detalhado'));

      $arrObjProcedimentoDTO = array();
      foreach ($arr as $dblIdProcedimento) {
        $objProcedimentoDTO = new ProcedimentoDTO();
        $objProcedimentoDTO->setDblIdProcedimento($dblIdProcedimento);
        $arrObjProcedimentoDTO[] = $objProcedimentoDTO;
      }

      try {
        $objProcedimentoRN->concluir($arrObjProcedimentoDTO);

        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');

      } catch (Exception $e) {
        PaginaSEI::getInstance()->processarExcecao($e);
      }

      header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']));
      die;

    case 'procedimento_controlar':
      $strTitulo = 'Controle de Processos';
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  AvisoINT::processar($strJsAviso, $strHtmlBanner);
  NovidadeINT::processar($strJsNovidades);

  $objPainelControleRN = new PainelControleRN();
  $objPainelControleDTO = $objPainelControleRN->carregarConfiguracoes();

  if (isset($_GET['tipo_filtro'])) {
    $strTipoFiltro = $_GET['tipo_filtro'];
  } else {
    $strTipoFiltro = null;
  }

  if (isset($_GET['tipo_visualizacao'])) {
    $strTipoVisualizacao = $_GET['tipo_visualizacao'];
  } else {
    $strTipoVisualizacao = PaginaSEI::getInstance()->recuperarCampo('hdnTipoVisualizacao', ProcedimentoINT::$TV_RESUMIDA);
  }

  if (isset($_GET['tipo_atribuicao'])) {
    $strTipoAtribuicao = $_GET['tipo_atribuicao'];
  } else {
    $strTipoAtribuicao = PaginaSEI::getInstance()->recuperarCampo('hdnMeusProcessos', AtividadeRN::$TA_TODAS);
  }

  if (isset($_GET['recebidos'])) {
    $strFiltroRecebidos = $_GET['recebidos'];
  } else {
    $strFiltroRecebidos = PaginaSEI::getInstance()->recuperarCampo('recebidos', '0');
  }

  if (isset($_GET['gerados'])) {
    $strFiltroGerados = $_GET['gerados'];
  } else {
    $strFiltroGerados = PaginaSEI::getInstance()->recuperarCampo('gerados', '0');
  }

  if (isset($_GET['nao_visualizados'])) {
    $strFiltroNaoVisualizados = $_GET['nao_visualizados'];
  } else {
    $strFiltroNaoVisualizados = PaginaSEI::getInstance()->recuperarCampo('nao_visualizados', '0');
  }

  if (isset($_GET['sem_acompanhamento'])) {
    $strFiltroSemAcompanhamento = $_GET['sem_acompanhamento'];
  } else {
    $strFiltroSemAcompanhamento = PaginaSEI::getInstance()->recuperarCampo('sem_acompanhamento', '0');
  }

  if (isset($_GET['alterados'])) {
    $strFiltroAlterados = $_GET['alterados'];
  } else {
    $strFiltroAlterados = PaginaSEI::getInstance()->recuperarCampo('alterados', '0');
  }

  if (isset($_GET['id_tipo_procedimento'])) {
    $numIdTipoProcedimentoPainel = $_GET['id_tipo_procedimento'];
  } else {
    $numIdTipoProcedimentoPainel = PaginaSEI::getInstance()->recuperarCampo('id_tipo_procedimento', '0');
  }

  if (isset($_GET['id_marcador'])) {
    $numIdMarcadorPainel = $_GET['id_marcador'];
  } else {
    $numIdMarcadorPainel = PaginaSEI::getInstance()->recuperarCampo('id_marcador', '0');
  }

  if (isset($_GET['id_usuario'])) {
    $numIdUsuarioPainel = $_GET['id_usuario'];
  } else {
    $numIdUsuarioPainel = PaginaSEI::getInstance()->recuperarCampo('id_usuario', '0');
  }

  if (isset($_GET['tipo_controle_prazo'])) {
    $strStaTipoControlePrazo = $_GET['tipo_controle_prazo'];
  } else {
    $strStaTipoControlePrazo = PaginaSEI::getInstance()->recuperarCampo('tipo_controle_prazo', '0');
  }

  if (isset($_GET['tipo_retorno_programado'])) {
    $strStaTipoRetornoProgramado = $_GET['tipo_retorno_programado'];
  } else {
    $strStaTipoRetornoProgramado = PaginaSEI::getInstance()->recuperarCampo('tipo_retorno_programado', '0');
  }


  if (isset($_GET['reset'])){

    $strTipoVisualizacao = ProcedimentoINT::$TV_RESUMIDA;
    //$strTipoAtribuicao
    $strFiltroRecebidos = '0';
    $strFiltroGerados = '0';
    $strFiltroNaoVisualizados = '0';
    $strFiltroSemAcompanhamento = '0';
    $strFiltroAlterados = '0';
    $numIdTipoProcedimentoPainel = '0';
    $numIdMarcadorPainel = '0';
    $numIdUsuarioPainel = '0';
    $strStaTipoControlePrazo = '0';
    $strStaTipoRetornoProgramado = '0';

  }

  PaginaSEI::getInstance()->salvarCampo('hdnTipoVisualizacao', $strTipoVisualizacao);
  PaginaSEI::getInstance()->salvarCampo('hdnMeusProcessos', $strTipoAtribuicao);
  PaginaSEI::getInstance()->salvarCampo('recebidos', $strFiltroRecebidos);
  PaginaSEI::getInstance()->salvarCampo('gerados', $strFiltroGerados);
  PaginaSEI::getInstance()->salvarCampo('nao_visualizados', $strFiltroNaoVisualizados);
  PaginaSEI::getInstance()->salvarCampo('sem_acompanhamento', $strFiltroSemAcompanhamento);
  PaginaSEI::getInstance()->salvarCampo('alterados', $strFiltroAlterados);
  PaginaSEI::getInstance()->salvarCampo('id_tipo_procedimento', $numIdTipoProcedimentoPainel);
  PaginaSEI::getInstance()->salvarCampo('id_marcador', $numIdMarcadorPainel);
  PaginaSEI::getInstance()->salvarCampo('id_usuario', $numIdUsuarioPainel);
  PaginaSEI::getInstance()->salvarCampo('tipo_controle_prazo', $strStaTipoControlePrazo);
  PaginaSEI::getInstance()->salvarCampo('tipo_retorno_programado', $strStaTipoRetornoProgramado);


  $bolOpcoesFiltro = !(($strFiltroRecebidos != '0' xor $strFiltroGerados != '0') || $strFiltroNaoVisualizados != '0' ||
    $strFiltroSemAcompanhamento != '0' || $strFiltroAlterados != '0' || $numIdTipoProcedimentoPainel != '0' ||
    $numIdMarcadorPainel != '0' || $numIdUsuarioPainel != '0' || $strStaTipoControlePrazo != '0' ||
    $strStaTipoRetornoProgramado != '0');

  $strLinkIncluirEmBloco = '';
  $strLinkCredencialAcessar = '';

  $strResultadoRecebidos = '';
  $strResultadoGerados = '';
  $strResultadoDetalhado = '';
  $numRegistrosMarcadores = 0;
  $strResultadoMarcadores = '';
  $numRegistrosTiposProcedimento = 0;
  $strResultadoTiposProcedimento = '';

  $objPesquisaPendenciaDTO = new PesquisaPendenciaDTO();
  $objPesquisaPendenciaDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
  $objPesquisaPendenciaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  $objPesquisaPendenciaDTO->setStrStaTipoAtribuicao($strTipoAtribuicao);

  $numIdMarcadorFiltro = PaginaSEI::getInstance()->recuperarCampo($strIdHdnMarcador);
  $objMarcadorDTOFiltro = null;
  if (!InfraString::isBolVazia($numIdMarcadorFiltro)) {
    $objMarcadorDTOFiltro = new MarcadorDTO();
    $objMarcadorDTOFiltro->setBolExclusaoLogica(false);
    $objMarcadorDTOFiltro->retStrNome();
    $objMarcadorDTOFiltro->retStrStaIcone();
    $objMarcadorDTOFiltro->setNumIdMarcador($numIdMarcadorFiltro);
    $objMarcadorDTOFiltro->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

    $objMarcadorRN = new MarcadorRN();
    $objMarcadorDTOFiltro = $objMarcadorRN->consultar($objMarcadorDTOFiltro);

    if ($objMarcadorDTOFiltro!=null){
      $objPesquisaPendenciaDTO->setNumIdMarcador($numIdMarcadorFiltro);
    }else{
      $numIdMarcadorFiltro = null;
    }
  }

  $numIdTipoProcedimentoFiltro = PaginaSEI::getInstance()->recuperarCampo($strIdHdnTipoProcedimento);
  $objTipoProcedimentoDTOFiltro = null;
  if (!InfraString::isBolVazia($numIdTipoProcedimentoFiltro)){

    $objTipoProcedimentoDTOFiltro = new TipoProcedimentoDTO();
    $objTipoProcedimentoDTOFiltro->setBolExclusaoLogica(false);
    $objTipoProcedimentoDTOFiltro->retStrNome();
    $objTipoProcedimentoDTOFiltro->setNumIdTipoProcedimento($numIdTipoProcedimentoFiltro);

    $objTipoProcedimentoRN = new TipoProcedimentoRN();
    $objTipoProcedimentoDTOFiltro = $objTipoProcedimentoRN->consultarRN0267($objTipoProcedimentoDTOFiltro);

    if ($objTipoProcedimentoDTOFiltro!=null){
      $objPesquisaPendenciaDTO->setNumIdTipoProcedimento($numIdTipoProcedimentoFiltro);
    }else{
      $numIdTipoProcedimentoFiltro = null;
    }
  }


  if ($strTipoFiltro == ProcedimentoINT::$TF_MARCADORES) {

    PaginaSEI::getInstance()->prepararOrdenacao($objPesquisaPendenciaDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC, false, 'Marcadores');

    $objAtividadeRN = new AtividadeRN();
    $arrObjMarcadorDTO = $objAtividadeRN->listarPendenciasPorMarcadores($objPesquisaPendenciaDTO);

    $numRegistrosMarcadores = count($arrObjMarcadorDTO);

    if ($numRegistrosMarcadores) {

      $strResultadoMarcadores .= '<table id="tblMarcadores" border="0" cellspacing="0" cellpadding="1" width="75%" class="infraTable tabelaControle" summary="Tabela de Quantidade de Processos por Marcador.">'."\n";
      //$strResultadoMarcadores .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela('', $numRegistrosMarcadores, '') . '</caption>';
      $strResultadoMarcadores .= '<tr>';
      $strResultadoMarcadores .= '<th class="infraTh" style="display:none">'.PaginaSEI::getInstance()->getThCheck('','Marcadores').'</th>'."\n";
      $strResultadoMarcadores .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objPesquisaPendenciaDTO, 'Processos','Processos', $arrObjMarcadorDTO, false, 'Marcadores').'</th>'."\n";
      $strResultadoMarcadores .= '<th class="infraTh" colspan="2">'.PaginaSEI::getInstance()->getThOrdenacao($objPesquisaPendenciaDTO, 'Marcador','Nome', $arrObjMarcadorDTO, false, 'Marcadores').'</th>'."\n";
      $strResultadoMarcadores .= '</tr>'."\n";

      for ($i = 0; $i < $numRegistrosMarcadores; $i++) {
        $strResultadoMarcadores .= '<tr class="infraTrClara">'."\n";
        $strResultadoMarcadores .= '<td align="center" width="10%">'.PaginaSEI::getInstance()->getTrCheck($i, $arrObjMarcadorDTO[$i]->getNumIdMarcador(), '', 'N', 'Marcadores', 'style="display:none"').'<a href="javascript:void(0);" onclick="filtrarMarcador('.$arrObjMarcadorDTO[$i]->getNumIdMarcador().')" class="ancoraPadraoAzul">'.InfraUtil::formatarMilhares($arrObjMarcadorDTO[$i]->getNumProcessos()).'</a></td>'."\n";
        $strResultadoMarcadores .= '<td align="right"><img src='.$arrObjMarcadorDTO[$i]->getStrArquivoIcone().' class="InfraImg" /></td>'."\n";
        $strResultadoMarcadores .= '<td align="left">'.PaginaSEI::tratarHTML($arrObjMarcadorDTO[$i]->getStrNome()).'</td>'."\n";
        $strResultadoMarcadores .= '</tr>'."\n";
      }
      $strResultadoMarcadores .= '</table>';
    }

  }else if ($strTipoFiltro == ProcedimentoINT::$TF_TIPO_PROCEDIMENTO){


    PaginaSEI::getInstance()->prepararOrdenacao($objPesquisaPendenciaDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC, false, 'TiposProcessos');

    $objAtividadeRN = new AtividadeRN();
    $arrObjTipoProcedimentoDTO = $objAtividadeRN->listarPendenciasPorTipoProcedimento($objPesquisaPendenciaDTO);

    $numRegistrosTiposProcedimento = count($arrObjTipoProcedimentoDTO);

    if ($numRegistrosTiposProcedimento) {

      $strResultadoTiposProcedimento .= '<table id="tblTiposProcedimento" border="0" cellspacing="0" cellpadding="1" width="75%" class="infraTable tabelaControle" summary="Tabela de Quantidade de Processos por Tipo.">'."\n";
      //$strResultadoTiposProcedimento .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela('', $numRegistrosTiposProcedimento, '') . '</caption>';
      $strResultadoTiposProcedimento .= '<tr>';
      $strResultadoTiposProcedimento .= '<th class="infraTh" style="display:none">'.PaginaSEI::getInstance()->getThCheck('','TiposProcessos').'</th>'."\n";
      $strResultadoTiposProcedimento .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objPesquisaPendenciaDTO, 'Processos','Processos', $arrObjTipoProcedimentoDTO, false, 'TiposProcessos').'</th>'."\n";
      $strResultadoTiposProcedimento .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objPesquisaPendenciaDTO, 'Tipo', 'Nome', $arrObjTipoProcedimentoDTO, false, 'TiposProcessos').'</th>'."\n";
      $strResultadoTiposProcedimento .= '</tr>'."\n";

      for ($i = 0; $i < $numRegistrosTiposProcedimento; $i++) {
        $strResultadoTiposProcedimento .= '<tr class="infraTrClara">'."\n";
        $strResultadoTiposProcedimento .= '<td align="center" width="10%">'.PaginaSEI::getInstance()->getTrCheck($i, $arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento(), '', 'N', 'TiposProcessos', 'style="display:none"').'<a href="javascript:void(0);" onclick="filtrarTipoProcedimento('.$arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento().')" class="ancoraPadraoAzul">'.InfraUtil::formatarMilhares($arrObjTipoProcedimentoDTO[$i]->getNumProcessos()).'</a></td>'."\n";
        $strResultadoTiposProcedimento .= '<td align="left">'.PaginaSEI::tratarHTML($arrObjTipoProcedimentoDTO[$i]->getStrNome()).'</td>'."\n";
        $strResultadoTiposProcedimento .= '</tr>'."\n";
      }
      $strResultadoTiposProcedimento .= '</table>';
    }

  }else{

    $objPesquisaPendenciaDTO->setStrStaEstadoProcedimento(array(ProtocoloRN::$TE_NORMAL,ProtocoloRN::$TE_PROCEDIMENTO_BLOQUEADO));
    $objPesquisaPendenciaDTO->setStrSinCredenciais('S');

    //$objPesquisaPendenciaDTO->setStrSinAcompanhamentos('S');
    $objPesquisaPendenciaDTO->setStrSinAnotacoes('S');
    $objPesquisaPendenciaDTO->setStrSinRetornoProgramado('S');
    $objPesquisaPendenciaDTO->setStrSinSituacoes('S');
    $objPesquisaPendenciaDTO->setStrSinMarcadores('S');
    $objPesquisaPendenciaDTO->setStrSinControlePrazo('S');

    $strCaptionDetalhada = 'Processos';

    if ($strFiltroRecebidos == '1' && $strFiltroGerados != '1') {
      $objPesquisaPendenciaDTO->setStrSinInicial('N');
      $strCaptionDetalhada .= ' <b>Recebidos</b>';
    }

    if ($strFiltroGerados == '1' && $strFiltroRecebidos != '1') {
      $objPesquisaPendenciaDTO->setStrSinInicial('S');
      $strCaptionDetalhada .= ' <b>Gerados</b>';
    }

    if ($strFiltroNaoVisualizados == '1') {
      $objPesquisaPendenciaDTO->setStrSinNaoVisualizados('S');
      $strCaptionDetalhada .= ' <b>não Visualizados</b>';
    }

    if ($strFiltroSemAcompanhamento == '1') {
      $objPesquisaPendenciaDTO->setNumIdAcompanhamento(null);
      $strCaptionDetalhada .= ' <b>sem Acompanhamento Especial</b>';
    }

    if ($strFiltroAlterados == '1') {
      $objPesquisaPendenciaDTO->setStrSinAlterados('S');
      $strCaptionDetalhada .= ' <b>Alterados</b>';
    }

    if ($numIdTipoProcedimentoPainel != '0') {
      $objPesquisaPendenciaDTO->setNumIdTipoProcedimento(($numIdTipoProcedimentoPainel != '-1' ? $numIdTipoProcedimentoPainel : null));
      $strCaptionDetalhada .= ' <b>por Tipo de Processo</b>';
    }

    if ($numIdMarcadorPainel != '0') {
      $objPesquisaPendenciaDTO->setNumIdMarcador(($numIdMarcadorPainel != '-1' ? $numIdMarcadorPainel : null));
      $strCaptionDetalhada .= ' <b>por Marcador</b>';
    }

    if ($numIdUsuarioPainel != '0') {
      $objPesquisaPendenciaDTO->setStrStaTipoAtribuicao(AtividadeRN::$TA_ESPECIFICAS);
      $objPesquisaPendenciaDTO->setNumIdUsuarioAtribuicao(($numIdUsuarioPainel != '-1' ? $numIdUsuarioPainel : null));
      $strCaptionDetalhada .= ' <b>por Atribuição</b>';
    }

    if ($strStaTipoControlePrazo != '0'){
      $objPesquisaPendenciaDTO->setStrStaTipoControlePrazo($strStaTipoControlePrazo);
      $strCaptionDetalhada .= ' <b>por Controle de Prazo</b>';
    }

    if ($strStaTipoRetornoProgramado != '0'){
      $objPesquisaPendenciaDTO->setStrStaTipoRetornoProgramado($strStaTipoRetornoProgramado);
      $strCaptionDetalhada .= ' <b>por Retorno Programado</b>';
    }

    if ($strTipoVisualizacao != ProcedimentoINT::$TV_DETALHADA) {
      $objPesquisaPendenciaDTO->setStrSinInteressados('N');
    } else {

      if ($objPainelControleDTO->getStrSinNivelInteressados() == 'S') {
        $objPesquisaPendenciaDTO->setStrSinInteressados('S');
      }

      if ($objPainelControleDTO->getStrSinNivelObservacao() == 'S') {
        $objPesquisaPendenciaDTO->setStrSinObservacoes('S');
      }
    }

    $objAtividadeRN = new AtividadeRN();

    //$numSeg = InfraUtil::verificarTempoProcessamento();

    $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
    $numPaginacaoControle = $objInfraParametro->getValor('SEI_NUM_PAGINACAO_CONTROLE_PROCESSOS');

    if ($strTipoVisualizacao == ProcedimentoINT::$TV_DETALHADA) {

      $objAtividadeDTOOrdenacao = new AtividadeDTO();
      PaginaSEI::getInstance()->prepararOrdenacao($objAtividadeDTOOrdenacao, 'IdAtividade', InfraDTO::$TIPO_ORDENACAO_DESC);
      $objPesquisaPendenciaDTO->setObjAtividadeDTOOrdenacao($objAtividadeDTOOrdenacao);

      if ($numPaginacaoControle > 0) {
        PaginaSEI::getInstance()->prepararPaginacao($objPesquisaPendenciaDTO, $numPaginacaoControle);
      }

      $arrObjProcedimentoDTO = $objAtividadeRN->listarPendenciasRN0754($objPesquisaPendenciaDTO);

      if ($numPaginacaoControle > 0) {
        PaginaSEI::getInstance()->processarPaginacao($objPesquisaPendenciaDTO);
      }

    } else {

      $objPesquisaPendenciaDTORecebidos = clone($objPesquisaPendenciaDTO);
      $objPesquisaPendenciaDTORecebidos->setStrSinInicial('N');

      if ($numPaginacaoControle > 0) {
        PaginaSEI::getInstance()->prepararPaginacao($objPesquisaPendenciaDTORecebidos, $numPaginacaoControle, false, null, 'Recebidos');
      }

      $arrObjProcedimentoDTORecebidos = $objAtividadeRN->listarPendenciasRN0754($objPesquisaPendenciaDTORecebidos);

      if ($numPaginacaoControle > 0) {
        PaginaSEI::getInstance()->processarPaginacao($objPesquisaPendenciaDTORecebidos, 'Recebidos');
      }

      $objPesquisaPendenciaDTOGerados = clone($objPesquisaPendenciaDTO);
      $objPesquisaPendenciaDTOGerados->setStrSinInicial('S');

      if ($numPaginacaoControle > 0) {
        PaginaSEI::getInstance()->prepararPaginacao($objPesquisaPendenciaDTOGerados, $numPaginacaoControle, false, null, 'Gerados');
      }

      $arrObjProcedimentoDTOGerados = $objAtividadeRN->listarPendenciasRN0754($objPesquisaPendenciaDTOGerados);

      if ($numPaginacaoControle > 0) {
        PaginaSEI::getInstance()->processarPaginacao($objPesquisaPendenciaDTOGerados, 'Gerados');
      }

      $numRegistrosRecebidos = InfraArray::contar($arrObjProcedimentoDTORecebidos);
      $numRegistrosGerados = InfraArray::contar($arrObjProcedimentoDTOGerados);

      $arrObjProcedimentoDTO = array_merge($arrObjProcedimentoDTORecebidos, $arrObjProcedimentoDTOGerados);

    }

    $numRegistros = count($arrObjProcedimentoDTO);


    //$numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
    //InfraDebug::getInstance()->gravar('#'.$numSeg.' s');

    $bolAcaoAtribuicaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('procedimento_atribuicao_cadastrar');
    $bolAcaoDefinirAtividade = SessaoSEI::getInstance()->verificarPermissao('procedimento_atualizar_andamento');
    $bolAcaoGerarPendencia = SessaoSEI::getInstance()->verificarPermissao('procedimento_enviar');
    $bolAcaoSobrestarProcesso = SessaoSEI::getInstance()->verificarPermissao('procedimento_sobrestar');
    $bolAcaoConcluirProcesso = SessaoSEI::getInstance()->verificarPermissao('procedimento_concluir');
    $bolAcaoIncluirEmBloco = SessaoSEI::getInstance()->verificarPermissao('rel_bloco_protocolo_cadastrar');
    $bolAcaoRegistrarAnotacao = SessaoSEI::getInstance()->verificarPermissao('anotacao_registrar');
    $bolAcaoCadastrarAcompanhamento = SessaoSEI::getInstance()->verificarPermissao('acompanhamento_cadastrar');
    $bolAcaoDefinirControlePrazo = SessaoSEI::getInstance()->verificarPermissao('controle_prazo_definir');
    $bolAcaoDocumentoGerarMultiplo = SessaoSEI::getInstance()->verificarPermissao('documento_gerar_multiplo');
    $bolAcaoAndamentoSituacaoGerenciar = SessaoSEI::getInstance()->verificarPermissao('andamento_situacao_gerenciar');
    $bolAcaoAndamentoMarcadorGerenciar = SessaoSEI::getInstance()->verificarPermissao('andamento_marcador_gerenciar');
    $bolAcaoAndamentoMarcadorCadastrar = SessaoSEI::getInstance()->verificarPermissao('andamento_marcador_cadastrar');
    $bolAcaoAndamentoMarcadorRemover = SessaoSEI::getInstance()->verificarPermissao('andamento_marcador_remover');
    $bolAcaoUsuarioValidarAcesso  = SessaoSEI::getInstance()->verificarPermissao('usuario_validar_acesso');

    if ($numRegistros > 0) {

      foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
        if ($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo() == ProtocoloRN::$NA_SIGILOSO) {
          $arrIdProcessosSigilosos[] = $objProcedimentoDTO->getDblIdProcedimento();
        }

        if ($objProcedimentoDTO->isSetArrObjAndamentoMarcadorDTO() && $objProcedimentoDTO->getArrObjAndamentoMarcadorDTO()!=null) {
          $arrIdProcessosComMarcador[] = $objProcedimentoDTO->getDblIdProcedimento();
        }
      }


      $numTabBotao = PaginaSEI::getInstance()->getProxTabBarraComandosSuperior();
      if ($bolAcaoGerarPendencia) {
        $arrComandos[] = '<a href="#" onclick="return acaoControleProcessos(\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_enviar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\', true, false);" tabindex="' . $numTabBotao . '" ><img src="'.Icone::PROCESSO_ENVIAR.'" alt="Enviar Processo" title="Enviar Processo"/></a>'."\n";
      }

      if ($bolAcaoDefinirAtividade) {
        $arrComandos[] = '<a href="#" onclick="return acaoControleProcessos(\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_atualizar_andamento&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\', true, true);" tabindex="' . $numTabBotao . '" ><img src="'.Icone::PROCESSO_ATUALIZAR_ANDAMENTO.'" alt="Atualizar Andamento" title="Atualizar Andamento"/></a>'."\n";
      }

      if ($bolAcaoAtribuicaoCadastrar) {
        $arrComandos[] = '<a href="#" onclick="return acaoControleProcessos(\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_atribuicao_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\', true, false);" tabindex="' . $numTabBotao . '" ><img src="'.Icone::PROCESSO_ATRIBUIR.'"  alt="Atribuição de Processos" title="Atribuição de Processos"/></a>'."\n";
      }

      if ($bolAcaoIncluirEmBloco) {
        $strLinkIncluirEmBloco = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=rel_bloco_protocolo_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']);
        $arrComandos[] = '<a href="#" onclick="return acaoBlocoProcessar();" tabindex="' . $numTabBotao . '" ><img src="'.Icone::BLOCO_INCLUIR_PROTOCOLO.'"  alt="Incluir em Bloco" title="Incluir em Bloco"/></a>'."\n";
      }

      if ($bolAcaoSobrestarProcesso) {
        $arrComandos[] = '<a href="#" onclick="return acaoControleProcessos(\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_sobrestar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\', true, false);" tabindex="' . $numTabBotao . '" ><img src="'.Icone::PROCESSO_SOBRESTAR.'"  alt="Sobrestar Processo" title="Sobrestar Processo"/></a>'."\n";
      }

      if ($bolAcaoConcluirProcesso) {
        $arrComandos[] = '<a href="#" onclick="return acaoControleProcessos(\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_concluir&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\', true, true);" tabindex="' . $numTabBotao . '" ><img src="'.Icone::PROCESSO_CONCLUIR.'"  alt="Concluir Processo nesta Unidade" title="Concluir Processo nesta Unidade"/></a>'."\n";
      }

      if ($bolAcaoRegistrarAnotacao) {
        $arrComandos[] = '<a href="#" onclick="return acaoControleProcessos(\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=anotacao_registrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\', true, true);" tabindex="' . $numTabBotao . '" ><img src="'.Icone::ANOTACAO_CADASTRO.'"  alt="Anotações" title="Anotações"/></a>'."\n";
      }

      if ($bolAcaoCadastrarAcompanhamento) {
        $arrComandos[] = '<a href="#" onclick="return acaoControleProcessos(\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acompanhamento_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\', true, true);" tabindex="' . $numTabBotao . '" ><img src="'.Icone::ACOMPANHAMENTO_ESPECIAL_CADASTRO.'"  alt="Acompanhamento Especial" title="Acompanhamento Especial"/></a>'."\n";
      }

      if ($bolAcaoDocumentoGerarMultiplo) {
        $arrComandos[] = '<a href="#" onclick="return acaoControleProcessos(\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_gerar_multiplo&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\', true, true);" tabindex="' . $numTabBotao . '" ><img src="'.Icone::DOCUMENTO_INCLUIR.'"  alt="Incluir Documento" title="Incluir Documento"/></a>'."\n";
      }

      if ($bolAcaoAndamentoSituacaoGerenciar) {
        $objRelSituacaoUnidadeDTO = new RelSituacaoUnidadeDTO();
        $objRelSituacaoUnidadeDTO->retNumIdSituacao();
        $objRelSituacaoUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objRelSituacaoUnidadeDTO->setStrSinAtivoSituacao('S');
        $objRelSituacaoUnidadeDTO->setNumMaxRegistrosRetorno(1);

        $objRelSituacaoUnidadeRN = new RelSituacaoUnidadeRN();

        if ($objRelSituacaoUnidadeRN->consultar($objRelSituacaoUnidadeDTO) != null) {
          $arrComandos[] = '<a href="#" onclick="return acaoControleProcessos(\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=andamento_situacao_gerenciar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\', true, false);" tabindex="' . $numTabBotao . '" ><img src="'.Icone::SITUACAO_GERENCIAR.'"  alt="Gerenciar Ponto de Controle" title="Gerenciar Ponto de Controle"/></a>'."\n";
        }
      }

      if ($bolAcaoAndamentoMarcadorCadastrar) {
        $arrComandos[] = '<a href="#" onclick="return acaoControleProcessos(\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=andamento_marcador_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\', true, true);" tabindex="' . $numTabBotao . '" ><img src="'.Icone::MARCADOR_ADICIONAR.'"  alt="Adicionar Marcador" title="Adicionar Marcador"/></a>'."\n";
      }

      if ($bolAcaoAndamentoMarcadorRemover && InfraArray::contar($arrIdProcessosComMarcador)){
        $arrComandos[] = '<a href="#" onclick="return acaoRemoverMarcadorProcessar(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=andamento_marcador_remover&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\', true, true);" tabindex="'.$numTabBotao.'" ><img src="'.Icone::MARCADOR_REMOVER.'"  alt="Remover Marcador" title="Remover Marcador"/></a>'."\n";
      }

      if ($bolAcaoDefinirControlePrazo) {
        $arrComandos[] = '<a href="#" onclick="return acaoControleProcessos(\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=controle_prazo_definir&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\', true, true);" tabindex="' . $numTabBotao . '" ><img src="'.Icone::CONTROLE_PRAZO_GERENCIAR.'"  alt="Controle de Prazos" title="Controle de Prazos"/></a>'."\n";
      }
    }

    if ($bolAcaoUsuarioValidarAcesso) {
      $objAcessoDTO = new AcessoDTO();
      $objAcessoDTO->retNumIdAcesso();
      $objAcessoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAcessoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $objAcessoDTO->setStrStaTipo(AcessoRN::$TA_CREDENCIAL_PROCESSO);
      $objAcessoDTO->setNumMaxRegistrosRetorno(1);

      $objAcessoRN = new AcessoRN();
      if ($objAcessoRN->consultar($objAcessoDTO) != null) {
        $strLinkCredencialAcessar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=usuario_validar_acesso&acao_origem='.$_GET['acao'].'&acao_destino=procedimento_credencial_listar&acao_negado=procedimento_controlar');
        $arrComandos[] = '<a href="#" onclick="return listarCredenciais();" tabindex="' . $numTabBotao . '" ><img src="'.Icone::CREDENCIAL_CONSULTAR.'"  alt="Processos com Credencial de Acesso nesta Unidade" title="Processos com Credencial de Acesso nesta Unidade"/></a>'."\n";
      }
    }

    foreach ($SEI_MODULOS as $seiModulo) {
      if (($arrRetBotaoIntegracao = $seiModulo->executar('montarBotaoControleProcessos')) != null) {
        foreach ($arrRetBotaoIntegracao as $strBotaoIntegracao) {
          $arrComandos[] = $strBotaoIntegracao;
        }
      }
    }

    $arrRetIconeIntegracao = ProcedimentoINT::montarIconesIntegracaoControleProcessos($arrObjProcedimentoDTO);

    if ($numRegistros > 0) {

      $arrProtocolosVisitados = SessaoSEI::getInstance()->getAtributo('PROTOCOLOS_VISITADOS_' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual());

      if ($strTipoVisualizacao == ProcedimentoINT::$TV_RESUMIDA) {

        $numCheckRecebidos = 0;
        $numCheckGerados = 0;

        $strRecebidos = '';
        $strGerados = '';


        $strResultadoRecebidos .= '<table id="tblProcessosRecebidos" width="100%" border="0" cellspacing="0" cellpadding="1" class="infraTable tabelaControle" summary="Tabela de Processos Recebidos.">' . "\n";
        $strResultadoRecebidos .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela('', $numRegistrosRecebidos, '', 'Recebidos') . '</caption>';
        $strResultadoRecebidos .= '<tr>';
        $strResultadoRecebidos .= '<th class="infraTh" width="5%" align="center">' . PaginaSEI::getInstance()->getThCheck('', 'Recebidos', '', false) . '</th>' . "\n";
        $strResultadoRecebidos .= '<th class="infraTh" colspan="3">Recebidos</th>' . "\n";
        $strResultadoRecebidos .= '</tr>' . "\n";

        for ($i = 0; $i < $numRegistrosRecebidos; $i++) {

          $objProcedimentoDTO = $arrObjProcedimentoDTORecebidos[$i];

          $strImagemStatus = '';
          $strLinkUsuarioAtribuicao = '&nbsp;';
          $dblIdProcedimento = $objProcedimentoDTO->getDblIdProcedimento();

          ProcedimentoINT::processarControleProcessos($objProcedimentoDTO, $bolAcaoRegistrarAnotacao,$bolAcaoCadastrarAcompanhamento, $bolAcaoDefinirControlePrazo, $bolAcaoAndamentoSituacaoGerenciar, $bolAcaoAndamentoMarcadorGerenciar, $arrProtocolosVisitados, $arrRetIconeIntegracao, $strImagemStatus, $strLinkUsuarioAtribuicao, $strLinkProcesso, $strTextoCheckBox, true);

          $strRecebidos .= '<tr id="P' . $dblIdProcedimento . '" class="infraTrClara">' . "\n";

          $strRecebidos .= '<td align="center">';
          $strRecebidos .= PaginaSEI::getInstance()->getTrCheck($numCheckRecebidos++, $dblIdProcedimento, $strTextoCheckBox, 'N', 'Recebidos');
          $strRecebidos .= '</td>' . "\n";

          $strRecebidos .= '<td align="center" width="20%">' . $strImagemStatus . '</td>' . "\n";
          $strRecebidos .= '<td align="center">' . $strLinkProcesso . '</td>' . "\n";
          $strRecebidos .= '<td align="center" width="10%">' . $strLinkUsuarioAtribuicao . '</td>' . "\n";
          $strRecebidos .= '</tr>' . "\n";
        }

        $strResultadoRecebidos .= $strRecebidos;
        $strResultadoRecebidos .= '</table>';

        $strResultadoGerados .= '<table id="tblProcessosGerados" width="100%" border="0" cellspacing="0" cellpadding="1" class="infraTable tabelaControle" summary="Tabela de Processos Gerados.">' . "\n";
        $strResultadoGerados .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela('', $numRegistrosGerados, '', 'Gerados') . '</caption>';
        $strResultadoGerados .= '<tr>';
        $strResultadoGerados .= '<th class="infraTh" width="5%" align="center">' . PaginaSEI::getInstance()->getThCheck('', 'Gerados', '', false) . '</th>' . "\n";
        $strResultadoGerados .= '<th class="infraTh" colspan="3">Gerados</th>' . "\n";
        $strResultadoGerados .= '</tr>' . "\n";

        for ($i = 0; $i < $numRegistrosGerados; $i++) {

          $objProcedimentoDTO = $arrObjProcedimentoDTOGerados[$i];

          $strImagemStatus = '';
          $strLinkUsuarioAtribuicao = '';
          $dblIdProcedimento = $objProcedimentoDTO->getDblIdProcedimento();

          ProcedimentoINT::processarControleProcessos($objProcedimentoDTO, $bolAcaoRegistrarAnotacao, $bolAcaoCadastrarAcompanhamento, $bolAcaoDefinirControlePrazo, $bolAcaoAndamentoSituacaoGerenciar, $bolAcaoAndamentoMarcadorGerenciar, $arrProtocolosVisitados, $arrRetIconeIntegracao, $strImagemStatus, $strLinkUsuarioAtribuicao, $strLinkProcesso, $strTextoCheckBox, true);

          $strGerados .= '<tr id="P' . $dblIdProcedimento . '" class="infraTrClara">' . "\n";

          $strGerados .= '<td align="center">';
          $strGerados .= PaginaSEI::getInstance()->getTrCheck($numCheckGerados++, $dblIdProcedimento, $strTextoCheckBox, 'N', 'Gerados');
          $strGerados .= '</td>' . "\n";

          $strGerados .= '<td align="center" width="20%">' . $strImagemStatus . '</td>' . "\n";
          $strGerados .= '<td align="center">' . $strLinkProcesso . '</td>' . "\n";
          $strGerados .= '<td align="center" width="10%">' . $strLinkUsuarioAtribuicao . '</td>' . "\n";
          $strGerados .= '</tr>' . "\n";
        }

        $strResultadoGerados .= $strGerados;
        $strResultadoGerados .= '</table>';

      } else {

        $numRegistrosDetalhado = $numRegistros;


        $strResultadoDetalhado .= '<table id="tblProcessosDetalhado"  border="0" cellspacing="0" cellpadding="1" width="99%" class="infraTable tabelaControle" summary="Tabela de Processos.">'."\n";

        $strResultadoDetalhado .= '<caption style="padding-bottom:.4em;" class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionDetalhada, $numRegistrosDetalhado).'</caption>';

        $strResultadoDetalhado .= '<tr>';
        $strResultadoDetalhado .= '<th class="infraTh" width="1%" align="center">'.PaginaSEI::getInstance()->getThCheck('', 'Detalhado', '', false).'</th>'."\n";
        $strResultadoDetalhado .= '<th class="infraTh" width="8%">&nbsp;</th>'."\n";

        $strResultadoDetalhado .= '<th class="infraTh" width="18%">'.PaginaSEI::getInstance()->getThOrdenacao($objAtividadeDTOOrdenacao, 'Processo', 'IdProtocolo', $arrObjProcedimentoDTO).'</th>'."\n";

        if ($objPainelControleDTO->getStrSinNivelAtribuicao() == 'S') {
          $strResultadoDetalhado .= '<th class="infraTh" width="8%">'.PaginaSEI::getInstance()->getThOrdenacao($objAtividadeDTOOrdenacao, 'Atribuição', 'SiglaUsuarioAtribuicao', $arrObjProcedimentoDTO).'</th>'."\n";
        }

        if ($objPainelControleDTO->getStrSinNivelAnotacao() == 'S') {
          $strResultadoDetalhado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objAtividadeDTOOrdenacao, 'Anotação', 'DescricaoAnotacao', $arrObjProcedimentoDTO).'</th>'."\n";
        }

        if ($objPainelControleDTO->getStrSinNivelTipoProcesso() == 'S') {
          $strResultadoDetalhado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objAtividadeDTOOrdenacao, 'Tipo', 'NomeTipoProcedimentoProtocolo', $arrObjProcedimentoDTO).'</th>'."\n";
        }

        if ($objPainelControleDTO->getStrSinNivelEspecificacao() == 'S') {
          $strResultadoDetalhado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objAtividadeDTOOrdenacao, 'Especificação', 'DescricaoProtocolo', $arrObjProcedimentoDTO).'</th>'."\n";
        }

        if ($objPainelControleDTO->getStrSinNivelInteressados() == 'S') {
          $strResultadoDetalhado .= '<th class="infraTh">Interessados</th>'."\n";
        }

        if ($objPainelControleDTO->getStrSinNivelObservacao() == 'S') {
          $strResultadoDetalhado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objAtividadeDTOOrdenacao, 'Observação', 'DescricaoObservacao', $arrObjProcedimentoDTO).'</th>'."\n";
        }

        if ($objPainelControleDTO->getStrSinNivelControlePrazo() == 'S') {
          $strResultadoDetalhado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objAtividadeDTOOrdenacao, '<img '.PaginaSEI::montarTitleTooltip('Controle de Prazo').' src="'.Icone::CONTROLE_PRAZO_TABELA.'" class="infraImg" />', 'PrazoControlePrazo', $arrObjProcedimentoDTO).'</th>'."\n";
        }

        if ($objPainelControleDTO->getStrSinNivelRetornoDevolver() == 'S') {
          $strResultadoDetalhado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objAtividadeDTOOrdenacao, '<img '.PaginaSEI::montarTitleTooltip('Para Devolver').' src="'.Icone::RETORNO_PROGRAMADO_TABELA.'" class="infraImg" />', 'IdUnidadeRetornoRetornoProgramado', $arrObjProcedimentoDTO).'</th>'."\n";
        }

        if ($objPainelControleDTO->getStrSinNivelRetornoAguardando() == 'S') {
          $strResultadoDetalhado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objAtividadeDTOOrdenacao, '<img '.PaginaSEI::montarTitleTooltip('Aguardando Retorno').' src="'.Icone::RETORNO_AGUARDANDO_TABELA.'" class="infraImg" />', 'IdUnidadeEnvioRetornoProgramado', $arrObjProcedimentoDTO).'</th>'."\n";
        }

        if ($objPainelControleDTO->getStrSinNivelUltimaMovimentacao() == 'S') {
          $strResultadoDetalhado .= '<th width="10%" class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objAtividadeDTOOrdenacao,'Última&nbsp;Mov.','IdAtividade',$arrObjProcedimentoDTO).'</th>'."\n";
        }

        if ($objPainelControleDTO->getStrSinNivelMarcadores() == 'S') {
          $strResultadoDetalhado .= '<th class="infraTh">Marcadores</th>'."\n";
        }

        $strResultadoDetalhado .= '<th class="infraTh">&nbsp;</th>'."\n";

        $strResultadoDetalhado .= '</tr>'."\n";

        $numCheck = 0;

        for ($i = 0; $i < $numRegistrosDetalhado; $i++) {

          $objProcedimentoDTO = $arrObjProcedimentoDTO[$i];

          $strImagemStatus = '';
          $strLinkUsuarioAtribuicao = '';
          $dblIdProcedimento = $objProcedimentoDTO->getDblIdProcedimento();

          $objAnotacaoDTO = null;
          if ($objPainelControleDTO->getStrSinNivelAnotacao() == 'S' && $objProcedimentoDTO->getObjAnotacaoDTO() != null && $objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo() != ProtocoloRN::$NA_SIGILOSO) {
            $objAnotacaoDTO = $objProcedimentoDTO->getObjAnotacaoDTO();
            $objProcedimentoDTO->setObjAnotacaoDTO(null);
          }

          ProcedimentoINT::processarControleProcessos($objProcedimentoDTO, $bolAcaoRegistrarAnotacao, $bolAcaoCadastrarAcompanhamento, $bolAcaoDefinirControlePrazo,  $bolAcaoAndamentoSituacaoGerenciar, $bolAcaoAndamentoMarcadorGerenciar, $arrProtocolosVisitados, $arrRetIconeIntegracao, $strImagemStatus, $strLinkUsuarioAtribuicao, $strLinkProcesso, $strTextoCheckBox, ($objPainelControleDTO->getStrSinNivelMarcadores() != 'S'));

          $strResultadoDetalhado .= '<tr id="P'.$dblIdProcedimento.'" class="infraTrClara">';

          $strResultadoDetalhado .= '<td align="center" valign="top">';
          $strResultadoDetalhado .= PaginaSEI::getInstance()->getTrCheck($numCheck++, $dblIdProcedimento, $strTextoCheckBox, 'N', 'Detalhado');
          $strResultadoDetalhado .= '</td>'."\n";

          $strResultadoDetalhado .= '<td align="center" valign="top">'.$strImagemStatus.'</td>'."\n";

          $strResultadoDetalhado .= '<td align="center" valign="top">'.$strLinkProcesso.'</td>'."\n";

          if ($objPainelControleDTO->getStrSinNivelAtribuicao() == 'S') {
            $strResultadoDetalhado .= '<td align="center" valign="top">'.str_replace(array('(',')'),'',$strLinkUsuarioAtribuicao).'</td>'."\n";
          }

          if ($objPainelControleDTO->getStrSinNivelAnotacao() == 'S') {

            $strResultadoDetalhado .= '<td align="left" valign="top">';
            if ($objAnotacaoDTO == null){
              $strResultadoDetalhado .= '&nbsp;';
            }else{

              $strCssPrioridade = '';
              if ($objAnotacaoDTO->getStrSinPrioridade()=='S'){
                $strCssPrioridade = 'color:#ed1c24;';
              }

              $strResultadoDetalhado .= '<span style="'.$strCssPrioridade.'">'.PaginaSEI::tratarHTML($objAnotacaoDTO->getStrDescricao()).'</span>';
            }
            $strResultadoDetalhado .= '</td>';
          }

          if ($objPainelControleDTO->getStrSinNivelTipoProcesso() == 'S') {
            $strResultadoDetalhado .= '<td align="left" valign="top">'.PaginaSEI::tratarHTML($objProcedimentoDTO->getStrNomeTipoProcedimento()).'</td>';
          }

          if ($objPainelControleDTO->getStrSinNivelEspecificacao() == 'S') {
            $strResultadoDetalhado .= '<td align="left" valign="top">';
            if ($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo() != ProtocoloRN::$NA_SIGILOSO) {
              $strResultadoDetalhado .= PaginaSEI::tratarHTML($objProcedimentoDTO->getStrDescricaoProtocolo());
            }else{
              $strResultadoDetalhado .= '&nbsp;';
            }
            $strResultadoDetalhado .= '</td>';
          }

          if ($objPainelControleDTO->getStrSinNivelInteressados() == 'S') {

            $arrObjParticipanteDTO = null;

            if ($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo() != ProtocoloRN::$NA_SIGILOSO) {
              $arrObjParticipanteDTO = $objProcedimentoDTO->getArrObjParticipanteDTO();
            }

            $strParticipantes = '';
            if ($arrObjParticipanteDTO == null) {
              $strParticipantes = '&nbsp;';
            }else{
              $numParticipantes = InfraArray::contar($arrObjParticipanteDTO);
              for ($j = 0; $j < $numParticipantes; $j++) {
                $strParticipantes .=  SeiINT::montarItemCelula($arrObjParticipanteDTO[$j]->getStrNomeContato(),'Interessado');
              }
            }
            $strResultadoDetalhado .= '<td align="left"  valign="top">'.'<div>'.$strParticipantes.'</div>'.'</td>';
          }

          if ($objPainelControleDTO->getStrSinNivelObservacao() == 'S') {

            $objObservacaoDTO = null;

            if ($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo() != ProtocoloRN::$NA_SIGILOSO) {
              $objObservacaoDTO = $objProcedimentoDTO->getObjObservacaoDTO();
            }

            $strResultadoDetalhado .= '<td align="left" valign="top">';
            if ($objObservacaoDTO == null){
              $strResultadoDetalhado .= '&nbsp;';
            }else{
              $strResultadoDetalhado .= PaginaSEI::tratarHTML($objObservacaoDTO->getStrDescricao());
            }
            $strResultadoDetalhado .= '</td>';
          }

          if ($objPainelControleDTO->getStrSinNivelControlePrazo() == 'S') {
            $strResultadoDetalhado .= '<td align="center"  valign="top">';
            $objControlePrazoDTO = $objProcedimentoDTO->getObjControlePrazoDTO();
            if ($objControlePrazoDTO != null && $objControlePrazoDTO->getDtaConclusao()==null) {
              $strResultadoDetalhado .= InfraData::compararDatas(InfraData::getStrDataHoraAtual(), $objControlePrazoDTO->getDtaPrazo());
            }
            $strResultadoDetalhado .= '</td>';
          }

          if ($objPainelControleDTO->getStrSinNivelRetornoDevolver() == 'S') {
            $strResultadoDetalhado .= '<td align="center"  valign="top">';
            $arrObjRetornoProgramadoDTO = $objProcedimentoDTO->getArrObjRetornoProgramadoDTO();
            if (InfraArray::contar($arrObjRetornoProgramadoDTO)) {
              foreach ($arrObjRetornoProgramadoDTO as $objRetornoProgramadoDTO) {
                if ($objRetornoProgramadoDTO->getNumIdUnidadeRetorno() == SessaoSEI::getInstance()->getNumIdUnidadeAtual() && $objRetornoProgramadoDTO->getNumIdAtividadeRetorno() == null) {
                  $strResultadoDetalhado .= InfraData::compararDatas(InfraData::getStrDataHoraAtual(), $arrObjRetornoProgramadoDTO[0]->getDtaProgramada());
                  break;
                }
              }
            }
            $strResultadoDetalhado .= '</td>';
          }

          if ($objPainelControleDTO->getStrSinNivelRetornoAguardando() == 'S') {
            $strResultadoDetalhado .= '<td align="center"  valign="top">';
            $arrObjRetornoProgramadoDTO = $objProcedimentoDTO->getArrObjRetornoProgramadoDTO();
            if (InfraArray::contar($arrObjRetornoProgramadoDTO)) {
              foreach ($arrObjRetornoProgramadoDTO as $objRetornoProgramadoDTO) {
                if ($objRetornoProgramadoDTO->getNumIdUnidadeEnvio() == SessaoSEI::getInstance()->getNumIdUnidadeAtual() && $objRetornoProgramadoDTO->getNumIdAtividadeRetorno() == null) {
                  $strResultadoDetalhado .= InfraData::compararDatas(InfraData::getStrDataHoraAtual(), $arrObjRetornoProgramadoDTO[0]->getDtaProgramada());
                  break;
                }
              }
            }
            $strResultadoDetalhado .= '</td>';
          }


          if ($objPainelControleDTO->getStrSinNivelUltimaMovimentacao() == 'S') {
            $arrObjAtividadeDTO = $objProcedimentoDTO->getArrObjAtividadeDTO();
            $strResultadoDetalhado .= '<td align="center"  valign="top">'.InfraData::compararDatas($arrObjAtividadeDTO[0]->getDthAbertura(),InfraData::getStrDataHoraAtual()).'</td>';
          }

          if ($objPainelControleDTO->getStrSinNivelMarcadores() == 'S') {

            $arrObjAndamentoMarcadorDTO = $objProcedimentoDTO->getArrObjAndamentoMarcadorDTO();

            $strResultadoDetalhado .= '<td align="left" valign="top">';
            if ($arrObjAndamentoMarcadorDTO == null){
              $strResultadoDetalhado .= '&nbsp;';
            }else{

              foreach($arrObjAndamentoMarcadorDTO as $objAndamentoMarcadorDTO) {

                if ($bolAcaoAndamentoMarcadorGerenciar) {
                  $strLinkGerenciarMarcador = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=andamento_marcador_gerenciar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$objProcedimentoDTO->getDblIdProcedimento().PaginaSEI::montarAncora($objAndamentoMarcadorDTO->getNumIdMarcador()));
                } else {
                  $strLinkGerenciarMarcador = 'javascript:void(0);';
                }

                $strMarcador = MarcadorINT::formatarMarcadorDesativado($objAndamentoMarcadorDTO->getStrNomeMarcador(), $objAndamentoMarcadorDTO->getStrSinAtivoMarcador());

                $strStyleMarcador = '';
                if ($objAndamentoMarcadorDTO->getNumIdMarcador() == $numIdMarcadorPainel){
                  $strStyleMarcador = 'style="border-color: #666 !important;"';
                }

                $strResultadoDetalhado .= '<a class="ancMarcador" href="'.$strLinkGerenciarMarcador.'" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" '.PaginaSEI::montarTitleTooltip($objAndamentoMarcadorDTO->getStrTexto()).'>'.
                  '<div><img src="'.$objAndamentoMarcadorDTO->getStrArquivoIconeMarcador().'" class="imagemStatus"/></div>'.
                  '<div>'.PaginaSEI::tratarHTML($strMarcador).'</div>'.
                  '</a>';

              }
            }
            $strResultadoDetalhado .= '</td>';
          }

          $strResultadoDetalhado .= '<td>&nbsp;</td>';

          $strResultadoDetalhado .= '</tr>'."\n";
        }

        $strResultadoDetalhado .= '</table>';

        $strResultadoDetalhado = '<div id="divTabelaDetalhado">'.$strResultadoDetalhado.'</div>';
      }
    }
  }


  //$arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

  $strLinkTipoVisualizacao = '';
  $strLinkNivelDetalhe = '';
  $strLinkMeusProcessos = '';
  $strLinkFiltroMarcadores = '';
  $strLinkFiltroTipoProcesso = '';

  $bolAcaoControlarVisualizacao = SessaoSEI::getInstance()->verificarPermissao('procedimento_controlar_visualizacao');
  if ($bolAcaoControlarVisualizacao && $strTipoFiltro==null){
    $strLinkTipoVisualizacao = "\n".'<div class="divLink">';
    if ($strTipoVisualizacao == ProcedimentoINT::$TV_RESUMIDA) {
      $strLinkTipoVisualizacao .= '<a href="javascript:void(0);" onclick="trocarVisualizacao(\''.ProcedimentoINT::$TV_DETALHADA.'\');" class="ancoraPadraoPreta" tabindex="'.PaginaSEI::getInstance()->getProxTabBarraComandosSuperior().'">Visualização detalhada</a>';
    } else {
      $strLinkTipoVisualizacao .= '<a href="javascript:void(0);" onclick="trocarVisualizacao(\''.ProcedimentoINT::$TV_RESUMIDA.'\');" class="ancoraPadraoPreta" tabindex="'.PaginaSEI::getInstance()->getProxTabBarraComandosSuperior().'">Visualização resumida</a>';
    }
    $strLinkTipoVisualizacao .= '</div>'."\n";
  }

  if ($strTipoVisualizacao == ProcedimentoINT::$TV_DETALHADA) {
    if ($numRegistrosDetalhado) {
      $strLinkNivelDetalhe = "\n".'<div class="divLink">';
      $bolAcaoConfigurarDetalhe = SessaoSEI::getInstance()->verificarPermissao('procedimento_configurar_detalhe');
      if ($bolAcaoConfigurarDetalhe) {
        $strLinkNivelDetalhe .= '<a id="ancNivelDetalhe" href="javascript:void(0);" onclick="acaoConfigurarDetalhe(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_configurar_detalhe&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\');" class="ancoraPadraoPreta" tabindex="'.PaginaSEI::getInstance()->getProxTabBarraComandosSuperior().'">Configurar nível de detalhe</a>';
      }
      $strLinkNivelDetalhe .= '</div>'."\n";
    }
  }

  if ($bolOpcoesFiltro) {
    $strLinkMeusProcessos = "\n".'<div class="divLink ml-0 ml-md-2 ">';
    if ($objPesquisaPendenciaDTO->getStrStaTipoAtribuicao() != AtividadeRN::$TA_MINHAS) {
      $strLinkMeusProcessos .= '<a href="javascript:void(0);" onclick="verMeusProcessos(\''.AtividadeRN::$TA_MINHAS.'\');" class="ancoraPadraoPreta" tabindex="'.PaginaSEI::getInstance()->getProxTabBarraComandosSuperior().'">Ver processos atribuídos a mim</a>';
    } else {
      $strLinkMeusProcessos .= "\n".'<div id="divFiltroMeusProcessos" class="caixaFiltro">';
      $strLinkMeusProcessos .= '<a id="ancLiberarMeusProcessos" class="botaoFecharFiltro" title="'.PaginaSEI::tratarHTML('Remover filtro de processos atribuídos a mim').'"></a><p>Processos atribuídos a mim</p>';
      $strLinkMeusProcessos .= '</div>'."\n";
    }
    $strLinkMeusProcessos .= '</div>'."\n";

    $strLinkFiltroMarcadores = "\n".'<div class="divLink">';
    if ($objMarcadorDTOFiltro == null) {
      if ($strTipoFiltro != ProcedimentoINT::$TF_MARCADORES) {
        $strLinkFiltroMarcadores .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem='.$_GET['acao'].'&tipo_filtro='.ProcedimentoINT::$TF_MARCADORES).'" class="ancoraPadraoPreta" tabindex="'.PaginaSEI::getInstance()->getProxTabBarraComandosSuperior().'">Ver por marcadores</a>';
      } else {
        $strLinkFiltroMarcadores .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem='.$_GET['acao']).'" class="ancoraPadraoPreta" tabindex="'.PaginaSEI::getInstance()->getProxTabBarraComandosSuperior().'">Ver por processos</a>';
      }
    } else {
      $arrObjIconeMarcadorDTO = InfraArray::indexarArrInfraDTO($objMarcadorRN->listarValoresIcone(), 'StaIcone');
      $strLinkFiltroMarcadores .= "\n".'<div id="divFiltroMarcador" class="caixaFiltro">';
      $strLinkFiltroMarcadores .= '<a id="ancLiberarMarcador" class="botaoFecharFiltro" title="'.PaginaSEI::tratarHTML('Remover filtro pelo marcador').'"></a><img src="'.$arrObjIconeMarcadorDTO[$objMarcadorDTOFiltro->getStrStaIcone()]->getStrArquivo().'" class="infraImg" /><p>&nbsp;'.PaginaSEI::tratarHTML($objMarcadorDTOFiltro->getStrNome()).'</p>';
      $strLinkFiltroMarcadores .= '</div>'."\n";
    }
    $strLinkFiltroMarcadores .= '</div>'."\n";

    $strLinkFiltroTipoProcesso = "\n".'<div class="divLink">';
    if ($objTipoProcedimentoDTOFiltro == null) {
      if ($strTipoFiltro != ProcedimentoINT::$TF_TIPO_PROCEDIMENTO) {
        $strLinkFiltroTipoProcesso .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem='.$_GET['acao'].'&tipo_filtro='.ProcedimentoINT::$TF_TIPO_PROCEDIMENTO).'" class="ancoraPadraoPreta" tabindex="'.PaginaSEI::getInstance()->getProxTabBarraComandosSuperior().'">Ver por tipo de processo</a>';
      } else {
        $strLinkFiltroTipoProcesso .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem='.$_GET['acao']).'" class="ancoraPadraoPreta" tabindex="'.PaginaSEI::getInstance()->getProxTabBarraComandosSuperior().'">Ver por processos</a>';
      }
    } else {
      $strLinkFiltroTipoProcesso .= "\n".'<div id="divFiltroTipoProcesso" class="caixaFiltro">';
      $strLinkFiltroTipoProcesso .= '<a id="ancLiberarTipoProcedimento" class="botaoFecharFiltro" title="'.PaginaSEI::tratarHTML('Remover filtro pelo tipo de processo').'"></a><p>'.PaginaSEI::tratarHTML($objTipoProcedimentoDTOFiltro->getStrNome()).'</p>';
      $strLinkFiltroTipoProcesso .= '</div>'."\n";
    }
    $strLinkFiltroTipoProcesso .= '</div>'."\n";
  }

  $strLinkBlocoPesquisaSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_selecionar_processo&tipo_selecao=1&id_object=objLupaBlocoPesquisa');

  //$numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
  //InfraDebug::getInstance()->gravar($numSeg.' s');

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
  @media only print {

    #divInfraAreaTelaD,
    #divTabelaProcesso,
    #divTabelaProcesso div{
      display:block;
      min-height:100%;
      width: auto !important;
      height: auto !important;
      overflow: visible !important;
    }

    #divInfraAreaTelaE,
    #divComandos,
    #divFiltro,
    div.infraAreaPaginacao{
      display:none !important;
    }

    a {
      text-decoration:none !important;
    }
  }

  #divFiltro{
  margin: 15px 0 2px 0;
  }

  .caixaFiltro{
  background-color:#fff;
  color:#7F7F7F;
  padding: 2px 6px;
  border-radius: 4px;
  box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, .3), 0 0.0625rem 0.125rem rgba(0, 0, 0, .2);
  z-index:101;
  }

  div.caixaFiltro p{
  display:inline;
  vertical-align:top;
  font-size:0.875rem;
  line-height:24px;
  }

  div.caixaFiltro img{
  vertical-align:bottom;
  }

  a.botaoFecharFiltro,
  a.botaoFecharBanner{
  cursor:pointer;
  color: #fff;
  border-radius: 10px;
  background: #605F61;
  font-size: 16px;
  font-weight: bold;
  display: inline-block;
  line-height: 1px;
  padding: 9px 5px;
  }

  a.botaoFecharFiltro{
  float:right;
  margin-top:-9px;
  margin-right:-15px;
  }

  a.botaoFecharBanner{
  vertical-align:top;
  margin-right:-10px;
  }

  .botaoFecharFiltro:before,
  .botaoFecharBanner:before {
    content: "×";
  }

  table.tabelaControle,
  tr.infraTrClara td  {
  border:0;
  }

  a.ancMarcador{
  font-size: 0.875rem;
  text-decoration:none !important;
  border:1px solid #d0d0d0;
  padding:4px;
  margin:2px;
  -moz-border-radius: 4px;
  border-radius: 4px;
  -webkit-border-radius: 4px;
  float:left;
  background-color:#f5f5f5;
  }

  a.ancMarcador img{
  padding: 0 4px 0 0;
  }

  a.ancMarcador div{
  display:table-cell;
  }


  .divLink .ancoraPadraoPreta{
  padding: 0px;
  }


  .divLink:not(:first-child){
  margin-left:2.5em !important;
  }

  .divLink:last-child{
  margin-right:15px;
  }

  #divInfraBtnTopo{
  flex: 0 0 100%;
  max-width: 100%;
  }

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script>


  $( document ).ready(function() {

    $('#ancLiberarMeusProcessos').click(function(){
      verMeusProcessos('<?=AtividadeRN::$TA_TODAS?>')
    });

    $('#ancLiberarMarcador').click(function(){
      filtrarMarcador(null);
    });

    $('#ancLiberarTipoProcedimento').click(function(){
      filtrarTipoProcedimento(null);
    });

  });


  var objLupaBlocoPesquisa = null;
  var bolCarregando = true;

  function inicializar(){

    $('#divInfraBarraLocalizacao').prependTo( $('#divControleProcessosConteudo') );

    if($('#divInfraBarraAcesso').length){
      $('#divInfraBarraAcesso').prependTo( $('#divControleProcessosConteudo') );
    }

    if (infraIsBreakpointBootstrap("lg")){
  <? if (isset($_GET['inicializando'])){ ?>
    infraExibirMenuSistemaEsquema();
  <? }else{ ?>
    infraMenuSistemaEsquema(true);
  <? } ?>
    }

  <?=$strJsAviso?>
  <?=$strJsNovidades?>

    objLupaBlocoPesquisa = new infraLupaText('txtBloco','hdnIdBloco','<?=$strLinkBlocoPesquisaSelecao?>');
    objLupaBlocoPesquisa.finalizarSelecao = function(){
      document.getElementById('frmProcedimentoControlar').action = '<?=$strLinkIncluirEmBloco?>';
      document.getElementById('frmProcedimentoControlar').submit();
    };

    infraEfeitoTabelas();
  }

  function acaoPendenciaMultipla(bolMsg){
    if ('<?=$strTipoVisualizacao?>' == '<?=ProcedimentoINT::$TV_RESUMIDA?>'){
      if (document.getElementById('hdnGeradosItensSelecionados').value=='' && document.getElementById('hdnRecebidosItensSelecionados').value==''){
        if (bolMsg){
          alert('Nenhum processo selecionado.');
        }
        return false;
      }
      document.getElementById('hdnGeradosItemId').value = '';
      document.getElementById('hdnRecebidosItemId').value = '';
    }else{
      if (document.getElementById('hdnDetalhadoItensSelecionados').value==''){
        if (bolMsg){
          alert('Nenhum processo selecionado.');
        }
        return false;
      }
      document.getElementById('hdnDetalhadoItemId').value = '';
    }

    return true;
  }

  function acaoControleProcessos(link, requerSelecionado, aceitaSigiloso){
    if ((!requerSelecionado || acaoPendenciaMultipla(true)) && (aceitaSigiloso || !bloquearSigilosoSelecionado())){
      document.getElementById('frmProcedimentoControlar').action = link;
      document.getElementById('frmProcedimentoControlar').submit();
    }
  }

  function acaoBlocoProcessar(){
    if (acaoPendenciaMultipla(true) && !bloquearSigilosoSelecionado()){
      document.getElementById('txtBloco').value = '';
      document.getElementById('hdnIdBloco').value = '';
      objLupaBlocoPesquisa.selecionar(700,500);
    }
  }

  function acaoRemoverMarcadorProcessar(link, requerSelecionado, aceitaSigiloso){
    if (!bloquearNenhumComMarcadorSelecionado()) {
      acaoControleProcessos(link, requerSelecionado, aceitaSigiloso);
    }
  }

  function bloquearSigilosoSelecionado(){

    var sigilosos = document.getElementById('hdnIdSigilosos').value;

    if (sigilosos!='') {

      selecionados = '';

      if ('<?=$strTipoVisualizacao?>' == '<?=ProcedimentoINT::$TV_RESUMIDA?>') {

        if (document.getElementById('hdnGeradosItensSelecionados').value!='') {
          selecionados = document.getElementById('hdnGeradosItensSelecionados').value;
        }

        if (document.getElementById('hdnRecebidosItensSelecionados').value!='') {
          if (selecionados!='') {
            selecionados += ',';
          }
          selecionados += document.getElementById('hdnRecebidosItensSelecionados').value;
        }

      } else {
        selecionados = document.getElementById('hdnDetalhadoItensSelecionados').value;
      }

      if (selecionados!='') {

        sigilosos = sigilosos.split(',');
        selecionados = selecionados.split(',');

        for (var i = 0; i<sigilosos.length; i++) {
          for (var j = 0; j<selecionados.length; j++) {
            if (sigilosos[i]==selecionados[j]) {
              alert('Operação não aplicável em processo sigiloso.');
              return true;
            }
          }
        }
      }
    }
    return false;
  }

  function bloquearNenhumComMarcadorSelecionado(){

    var commarcador = document.getElementById('hdnIdComMarcador').value;

    if (commarcador!='') {

      selecionados = '';

      if ('<?=$strTipoVisualizacao?>' == '<?=ProcedimentoINT::$TV_RESUMIDA?>') {

        if (document.getElementById('hdnGeradosItensSelecionados').value!='') {
          selecionados = document.getElementById('hdnGeradosItensSelecionados').value;
        }

        if (document.getElementById('hdnRecebidosItensSelecionados').value!='') {
          if (selecionados!='') {
            selecionados += ',';
          }
          selecionados += document.getElementById('hdnRecebidosItensSelecionados').value;
        }

      } else {
        selecionados = document.getElementById('hdnDetalhadoItensSelecionados').value;
      }

      if (selecionados!='') {

        commarcador = commarcador.split(',');
        selecionados = selecionados.split(',');

        for (var i = 0; i<commarcador.length; i++) {
          for (var j = 0; j<selecionados.length; j++) {
            if (commarcador[i]==selecionados[j]) {
              return false;
            }
          }
        }
      }
    }
    alert('Nenhum processo com marcador selecionado.');
    return true;
  }


  function listarCredenciais(){
    infraAbrirJanelaModal('<?=$strLinkCredencialAcessar?>',500,300);
  }

  function trocarVisualizacao(valor){
    document.getElementById('hdnTipoVisualizacao').value = valor;
    document.getElementById('frmProcedimentoControlar').submit();
  }

  function verMeusProcessos(valor){
    document.getElementById('hdnMeusProcessos').value = valor;
    document.getElementById('frmProcedimentoControlar').submit();
  }

  function filtrarMarcador(idMarcador){
    document.getElementById('<?=$strIdHdnMarcador?>').value = idMarcador;
    if (idMarcador==null){
      document.getElementById('frmProcedimentoControlar').action = '<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem='.$_GET['acao'].'&tipo_visualizacao='.$strTipoVisualizacao.'&tipo_filtro='.($strTipoFiltro==null ? ProcedimentoINT::$TF_MARCADORES : $strTipoFiltro)).PaginaSEI::montarAncora($numIdMarcadorFiltro)?>';
    }else{
      document.getElementById('frmProcedimentoControlar').action = '<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem='.$_GET['acao'].'&tipo_visualizacao='.$strTipoVisualizacao).PaginaSEI::montarAncora($_POST[$strIdHdnMarcador])?>';
    }

    document.getElementById('frmProcedimentoControlar').submit();
  }

  function filtrarTipoProcedimento(idTipoProcedimento){
    document.getElementById('<?=$strIdHdnTipoProcedimento?>').value = idTipoProcedimento;

    if (idTipoProcedimento == null){
      document.getElementById('frmProcedimentoControlar').action = '<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem='.$_GET['acao'].'&tipo_visualizacao='.$strTipoVisualizacao.'&tipo_filtro='.($strTipoFiltro==null ? ProcedimentoINT::$TF_TIPO_PROCEDIMENTO : $strTipoFiltro)).PaginaSEI::montarAncora($numIdTipoProcedimentoFiltro)?>';
    }else{
      document.getElementById('frmProcedimentoControlar').action = '<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem='.$_GET['acao'].'&tipo_visualizacao='.$strTipoVisualizacao).PaginaSEI::montarAncora($_POST[$strIdHdnTipoProcedimento])?>';
    }

    document.getElementById('frmProcedimentoControlar').submit();
  }

  function alterarVisualizacaoTabela(campo, valor, campoEsconder){
    document.getElementById(campo).value = valor;
    document.getElementById(campoEsconder).value = "false";
    document.getElementById('frmProcedimentoControlar').submit();
  }

<? if ($bolAcaoConfigurarDetalhe){ ?>
  function acaoConfigurarDetalhe(link){
    infraAbrirJanelaModal(link,500,500);
  }
<? } ?>

  function fecharBanner(chave){
    $("#divBanner").removeClass('d-md-block');
    infraCriarCookie('<?=PaginaSEI::getInstance()->getStrPrefixoCookie()?>_banner', chave, 3650);
    $("#frmProcedimentoControlar").submit();
  }

  //</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');

?>


<form id="frmProcedimentoControlar" class="h-100" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&tipo_filtro='.$strTipoFiltro)?>">
  <div id="divControleProcessosConteudo"   class="h-100  d-flex flex-column">
    <?=$strHtmlBanner?>

    <div  class="barraBotoesSEIMovel">
        <a class="btn d-md-none" data-toggle="collapse" href="#collapseControle" role="button" aria-expanded="true" aria-controls="collapseControle" title="Exibir/Ocultar Ícones">
          <img src="<?=PaginaSEI::getInstance()->getIconeMenuPontos()?>" width="32" height="32" />
        </a>
      </div>
    <div class="collapse d-md-block" id="collapseControle">
        <div id="divComandos" class="barraBotoesSEI">
          <?
          foreach($arrComandos as $comando){
            echo $comando;
          }
          ?>
        </div>
      </div>
    <div id="divFiltro" class="d-flex justify-content-start">
        <?=$strLinkTipoVisualizacao?>
        <?=$strLinkNivelDetalhe?>
        <?=$strLinkMeusProcessos?>
        <?=$strLinkFiltroMarcadores?>
        <?=$strLinkFiltroTipoProcesso?>
      </div>
    <?
    echo '<div style="overflow-y: auto;min-height: 200px;margin-top:'.( PaginaSEI::getInstance()->isBolNavegadorSafari() ? "20" : "5").'px;'.( PaginaSEI::getInstance()->isBolNavegadorSafariIpad() ? "height: 70vh;" : "").'" class="flex-grow-1 row mx-0 mb-0  d-flex divTabelaProcesso" id="divTabelaProcesso">';

      if ($strTipoFiltro == ProcedimentoINT::$TF_MARCADORES){

        if ($numRegistrosMarcadores == 0){
          echo '<label>Nenhum processo encontrado filtrando por marcadores.</label>'."\n";
        }else {
          PaginaSEI::getInstance()->montarAreaTabela($strResultadoMarcadores, $numRegistrosMarcadores, true, '', null, 'Marcadores');
        }

      }else if ($strTipoFiltro == ProcedimentoINT::$TF_TIPO_PROCEDIMENTO) {

        if ($numRegistrosTiposProcedimento == 0){
          echo '<label>Nenhum processo encontrado filtrando por tipo.</label>'."\n";
        }else {
          PaginaSEI::getInstance()->montarAreaTabela($strResultadoTiposProcedimento, $numRegistrosTiposProcedimento, true, '', null, 'TiposProcessos');
        }

      }else if ($strTipoVisualizacao == ProcedimentoINT::$TV_RESUMIDA) {
        echo '
        <div class="d-flex justify-content-center w-100 d-md-none" style="height: 25px;">
                <a class="ml-0 mt-1 pl-0 ancoraPadraoAzul   d-md-none mx-auto"  href="#" onclick="alterarVisualizacaoTabela(\'hdnExibirRecebidos\',\''.($strExibirRecebidos ==  "true" ? "false" : "true").'\',\'hdnExibirGerados\')" > 
                  Processos Recebidos
                </a>
                 <a class="ml-0 mt-1 pl-0 ancoraPadraoAzul  d-md-none mx-auto" href="#" onclick="alterarVisualizacaoTabela(\'hdnExibirGerados\',\''.($strExibirGerados ==  "true" ? "false" : "true").'\',\'hdnExibirRecebidos\')">
                    Processos Gerados
                </a>
          </div>   ';

        echo '   <div id="divRecebidos" class="ml-0  pl-0 '.($strExibirRecebidos ==  "true" ? "d-block" : "d-none").'  d-md-block  col-12 col-md-6">' . "\n";
        PaginaSEI::getInstance()->montarAreaTabela($strResultadoRecebidos, $numRegistrosRecebidos, false, '', null, 'Recebidos');
        echo '  </div>' . "\n";

        echo '  <div id="divGerados" class=" ml-0 pl-0  '.($strExibirGerados == "true" ? "d-block" : "d-none").' d-md-block col-12 col-md-6">' . "\n";
        PaginaSEI::getInstance()->montarAreaTabela($strResultadoGerados, $numRegistrosGerados, false, '', null, 'Gerados');
        echo '  </div>' . "\n";

        echo "</div>";

      } else {
        PaginaSEI::getInstance()->montarAreaTabela($strResultadoDetalhado, $numRegistros);
      }
    echo '</div>';

      PaginaSEI::getInstance()->montarAreaDebug();
      //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
      ?>

    <input type="hidden" id="hdnTipoVisualizacao" name="hdnTipoVisualizacao" value="<?=$strTipoVisualizacao?>" />
    <input type="hidden" id="hdnExibirRecebidos" name="hdnExibirRecebidos" value="<?=$strExibirRecebidos?>" />
    <input type="hidden" id="hdnExibirGerados" name="hdnExibirGerados" value="<?=$strExibirGerados?>" />
    <input type="hidden" id="hdnMeusProcessos" name="hdnMeusProcessos" value="<?=$strTipoAtribuicao?>" />
    <input type="hidden" id="hdnIdBloco" name="hdnIdBloco" value="" />
    <input type="text" id="txtBloco" name="txtBloco" value=""  style="display:none"/>
    <input type="hidden" id="hdnIdSigilosos" value="<?=implode(',',$arrIdProcessosSigilosos)?>" />
    <input type="hidden" id="hdnIdComMarcador" value="<?=implode(',',$arrIdProcessosComMarcador)?>" />
    <input type="hidden" id="<?=$strIdHdnMarcador?>" name="<?=$strIdHdnMarcador?>" value="<?=$numIdMarcadorFiltro?>" />
    <input type="hidden" id="<?=$strIdHdnTipoProcedimento?>" name="<?=$strIdHdnTipoProcedimento?>" value="<?=$numIdTipoProcedimentoFiltro?>" />
    <input type="hidden" id="hdnFlagControleProcessos" name="hdnFlagControleProcessos" value="1" />
  </div>
</form>

  <script>    divInfraMoverTopo = document.getElementById("divTabelaProcesso");</script>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>