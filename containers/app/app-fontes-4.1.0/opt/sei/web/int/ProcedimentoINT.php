<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 31/01/2008 - criado por marcio_db
 * 15/06/2018 - cjy - ícone de acompanhamento no controle de processos
 *
 * Versão do Gerador de Código: 1.13.1
 *
 * Versão no CVS: $Id$
 */

require_once dirname(__FILE__) . '/../SEI.php';

class ProcedimentoINT extends InfraINT {

  //Tipo Visualizacao
  public static $TV_RESUMIDA = 'R';
  public static $TV_DETALHADA = 'D';

  //Tipo Filtro
  public static $TF_MARCADORES = 'M';
  public static $TF_TIPO_PROCEDIMENTO = 'P';
  public static $TF_TIPO_PRIORIDADE = 'R';

  public static function pesquisarDigitadoRI1023($strIdProcedimento) {
    $objInfraException = new InfraException();

    if (InfraString::isBolVazia($strIdProcedimento)) {
      $objInfraException->lancarValidacao('Protocolo para pesquisa não informado.');
    }

    $objProtocoloDTO = new ProtocoloDTO();
    $objProtocoloDTO->setStrProtocoloFormatadoPesquisa(InfraUtil::retirarFormatacao($strIdProcedimento, false));

    $objProtocoloRN = new ProtocoloRN();
    $arrObjProtocoloDTOPesquisado = $objProtocoloRN->pesquisarProtocoloFormatado($objProtocoloDTO);

    if (count($arrObjProtocoloDTOPesquisado) == 0 || $arrObjProtocoloDTOPesquisado[0]->getStrStaProtocolo() != ProtocoloRN::$TP_PROCEDIMENTO) {
      $objInfraException->lancarValidacao('Processo não encontrado.');
    }

    $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
    $objPesquisaProtocoloDTO->setDblIdProtocolo($arrObjProtocoloDTOPesquisado[0]->getDblIdProtocolo());
    $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_PROCEDIMENTOS);
    $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);

    $objProtocoloRN = new ProtocoloRN();
    $arrObjProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);

    if (count($arrObjProtocoloDTO) == 0) {
      $objInfraException->lancarValidacao('Processo não encontrado.');
    }

    return array(
      'IdProcedimento' => $arrObjProtocoloDTO[0]->getDblIdProtocolo(), 'ProtocoloProcedimentoFormatado' => $arrObjProtocoloDTO[0]->getStrProtocoloFormatado(), 'NomeTipoProcedimento' => $arrObjProtocoloDTO[0]->getStrNomeTipoProcedimentoProcedimento()
    );
  }

  public static function montarSelectArvoreOrdenacao($dblIdProcedimento) {
    $objProcedimentoDTO = new ProcedimentoDTO();

    $objProcedimentoDTO->setDblIdProcedimento($dblIdProcedimento);
    $objProcedimentoDTO->setStrSinDocTodos('S');
    $objProcedimentoDTO->setStrSinProcAnexados('S');

    $objProcedimentoRN = new ProcedimentoRN();

    $arr = $objProcedimentoRN->listarCompleto($objProcedimentoDTO);

    $arrObjRelProtocoloProtocoloDTO = $arr[0]->getArrObjRelProtocoloProtocoloDTO();

    foreach ($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {
      if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO) {
        $objRelProtocoloProtocoloDTO->setStrIdentificacaoProtocolo2(DocumentoINT::montarIdentificacaoArvore($objRelProtocoloProtocoloDTO->getObjProtocoloDTO2()));
      } else {
        if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_DOCUMENTO_MOVIDO) {
          $objRelProtocoloProtocoloDTO->setStrIdentificacaoProtocolo2(DocumentoINT::montarIdentificacaoArvore($objRelProtocoloProtocoloDTO->getObjProtocoloDTO2()) . ' (movido)');
        } else {
          if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO) {
            $objRelProtocoloProtocoloDTO->setStrIdentificacaoProtocolo2(ProcedimentoINT::montarIdentificacaoArvore($objRelProtocoloProtocoloDTO->getObjProtocoloDTO2()) . ' (anexado)');
          } else {
            if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_DESANEXADO) {
              $objRelProtocoloProtocoloDTO->setStrIdentificacaoProtocolo2(ProcedimentoINT::montarIdentificacaoArvore($objRelProtocoloProtocoloDTO->getObjProtocoloDTO2()) . ' (desanexado)');
            }
          }
        }
      }
    }

    return parent::montarSelectArrInfraDTO(null, null, null, $arrObjRelProtocoloProtocoloDTO, 'IdRelProtocoloProtocolo', 'IdentificacaoProtocolo2');
  }

  public static function formatarProtocoloTipoRI0200($strProtocoloFormatado, $strNomeTipoProcedimento) {
    return $strProtocoloFormatado . ' - ' . $strNomeTipoProcedimento;
  }

  public static function conjuntoCompletoFormatadoRI0903($arrProcedimentos) {
    if (InfraArray::contar($arrProcedimentos)) {
      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->retDblIdProcedimento();
      $objProcedimentoDTO->retStrProtocoloProcedimentoFormatado();
      $objProcedimentoDTO->retNumIdTipoProcedimento();
      $objProcedimentoDTO->retStrNomeTipoProcedimento();
      $objProcedimentoDTO->setDblIdProcedimento($arrProcedimentos, InfraDTO::$OPER_IN);

      $objProcedimentoRN = new ProcedimentoRN();
      $arrObjProcedimentoDTO = $objProcedimentoRN->listarRN0278($objProcedimentoDTO);

      foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
        $objProcedimentoDTO->setStrNomeTipoProcedimento(ProcedimentoINT::formatarProtocoloTipoRI0200($objProcedimentoDTO->getStrProtocoloProcedimentoFormatado(), $objProcedimentoDTO->getStrNomeTipoProcedimento()));
      }
    } else {
      $arrObjProcedimentoDTO = array();
    }

    return parent::montarSelectArrInfraDTO(null, null, null, $arrObjProcedimentoDTO, 'IdProcedimento', 'NomeTipoProcedimento');
  }

  public static function montarIconeVisualizacao(
    $numTipoVisualizacao, $objProcedimentoDTO, $arrIconeIntegracao = null, $bolAcaoAndamentoSituacaoGerenciar, $bolAcaoAndamentoMarcadorGerenciar, $strParametros = '', $bolExibirMarcadores = true) {
    $dblIdProcedimento = $objProcedimentoDTO->getDblIdProcedimento();

    if ($objProcedimentoDTO->isSetObjAndamentoSituacaoDTO()) {
      $objAndamentoSituacaoDTO = $objProcedimentoDTO->getObjAndamentoSituacaoDTO();
    } else {
      $objAndamentoSituacaoDTO = null;
    }

    if ($objProcedimentoDTO->isSetArrObjAndamentoMarcadorDTO()) {
      $arrObjAndamentoMarcadorDTO = $objProcedimentoDTO->getArrObjAndamentoMarcadorDTO();
    } else {
      $arrObjAndamentoMarcadorDTO = null;
    }

    $strImagemStatus = '';

    if ($numTipoVisualizacao & AtividadeRN::$TV_ATENCAO) {
      $strImagemStatus .= '<a href="javascript:void(0);" ' . PaginaSEI::montarTitleTooltip('Um documento foi incluído ou assinado neste processo') . '><img src="' . Icone::EXCLAMACAO . '" class="imagemStatus" /></a>';
    }

    if ($numTipoVisualizacao & AtividadeRN::$TV_PUBLICACAO) {
      $strImagemStatus .= '<a href="javascript:void(0);" ' . PaginaSEI::montarTitleTooltip('Um documento do processo foi publicado') . '><img src="' . Icone::PUBLICACAO . '" class="imagemStatus" /></a>';
    }

    if ($objProcedimentoDTO->getNumIdTipoPrioridade() != null) {
      $strImagemStatus .= '<a href="javascript:void(0);" ' . PaginaSEI::montarTitleTooltip($objProcedimentoDTO->getStrNomeTipoPrioridade(), 'Prioritário') . '><img src="' . Icone::PROCESSO_PRIORITARIO . '" class="imagemStatus" /></a>';
    }


    if ($numTipoVisualizacao & AtividadeRN::$TV_REABERTURA_PROGRAMADA) {
      $strReaberturaProgramada = '';
      if ($objProcedimentoDTO->isSetArrObjReaberturaProgramadaDTO() && $objProcedimentoDTO->getArrObjReaberturaProgramadaDTO() != null) {
        foreach ($objProcedimentoDTO->getArrObjReaberturaProgramadaDTO() as $objReaberturaProgramadaDTO) {
          $strReaberturaProgramada .= $objReaberturaProgramadaDTO->getStrSiglaUsuario() . ' em ' . $objReaberturaProgramadaDTO->getDtaProgramada() . "\n";
        }
      }
      $strImagemStatus .= '<a href="javascript:void(0);" ' . PaginaSEI::montarTitleTooltip($strReaberturaProgramada, 'Reabertura Programada') . '><img src="' . Icone::PROCESSO_REABERTURA_PROGRAMADA . '" class="imagemStatus" /></a>';
    }

    if ($objProcedimentoDTO->isSetStrSinFederacao() && $objProcedimentoDTO->getStrSinFederacao() == 'S') {
      if ($numTipoVisualizacao & AtividadeRN::$TV_ENVIO_FEDERACAO) {
        $strImagemStatus .= '<a href="javascript:void(0);" ' . PaginaSEI::montarTitleTooltip('Um novo envio para o SEI Federação foi realizado') . '><img src="' . Icone::FEDERACAO_ACESSO_LIBERACAO . '" class="imagemStatus" /></a>';
      }

      if ($numTipoVisualizacao & AtividadeRN::$TV_CANCELAMENTO_FEDERACAO) {
        $strImagemStatus .= '<a href="javascript:void(0);" ' . PaginaSEI::montarTitleTooltip('Um envio para o SEI Federação foi cancelado') . '><img src="' . Icone::FEDERACAO_ACESSO_CANCELAMENTO . '" class="imagemStatus" /></a>';
      }

      $strImagemStatus .= '<a href="javascript:void(0);" ' . PaginaSEI::montarTitleTooltip('SEI Federação') . '><img src="' . Icone::FEDERACAO . '" class="imagemStatus" /></a>';
    }

    if ($objProcedimentoDTO->getStrStaEstadoProtocolo() == ProtocoloRN::$TE_PROCEDIMENTO_BLOQUEADO) {
      $strImagemStatus .= '<a href="javascript:void(0);" ' . PaginaSEI::montarTitleTooltip('Processo bloqueado') . '><img src="' . Icone::PROCESSO_BLOQUEADO . '" class="imagemStatus" /></a>';
    }

    if ($numTipoVisualizacao & AtividadeRN::$TV_REMOCAO_SOBRESTAMENTO) {
      $strImagemStatus .= '<a href="javascript:void(0);" ' . PaginaSEI::montarTitleTooltip('Processo deixou de estar sobrestado') . '><img src="' . Icone::PROCESSO_REMOVER_SOBRESTAMENTO . '" class="imagemStatus" /></a>';
    }

    if ($objProcedimentoDTO->isSetArrObjRetornoProgramadoDTO() && $objProcedimentoDTO->getArrObjRetornoProgramadoDTO() != null) {
      if (RetornoProgramadoINT::montarIconeRetornoProgramadoDevolver($objProcedimentoDTO->getArrObjRetornoProgramadoDTO(), $strIconeRetornoProgramado, $strTituloRetornoProgramado, $strRetornoProgramado)) {
        $strImagemStatus .= '<a href="javascript:void(0);" ' . PaginaSEI::montarTitleTooltip($strRetornoProgramado, $strTituloRetornoProgramado) . '><img src="' . $strIconeRetornoProgramado . '" class="imagemStatus" /></a>';
      }

      if (RetornoProgramadoINT::montarIconeRetornoProgramadoAguardando($objProcedimentoDTO->getArrObjRetornoProgramadoDTO(), $strIconeRetornoProgramado, $strTituloRetornoProgramado, $strRetornoProgramado)) {
        $strImagemStatus .= '<a href="javascript:void(0);" ' . PaginaSEI::montarTitleTooltip($strRetornoProgramado, $strTituloRetornoProgramado) . '><img src="' . $strIconeRetornoProgramado . '" class="imagemStatus" /></a>';
      }
    }

    if ($objAndamentoSituacaoDTO != null) {
      if ($bolAcaoAndamentoSituacaoGerenciar) {
        $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=andamento_situacao_gerenciar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_procedimento=' . $dblIdProcedimento . $strParametros);
      } else {
        $strLink = 'javascript:void(0);';
      }

      $strSituacao = SituacaoINT::formatarSituacaoDesativada($objAndamentoSituacaoDTO->getStrNomeSituacao(), $objAndamentoSituacaoDTO->getStrSinAtivoSituacao());

      $strAcao = '<a href="' . $strLink . '" ' . PaginaSEI::montarTitleTooltip($strSituacao, '', 'Ponto de Controle') . '><img src="' . Icone::SITUACAO . '" class="imagemStatus" /></a>';

      $strImagemStatus .= $strAcao;
    }

    if ($bolExibirMarcadores && $arrObjAndamentoMarcadorDTO != null) {
      foreach ($arrObjAndamentoMarcadorDTO as $objAndamentoMarcadorDTO) {
        if ($bolAcaoAndamentoMarcadorGerenciar) {
          $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=andamento_marcador_gerenciar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_procedimento=' . $dblIdProcedimento . $strParametros);
        } else {
          $strLink = 'javascript:void(0);';
        }

        $strMarcador = MarcadorINT::formatarMarcadorDesativado($objAndamentoMarcadorDTO->getStrNomeMarcador(), $objAndamentoMarcadorDTO->getStrSinAtivoMarcador());

        $strAcao = '<a href="' . $strLink . '" ' . PaginaSEI::montarTitleTooltip($objAndamentoMarcadorDTO->getStrTexto(), $strMarcador,
            'Marcador') . '><img src="' . $objAndamentoMarcadorDTO->getStrArquivoIconeMarcador() . '" class="imagemStatus" /></a>';

        $strImagemStatus .= $strAcao;
      }
    }

    if ($arrIconeIntegracao != null && isset($arrIconeIntegracao[$dblIdProcedimento])) {
      foreach ($arrIconeIntegracao[$dblIdProcedimento] as $strIconeIntegracao) {
        $strImagemStatus .= $strIconeIntegracao;
      }
    }

    return $strImagemStatus;
  }

  public static function montarAcoesArvore(
    $dblIdProcedimento, $numIdUnidadeAtual, &$bolFlagAberto, &$bolFlagAnexado, &$bolFlagAbertoAnexado, &$bolFlagProtocolo, &$bolFlagArquivo, &$bolFlagTramitacao, &$bolFlagSobrestado, &$bolFlagBloqueado, &$bolFlagEliminado,
    &$bolFlagLinhaDireta, &$numCodigoAcesso, &$numNo, &$strNoProc, &$numNoAcao, &$strNosAcaoProc, &$bolErro) {
    try {
      global $SEI_MODULOS;

      $objSessaoSEI = SessaoSEI::getInstance();
      $objPaginaSEI = PaginaSEI::getInstance();

      $objPesquisaPendenciaDTO = new PesquisaPendenciaDTO();
      $objPesquisaPendenciaDTO->setDblIdProtocolo($dblIdProcedimento);
      $objPesquisaPendenciaDTO->setNumIdUsuario($objSessaoSEI->getNumIdUsuario());
      $objPesquisaPendenciaDTO->setNumIdUnidade($numIdUnidadeAtual);
      $objPesquisaPendenciaDTO->setStrSinMontandoArvore('S');
      $objPesquisaPendenciaDTO->setStrSinRetornoProgramado('S');
      $objPesquisaPendenciaDTO->setStrSinControlePrazo('S');
      $objPesquisaPendenciaDTO->setStrSinReaberturaProgramada('S');

      if ($bolFlagLinhaDireta) {
        $objPesquisaPendenciaDTO->setStrSinLinhaDireta('S');
      }

      $objAtividadeRN = new AtividadeRN();
      $arrObjProcedimentoDTO = $objAtividadeRN->listarPendenciasRN0754($objPesquisaPendenciaDTO);

      $numRegistrosProcedimento = count($arrObjProcedimentoDTO);

      $bolFlagAberto = false;
      $bolFlagAbertoAnexado = false;
      $bolFlagTramitacao = false;
      $bolFlagSobrestado = false;
      $bolUnidadeSobrestamento = false;
      $bolFlagAnexado = false;
      $objProcedimentoDTO = null;

      if ($numRegistrosProcedimento == 1) {
        $objProcedimentoDTO = $arrObjProcedimentoDTO[0];
        $bolFlagAberto = true;
        $bolFlagTramitacao = true;
      } else {
        $dto = new ProcedimentoDTO();
        $dto->setDblIdProcedimento($dblIdProcedimento);
        $dto->setStrSinMontandoArvore('S');

        if ($bolFlagLinhaDireta) {
          $dto->setStrSinLinhaDireta('S');
        }

        $objProcedimentoRN = new ProcedimentoRN();
        $arr = $objProcedimentoRN->listarCompleto($dto);

        if (count($arr) == 1) {
          $objProcedimentoDTO = $arr[0];

          if ($objProcedimentoDTO->getNumIdUnidadeGeradoraProtocolo() == $numIdUnidadeAtual) {
            $bolFlagTramitacao = true;
          } else {
            $objAtividadeDTO = new AtividadeDTO();
            $objAtividadeDTO->retNumIdAtividade();
            $objAtividadeDTO->setNumIdUnidadeOrigem($numIdUnidadeAtual, InfraDTO::$OPER_DIFERENTE);
            $objAtividadeDTO->setNumIdUnidade($numIdUnidadeAtual);
            $objAtividadeDTO->setDblIdProtocolo($dblIdProcedimento);
            $objAtividadeDTO->setNumMaxRegistrosRetorno(1);

            //se teve andamento enviado para a unidade
            if ($objAtividadeRN->consultarRN0033($objAtividadeDTO) != null) {
              $bolFlagTramitacao = true;
            }
          }
        }
      }


      if ($objProcedimentoDTO == null) {
        $objPaginaSEI->setStrMensagem('Processo não encontrado.', PaginaSEI::$TIPO_MSG_AVISO);
        $bolErro = true;
      } else {
        if ($objProcedimentoDTO->getStrSinEliminadoProtocolo() == 'S') {
          $bolFlagEliminado = true;
        }

        if ($objProcedimentoDTO->getStrStaEstadoProtocolo() == ProtocoloRN::$TE_PROCEDIMENTO_SOBRESTADO) {
          //se o processo esta aberto entao foi a unidade atual que o sobrestou
          if ($bolFlagAberto) {
            $bolUnidadeSobrestamento = true;

            //tratar como um processo concluido
            $bolFlagAberto = false;
          }

          $bolFlagSobrestado = true;
        } else {
          if ($objProcedimentoDTO->getStrStaEstadoProtocolo() == ProtocoloRN::$TE_PROCEDIMENTO_ANEXADO) {
            $bolFlagAnexado = true;

            $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
            $objRelProtocoloProtocoloDTO->retDblIdProtocolo1();
            $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($dblIdProcedimento);
            $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);

            $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
            $objProcedimentoDTOPai = $objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO);

            $objPesquisaPendenciaDTO = new PesquisaPendenciaDTO();
            $objPesquisaPendenciaDTO->setDblIdProtocolo($objProcedimentoDTOPai->getDblIdProtocolo1());
            $objPesquisaPendenciaDTO->setNumIdUsuario($objSessaoSEI->getNumIdUsuario());
            $objPesquisaPendenciaDTO->setNumIdUnidade($numIdUnidadeAtual);

            $arrObjProcedimentoDTOPai = $objAtividadeRN->listarPendenciasRN0754($objPesquisaPendenciaDTO);

            if (count($arrObjProcedimentoDTOPai)) {
              $bolFlagAbertoAnexado = true;
            }
          } else {
            if ($objProcedimentoDTO->getStrStaEstadoProtocolo() == ProtocoloRN::$TE_PROCEDIMENTO_BLOQUEADO) {
              //tratar como um processo concluido
              //$bolFlagAberto = false;

              $bolFlagBloqueado = true;
            }
          }
        }

        $numProtocolosAssociados = InfraArray::contar($objProcedimentoDTO->getArrObjRelProtocoloProtocoloDTO());

        $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
        $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_PROCEDIMENTOS);
        $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_TODOS);
        $objPesquisaProtocoloDTO->setDblIdProtocolo($dblIdProcedimento);

        $objProtocoloRN = new ProtocoloRN();
        $arrObjProtocoloDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO), 'IdProtocolo');

        if (!isset($arrObjProtocoloDTO[$dblIdProcedimento])) {
          $objPaginaSEI->setStrMensagem('Acesso negado ao processo.', PaginaSEI::$TIPO_MSG_AVISO);
          $bolErro = true;
        } else {
          $numCodigoAcesso = $arrObjProtocoloDTO[$dblIdProcedimento]->getNumCodigoAcesso();

          $objUnidadeDTO = new UnidadeDTO();
          $objUnidadeDTO->setBolExclusaoLogica(false);
          $objUnidadeDTO->retStrSinProtocolo();
          $objUnidadeDTO->retStrSinOuvidoria();
          $objUnidadeDTO->retStrSinArquivamento();
          $objUnidadeDTO->setNumIdUnidade($numIdUnidadeAtual);

          $objUnidadeRN = new UnidadeRN();
          $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

          if ($objUnidadeDTO == null) {
            throw new InfraException('Unidade ' . $objSessaoSEI->getStrSiglaUnidadeAtual() . ' não encontrada.');
          }

          $bolFlagProtocolo = ($objUnidadeDTO->getStrSinProtocolo() == 'S');
          $bolFlagArquivo = ($objUnidadeDTO->getStrSinArquivamento() == 'S');

          $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
          $numTipoPesquisaRestrito = $objInfraParametro->getValor('SEI_EXIBIR_ARVORE_RESTRITO_SEM_ACESSO', false);

          if ($objProcedimentoDTO->getStrStaNivelAcessoLocalProtocolo() == ProtocoloRN::$NA_RESTRITO && $numCodigoAcesso < 0 && !$bolFlagProtocolo && $numTipoPesquisaRestrito != '1') {
            $objPaginaSEI->setStrMensagem('Unidade atual não possui acesso ao processo restrito ' . $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado() . '.', PaginaSEI::$TIPO_MSG_AVISO);
            $bolErro = true;
          } else {
            //processos sigilosos somente com credencial de assinatura
            if ($objProcedimentoDTO->getStrStaNivelAcessoLocalProtocolo() == ProtocoloRN::$NA_SIGILOSO && $arrObjProtocoloDTO[$dblIdProcedimento]->getStrSinCredencialProcesso() == 'N') {
              $bolFlagAberto = false;
              $bolFlagTramitacao = false;
            }

            $bolAcaoProcedimentoEnviar = $objSessaoSEI->verificarPermissao('procedimento_enviar');
            $bolAcaoProcedimentoCredencialGerenciar = $objSessaoSEI->verificarPermissao('procedimento_credencial_gerenciar');
            $bolAcaoProcedimentoCredencialRenunciar = $objSessaoSEI->verificarPermissao('procedimento_credencial_renunciar');
            $bolAcaoDefinirAtividade = $objSessaoSEI->verificarPermissao('procedimento_atualizar_andamento');
            $bolAcaoAtribuirProcesso = $objSessaoSEI->verificarPermissao('procedimento_atribuicao_cadastrar');
            $bolAcaoProtocoloModeloGerenciar = $objSessaoSEI->verificarPermissao('protocolo_modelo_gerenciar');
            $bolAcaoConsultarProcedimento = $objSessaoSEI->verificarPermissao('procedimento_consultar');
            $bolAcaoAlterarProcedimento = $objSessaoSEI->verificarPermissao('procedimento_alterar');
            $bolAcaoDuplicarProcedimento = $objSessaoSEI->verificarPermissao('procedimento_duplicar');
            $bolAcaoProcedimentoEnviarEmail = $objSessaoSEI->verificarPermissao('procedimento_enviar_email');
            $bolAcaoProcedimentoRelacionar = $objSessaoSEI->verificarPermissao('procedimento_relacionar');
            $bolAcaoEscolherTipo = $objSessaoSEI->verificarPermissao('documento_escolher_tipo');
            $bolAcaoDocumentoReceber = $objSessaoSEI->verificarPermissao('documento_receber');
            $bolAcaoExcluirProcedimento = $objSessaoSEI->verificarPermissao('procedimento_excluir');
            $bolAcaoIncluirEmBloco = $objSessaoSEI->verificarPermissao('rel_bloco_protocolo_cadastrar');
            $bolAcaoConcluirProcedimento = $objSessaoSEI->verificarPermissao('procedimento_concluir');
            $bolAcaoReabrirProcedimento = $objSessaoSEI->verificarPermissao('procedimento_reabrir');
            $bolAcaoReaberturaProgramada = $objSessaoSEI->verificarPermissao('reabertura_programada_registrar');
            $bolAcaoSobrestarProcesso = $objSessaoSEI->verificarPermissao('procedimento_sobrestar');
            $bolAcaoAnexarProcesso = $objSessaoSEI->verificarPermissao('procedimento_anexar');
            $bolAcaoRemoverSobrestamento = $objSessaoSEI->verificarPermissao('procedimento_remover_sobrestamento');
            $bolAcaoRegistrarAnotacao = $objSessaoSEI->verificarPermissao('anotacao_registrar');
            $bolAcaoProcedimentoControlar = $objSessaoSEI->verificarPermissao('procedimento_controlar');
            $bolAcaoArvoreOrdenar = $objSessaoSEI->verificarPermissao('arvore_ordenar');
            $bolAcaoAcessoExternoGerenciar = $objSessaoSEI->verificarPermissao('acesso_externo_gerenciar');
            $bolAcaoAcompanhamentoGerenciar = $objSessaoSEI->verificarPermissao('acompanhamento_gerenciar');
            $bolAcaoProcedimentoCiencia = $objSessaoSEI->verificarPermissao('procedimento_ciencia');
            $bolAcaoProcedimentoGerarPdf = $objSessaoSEI->verificarPermissao('procedimento_gerar_pdf');
            $bolAcaoProcedimentoGerarZip = $objSessaoSEI->verificarPermissao('procedimento_gerar_zip');
            $bolAcaoReencaminharOuvidoria = $objSessaoSEI->verificarPermissao('procedimento_reencaminhar_ouvidoria');
            $bolAcaoFinalizarOuvidoria = $objSessaoSEI->verificarPermissao('procedimento_finalizar_ouvidoria');
            $bolAcaoAndamentoSituacaoGerenciar = $objSessaoSEI->verificarPermissao('andamento_situacao_gerenciar');
            $bolAcaoProcedimentoPesquisar = $objSessaoSEI->verificarPermissao('procedimento_pesquisar');
            $bolAcaoProcedimentoEscolherTipoRelacionado = $objSessaoSEI->verificarPermissao('procedimento_escolher_tipo_relacionado');
            $bolAcaoAndamentoMarcadorGerenciar = $objSessaoSEI->verificarPermissao('andamento_marcador_gerenciar');
            $bolAcaoControlePrazoDefinir = $objSessaoSEI->verificarPermissao('controle_prazo_definir');
            $bolFederacaoHabilitado = ConfiguracaoSEI::getInstance()->getValor('Federacao', 'Habilitado', false, false);
            $bolAcaoAcessoFederacaoGerenciar = $objSessaoSEI->verificarPermissao('acesso_federacao_gerenciar');
            $bolAcaoProcedimentoLinhaDireta = SessaoSEI::getInstance()->verificarPermissao('procedimento_linha_direta');
            $bolAcaoComentarioCadastrar = $objSessaoSEI->verificarPermissao('comentario_cadastrar');
            $bolAcaoPlanoTrabalhoDetalhar = $objSessaoSEI->verificarPermissao('plano_trabalho_detalhar');

            $arrProtocolosVisitados = SessaoSEI::getInstance()->getAtributo('PROTOCOLOS_VISITADOS_' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual());

            $strLinkProcesso = 'about:blank';

            //adiciona também acesso ao protocolo para permitir inclusão de documentos
            if ($numCodigoAcesso > 0 || $bolFlagProtocolo || $bolFlagEliminado) {
              $strLinkProcesso = $objSessaoSEI->assinarLink('controlador.php?acao=arvore_visualizar&acao_origem=procedimento_visualizar&id_procedimento=' . $dblIdProcedimento);
            }


            $strTooltipProcesso = $objPaginaSEI->formatarParametrosJavaScript($objProcedimentoDTO->getStrNomeTipoProcedimento());

            if ($bolFlagEliminado) {
              $strTooltipProcesso = ProtocoloINT::formatarEliminado($strTooltipProcesso);
              $strIcone = Icone::AVALIACAO_ELIMINADO;
            } else {
              if ($objProcedimentoDTO->getStrStaEstadoProtocolo() == ProtocoloRN::$TE_PROCEDIMENTO_ANEXADO) {
                $strIcone = Icone::PROCESSO_ANEXADO;
              } else {
                $strIcone = Icone::PROCESSO;
              }
            }


            $strNoProc .= "\n";
            $strNoProc .= "\n\n" . '//CA=' . $numCodigoAcesso;
            $strNoProc .= "\n";

            $strNoProc .= 'Nos[' . $numNo . '] = new infraArvoreNo("PROCESSO",' . '"' . $dblIdProcedimento . '",' . 'null,' . '"' . $strLinkProcesso . '",' . '"ifrVisualizacao",' . '"' . $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado() . '",' . '"' . $strTooltipProcesso . '",' . '"' . $strIcone . '",' . '"' . $strIcone . '",' . '"' . $strIcone . '",' . 'true,' . (($strLinkProcesso != 'about:blank') ? 'true,' : 'false,') . (isset($arrProtocolosVisitados[$dblIdProcedimento]) ? '"noVisitado"' : 'null') . ',' . 'null,' . '"noVisitado",' . '"' . $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado() . '");' . "\n";

            if ($objProcedimentoDTO->getNumIdTipoPrioridade() != null) {
              $strNosAcaoProc .= 'NosAcoes[' . $numNoAcao++ . '] = new infraArvoreAcao("PRIORITARIO",' . '"PRT' . $dblIdProcedimento . '",' . '"' . $dblIdProcedimento . '",' . '"javascript:alert(\'Processo Prioritário\');",' . 'null,' . '"Processo Prioritário",' . '"' . Icone::PROCESSO_PRIORITARIO . '",' . 'true);' . "\n";
            }

            if ($bolFlagTramitacao && $bolAcaoProcedimentoLinhaDireta) {
              if (!$bolFlagLinhaDireta) {
                $strTituloLinhaDireta = 'Filtrar Linha Direta';
                $strIconeLinhaDireta = Icone::LINHA_DIRETA1;
              } else {
                $strTituloLinhaDireta = 'Remover Filtro Linha Direta';
                $strIconeLinhaDireta = Icone::LINHA_DIRETA2;
              }

              $strNosAcaoProc .= 'NosAcoes[' . $numNoAcao++ . '] = new infraArvoreAcao("LINHA_DIRETA",' . '"LD' . $dblIdProcedimento . '",' . '"' . $dblIdProcedimento . '",' . '"' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem=procedimento_visualizar&id_procedimento=' . $dblIdProcedimento . '&linha_direta=' . !$bolFlagLinhaDireta) . '",' . 'null,' . '"' . $strTituloLinhaDireta . '",' . '"' . $strIconeLinhaDireta . '",' . 'true);' . "\n";
            }

            if ($objProcedimentoDTO->getStrStaNivelAcessoLocalProtocolo() != ProtocoloRN::$NA_PUBLICO) {
              $arrObjGrauSigiloDTO = null;
              if ($objProcedimentoDTO->getStrStaNivelAcessoLocalProtocolo() == ProtocoloRN::$NA_SIGILOSO) {
                $arrObjGrauSigiloDTO = InfraArray::indexarArrInfraDTO(ProtocoloRN::listarGrausSigiloso(), 'StaGrau');
              }

              $strNosAcaoProc .= ProtocoloINT::montarNoAcaoAcesso($dblIdProcedimento, $numNoAcao++, $objProcedimentoDTO->getStrStaNivelAcessoLocalProtocolo(), $objProcedimentoDTO->getStrStaGrauSigiloProtocolo(),
                $objProcedimentoDTO->getStrNomeHipoteseLegal(), $objProcedimentoDTO->getStrBaseLegalHipoteseLegal(), $arrObjGrauSigiloDTO);
            }

            if ($bolFlagBloqueado) {
              $strNosAcaoProc .= 'NosAcoes[' . $numNoAcao++ . '] = new infraArvoreAcao("BLOQUEIO",' . '"BL' . $dblIdProcedimento . '",' . '"' . $dblIdProcedimento . '",' . '"javascript:alert(\'Processo Bloqueado\');",' . 'null,' . '"Processo Bloqueado",' . '"' . Icone::PROCESSO_BLOQUEADO . '",' . 'true);' . "\n";
            }

            if ($arrObjProtocoloDTO[$dblIdProcedimento]->getArrAcessoModulos() != null) {
              $strNosAcaoProc .= ProtocoloINT::montarNoAcaoAcessoModulos($dblIdProcedimento, $numNoAcao++, $arrObjProtocoloDTO[$dblIdProcedimento]->getArrAcessoModulos());
            }

            if ($objProcedimentoDTO->isSetArrObjRetornoProgramadoDTO() && $objProcedimentoDTO->getArrObjRetornoProgramadoDTO() != null) {
              if (RetornoProgramadoINT::montarIconeRetornoProgramadoDevolver($objProcedimentoDTO->getArrObjRetornoProgramadoDTO(), $strIconeRetornoProgramado, $strTituloRetornoProgramado, $strTextoRetornoProgramado)) {
                $strTextoRetornoProgramado = $strTituloRetornoProgramado . ':' . "\n" . $strTextoRetornoProgramado;

                $strNosAcaoProc .= 'NosAcoes[' . $numNoAcao++ . '] = new infraArvoreAcao("RETORNO_DEVOLVER",' . '"RETD' . $dblIdProcedimento . '",' . '"' . $dblIdProcedimento . '",' . '"javascript:alert(\'' . PaginaSEI::formatarParametrosJavaScript(str_replace("\n",
                      '\\\n', $strTextoRetornoProgramado)) . '\');",' . 'null,' . '"' . PaginaSEI::formatarParametrosJavaScript($strTextoRetornoProgramado) . '",' . '"' . $strIconeRetornoProgramado . '",' . 'true);' . "\n";
              }

              if (RetornoProgramadoINT::montarIconeRetornoProgramadoAguardando($objProcedimentoDTO->getArrObjRetornoProgramadoDTO(), $strIconeRetornoProgramado, $strTituloRetornoProgramado, $strTextoRetornoProgramado)) {
                $strTextoRetornoProgramado = $strTituloRetornoProgramado . ':' . "\n" . $strTextoRetornoProgramado;

                $strNosAcaoProc .= 'NosAcoes[' . $numNoAcao++ . '] = new infraArvoreAcao("RETORNO_AGUARDANDO",' . '"RETA' . $dblIdProcedimento . '",' . '"' . $dblIdProcedimento . '",' . '"javascript:alert(\'' . PaginaSEI::formatarParametrosJavaScript(str_replace("\n",
                      '\\\n', $strTextoRetornoProgramado)) . '\');",' . 'null,' . '"' . PaginaSEI::formatarParametrosJavaScript($strTextoRetornoProgramado) . '",' . '"' . $strIconeRetornoProgramado . '",' . 'true);' . "\n";
              }
            }

            if ($objUnidadeDTO->getStrSinOuvidoria() == 'S' && $objProcedimentoDTO->getStrSinOuvidoriaTipoProcedimento() == 'S') {
              if ($objProcedimentoDTO->getStrStaOuvidoria() == ProcedimentoRN::$TFO_SIM) {
                $strNosAcaoProc .= 'NosAcoes[' . $numNoAcao++ . '] = new infraArvoreAcao("SOLICITACAO",' . '"SO' . $dblIdProcedimento . '",' . '"' . $dblIdProcedimento . '",' . '"javascript:alert(\'Solicitação Atendida\');",' . 'null,' . '"Solicitação Atendida",' . '"' . Icone::OUVIDORIA_SOLICITACAO_ATENDIDA . '",' . 'true);' . "\n";
              } else {
                if ($objProcedimentoDTO->getStrStaOuvidoria() == ProcedimentoRN::$TFO_NAO) {
                  $strNosAcaoProc .= 'NosAcoes[' . $numNoAcao++ . '] = new infraArvoreAcao("SOLICITACAO",' . '"SO' . $dblIdProcedimento . '",' . '"' . $dblIdProcedimento . '",' . '"javascript:alert(\'Solicitação não Atendida\');",' . 'null,' . '"Solicitação não Atendida",' . '"' . Icone::OUVIDORIA_SOLICITACAO_NAO_ATENDIDA . '",' . 'true);' . "\n";
                }
              }
            }

            $bolFlagSituacao = false;

            $strStaNivelAcessoGlobal = $objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo();

            if ($strStaNivelAcessoGlobal != ProtocoloRN::$NA_SIGILOSO) {
              $objRelSituacaoUnidadeDTO = new RelSituacaoUnidadeDTO();
              $objRelSituacaoUnidadeDTO->retNumIdSituacao();
              $objRelSituacaoUnidadeDTO->setNumIdUnidade($numIdUnidadeAtual);
              $objRelSituacaoUnidadeDTO->setStrSinAtivoSituacao('S');
              $objRelSituacaoUnidadeDTO->setNumMaxRegistrosRetorno(1);

              $objRelSituacaoUnidadeRN = new RelSituacaoUnidadeRN();
              $bolFlagSituacao = ($objRelSituacaoUnidadeRN->consultar($objRelSituacaoUnidadeDTO) != null);

              $objAndamentoSituacaoDTO = $objProcedimentoDTO->getObjAndamentoSituacaoDTO();

              if ($objAndamentoSituacaoDTO != null) {
                if ($bolAcaoAndamentoSituacaoGerenciar) {
                  $strLinkControleUnidadeGerenciar = $objSessaoSEI->assinarLink('controlador.php?acao=andamento_situacao_gerenciar&acao_origem=procedimento_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1');
                  $strTargetControleUnidadeGerenciar = '"ifrVisualizacao"';
                } else {
                  $strLinkControleUnidadeGerenciar = 'javascript:alert(\'' . $objPaginaSEI->formatarParametrosJavaScript(SituacaoINT::formatarSituacaoDesativada($objAndamentoSituacaoDTO->getStrNomeSituacao(),
                      $objAndamentoSituacaoDTO->getStrSinAtivoSituacao())) . '\');';
                  $strTargetControleUnidadeGerenciar = 'null';
                }

                $strNosAcaoProc .= 'NosAcoes[' . $numNoAcao++ . '] = new infraArvoreAcao("SITUACAO",' . '"SIT' . $dblIdProcedimento . '",' . '"' . $dblIdProcedimento . '",' . '"' . $strLinkControleUnidadeGerenciar . '",' . $strTargetControleUnidadeGerenciar . ',' . '"' . $objPaginaSEI->formatarParametrosJavaScript('Ponto de Controle' . "\n" . SituacaoINT::formatarSituacaoDesativada($objAndamentoSituacaoDTO->getStrNomeSituacao(),
                      $objAndamentoSituacaoDTO->getStrSinAtivoSituacao())) . '",' . '"' . Icone::SITUACAO . '",' . 'true);' . "\n";
              }
            }

            if ($numCodigoAcesso > 0 && $objSessaoSEI->verificarPermissao('comentario_listar')) {
              $objComentarioDTO = new ComentarioDTO();
              $objComentarioDTO->setNumMaxRegistrosRetorno(1);
              $objComentarioDTO->retNumIdComentario();
              $objComentarioDTO->setDblIdProcedimento($dblIdProcedimento);

              $objComentarioRN = new ComentarioRN();
              $arrObjComentarioDTO = $objComentarioRN->listar($objComentarioDTO);

              if (count($arrObjComentarioDTO)) {
                $strNosAcaoProc .= 'NosAcoes[' . $numNoAcao++ . '] = new infraArvoreAcao("COMENTARIOS",' . '"COMP' . $dblIdProcedimento . '",' . '"' . $dblIdProcedimento . '",' . '"' . $objSessaoSEI->assinarLink('controlador.php?acao=comentario_listar&acao_origem=procedimento_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '",' . '"ifrVisualizacao",' . '"Visualizar Comentários",' . '"' . Icone::COMENTARIO . '",' . 'true);' . "\n";
              }
            }

            $arrObjAcompanhamentoDTO = $objProcedimentoDTO->getArrObjAcompanhamentoDTO();

            if ($arrObjAcompanhamentoDTO != null) {
              $numAcompanhamentos = InfraArray::contar($arrObjAcompanhamentoDTO);

              if ($numAcompanhamentos) {
                $objAcompanhamentoRN = new AcompanhamentoRN();

                foreach ($arrObjAcompanhamentoDTO as $objAcompanhamentoDTO) {
                  if ($objAcompanhamentoDTO->getNumTipoVisualizacao() != AtividadeRN::$TV_VISUALIZADO) {
                    $objAcompanhamentoDTO->setObjProcedimentoDTO($objProcedimentoDTO);
                    $objAcompanhamentoRN->marcarVisualizado($objAcompanhamentoDTO);
                  }
                }

                if ($bolAcaoAcompanhamentoGerenciar) {
                  $strLinkAcompanhamentoGerenciar = $objSessaoSEI->assinarLink('controlador.php?acao=acompanhamento_gerenciar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1');
                  $strTargetAcompanhamentoGerenciar = '"ifrVisualizacao"';
                } else {
                  $strLinkAcompanhamentoGerenciar = 'javascript:alert(\'Acompanhamento Especial\');';
                  $strTargetAcompanhamentoGerenciar = 'null';
                }

                $strNosAcaoProc .= 'NosAcoes[' . $numNoAcao++ . '] = new infraArvoreAcao("ACOMPANHAMENTO",' . '"AC' . $dblIdProcedimento . '",' . '"' . $dblIdProcedimento . '",' . '"' . $strLinkAcompanhamentoGerenciar . '",' . $strTargetAcompanhamentoGerenciar . ',' . '"' . $objPaginaSEI->formatarParametrosJavaScript($numAcompanhamentos . ' ' . ($numAcompanhamentos == 1 ? 'Acompanhamento Especial' : 'Acompanhamentos Especiais')) . '",' . '"' . Icone::ACOMPANHAMENTO_ESPECIAL . '",' . 'true);' . "\n";
              }
            }


            $arrObjAndamentoMarcadorDTO = $objProcedimentoDTO->getArrObjAndamentoMarcadorDTO();

            if ($arrObjAndamentoMarcadorDTO != null) {
              if ($bolAcaoAndamentoMarcadorGerenciar) {
                $strLinkAndamentoMarcadorGerenciar = $objSessaoSEI->assinarLink('controlador.php?acao=andamento_marcador_gerenciar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1');
                $strTargetAndamentoMarcadorGerenciar = '"ifrVisualizacao"';
              } else {
                $strLinkAndamentoMarcadorGerenciar = 'javascript:alert(\'Marcador\');';
                $strTargetAndamentoMarcadorGerenciar = 'null';
              }

              foreach ($arrObjAndamentoMarcadorDTO as $objAndamentoMarcadorDTO) {
                $strNosAcaoProc .= 'NosAcoes[' . $numNoAcao++ . '] = new infraArvoreAcao("MARCADOR",' . '"MC' . $dblIdProcedimento . '",' . '"' . $dblIdProcedimento . '",' . '"' . $strLinkAndamentoMarcadorGerenciar . '",' . $strTargetAndamentoMarcadorGerenciar . ',' . '"' . $objPaginaSEI->formatarParametrosJavaScript('Marcador' . "\n" . MarcadorINT::formatarMarcadorDesativado($objAndamentoMarcadorDTO->getStrNomeMarcador(),
                      $objAndamentoMarcadorDTO->getStrSinAtivoMarcador())) . '",' . '"' . $objAndamentoMarcadorDTO->getStrArquivoIconeMarcador() . '",' . 'true);' . "\n";
              }
            }

            if ($objProcedimentoDTO->isSetObjControlePrazoDTO() && $objProcedimentoDTO->getObjControlePrazoDTO() != null) {
              $objControlePrazoDTO = $objProcedimentoDTO->getObjControlePrazoDTO();

              $strIcone = "";
              $strTexto = "";
              ControlePrazoINT::montarIconeControlePrazo($bolAcaoControlePrazoDefinir, $objProcedimentoDTO, false, '', $strIcone, $strTexto);

              if ($bolAcaoControlePrazoDefinir) {
                $strLinkControlePrazo = $objSessaoSEI->assinarLink('controlador.php?acao=controle_prazo_definir&acao_origem=procedimento_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&id_controle_prazo=' . $objControlePrazoDTO->getNumIdControlePrazo() . '&arvore=1');
                $strTargetControlePrazo = '"ifrVisualizacao"';
              } else {
                $strLinkControlePrazo = 'javascript:alert(\'Controle de Prazo\');';
                $strTargetControlePrazo = 'null';
              }

              $strNosAcaoProc .= 'NosAcoes[' . $numNoAcao++ . '] = new infraArvoreAcao("CONTROLEPRAZO",' . '"CP' . $dblIdProcedimento . '",' . '"' . $dblIdProcedimento . '",' . '"' . $strLinkControlePrazo . '",' . $strTargetControlePrazo . ',' . '"' . $strTexto . '",' . '"' . $strIcone . '",' . 'true);' . "\n";
            }

            $strAcoesProcedimento = '';
            $strHtmlProcesso = '';
            $numTabBotao = $objPaginaSEI->getProxTabBarraComandosSuperior();

            if (count($SEI_MODULOS)) {
              $objProcedimentoAPI = new ProcedimentoAPI();
              $objProcedimentoAPI->setIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
              $objProcedimentoAPI->setNumeroProtocolo($objProcedimentoDTO->getStrProtocoloProcedimentoFormatado());
              $objProcedimentoAPI->setIdTipoProcedimento($objProcedimentoDTO->getNumIdTipoProcedimento());
              $objProcedimentoAPI->setNomeTipoProcedimento($objProcedimentoDTO->getStrNomeTipoProcedimento());
              $objProcedimentoAPI->setIdTipoPrioridade($objProcedimentoDTO->getNumIdTipoPrioridade());
              $objProcedimentoAPI->setNivelAcesso($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo());
              $objProcedimentoAPI->setIdUnidadeGeradora($objProcedimentoDTO->getNumIdUnidadeGeradoraProtocolo());
              $objProcedimentoAPI->setIdOrgaoUnidadeGeradora($objProcedimentoDTO->getNumIdOrgaoUnidadeGeradoraProtocolo());
              $objProcedimentoAPI->setIdHipoteseLegal($objProcedimentoDTO->getNumIdHipoteseLegalProtocolo());
              $objProcedimentoAPI->setGrauSigilo($objProcedimentoDTO->getStrStaGrauSigiloProtocolo());
              $objProcedimentoAPI->setCodigoAcesso($numCodigoAcesso);
              $objProcedimentoAPI->setSinAberto($bolFlagAberto ? 'S' : 'N');
            }

            if ($bolFlagEliminado) {
              $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
              $objAtributoAndamentoDTO->retNumIdAtividade();
              $objAtributoAndamentoDTO->setDblIdProtocoloAtividade($objProcedimentoDTO->getDblIdProcedimento());
              $objAtributoAndamentoDTO->setNumIdTarefaAtividade(TarefaRN::$TI_PROCESSO_INCLUSAO_EDITAL_ELIMINACAO);
              $objAtributoAndamentoDTO->retStrValor();
              $objAtributoAndamentoDTO->retStrIdOrigem();
              $objAtributoAndamentoDTO->setStrNome("DOCUMENTO");
              $objAtributoAndamentoDTO->setNumMaxRegistrosRetorno(1);
              $objAtributoAndamentoDTO->setOrdNumIdAtividade(InfraDTO::$TIPO_ORDENACAO_DESC);

              $objAtributoAndamentoRN = new AtributoAndamentoRN();
              $objAtributoAndamentoDTO = $objAtributoAndamentoRN->consultarRN1366($objAtributoAndamentoDTO);

              if ($objAtributoAndamentoDTO != null) {
                $strHtmlProcesso = '<div style="font-size:.875rem;display:inline;">Processo eliminado em ' . $objProcedimentoDTO->getDtaEliminacao() . ' (edital <a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&id_documento=' . $objAtributoAndamentoDTO->getStrIdOrigem()) . '" target="_blank" class="ancoraVisualizacaoArvore">' . $objAtributoAndamentoDTO->getStrValor() . '</a>).</div>';
              } else {
                $strHtmlProcesso = '<div style="font-size:.875rem;display:inline;">Processo eliminado.</div>';
              }
            } else {
              //não monta links e html se não tem acesso
              if ($strLinkProcesso != 'about:blank') {
                if (!$bolFlagBloqueado) {
                  if ($bolFlagAberto) {
                    if ($bolAcaoEscolherTipo) {
                      $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=documento_escolher_tipo&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img   src="' . Icone::DOCUMENTO_INCLUIR . '" alt="Incluir Documento" title="Incluir Documento"/></a>';
                    }
                  } else {
                    if ($bolFlagProtocolo && $bolAcaoDocumentoReceber && !$bolFlagAnexado && !$bolFlagSobrestado) {
                      $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=documento_receber&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1&flag_protocolo=S') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::DOCUMENTO_INCLUIR . '" alt="Registrar Documento Externo" title="Registrar Documento Externo"/></a>';
                    }
                  }
                }

                if ($bolAcaoProcedimentoEscolherTipoRelacionado) {
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=procedimento_escolher_tipo_relacionado&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento_destino=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::PROCESSO_GERAR_RELACIONADO . '" alt="Iniciar Processo Relacionado" title="Iniciar Processo Relacionado"/></a>';
                }

                if (OuvidoriaRN::verificarAcessoInteressado($objProcedimentoDTO)) {
                  if ($bolAcaoAlterarProcedimento && !$bolFlagBloqueado && ($bolFlagAberto || $bolFlagAbertoAnexado || ($bolFlagProtocolo && $objProcedimentoDTO->getNumIdUnidadeGeradoraProtocolo() == $numIdUnidadeAtual))) {
                    $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=procedimento_alterar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::PROCESSO_ALTERAR . '" alt="Consultar/Alterar Processo" title="Consultar/Alterar Processo"/></a>';
                  } else {
                    if ($bolAcaoConsultarProcedimento) {
                      $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=procedimento_consultar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::PROCESSO_ALTERAR . '" alt="Consultar Processo" title="Consultar Processo"/></a>';
                    }
                  }
                }

                if ($bolAcaoAcompanhamentoGerenciar /* && $strStaNivelAcessoGlobal != ProtocoloRN::$NA_SIGILOSO */) {
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=acompanhamento_gerenciar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::ACOMPANHAMENTO_ESPECIAL_CADASTRO . '" alt="Acompanhamento Especial" title="Acompanhamento Especial"/></a>';
                }

                if ($bolFlagAberto && $bolAcaoProcedimentoCiencia) {
                  $strAcoesProcedimento .= '<a href="#" onclick="cienciaProcesso();" tabindex="' . $numTabBotao . '" ><img src="' . Icone::CIENCIA . '" alt="Ciência" title="Ciência" />';
                }

                if ($bolFlagAberto && !$bolFlagBloqueado && $bolAcaoProcedimentoEnviar && $strStaNivelAcessoGlobal != ProtocoloRN::$NA_SIGILOSO) {
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=procedimento_enviar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::PROCESSO_ENVIAR . '" alt="Enviar Processo" title="Enviar Processo" /></a>';
                }

                if ($bolFlagAberto && $bolAcaoProcedimentoCredencialGerenciar && $strStaNivelAcessoGlobal == ProtocoloRN::$NA_SIGILOSO) {
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=procedimento_credencial_gerenciar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::CREDENCIAL_GERENCIAR . '" alt="Gerenciar Credenciais de Acesso" title="Gerenciar Credenciais de Acesso" /></a>';
                }

                if ($bolFlagAberto && $bolAcaoProcedimentoCredencialRenunciar && $strStaNivelAcessoGlobal == ProtocoloRN::$NA_SIGILOSO && $arrObjProtocoloDTO[$dblIdProcedimento]->getStrSinCredencialProcesso() == 'S') {
                  $strAcoesProcedimento .= '<a href="#" onclick="renunciarCredencial();" tabindex="' . $numTabBotao . '" ><img src="' . Icone::CREDENCIAL_RENUNCIAR . '" alt="Renunciar Credenciais de Acesso" title="Renunciar Credenciais de Acesso" />';
                }

                if ($bolFlagAberto && $bolAcaoDefinirAtividade) {
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=procedimento_atualizar_andamento&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::PROCESSO_ATUALIZAR_ANDAMENTO . '" alt="Atualizar Andamento" title="Atualizar Andamento" /></a>';
                }

                if ($bolFlagAberto && $bolAcaoAtribuirProcesso && $strStaNivelAcessoGlobal != ProtocoloRN::$NA_SIGILOSO) {
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=procedimento_atribuicao_cadastrar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::PROCESSO_ATRIBUIR . '" alt="Atribuir Processo" title="Atribuir Processo" /></a>';
                }

                if ($bolAcaoProtocoloModeloGerenciar && $strStaNivelAcessoGlobal != ProtocoloRN::$NA_SIGILOSO) {
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=protocolo_modelo_gerenciar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_protocolo=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::PROCESSO_MODELO . '" alt="Adicionar aos Favoritos" title="Adicionar aos Favoritos"/></a>';
                }

                if ($bolAcaoDuplicarProcedimento && ($bolFlagAberto || $bolFlagAnexado) && $strStaNivelAcessoGlobal != ProtocoloRN::$NA_SIGILOSO && $objProcedimentoDTO->getStrSinInternoTipoProcedimento() == 'N') {
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=procedimento_duplicar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::PROCESSO_DUPLICAR . '" alt="Duplicar Processo" title="Duplicar Processo"/></a>';
                }

                if (($bolFlagAberto || $bolFlagAnexado) && !$bolFlagBloqueado && $bolAcaoProcedimentoEnviarEmail) {
                  $strAcoesProcedimento .= '<a href="#" onclick="enviarEmailProcedimento();" tabindex="' . $numTabBotao . '" ><img src="' . Icone::EMAIL_ENVIAR . '" alt="Enviar Correspondência Eletrônica" title="Enviar Correspondência Eletrônica"/>';
                }

                if ($bolFlagAberto && $bolAcaoProcedimentoRelacionar) {
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=procedimento_relacionar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::PROCESSO_RELACIONADOS . '" alt="Relacionamentos do Processo" title="Relacionamentos do Processo"/></a>';
                }

                if ($bolFlagAberto && $bolAcaoIncluirEmBloco && $strStaNivelAcessoGlobal != ProtocoloRN::$NA_SIGILOSO) {
                  $strAcoesProcedimento .= '<a href="#" onclick="incluirEmBloco();" tabindex="' . $numTabBotao . '" ><img src="' . Icone::BLOCO_INCLUIR_PROTOCOLO . '" alt="Incluir em Bloco" title="Incluir em Bloco"/>';
                }

                if ($bolFlagAberto && !$bolFlagBloqueado && !$bolFlagLinhaDireta && $bolAcaoArvoreOrdenar && $numProtocolosAssociados > 1) {
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=arvore_ordenar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::PROCESSO_ORDENAR_ARVORE . '" alt="Ordenar Árvore do Processo" title="Ordenar Árvore do Processo"/></a>';
                }

                if ($bolFlagAberto && $bolAcaoAcessoExternoGerenciar) {
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=acesso_externo_gerenciar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::ACESSO_EXTERNO . '" alt="Gerenciar Disponibilizações de Acesso Externo" title="Gerenciar Disponibilizações de Acesso Externo"/></a>';
                }

                if ($bolFlagAberto && $bolAcaoAcessoFederacaoGerenciar && $bolFederacaoHabilitado) {
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=acesso_federacao_gerenciar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&id_procedimento_federacao=' . $objProcedimentoDTO->getStrIdProtocoloFederacaoProtocolo() . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::FEDERACAO_GERENCIAR . '" alt="Gerenciar Envios para o SEI Federação" title="Gerenciar Envios para o SEI Federação"/></a>';
                }

                if ($bolFlagTramitacao && $bolAcaoRegistrarAnotacao) {
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=anotacao_registrar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::ANOTACAO_CADASTRO . '" alt="Anotações" title="Anotações" /></a>';
                }

                if ($bolFlagAberto && !$bolFlagBloqueado && $bolAcaoSobrestarProcesso && !$bolFlagSobrestado && $strStaNivelAcessoGlobal != ProtocoloRN::$NA_SIGILOSO) {
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=procedimento_sobrestar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::PROCESSO_SOBRESTAR . '" alt="Sobrestar Processo" title="Sobrestar Processo" /></a>';
                }

                if ($bolFlagAberto && !$bolFlagBloqueado && $bolAcaoAnexarProcesso && !$bolFlagAnexado && $strStaNivelAcessoGlobal != ProtocoloRN::$NA_SIGILOSO) {
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=procedimento_anexar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::PROCESSO_ANEXAR . '" alt="Anexar Processo" title="Anexar Processo" /></a>';
                }

                if ($bolAcaoRemoverSobrestamento && !$bolFlagBloqueado && $bolFlagSobrestado && $bolUnidadeSobrestamento) {
                  $strAcoesProcedimento .= '<a href="#" onclick="removerSobrestamentoProcesso();" tabindex="' . $numTabBotao . '" ><img src="' . Icone::PROCESSO_REMOVER_SOBRESTAMENTO . '" alt="Remover Sobrestamento do Processo" title="Remover Sobrestamento do Processo" />';
                }

                if (!$bolFlagAberto && $bolAcaoReaberturaProgramada && $bolFlagTramitacao && !$bolFlagSobrestado && !$bolFlagAnexado && $strStaNivelAcessoGlobal != ProtocoloRN::$NA_SIGILOSO) {
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=reabertura_programada_gerenciar&acao_origem=procedimento_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::PROCESSO_REABERTURA_PROGRAMADA . '" alt="Reabertura Programada do Processo" title="Reabertura Programada do Processo" /></a>';
                }

                if (!$bolFlagAberto && $bolAcaoReabrirProcedimento && $bolFlagTramitacao && !$bolFlagSobrestado && !$bolFlagAnexado) {
                  $strAcoesProcedimento .= '<a href="#" onclick="reabrirProcesso();" tabindex="' . $numTabBotao . '" ><img src="' . Icone::PROCESSO_REABRIR . '" alt="Reabrir Processo" title="Reabrir Processo" />';
                }

                if ($bolFlagAberto && $bolAcaoConcluirProcedimento) {
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=procedimento_concluir&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::PROCESSO_CONCLUIR . '" alt="Concluir Processo" title="Concluir Processo"/></a>';
                }

                if ($bolAcaoProcedimentoGerarPdf && $numProtocolosAssociados > 0) {
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=procedimento_gerar_pdf&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::PROCESSO_GERAR_PDF . '" alt="Gerar Arquivo PDF do Processo" title="Gerar Arquivo PDF do Processo"/></a>';
                }

                if ($bolAcaoProcedimentoGerarZip && $numProtocolosAssociados > 0) {
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=procedimento_gerar_zip&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::PROCESSO_GERAR_ZIP . '" alt="Gerar Arquivo ZIP do Processo" title="Gerar Arquivo ZIP do Processo"/></a>';
                }

                if ($bolFlagTramitacao && $bolAcaoComentarioCadastrar) {
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=comentario_listar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::COMENTARIO . '" alt="Comentários" title="Comentários"/></a>';
                }

                if ($bolFlagAberto && !$bolFlagBloqueado && !$bolFlagSobrestado && $bolAcaoExcluirProcedimento && $objProcedimentoDTO->getNumIdUnidadeGeradoraProtocolo() == $numIdUnidadeAtual && $numProtocolosAssociados == 0) {
                  $strAcoesProcedimento .= '<a href="#" onclick="excluirProcesso();" tabindex="' . $numTabBotao . '" ><img src="' . Icone::PROTOCOLO_EXCLUIR . '" alt="Excluir" title="Excluir" />';
                }

                if ($bolFlagAberto && $bolAcaoReencaminharOuvidoria && $objUnidadeDTO->getStrSinOuvidoria() == 'S' && $objProcedimentoDTO->getStrSinOuvidoriaTipoProcedimento() == 'S') {
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=procedimento_reencaminhar_ouvidoria&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::OUVIDORIA_REENCAMINHAR . '" alt="Correção de Encaminhamento" title="Correção de Encaminhamento" /></a>';
                }

                if ($bolAcaoFinalizarOuvidoria && $objUnidadeDTO->getStrSinOuvidoria() == 'S' && $objProcedimentoDTO->getStrSinOuvidoriaTipoProcedimento() == 'S' /* && $objProcedimentoDTO->getNumIdUnidadeGeradoraProtocolo()==$numIdUnidadeAtual*/) {
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=procedimento_finalizar_ouvidoria&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::OUVIDORIA_FINALIZAR . '" alt="Registro do Atendimento" title="Registro do Atendimento" /></a>';
                }

                if ($bolAcaoAndamentoSituacaoGerenciar && $bolFlagSituacao && $strStaNivelAcessoGlobal != ProtocoloRN::$NA_SIGILOSO) {
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=andamento_situacao_gerenciar&acao_origem=procedimento_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::SITUACAO_GERENCIAR . '" alt="Gerenciar Ponto de Controle" title="Gerenciar Ponto de Controle" /></a>';
                }

                if ($bolFlagAberto && $bolAcaoAndamentoMarcadorGerenciar) {
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=andamento_marcador_gerenciar&acao_origem=procedimento_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::MARCADOR_GERENCIAR . '" alt="Gerenciar Marcador" title="Gerenciar Marcador" /></a>';
                }

                if ($bolAcaoControlePrazoDefinir) {
                  $objControlePrazoDTO = $objProcedimentoDTO->getObjControlePrazoDTO();
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=controle_prazo_definir&acao_origem=procedimento_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . ($objControlePrazoDTO != null ? '&id_controle_prazo=' . $objControlePrazoDTO->getNumIdControlePrazo() : '') . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::CONTROLE_PRAZO_GERENCIAR . '" alt="Controle de Prazo" title="Controle de Prazo" /></a>';
                }

                if ($bolAcaoPlanoTrabalhoDetalhar && $numCodigoAcesso > 0){
                  if ($objProcedimentoDTO->getNumIdPlanoTrabalho() == null) {
                    //mostrar associação apenas para administradores
                    if (SessaoSEI::getInstance()->verificarPermissao('plano_trabalho_cadastrar') && SessaoSEI::getInstance()->verificarPermissao('procedimento_plano_associar')) {
                      $strAcoesProcedimento .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_plano_associar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . PaginaSEI::getInstance()->getProxTabBarraComandosSuperior() . '" ><img  src="' . Icone::PLANO_TRABALHO . '" alt="Plano de Trabalho" title="Plano de Trabalho" /></a>';
                    }
                  } else {
                    $strAcoesProcedimento .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=plano_trabalho_detalhar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . PaginaSEI::getInstance()->getProxTabBarraComandosSuperior() . '" ><img  src="' . Icone::PLANO_TRABALHO . '" alt="Plano de Trabalho" title="Plano de Trabalho" /></a>';
                  }
                }

                if ($bolFlagTramitacao && $bolAcaoProcedimentoControlar && !$bolFlagAnexado) {
                  $strAcoesProcedimento .= '<a href="#" onclick="parent.parent.document.location.href=\\\'' . $objSessaoSEI->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=procedimento_visualizar&acao_retorno=principal' . $objPaginaSEI->montarAncora($dblIdProcedimento)) . '\\\';" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::CONTROLE_PROCESSOS . '" alt="Controle de Processos" title="Controle de Processos" /></a>';
                }

                if ($bolAcaoProcedimentoPesquisar && $strStaNivelAcessoGlobal != ProtocoloRN::$NA_SIGILOSO && $numCodigoAcesso != ProtocoloRN::$CA_BLOCO) {
                  $strAcoesProcedimento .= '<a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=procedimento_pesquisar&acao_origem=procedimento_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '" tabindex="' . $numTabBotao . '" ><img  src="' . Icone::PROCESSO_PESQUISAR . '" alt="Pesquisar no Processo" title="Pesquisar no Processo" /></a>';
                }

                foreach ($SEI_MODULOS as $seiModulo) {
                  if (($arrRetIntegracao = $seiModulo->executar('montarBotaoProcesso', $objProcedimentoAPI)) != null) {
                    foreach ($arrRetIntegracao as $strAcaoProcedimento) {
                      $strAcoesProcedimento .= $strAcaoProcedimento;
                    }
                  }
                }

                if ($objProcedimentoDTO->getStrStaEstadoProtocolo() == ProtocoloRN::$TE_PROCEDIMENTO_ANEXADO) {
                  $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
                  $objRelProtocoloProtocoloDTO->retDblIdProtocolo1();
                  $objRelProtocoloProtocoloDTO->retStrProtocoloFormatadoProtocolo1();
                  $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objProcedimentoDTO->getDblIdProcedimento());
                  $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);

                  $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
                  $objRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO);

                  $strHtmlProcesso = '<div style="font-size:.875rem;display:inline;">Processo anexado ao processo <a href="' . $objSessaoSEI->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=arvore_visualizar&id_procedimento=' . $objRelProtocoloProtocoloDTO->getDblIdProtocolo1() . '&id_procedimento_anexado=' . $objProcedimentoDTO->getDblIdProcedimento()) . '" target="_blank" class="ancoraVisualizacaoArvore">' . $objRelProtocoloProtocoloDTO->getStrProtocoloFormatadoProtocolo1() . '</a>.</div>';
                } else {
                  $objAtividadeDTO = new AtividadeDTO();
                  $objAtividadeDTO->setDistinct(true);
                  $objAtividadeDTO->retStrSiglaUnidade();
                  $objAtividadeDTO->retStrDescricaoUnidade();

                  $objAtividadeDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);

                  if ($strStaNivelAcessoGlobal == ProtocoloRN::$NA_SIGILOSO) {
                    $objAtividadeDTO->retNumIdUsuario();
                    $objAtividadeDTO->retStrSiglaUsuario();
                    $objAtividadeDTO->retStrNomeUsuario();
                  } else {
                    $objAtividadeDTO->retNumIdUsuarioAtribuicao();
                    $objAtividadeDTO->retStrSiglaUsuarioAtribuicao();
                    $objAtividadeDTO->retStrNomeUsuarioAtribuicao();

                    //ordena descendente pois no envio de processo que já existe na unidade e está atribuído ficará com mais de um andamento em aberto
                    //desta forma os andamentos com usuário nulo (envios do processo) serão listados depois
                    $objAtividadeDTO->setOrdStrSiglaUsuarioAtribuicao(InfraDTO::$TIPO_ORDENACAO_DESC);
                  }
                  $objAtividadeDTO->setDblIdProtocolo($dblIdProcedimento);
                  $objAtividadeDTO->setDthConclusao(null);

                  //sigiloso sem credencial nao considera o usuario atual
                  if ($strStaNivelAcessoGlobal == ProtocoloRN::$NA_SIGILOSO) {
                    $objAcessoDTO = new AcessoDTO();
                    $objAcessoDTO->setDistinct(true);
                    $objAcessoDTO->retNumIdUsuario();
                    $objAcessoDTO->setDblIdProtocolo($dblIdProcedimento);
                    $objAcessoDTO->setStrStaTipo(AcessoRN::$TA_CREDENCIAL_PROCESSO);

                    $objAcessoRN = new AcessoRN();
                    $arrObjAcessoDTO = $objAcessoRN->listar($objAcessoDTO);

                    $objAtividadeDTO->setNumIdUsuario(InfraArray::converterArrInfraDTO($arrObjAcessoDTO, 'IdUsuario'), InfraDTO::$OPER_IN);
                  }

                  $arrObjAtividadeDTO = $objAtividadeRN->listarRN0036($objAtividadeDTO);

                  if ($strStaNivelAcessoGlobal != ProtocoloRN::$NA_SIGILOSO) {
                    //filtra andamentos com indicação de usuário atribuído
                    $arrObjAtividadeDTO = InfraArray::distinctArrInfraDTO($arrObjAtividadeDTO, 'SiglaUnidade');
                  }

                  if (count($arrObjAtividadeDTO) == 0) {
                    $strHtmlProcesso .= 'Processo não possui andamentos abertos.';
                  } else {
                    foreach ($arrObjAtividadeDTO as $objAtividadeDTO) {
                      $objAtividadeDTO->setStrSiglaUnidade($objPaginaSEI->formatarParametrosJavaScript($objAtividadeDTO->getStrSiglaUnidade()));
                      $objAtividadeDTO->setStrDescricaoUnidade($objPaginaSEI->formatarParametrosJavaScript($objAtividadeDTO->getStrDescricaoUnidade()));

                      if ($objAtividadeDTO->isSetNumIdUsuarioAtribuicao()) {
                        $objAtividadeDTO->setStrSiglaUsuarioAtribuicao($objPaginaSEI->formatarParametrosJavaScript($objAtividadeDTO->getStrSiglaUsuarioAtribuicao()));
                        $objAtividadeDTO->setStrNomeUsuarioAtribuicao($objPaginaSEI->formatarParametrosJavaScript($objAtividadeDTO->getStrNomeUsuarioAtribuicao()));
                      }

                      if ($objAtividadeDTO->isSetNumIdUsuario()) {
                        $objAtividadeDTO->setStrSiglaUsuario($objPaginaSEI->formatarParametrosJavaScript($objAtividadeDTO->getStrSiglaUsuario()));
                        $objAtividadeDTO->setStrNomeUsuario($objPaginaSEI->formatarParametrosJavaScript($objAtividadeDTO->getStrNomeUsuario()));
                      }
                    }

                    if (count($arrObjAtividadeDTO) == 1) {
                      if ($strStaNivelAcessoGlobal != ProtocoloRN::$NA_SIGILOSO) {
                        $strHtmlProcesso .= 'Processo ' . (!$bolFlagSobrestado ? 'aberto somente' : 'sobrestado') . ' na unidade ';
                        $objAtividadeDTO = $arrObjAtividadeDTO[0];
                        $strHtmlProcesso .= '<a alt="' . $objAtividadeDTO->getStrDescricaoUnidade() . '" title="' . $objAtividadeDTO->getStrDescricaoUnidade() . '" class="ancoraSigla">' . $objAtividadeDTO->getStrSiglaUnidade() . '</a>';
                        if ($objAtividadeDTO->getNumIdUsuarioAtribuicao() != null) {
                          $strHtmlProcesso .= ' (atribuído para <a alt="' . $objAtividadeDTO->getStrNomeUsuarioAtribuicao() . '" title="' . $objAtividadeDTO->getStrNomeUsuarioAtribuicao() . '" class="ancoraSigla">' . $objAtividadeDTO->getStrSiglaUsuarioAtribuicao() . '</a>)';
                        }
                        $strHtmlProcesso .= '.';
                      } else {
                        $strHtmlProcesso .= 'Processo ' . (!$bolFlagSobrestado ? 'aberto somente' : 'sobrestado') . ' com o usuário ';
                        $objAtividadeDTO = $arrObjAtividadeDTO[0];
                        $strHtmlProcesso .= '<a alt="' . $objAtividadeDTO->getStrNomeUsuario() . '" title="' . $objAtividadeDTO->getStrNomeUsuario() . '" class="ancoraSigla">' . $objAtividadeDTO->getStrSiglaUsuario() . '</a>';
                        $strHtmlProcesso .= '&nbsp;/&nbsp;';
                        $strHtmlProcesso .= '<a alt="' . $objAtividadeDTO->getStrDescricaoUnidade() . '" title="' . $objAtividadeDTO->getStrDescricaoUnidade() . '" class="ancoraSigla">' . $objAtividadeDTO->getStrSiglaUnidade() . '</a>';
                        $strHtmlProcesso .= '.';
                      }
                    } else {
                      if ($strStaNivelAcessoGlobal != ProtocoloRN::$NA_SIGILOSO) {
                        $strHtmlProcesso .= 'Processo aberto nas unidades:<br />';
                        foreach ($arrObjAtividadeDTO as $objAtividadeDTO) {
                          $strHtmlProcesso .= '<a alt="' . $objAtividadeDTO->getStrDescricaoUnidade() . '" title="' . $objAtividadeDTO->getStrDescricaoUnidade() . '" class="ancoraSigla">' . $objAtividadeDTO->getStrSiglaUnidade() . '</a>';
                          if ($objAtividadeDTO->getNumIdUsuarioAtribuicao() != null) {
                            $strHtmlProcesso .= ' (atribuído para <a alt="' . $objAtividadeDTO->getStrNomeUsuarioAtribuicao() . '" title="' . $objAtividadeDTO->getStrNomeUsuarioAtribuicao() . '" class="ancoraSigla">' . $objAtividadeDTO->getStrSiglaUsuarioAtribuicao() . '</a>)';
                          }
                          $strHtmlProcesso .= '<br />';
                        }
                      } else {
                        $strHtmlProcesso .= 'Processo aberto com os usuários:<br />';
                        foreach ($arrObjAtividadeDTO as $objAtividadeDTO) {
                          $strHtmlProcesso .= '<a alt="' . $objAtividadeDTO->getStrNomeUsuario() . '" title="' . $objAtividadeDTO->getStrNomeUsuario() . '" class="ancoraSigla">' . $objAtividadeDTO->getStrSiglaUsuario() . '</a>';
                          $strHtmlProcesso .= '&nbsp;/&nbsp;';
                          $strHtmlProcesso .= '<a alt="' . $objAtividadeDTO->getStrDescricaoUnidade() . '" title="' . $objAtividadeDTO->getStrDescricaoUnidade() . '" class="ancoraSigla">' . $objAtividadeDTO->getStrSiglaUnidade() . '</a>';
                          $strHtmlProcesso .= '<br />';
                        }
                      }
                    }
                  }
                  $strHtmlProcesso .= '<br />';
                }

                foreach ($SEI_MODULOS as $seiModulo) {
                  if (($strMensagemModulo = $seiModulo->executar('montarMensagemProcesso', $objProcedimentoAPI)) != null) {
                    $strHtmlProcesso .= '<br />' . $strMensagemModulo . '<br />';
                  }
                }
              }
            }

            $strNoProc .= 'Nos[' . $numNo . '].acoes = \'' . $strAcoesProcedimento . '\';' . "\n";
            $strNoProc .= 'Nos[' . $numNo . '].src = \'\';' . "\n";
            $strNoProc .= 'Nos[' . $numNo . '].html = \'' . $strHtmlProcesso . '\';';
            $numNo++;

            if ($objSessaoSEI->verificarPermissao('base_conhecimento_listar_associadas')) {
              $objRelBaseConhecTipoProcedDTO = new RelBaseConhecTipoProcedDTO();
              $objRelBaseConhecTipoProcedDTO->setNumMaxRegistrosRetorno(1);
              $objRelBaseConhecTipoProcedDTO->retNumIdBaseConhecimento();
              $objRelBaseConhecTipoProcedDTO->setNumIdTipoProcedimento($objProcedimentoDTO->getNumIdTipoProcedimento());
              $objRelBaseConhecTipoProcedDTO->setStrStaEstadoBaseConhecimento(BaseConhecimentoRN::$TE_LIBERADO);

              $objRelBaseConhecTipoProcedRN = new RelBaseConhecTipoProcedRN();
              $arrObjRelBaseConhecTipoProcedDTO = $objRelBaseConhecTipoProcedRN->listar($objRelBaseConhecTipoProcedDTO);

              if (count($arrObjRelBaseConhecTipoProcedDTO)) {
                $strNosAcaoProc .= 'NosAcoes[' . $numNoAcao++ . '] = new infraArvoreAcao("BASE_CONHECIMENTO",' . '"BC",' . '"' . $dblIdProcedimento . '",' . '"' . $objSessaoSEI->assinarLink('controlador.php?acao=base_conhecimento_listar_associadas&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento=' . $dblIdProcedimento . '&id_tipo_procedimento=' . $objProcedimentoDTO->getNumIdTipoProcedimento() . '&arvore=1') . '",' . '"ifrVisualizacao",' . '"Visualizar Bases de Conhecimento Associadas",' . '"' . Icone::BASE_CONHECIMENTO . '",' . 'true);' . "\n";
              }
            }

            if ($objProcedimentoDTO->getStrSinCiencia() == 'S' && $numCodigoAcesso > 0) {
              $strNosAcaoProc .= 'NosAcoes[' . $numNoAcao++ . '] = new infraArvoreAcao("CIENCIAS",' . '"C",' . '"' . $dblIdProcedimento . '",' . '"' . $objSessaoSEI->assinarLink('controlador.php?acao=protocolo_ciencia_listar&acao_origem=procedimento_visualizar&id_procedimento=' . $dblIdProcedimento . '&arvore=1') . '",' . '"ifrVisualizacao",' . '"Visualizar Ciências",' . '"' . Icone::CIENCIA . '",' . 'true);' . "\n";
            }

            if ($bolFederacaoHabilitado) {
              $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
              $arrObjInstalacaoFederacaoDTO = $objInstalacaoFederacaoRN->listarAcessos($objProcedimentoDTO);

              if (count($arrObjInstalacaoFederacaoDTO)) {
                $strIcone = Icone::FEDERACAO;

                $strNoProc .= "\n\n" . 'Nos[' . $numNo . '] = new infraArvoreNo("FEDERACAO",' . '"FEDERACAO",' . '"' . $dblIdProcedimento . '",' . '"javascript:abrirFecharPasta(\'FEDERACAO\');",' . '"_self",' . '"SEI Federação",' . '"SEI Federação",' . '"' . $strIcone . '",' . '"' . $strIcone . '",' . '"' . $strIcone . '",' . 'true,' . 'true,' . 'null,' . 'null,' . 'null);' . "\n";

                $strNoProc .= 'Nos[' . $numNo . '].acoes = \'\';' . "\n";
                $strNoProc .= 'Nos[' . $numNo . '].src = \'\';' . "\n";
                $strNoProc .= 'Nos[' . $numNo . '].html = \'\';' . "\n";
                $strNoProc .= 'Nos[' . $numNo . '].carregado = true;' . "\n";
                $numNo++;

                $bolAcessoFederacaoLocal = false;
                foreach ($arrObjInstalacaoFederacaoDTO as $objInstalacaoFederacaoDTO) {
                  if ($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao() == $objInstalacaoFederacaoRN->obterIdInstalacaoFederacaoLocal()) {
                    $bolAcessoFederacaoLocal = ($objInstalacaoFederacaoDTO->getStrSinAcesso() == 'S');
                    break;
                  }
                }

                foreach ($arrObjInstalacaoFederacaoDTO as $objInstalacaoFederacaoDTO) {
                  $arrObjOrgaoFederacaoDTO = $objInstalacaoFederacaoDTO->getArrObjOrgaoFederacaoDTO();

                  $bolApenasUmOrgao = (count($arrObjOrgaoFederacaoDTO) == 1);

                  $strNoPaiOrgaoFederacao = 'FEDERACAO';

                  if (!$bolApenasUmOrgao) {
                    $strNoPaiOrgaoFederacao = $objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao();

                    $strIcone = Icone::FEDERACAO_INSTALACAO;

                    $strIdentificacaoInstalacaoFederacao = $objPaginaSEI->formatarParametrosJavaScript($objInstalacaoFederacaoDTO->getStrSigla());
                    $strTooltipInstalacaoFederacao = $objPaginaSEI->formatarParametrosJavaScript('Instalação ' . $objInstalacaoFederacaoDTO->getStrDescricao());

                    $strNoProc .= "\n\n" . 'Nos[' . $numNo . '] = new infraArvoreNo("INSTALACAO_FEDERACAO",' . '"' . $objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao() . '",' . '"FEDERACAO",' . '"javascript:abrirFecharPasta(\'' . $objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao() . '\');",' . '"_self",' . '"' . $strIdentificacaoInstalacaoFederacao . '",' . '"' . $strTooltipInstalacaoFederacao . '",' . '"' . $strIcone . '",' . '"' . $strIcone . '",' . '"' . $strIcone . '",' . 'true,' . 'true,' . 'null,' . 'null,' . 'null);' . "\n";

                    $strNoProc .= 'Nos[' . $numNo . '].acoes = \'\';' . "\n";
                    $strNoProc .= 'Nos[' . $numNo . '].src = \'\';' . "\n";
                    $strNoProc .= 'Nos[' . $numNo . '].html = \'\';' . "\n";
                    $strNoProc .= 'Nos[' . $numNo . '].carregado = true;' . "\n";
                    $numNo++;
                  }

                  foreach ($arrObjOrgaoFederacaoDTO as $objOrgaoFederacaoDTO) {
                    $strIdentificacaoOrgaoFederacao = $objPaginaSEI->formatarParametrosJavaScript($objOrgaoFederacaoDTO->getStrSigla());
                    $strTooltipOrgaoFederacao = $objPaginaSEI->formatarParametrosJavaScript($objOrgaoFederacaoDTO->getStrDescricao());

                    $strLinkProcedimentoFederacao = 'about:blank';
                    $strSrc = '';
                    $strOrgaoFederacaoHabilitado = 'false';

                    if ($bolAcessoFederacaoLocal && $objOrgaoFederacaoDTO->getStrSinAcesso() == 'S') {
                      $strIcone = Icone::PROCESSO_FEDERACAO;

                      if ($numCodigoAcesso > 0) {
                        if ($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao() != $objInstalacaoFederacaoRN->obterIdInstalacaoFederacaoLocal()) {
                          $strLinkProcedimentoFederacao = $objSessaoSEI->assinarLink('controlador.php?acao=arvore_visualizar&acao_origem=procedimento_visualizar&id_procedimento=' . $dblIdProcedimento);
                          $strSrc = $objSessaoSEI->assinarLink('controlador.php?acao=processo_consulta_federacao&acao_origem=procedimento_visualizar&id_procedimento_federacao=' . $objProcedimentoDTO->getStrIdProtocoloFederacaoProtocolo() . '&id_instalacao_federacao=' . $objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao() . '&id_orgao_federacao=' . $objOrgaoFederacaoDTO->getStrIdOrgaoFederacao());
                          $strOrgaoFederacaoHabilitado = 'true';
                        }
                      }
                    } else {
                      if ($objOrgaoFederacaoDTO->getStrSinAcesso() == 'S') {
                        $strIcone = Icone::PROCESSO_FEDERACAO;
                      } else {
                        $strIcone = Icone::PROCESSO_FEDERACAO_SEM_ACESSO;
                        $strLinkProcedimentoFederacao = 'javascript:alert(\'Instalação ' . $objInstalacaoFederacaoDTO->getStrSigla() . ' não possui mais acesso ao processo pelo SEI Federação.\');';
                        $strOrgaoFederacaoHabilitado = 'true';
                      }
                    }

                    $strNoProc .= "\n\n" . 'Nos[' . $numNo . '] = new infraArvoreNo("ORGAO_FEDERACAO",' . '"' . $objOrgaoFederacaoDTO->getStrIdOrgaoFederacao() . '",' . '"' . $strNoPaiOrgaoFederacao . '",' . '"' . $strLinkProcedimentoFederacao . '",' . '"ifrConteudoVisualizacao",' . '"' . $strIdentificacaoOrgaoFederacao . '",' . '"' . $strTooltipOrgaoFederacao . '",' . '"' . $strIcone . '",' . '"' . $strIcone . '",' . '"' . $strIcone . '",' . 'true,' . $strOrgaoFederacaoHabilitado . ',' . (isset($arrProtocolosVisitados[$objOrgaoFederacaoDTO->getStrIdOrgaoFederacao()]) ? '"noVisitado"' : 'null') . ',' . 'null,' . 'null);' . "\n";

                    $strNoProc .= 'Nos[' . $numNo . '].acoes = \'\';' . "\n";
                    $strNoProc .= 'Nos[' . $numNo . '].src = \'' . $strSrc . '\';' . "\n";
                    $strNoProc .= 'Nos[' . $numNo . '].html = \'\';' . "\n";
                    $numNo++;

                    if ($objOrgaoFederacaoDTO->getStrSinOrigem() == 'S') {
                      $strTextoSinalizacao = 'Órgão origem do processo no SEI Federação';

                      $strNosAcaoProc .= 'NosAcoes[' . $numNoAcao++ . '] = new infraArvoreAcao("ORIGEM_FEDERACAO",' . '"OF' . $objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao() . '",' . '"' . $objOrgaoFederacaoDTO->getStrIdOrgaoFederacao() . '",' . '"javascript:alert(\'' . PaginaSEI::formatarParametrosJavaScript($strTextoSinalizacao) . '\');",' . 'null,' . '"' . $strTextoSinalizacao . '",' . '"' . Icone::FEDERACAO_ORIGEM . '",' . 'true);' . "\n";
                    }
                  }


                  if ($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao() != $objInstalacaoFederacaoRN->obterIdInstalacaoFederacaoLocal()) {
                    $strNoPaiSinalizacoesFederacao = $strNoPaiOrgaoFederacao;

                    if ($bolApenasUmOrgao) {
                      $strNoPaiSinalizacoesFederacao = $arrObjOrgaoFederacaoDTO[0]->getStrIdOrgaoFederacao();
                    }

                    if ($objInstalacaoFederacaoDTO->getObjSinalizacaoFederacaoDTO() != null) {
                      $objSinalizacaoFederacaoDTO = $objInstalacaoFederacaoDTO->getObjSinalizacaoFederacaoDTO();

                      if ($objSinalizacaoFederacaoDTO->getNumStaSinalizacao() & SinalizacaoFederacaoRN::$TSF_ATENCAO) {
                        if ($bolApenasUmOrgao) {
                          $strTextoSinalizacao = 'Um documento foi incluído ou assinado neste processo na instalação deste órgão';
                        } else {
                          $strTextoSinalizacao = 'Um documento foi incluído ou assinado neste processo nesta instalação';
                        }

                        $strNosAcaoProc .= 'NosAcoes[' . $numNoAcao++ . '] = new infraArvoreAcao("ATENCAO_FEDERACAO",' . '"AF' . $objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao() . '",' . '"' . $strNoPaiSinalizacoesFederacao . '",' . '"javascript:alert(\'' . PaginaSEI::formatarParametrosJavaScript($strTextoSinalizacao) . '\');",' . 'null,' . '"' . $strTextoSinalizacao . '",' . '"' . Icone::EXCLAMACAO . '",' . 'true);' . "\n";
                      }

                      if ($objSinalizacaoFederacaoDTO->getNumStaSinalizacao() & SinalizacaoFederacaoRN::$TSF_PUBLICACAO) {
                        if ($bolApenasUmOrgao) {
                          $strTextoSinalizacao = 'Um documento foi publicado neste processo na instalação deste órgão';
                        } else {
                          $strTextoSinalizacao = 'Um documento foi publicado neste processo nesta instalação';
                        }

                        $strNosAcaoProc .= 'NosAcoes[' . $numNoAcao++ . '] = new infraArvoreAcao("PUBLICACAO_FEDERACAO",' . '"PF' . $objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao() . '",' . '"' . $strNoPaiSinalizacoesFederacao . '",' . '"javascript:alert(\'' . PaginaSEI::formatarParametrosJavaScript($strTextoSinalizacao) . '\');",' . 'null,' . '"' . $strTextoSinalizacao . '",' . '"' . Icone::PUBLICACAO . '",' . 'true);' . "\n";
                      }
                    }
                  }
                }
              }
            }

            foreach ($SEI_MODULOS as $seiModulo) {
              if (($arrRetIntegracao = $seiModulo->executar('montarIconeProcesso', $objProcedimentoAPI)) != null) {
                foreach ($arrRetIntegracao as $objArvoreAcaoItemAPI) {
                  $strNosAcaoProc .= 'NosAcoes[' . $numNoAcao++ . '] = new infraArvoreAcao("' . $objArvoreAcaoItemAPI->getTipo() . '",' . '"' . $objArvoreAcaoItemAPI->getId() . '",' . '"' . $objArvoreAcaoItemAPI->getIdPai() . '",' . '"' . $objArvoreAcaoItemAPI->getHref() . '",' . '"' . $objArvoreAcaoItemAPI->getTarget() . '",' . '"' . $objArvoreAcaoItemAPI->getTitle() . '",' . '"' . $objArvoreAcaoItemAPI->getIcone() . '",' . ($objArvoreAcaoItemAPI->getSinHabilitado() == 'S' ? 'true' : 'false') . ');' . "\n";
                }
              }
            }
          }
        }
      }

      return $objProcedimentoDTO;
    } catch (Exception $e) {
      throw new InfraException('Erro montando ações para processo.', $e);
    }
  }

  public static function montarIdentificacaoArvore($objProcedimentoDTO) {
    return $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado();
  }

  public static function processarControleProcessos(
    $objProcedimentoDTO, $bolAcaoRegistrarAnotacao, $bolAcaoRegistrarAcompanhamento, $bolAcaoRegistrarControlePrazo, $bolAcaoAndamentoSituacaoGerenciar, $bolAcaoAndamentoMarcadorGerenciar, $arrProcessosVisitados, $arrRetIconeIntegracao,
    &$strImagemStatus, &$strLinkUsuarioAtribuicao, &$strLinkProcesso, &$strAriaTexto, $bolExibirMarcadores, $numTabIndex) {
    $strImagemStatus = '';
    $strLinkUsuarioAtribuicao = '&nbsp;';
    $strLinkProcesso = '';
    $strAriaTexto = '';

    $dblIdProcedimento = $objProcedimentoDTO->getDblIdProcedimento();

    if ($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo() == ProtocoloRN::$NA_SIGILOSO) {
      $objProcedimentoDTO->setStrDescricaoProtocolo(null);
    }

    $arrObjAtividadeDTO = $objProcedimentoDTO->getArrObjAtividadeDTO();
    $strCssProcesso = '';

    foreach ($arrObjAtividadeDTO as $objAtividadeDTO) {
      $strImagemStatus = '';

      if ($objProcedimentoDTO->getObjAnotacaoDTO() != null) {
        $strImagemStatus = AnotacaoINT::montarIconeAnotacao($objProcedimentoDTO->getObjAnotacaoDTO(), $bolAcaoRegistrarAnotacao, $dblIdProcedimento, '');
      }

      if ($objProcedimentoDTO->isSetArrObjAcompanhamentoDTO() && $objProcedimentoDTO->getArrObjAcompanhamentoDTO() != null) {
        $strImagemStatus .= AcompanhamentoINT::montarIconeAcompanhamento($bolAcaoRegistrarAcompanhamento, $dblIdProcedimento, '');
      }

      $strCssProcesso = 'class="';
      $numTipoVisualizacao = $objAtividadeDTO->getNumTipoVisualizacao();

      if ($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo() != ProtocoloRN::$NA_SIGILOSO) {
        if ($numTipoVisualizacao & AtividadeRN::$TV_NAO_VISUALIZADO) {
          $strCssProcesso .= 'processoNaoVisualizado';
        } else {
          $strCssProcesso .= 'processoVisualizado';
          if ($arrProcessosVisitados != null && isset($arrProcessosVisitados[$objProcedimentoDTO->getDblIdProcedimento()])) {
            $strCssProcesso .= ' processoVisitado';
          }
        }
      } else {
        if ($objProcedimentoDTO->getStrSinCredencialProcesso() == 'S') {
          if ($numTipoVisualizacao & AtividadeRN::$TV_NAO_VISUALIZADO) {
            $strCssProcesso .= 'processoNaoVisualizadoSigiloso';
          } else {
            $strCssProcesso .= 'processoVisualizadoSigiloso';
            if ($arrProcessosVisitados != null && isset($arrProcessosVisitados[$objProcedimentoDTO->getDblIdProcedimento()])) {
              $strCssProcesso .= ' processoVisitadoSigiloso';
            }
          }
          if ($objProcedimentoDTO->getStrSinCredencialAssinatura() == 'S') {
            $strImagemStatus .= '<a href="javascript:void(0);" ' . PaginaSEI::montarTitleTooltip('Processo possui documento com Credencial para Assinatura') . '><img src="' . Icone::CREDENCIAL_ASSINATURA . '" class="imagemStatus" /></a>';
          }
        } else {
          if ($objProcedimentoDTO->getStrSinCredencialAssinatura() == 'S') {
            $strCssProcesso .= 'processoCredencialAssinaturaSigiloso';
            $strImagemStatus .= '<a href="javascript:void(0);" ' . PaginaSEI::montarTitleTooltip('Processo possui documento com Credencial para Assinatura') . '><img src="' . Icone::CREDENCIAL_ASSINATURA . '" class="imagemStatus" /></a>';
          }
          if ($arrProcessosVisitados != null && isset($arrProcessosVisitados[$objProcedimentoDTO->getDblIdProcedimento()])) {
            $strCssProcesso .= ' processoVisitadoCredencialAssinatura';
          }
        }
      }
      $strCssProcesso .= '"';


      $strImagemStatus .= self::montarIconeVisualizacao($numTipoVisualizacao, $objProcedimentoDTO, $arrRetIconeIntegracao, $bolAcaoAndamentoSituacaoGerenciar, $bolAcaoAndamentoMarcadorGerenciar, '', $bolExibirMarcadores);

      if ($objProcedimentoDTO->isSetObjControlePrazoDTO() && $objProcedimentoDTO->getObjControlePrazoDTO() != null) {
        $strImagemStatus .= ControlePrazoINT::montarIconeControlePrazo($bolAcaoRegistrarControlePrazo, $objProcedimentoDTO, true, '');
      }

      if ($objAtividadeDTO->getNumIdUsuarioAtribuicao() != null) {
        $strLinkUsuarioAtribuicao = '(<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_atribuicao_listar&acao_retorno=' . $_GET['acao'] . '&id_usuario_atribuicao=' . $objAtividadeDTO->getNumIdUsuarioAtribuicao() . '&id_procedimento=' . $dblIdProcedimento) . '" title="Atribuído para ' . PaginaSEI::tratarHTML($objAtividadeDTO->getStrNomeUsuarioAtribuicao()) . '" class="ancoraSigla" tabindex="' . $numTabIndex . '">' . PaginaSEI::tratarHTML($objAtividadeDTO->getStrSiglaUsuarioAtribuicao()) . '</a>)';
      }

      //pega somente do primeiro andamento, se remetido por outra unidade volta a ficar vermelho pois vem como não visualizado
      break;
    }

    $strAriaTexto = 'aria-label="';
    $strSeparadorAria = '';

    if ($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo() == ProtocoloRN::$NA_SIGILOSO) {
      $strAriaTexto .= $strSeparadorAria . 'Sigiloso';
      $strSeparadorAria = ' / ';
    }

    if (strpos($strCssProcesso, 'processoNaoVisualizado') !== false || strpos($strCssProcesso, 'processoNaoVisualizadoSigiloso') !== false) {
      $strAriaTexto .= $strSeparadorAria . 'Não recebido';
      $strSeparadorAria = ' / ';
    }

    if (strpos($strCssProcesso, 'processoVisitado') !== false || strpos($strCssProcesso, 'processoVisitadoSigiloso') !== false) {
      $strAriaTexto .= $strSeparadorAria . 'Já acessado';
      $strSeparadorAria = ' / ';
    }

    $strAriaTexto .= $strSeparadorAria . 'Tipo ' . PaginaSEI::getInstance()->tratarHTML($objProcedimentoDTO->getStrNomeTipoProcedimento());
    $strSeparadorAria = ' / ';

    if ($objProcedimentoDTO->getStrDescricaoProtocolo() != null) {
      $strAriaTexto .= $strSeparadorAria . 'Especificação ' . PaginaSEI::getInstance()->tratarHTML($objProcedimentoDTO->getStrDescricaoProtocolo());
      $strSeparadorAria = ' / ';
    }

    $strAriaTexto .= '"';


    $strLinkProcesso = '<a ' . $strCssProcesso . ' href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_procedimento=' . $dblIdProcedimento) . '" ' . PaginaSEI::montarTitleTooltip($objProcedimentoDTO->getStrDescricaoProtocolo(),
        $objProcedimentoDTO->getStrNomeTipoProcedimento()) . ' tabindex="' . $numTabIndex . '">' . str_replace('.', '.<wbr>', $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado()) . '</a>';
  }

  public static function montarCamposPesquisaSigiloso(
    PesquisaSigilosoDTO &$objPesquisaSigilosoDTO, &$strCss, &$strJs, &$strJsInicializar, &$strJsValidar, &$strHtml) {
    if (!$objPesquisaSigilosoDTO->isSetStrSinFiltroProtocolo()) {
      $objPesquisaSigilosoDTO->setStrSinFiltroProtocolo('N');
    }

    if (!$objPesquisaSigilosoDTO->isSetStrSinFiltroOrgao()) {
      $objPesquisaSigilosoDTO->setStrSinFiltroOrgao('N');
    }

    if (!$objPesquisaSigilosoDTO->isSetStrSinFiltroUnidade()) {
      $objPesquisaSigilosoDTO->setStrSinFiltroUnidade('N');
    }

    if (!$objPesquisaSigilosoDTO->isSetStrSinFiltroTipoProcedimento()) {
      $objPesquisaSigilosoDTO->setStrSinFiltroTipoProcedimento('N');
    }

    if (!$objPesquisaSigilosoDTO->isSetStrSinFiltroInteressado()) {
      $objPesquisaSigilosoDTO->setStrSinFiltroInteressado('N');
    }

    if (!$objPesquisaSigilosoDTO->isSetStrSinFiltroObservacoes()) {
      $objPesquisaSigilosoDTO->setStrSinFiltroObservacoes('N');
    }

    if (!$objPesquisaSigilosoDTO->isSetStrSinFiltroUsuarioCredencial()) {
      $objPesquisaSigilosoDTO->setStrSinFiltroUsuarioCredencial('N');
    }

    if (!$objPesquisaSigilosoDTO->isSetStrSinFiltroPeriodoAutuacao()) {
      $objPesquisaSigilosoDTO->setStrSinFiltroPeriodoAutuacao('N');
    }

    if (!$objPesquisaSigilosoDTO->isSetStrSinFiltroTramitacaoUnidade()) {
      $objPesquisaSigilosoDTO->setStrSinFiltroTramitacaoUnidade('N');
    }

    if (!$objPesquisaSigilosoDTO->isSetStrSinFiltroCredencialInativa()) {
      $objPesquisaSigilosoDTO->setStrSinFiltroCredencialInativa('N');
    }


    $strCss = '';
    $strJs = '';
    $strJsInicializar = '';
    $strJsValidar = '';

    $strJsGlobal = '';
    $strJsLimpar = 'function limpar(){' . "\n";

    if ($objPesquisaSigilosoDTO->getStrSinFiltroProtocolo() == 'S') {
      $strProtocoloSigiloso = $_POST['txtProtocoloSigiloso'];

      if (!InfraString::isBolVazia($strProtocoloSigiloso)) {
        $objPesquisaSigilosoDTO->setStrProtocoloFormatadoPesquisa($strProtocoloSigiloso);
      }

      $strCss .= "#lblProtocoloSigiloso {position:absolute;left:0%;width:17%;}\n";
      $strCss .= "#txtProtocoloSigiloso {position:absolute;left:18%;width:21%}\n";

      $strJsLimpar .= "  document.getElementById('txtProtocoloSigiloso').value='';\n";

      $strHtml .= '<div id="divProtocoloSigiloso" class="infraAreaDados" style="height:3em">' . "\n";
      $strHtml .= '<label id="lblProtocoloSigiloso" for="txtProtocoloSigiloso" accesskey="" class="infraLabelOpcional">Nº do Processo:</label>' . "\n";
      $strHtml .= '<input type="text" id="txtProtocoloSigiloso" name="txtProtocoloSigiloso" class="infraText" value="' . PaginaSEI::tratarHTML($strProtocoloSigiloso) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabDados() . '" />' . "\n";
      $strHtml .= '</div>' . "\n";
    }

    if ($objPesquisaSigilosoDTO->getStrSinFiltroOrgao() == 'S') {
      $numIdOrgaoSigiloso = $_POST['selOrgao'];

      if (!InfraString::isBolVazia($numIdOrgaoSigiloso) && $numIdOrgaoSigiloso != 'null') {
        $objPesquisaSigilosoDTO->setNumIdOrgaoUnidadeAtividade($numIdOrgaoSigiloso);
      }

      $strCss .= "#lblOrgao {position:absolute;left:0%;width:17%;}\n";
      $strCss .= "#selOrgao {position:absolute;left:18%;width:25%;}\n";

      $strItensSelOrgao = OrgaoINT::montarSelectSiglaRI1358('null', 'Todos', $numIdOrgaoSigiloso);

      $strJsGlobal .= '  function alterarOrgao(){' . "\n";
      $strJsGlobal .= '    if (objAutoCompletarUnidade!=null) {' . "\n";
      $strJsGlobal .= '      objAutoCompletarUnidade.limpar();' . "\n";
      $strJsGlobal .= '    }' . "\n";
      $strJsGlobal .= '  }' . "\n";

      $strJsLimpar .= "  document.getElementById('selOrgao').selectedIndex = 0;\n";

      $strHtml .= '<div id="divOrgaoSigiloso" class="infraAreaDados" style="height:3em">' . "\n";
      $strHtml .= '<label id="lblOrgao" for="selOrgao" accesskey="" class="infraLabelOpcional">Órgão Tramitação:</label>' . "\n";
      $strHtml .= '<select id="selOrgao" name="selOrgao" onchange="alterarOrgao()" class="infraSelect" tabindex="' . PaginaSEI::getInstance()->getProxTabDados() . '">' . "\n";
      $strHtml .= $strItensSelOrgao;
      $strHtml .= '  </select>' . "\n";
      $strHtml .= '</div>' . "\n";
    }


    if ($objPesquisaSigilosoDTO->getStrSinFiltroUnidade() == 'S') {
      $numIdUnidade = trim($_POST['hdnIdUnidade']);
      $strNomeUnidade = $_POST['txtUnidade'];
      if ($numIdUnidade != '') {
        $objPesquisaSigilosoDTO->setNumIdUnidadeAtividade($numIdUnidade);
      }

      $strCss .= "#lblUnidade {position:absolute;left:0%;width:17%;}\n";
      $strCss .= "#txtUnidade {position:absolute;left:18%;width:50%;}\n";

      $strLinkAjaxUnidade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=unidade_auto_completar_todas');

      $strJsGlobal .= "var objAutoCompletarUnidade = null;\n";

      $strJsLimpar .= "  objAutoCompletarUnidade.limpar();\n";

      $strJsInicializar .= "  objAutoCompletarUnidade = new infraAjaxAutoCompletar('hdnIdUnidade','txtUnidade','" . $strLinkAjaxUnidade . "');\n";
      $strJsInicializar .= "  objAutoCompletarUnidade.limparCampo = true;\n";
      $strJsInicializar .= "  objAutoCompletarUnidade.prepararExecucao = function(){\n";
      $strJsInicializar .= "    ret = 'palavras_pesquisa='+document.getElementById('txtUnidade').value;\n";
      $strJsInicializar .= "    if (document.getElementById('selOrgao')!=null){\n";
      $strJsInicializar .= "     ret += '&id_orgao='+document.getElementById('selOrgao').value;\n";
      $strJsInicializar .= "    }\n";
      $strJsInicializar .= "    return ret;\n";
      $strJsInicializar .= "  };\n";
      $strJsInicializar .= "  objAutoCompletarUnidade.selecionar('" . $numIdUnidade . "','" . PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeUnidade, false) . "');\n\n";

      $strHtml .= '<div id="divUnidadeSigiloso" class="infraAreaDados" style="height:3em">' . "\n";
      $strHtml .= '  <label id="lblUnidade" for="txtUnidade" accesskey="" class="infraLabelOpcional">Unidade Tramitação:</label>' . "\n";
      $strHtml .= '  <input type="text" id="txtUnidade" name="txtUnidade" class="infraText" value="' . $strNomeUnidade . '" tabindex="' . PaginaSEI::getInstance()->getProxTabDados() . '" />' . "\n";
      $strHtml .= '  <input type="hidden" id="hdnIdUnidade" name="hdnIdUnidade" value="' . $numIdUnidade . '" />' . "\n";
      $strHtml .= '</div>' . "\n";
    }

    if ($objPesquisaSigilosoDTO->getStrSinFiltroTipoProcedimento() == 'S') {
      $numIdTipoProcedimentoSigiloso = $_POST['selTipoProcedimentoSigiloso'];

      if (!InfraString::isBolVazia($numIdTipoProcedimentoSigiloso) && $numIdTipoProcedimentoSigiloso != 'null') {
        $objPesquisaSigilosoDTO->setNumIdTipoProcedimento($numIdTipoProcedimentoSigiloso);
      }

      $strCss .= "#lblTipoProcedimentoSigiloso {position:absolute;left:0%;width:17%;}\n";
      $strCss .= "#selTipoProcedimentoSigiloso {position:absolute;left:18%;width:20%;width:60%;}\n";

      $strItensSelTipoProcedimento = TipoProcedimentoINT::montarSelectNome('null', 'Todos', $numIdTipoProcedimentoSigiloso, false, true);

      $strJsLimpar .= "  document.getElementById('selTipoProcedimentoSigiloso').selectedIndex=0;\n";

      $strHtml .= '<div id="divTipoProcedimentoSigiloso" class="infraAreaDados" style="height:3em">' . "\n";
      $strHtml .= '<label id="lblTipoProcedimentoSigiloso" for="selTipoProcedimentoSigiloso" accesskey="" class="infraLabelOpcional">Tipo do Processo:</label>' . "\n";
      $strHtml .= '<select id="selTipoProcedimentoSigiloso" name="selTipoProcedimentoSigiloso" class="infraSelect" tabindex="' . PaginaSEI::getInstance()->getProxTabDados() . '" >' . "\n";
      $strHtml .= $strItensSelTipoProcedimento;
      $strHtml .= '</select>' . "\n";
      $strHtml .= '</div>' . "\n";
    }

    if ($objPesquisaSigilosoDTO->getStrSinFiltroInteressado() == 'S') {
      $strIdInteressadoSigiloso = $_POST['hdnIdInteressadoSigiloso'];
      $strNomeInteressadoSigiloso = $_POST['txtInteressadoSigiloso'];

      if (!InfraString::isBolVazia($strIdInteressadoSigiloso)) {
        $objPesquisaSigilosoDTO->setNumIdContatoParticipante($strIdInteressadoSigiloso);
      }

      $strCss .= "#lblInteressadoSigiloso {position:absolute;left:0%;width:17%;}\n";
      $strCss .= "#txtInteressadoSigiloso {position:absolute;left:18%;width:20%;width:60%;}\n";

      $strLinkAjaxContatos = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=contato_auto_completar_pesquisa');

      $strJsGlobal .= "var objAutoCompletarInteressado = null;\n";

      $strJsLimpar .= "  objAutoCompletarInteressado.limpar();\n";

      $strJsInicializar .= "  objAutoCompletarInteressado = new infraAjaxAutoCompletar('hdnIdInteressadoSigiloso','txtInteressadoSigiloso','" . $strLinkAjaxContatos . "');\n";
      $strJsInicializar .= "  objAutoCompletarInteressado.limparCampo = true;\n";
      $strJsInicializar .= "  objAutoCompletarInteressado.prepararExecucao = function(){\n";
      $strJsInicializar .= "    return 'palavras_pesquisa='+document.getElementById('txtInteressadoSigiloso').value;\n";
      $strJsInicializar .= "  };\n";
      $strJsInicializar .= "  objAutoCompletarInteressado.selecionar('" . $strIdInteressadoSigiloso . "','" . PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeInteressadoSigiloso, false) . "');\n\n";

      $strHtml .= '<div id="divInteressadoSigiloso" class="infraAreaDados" style="height:3em">' . "\n";
      $strHtml .= '<label id="lblInteressadoSigiloso" for="txtInteressadoSigiloso" accesskey=""  class="infraLabelOpcional">Interessado:</label>' . "\n";
      $strHtml .= '<input type="text" id="txtInteressadoSigiloso" name="txtInteressadoSigiloso" class="infraText" value="' . PaginaSEI::tratarHTML($strNomeInteressadoSigiloso) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabDados() . '" />' . "\n";
      $strHtml .= '<input type="hidden" id="hdnIdInteressadoSigiloso" name="hdnIdInteressadoSigiloso" class="infraText" value="' . $strIdInteressadoSigiloso . '" />' . "\n";
      $strHtml .= '</div>' . "\n";
    }

    if ($objPesquisaSigilosoDTO->getStrSinFiltroObservacoes() == 'S') {
      $strObservacoesSigiloso = $_POST['txtObservacoesSigiloso'];

      if (!InfraString::isBolVazia($strObservacoesSigiloso)) {
        $objPesquisaSigilosoDTO->setStrIdxObservacao($strObservacoesSigiloso);
      }

      $strCss .= "#lblObservacoesSigiloso {position:absolute;left:0%;width:17%;}\n";
      $strCss .= "#txtObservacoesSigiloso {position:absolute;left:18%;width:20%;width:60%;}\n";

      $strJsLimpar .= "  document.getElementById('txtObservacoesSigiloso').value='';\n";

      $strHtml .= '<div id="divObservacoesSigiloso" class="infraAreaDados" style="height:3em">' . "\n";
      $strHtml .= '<label id="lblObservacoesSigiloso" for="txtObservacoesSigiloso" accesskey=""  class="infraLabelOpcional">Obs. desta Unidade:</label>' . "\n";
      $strHtml .= '<input type="text" id="txtObservacoesSigiloso" name="txtObservacoesSigiloso" class="infraText" value="' . PaginaSEI::tratarHTML($strObservacoesSigiloso) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabDados() . '" />' . "\n";
      $strHtml .= '</div>' . "\n";
    }

    if ($objPesquisaSigilosoDTO->getStrSinFiltroUsuarioCredencial() == 'S') {
      $numIdContato = $_POST['hdnIdUsuarioSigiloso'];
      $strNomeUsuario = $_POST['txtUsuarioSigiloso'];

      if (!InfraString::isBolVazia($numIdContato)) {
        $objPesquisaSigilosoDTO->setNumIdContatoUsuario($numIdContato);
      }

      $strCss .= "#lblUsuarioSigiloso {position:absolute;left:0%;width:17%;}\n";
      $strCss .= "#txtUsuarioSigiloso {position:absolute;left:18%;width:20%;width:60%;}\n";

      $strLinkAjaxUsuario = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=contato_auto_completar_usuario_pesquisa');

      $strJsGlobal .= "var objAutoCompletarUsuario = null;\n";

      $strJsLimpar .= "  objAutoCompletarUsuario.limpar();\n";

      $strJsInicializar .= "  objAutoCompletarUsuario = new infraAjaxAutoCompletar('hdnIdUsuarioSigiloso','txtUsuarioSigiloso','" . $strLinkAjaxUsuario . "');\n";
      $strJsInicializar .= "  objAutoCompletarUsuario.limparCampo = true;\n";
      $strJsInicializar .= "  objAutoCompletarUsuario.prepararExecucao = function(){\n";
      $strJsInicializar .= "    return 'palavras_pesquisa='+document.getElementById('txtUsuarioSigiloso').value+'&sin_usuario_interno=S&sin_usuario_externo=N';\n";
      $strJsInicializar .= "  };\n";
      $strJsInicializar .= "  objAutoCompletarUsuario.selecionar('" . $numIdContato . "','" . PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeUsuario, false) . "');\n\n";

      $strHtml .= '<div id="divUsuarioSigiloso" class="infraAreaDados" style="height:3em">' . "\n";
      $strHtml .= '<label id="lblUsuarioSigiloso" for="txtUsuarioSigiloso" class="infraLabelOpcional">Credencial na Unidade:</label>' . "\n";
      $strHtml .= '<input type="text" id="txtUsuarioSigiloso" name="txtUsuarioSigiloso" class="infraText" value="' . PaginaSEI::tratarHTML($strNomeUsuario) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabDados() . '" />' . "\n";
      $strHtml .= '<input type="hidden" id="hdnIdUsuarioSigiloso" name="hdnIdUsuarioSigiloso" class="infraText" value="' . $numIdContato . '" />' . "\n";
      $strHtml .= '</div>' . "\n";
    }

    if ($objPesquisaSigilosoDTO->getStrSinFiltroPeriodoAutuacao() == 'S') {
      $dtaInicio = $_POST['txtDataInicio'];
      $dtaFim = $_POST['txtDataFim'];

      $objPesquisaSigilosoDTO->setDtaInicio($dtaInicio);

      if (!InfraString::isBolVazia($dtaFim)) {
        $objPesquisaSigilosoDTO->setDtaFim($dtaFim);
      } else {
        $objPesquisaSigilosoDTO->setDtaFim($dtaInicio);
      }

      $strCss .= "#lblDataInicio {position:absolute;left:0%;width:17%;}\n";
      $strCss .= "#txtDataInicio {position:absolute;left:18%;width:10%;}\n";
      $strCss .= "#imgCalDataInicio {position:absolute;left:28.7%;}\n";

      $strCss .= "#lblDataFim 	{position:absolute;left:31.5%;width:2%;}\n";
      $strCss .= "#txtDataFim 	{position:absolute;left:33.5%;width:10%;}\n";
      $strCss .= "#imgCalDataFim {position:absolute;left:44.2%;}\n";

      $strJsLimpar .= "  document.getElementById('txtDataFim').value = '';\n";
      $strJsLimpar .= "  document.getElementById('txtDataInicio').value = '';\n";

      $strJsValidar .= "
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
        alert('Data inicial deve ser informada.');
        return false;
      }\n";

      $strHtml .= '<div id="divDtaAutuacaoSigiloso" class="infraAreaDados" style="height:3em">' . "\n";
      $strHtml .= '  <label id="lblDataInicio" for="txtDataInicio" accesskey="P" class="infraLabelOpcional"><span class="infraTeclaAtalho">P</span>eríodo de Autuação:</label>' . "\n";
      $strHtml .= '  <input type="text" id="txtDataInicio" name="txtDataInicio" class="infraText" value="' . $dtaInicio . '" onkeypress="return infraMascaraData(this, event)" tabindex="' . PaginaSEI::getInstance()->getProxTabDados() . '" />' . "\n";
      $strHtml .= '  <img id="imgCalDataInicio" title="Selecionar Data Inicial" alt="Selecionar Data Inicial" src="' . PaginaSEI::getInstance()->getIconeCalendario() . '" class="infraImg" onclick="infraCalendario(\'txtDataInicio\',this);" tabindex="' . PaginaSEI::getInstance()->getProxTabDados() . '" />' . "\n";

      $strHtml .= '  <label id="lblDataFim" for="txtDataFim" accesskey="" class="infraLabelOpcional">a</label>' . "\n";
      $strHtml .= '  <input type="text" id="txtDataFim" name="txtDataFim" class="infraText" value="' . $dtaFim . '" onkeypress="return infraMascaraData(this, event)" tabindex="' . PaginaSEI::getInstance()->getProxTabDados() . '" />' . "\n";
      $strHtml .= '  <img id="imgCalDataFim" title="Selecionar Data Final" alt="Selecionar Data Final" src="' . PaginaSEI::getInstance()->getIconeCalendario() . '" class="infraImg" onclick="infraCalendario(\'txtDataFim\',this);" tabindex="' . PaginaSEI::getInstance()->getProxTabDados() . '" />' . "\n";
      $strHtml .= '</div>' . "\n";
    }

    if ($objPesquisaSigilosoDTO->getStrSinFiltroTramitacaoUnidade() == 'S') {
      $strSinTramitacao = PaginaSEI::getInstance()->getCheckbox($_POST['chkSinTramitacao']);

      if ($strSinTramitacao == 'S') {
        $objPesquisaSigilosoDTO->setDthConclusaoAtividade(null);
      }

      $strCss .= "#divSinTramitacao {position:absolute;left:18%;}\n";

      $strJsLimpar .= "  document.getElementById('chkSinTramitacao').checked=false;\n";

      $strHtml .= '<div id="divTramitacaoSigiloso" class="infraAreaDados" style="height:3em">' . "\n";
      $strHtml .= '<div id="divSinTramitacao" class="infraDivCheckbox">' . "\n";
      $strHtml .= '<input type="checkbox" id="chkSinTramitacao" name="chkSinTramitacao" class="infraCheckbox" ' . PaginaSEI::getInstance()->setCheckbox($strSinTramitacao) . ' tabindex="' . PaginaSEI::getInstance()->getProxTabDados() . '" />' . "\n";
      $strHtml .= '<label id="lblSinTramitacao" for="chkSinTramitacao" accesskey="" class="infraLabelCheckbox" >Somente processos em tramitação na unidade</label>' . "\n";
      $strHtml .= '</div>' . "\n";
      $strHtml .= '</div>' . "\n";
    }

    if ($objPesquisaSigilosoDTO->getStrSinFiltroCredencialInativa() == 'S') {
      $strSinCredencialInativa = PaginaSEI::getInstance()->getCheckbox($_POST['chkSinCredencialInativa']);
      $objPesquisaSigilosoDTO->setStrSinCredencialInativa($strSinCredencialInativa);

      $strCss .= "#divSinCredencialInativa {position:absolute;left:18%;}}\n";

      $strJsLimpar .= "  document.getElementById('chkSinCredencialInativa').checked=false;\n";

      $strHtml .= '<div id="divCredencialInativaSigiloso" class="infraAreaDados" style="height:3em">' . "\n";
      $strHtml .= '<div id="divSinCredencialInativa" class="infraDivCheckbox">' . "\n";
      $strHtml .= '<input type="checkbox" id="chkSinCredencialInativa" name="chkSinCredencialInativa" ' . PaginaSEI::getInstance()->setCheckbox($strSinCredencialInativa) . ' class="infraCheckbox" tabindex="' . PaginaSEI::getInstance()->getProxTabDados() . '" />' . "\n";
      $strHtml .= '<label id="lblSinCredencialInativa" for="chkSinCredencialInativa" accesskey="" class="infraLabelCheckbox" >Somente processos sem credencial ativa</label>' . "\n";
      $strHtml .= '</div>' . "\n";
      $strHtml .= '</div>' . "\n";
    }

    $strJsLimpar .= "}\n\n";

    $strJs = $strJsGlobal . "\n\n" . $strJsLimpar;
  }

  public static function montarIconesIntegracaoControleProcessos($arrObjProcedimentoDTO) {
    global $SEI_MODULOS;

    $arrRetIconeIntegracao = array();

    if (count($SEI_MODULOS)) {
      $arrObjProcedimentoAPI = array();
      foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
        $dto = new ProcedimentoAPI();
        $dto->setIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
        $dto->setNumeroProtocolo($objProcedimentoDTO->getStrProtocoloProcedimentoFormatado());
        $dto->setIdTipoProcedimento($objProcedimentoDTO->getNumIdTipoProcedimento());
        $dto->setNomeTipoProcedimento($objProcedimentoDTO->getStrNomeTipoProcedimento());
        $dto->setNivelAcesso($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo());
        $dto->setIdUnidadeGeradora($objProcedimentoDTO->getNumIdUnidadeGeradoraProtocolo());
        $dto->setIdOrgaoUnidadeGeradora($objProcedimentoDTO->getNumIdOrgaoUnidadeGeradoraProtocolo());
        $dto->setIdHipoteseLegal($objProcedimentoDTO->getNumIdHipoteseLegalProtocolo());
        $dto->setGrauSigilo($objProcedimentoDTO->getStrStaGrauSigiloProtocolo());
        $arrObjProcedimentoAPI[] = $dto;
      }

      if (count($arrObjProcedimentoAPI)) {
        foreach ($SEI_MODULOS as $seiModulo) {
          if (($arrRetIconeIntegracaoModulo = $seiModulo->executar('montarIconeControleProcessos', $arrObjProcedimentoAPI)) != null) {
            foreach ($arrRetIconeIntegracaoModulo as $dblIdProcedimento => $arrIcone) {
              if (!isset($arrRetIconeIntegracao[$dblIdProcedimento])) {
                $arrRetIconeIntegracao[$dblIdProcedimento] = $arrIcone;
              } else {
                $arrRetIconeIntegracao[$dblIdProcedimento] = array_merge($arrRetIconeIntegracao[$dblIdProcedimento], $arrIcone);
              }
            }
          }
        }
      }
    }
    return $arrRetIconeIntegracao;
  }

  public static function montarLinkControleProcessos(
    $numRegistros, $flagRecebidos, $flagGerados, $flagNaoVisualizados, $flagSemAcompanhamento, $flagAlterados, $flagReaberturaProgramada, $flagFederacao, $flagSigilosos, $flagCredenciaisAssinatura, $strStaTipoControlePrazo,
    $strStaTipoRetornoProgramado, $numIdTipoProcedimento, $numIdMarcador, $numIdUsuario, $flagPrioritarios, $numIdTipoPrioritario) {
    $ret = null;

    if ($numRegistros) {
      $ret = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem=' . $_GET['acao'] . '&tipo_visualizacao=' . ProcedimentoINT::$TV_DETALHADA . '&tipo_atribuicao=' . AtividadeRN::$TA_TODAS . '&recebidos=' . $flagRecebidos . '&gerados=' . $flagGerados . '&nao_visualizados=' . $flagNaoVisualizados . '&sem_acompanhamento=' . $flagSemAcompanhamento . '&alterados=' . $flagAlterados . '&reaberturas_programadas=' . $flagReaberturaProgramada . '&federacao=' . $flagFederacao . '&sigilosos=' . $flagSigilosos . '&credenciais_assinatura=' . $flagCredenciaisAssinatura . '&tipo_controle_prazo=' . $strStaTipoControlePrazo . '&tipo_retorno_programado=' . $strStaTipoRetornoProgramado . '&id_tipo_procedimento=' . $numIdTipoProcedimento . '&id_marcador=' . $numIdMarcador . '&id_usuario=' . $numIdUsuario . '&id_usuario=' . $numIdUsuario . "&prioritarios=" . $flagPrioritarios . "&id_tipo_prioridade=" . $numIdTipoPrioritario);
    }

    return $ret;
  }

  public static function montarLinkProcessosPainel(
    $flagRecebidos, $flagGerados, $flagNaoVisualizados, $flagSemAcompanhamento, $flagAlterados, $strStaTipoControlePrazo, $strStaTipoRetornoProgramado, $numIdTipoProcedimento, $numIdMarcador, $numIdUsuario, $bolVermelho, $numRegistros,
    $strTextoAria = '', $numIdTipoPrioritario = 0) {
    $ret = '<a onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);"';

    if ($numRegistros == 0) {
      $ret .= ' href="javascript:void(0);"';
    } else {
      $ret .= ' target="painelControleControle" href="' . self::montarLinkControleProcessos($numRegistros, $flagRecebidos, $flagGerados, $flagNaoVisualizados, $flagSemAcompanhamento, $flagAlterados, 0, 0, 0, 0, $strStaTipoControlePrazo,
          $strStaTipoRetornoProgramado, $numIdTipoProcedimento, $numIdMarcador, $numIdUsuario, 0, $numIdTipoPrioritario) . '"';
    }

    if ($bolVermelho && $numRegistros) {
      $ret .= ' class="ancoraPadraoVermelha"';
    } else {
      $ret .= ' class="ancoraPadraoAzul"';
    }

    $valorFormatado = InfraUtil::formatarMilhares($numRegistros);

    if ($strTextoAria != '') {
      $ret .= ' aria-label="' . PaginaSEI::tratarHTML($valorFormatado . ' ' . $strTextoAria) . '"';
    } else {
      $ret .= ' aria-label="' . PaginaSEI::tratarHTML($valorFormatado) . '"';
    }

    $ret .= ' style="padding:0 1em;" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '">' . $valorFormatado . '</a>';

    return $ret;
  }

  public static function montarLinkBlocosPainel(
    $bolBloco, $strStaEstado, $flagNaoAssinados, $numRegistros, $strTextoAria = '') {
    $ret = '<a onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" target="painelControleBlocos" ';

    $strLink = 'controlador.php?acao=' . ($bolBloco ? 'bloco_assinatura_listar' : 'rel_bloco_protocolo_listar') . '&acao_origem=' . $_GET['acao'] . '&acao_retorno=bloco_assinatura_listar&sta_estado=' . $strStaEstado . '&nao_assinados=' . $flagNaoAssinados;

    $ret .= ' href="' . SessaoSEI::getInstance()->assinarLink($strLink) . '"';

    $valorFormatado = InfraUtil::formatarMilhares($numRegistros);
    if ($strTextoAria != '') {
      $ret .= ' aria-label="' . PaginaSEI::tratarHTML($valorFormatado . ' ' . $strTextoAria) . '"';
    } else {
      $ret .= ' aria-label="' . PaginaSEI::tratarHTML($valorFormatado) . '"';
    }

    $ret .= ' class="ancoraPadraoAzul" style="padding:0 1em;" style="padding:0 1em;" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '">' . $valorFormatado . '</a>';

    return $ret;
  }

  public static function montarLinkGruposBlocosPainel(
    $bolBloco, $numIdGrupoBloco, $flagNaoAssinados, $numRegistros, $strTextoAria = '') {
    $ret = '<a onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" target="painelControleGrupoBlocos" ';

    $strLink = 'controlador.php?acao=' . ($bolBloco ? 'bloco_assinatura_listar' : 'rel_bloco_protocolo_listar') . '&acao_origem=' . $_GET['acao'] . '&acao_retorno=bloco_assinatura_listar&id_grupo_bloco=' . $numIdGrupoBloco . '&sta_estado=' . implode(',',
        array(BlocoRN::$TE_ABERTO, BlocoRN::$TE_DISPONIBILIZADO, BlocoRN::$TE_RECEBIDO, BlocoRN::$TE_RETORNADO)) . '&nao_assinados=' . $flagNaoAssinados;

    $ret .= ' href="' . SessaoSEI::getInstance()->assinarLink($strLink) . '"';

    $valorFormatado = InfraUtil::formatarMilhares($numRegistros);
    if ($strTextoAria != '') {
      $ret .= ' aria-label="' . PaginaSEI::tratarHTML($valorFormatado . ' ' . $strTextoAria) . '"';
    } else {
      $ret .= ' aria-label="' . PaginaSEI::tratarHTML($valorFormatado) . '"';
    }

    $ret .= ' class="ancoraPadraoAzul" style="padding:0 1em;" style="padding:0 1em;" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '">' . $valorFormatado . '</a>';

    return $ret;
  }

  public static function montarLinkAcompanhamentosPainel(
    $numIdGrupoAcompanhamento, $flagAbertos, $flagFechados, $flagAlterados, $bolVermelho, $numRegistros, $strTextoAria = '') {
    $ret = '<a onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);"';

    if ($numRegistros == 0) {
      $ret .= ' href="javascript:void(0);"';
    } else {
      $ret .= ' target="painelControleAcompanhamento" href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acompanhamento_listar&acao_origem=' . $_GET['acao'] . '&acao_retorno=principal&id_grupo_acompanhamento=' . $numIdGrupoAcompanhamento . '&abertos=' . $flagAbertos . '&fechados=' . $flagFechados . '&alterados=' . $flagAlterados) . '"';
    }

    if ($bolVermelho && $numRegistros) {
      $ret .= ' class="ancoraPadraoVermelha"';
    } else {
      $ret .= ' class="ancoraPadraoAzul"';
    }

    $valorFormatado = InfraUtil::formatarMilhares($numRegistros);
    if ($strTextoAria != '') {
      $ret .= ' aria-label="' . PaginaSEI::tratarHTML($valorFormatado . ' ' . $strTextoAria) . '"';
    } else {
      $ret .= ' aria-label="' . PaginaSEI::tratarHTML($valorFormatado) . '"';
    }

    $ret .= ' style="padding:0 1em;" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '">' . $valorFormatado . '</a>';

    return $ret;
  }

  public static function adicionarLinhaDireta($dblIdProcedimento) {
    $arr = SessaoSEI::getInstance()->getAtributo('LINHA_DIRETA_' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual());
    if (!is_array($arr)) {
      $arr = array($dblIdProcedimento => 0);
    } else {
      if (!isset($arr[$dblIdProcedimento])) {
        $arr[$dblIdProcedimento] = 0;
      }
    }
    SessaoSEI::getInstance()->setAtributo('LINHA_DIRETA_' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual(), $arr);
  }

  public static function removerLinhaDireta($dblIdProcedimento) {
    $arr = SessaoSEI::getInstance()->getAtributo('LINHA_DIRETA_' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual());
    if (is_array($arr)) {
      if (isset($arr[$dblIdProcedimento])) {
        unset($arr[$dblIdProcedimento]);
      }
      SessaoSEI::getInstance()->setAtributo('LINHA_DIRETA_' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual(), $arr);
    }
  }

  public static function montarTabelaAutuacao(ProcedimentoDTO $objProcedimentoDTO) {
    $strRet = '';
    $strRet .= '<table id="tblSeiCabecalhoProcesso" width="99.3%" class="infraTable" summary="Autuação do Processo">' . "\n";
    $strRet .= '<tr><td width="20%"><b>Órgão:</b></td><td>' . PaginaSEI::tratarHTML($objProcedimentoDTO->getStrDescricaoOrgaoUnidadeGeradoraProtocolo()) . '</td></tr>' . "\n";
    $strRet .= '<tr><td width="20%"><b>Processo:</b></td><td>' . PaginaSEI::tratarHTML($objProcedimentoDTO->getStrProtocoloProcedimentoFormatado()) . '</td></tr>' . "\n";
    $strRet .= '<tr><td width="20%"><b>Tipo:</b></td><td>' . PaginaSEI::tratarHTML($objProcedimentoDTO->getStrNomeTipoProcedimento()) . '</td></tr>' . "\n";
    $strRet .= '<tr><td width="20%"><b>Data de Geração:</b></td><td>' . PaginaSEI::tratarHTML($objProcedimentoDTO->getDtaGeracaoProtocolo()) . '</td></tr>' . "\n";

    $objProtocoloRN = new ProtocoloRN();
    $arrObjNivelAcessoDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->listarNiveisAcessoRN0878(), 'StaNivel');
    $strNivelAcesso = 'Não Identificado';
    if (isset($arrObjNivelAcessoDTO[$objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo()])) {
      $strNivelAcesso = $arrObjNivelAcessoDTO[$objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo()]->getStrDescricao();
    }
    $strRet .= '<tr><td width="20%"><b>Nível de Acesso:</b></td><td>' . PaginaSEI::tratarHTML($strNivelAcesso) . '</td></tr>' . "\n";

    if (count($objProcedimentoDTO->getArrObjParticipanteDTO()) == 0) {
      $strInteressados = '&nbsp;';
    } else {
      $strInteressados = '';
      foreach ($objProcedimentoDTO->getArrObjParticipanteDTO() as $objParticipanteDTO) {
        $strInteressados .= PaginaSEI::tratarHTML($objParticipanteDTO->getStrNomeContato()) . "<br /> ";
      }
    }

    $strRet .= '<tr><td width="20%" valign="top"><b>Interessados:</b></td><td> ' . $strInteressados . '</td></tr>' . "\n";

    $strRet .= '</table>' . "\n";
    return $strRet;
  }

}

?>