<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 31/01/2008 - criado por marcio_db
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
    InfraDebug::getInstance()->setBolDebugInfra(true);
    InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSEI::getInstance()->validarLink();

    if (isset($_GET['arvore'])) {
        PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
        $strParametros .= '&arvore=' . $_GET['arvore'];
    }

    //PaginaSEI::getInstance()->verificarSelecao('procedimento_selecionar');
    //PaginaSEI::getInstance()->verificarSelecao('procedimento_pendencia_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    //Filtrar parâmetros
    $strParametros = '';
    if (isset($_GET['arvore'])) {
        PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
        $strParametros .= '&arvore=' . $_GET['arvore'];
    }

    $strAncora = '';
    if (isset($_GET['id_procedimento'])) {
        $strParametros .= '&id_procedimento=' . $_GET['id_procedimento'];
        $strAncora = PaginaSEI::getInstance()->montarAncora($_GET['id_procedimento']);
    }

    if (isset($_GET['id_procedimento_destino'])) {
        $strParametros .= '&id_procedimento_destino=' . $_GET['id_procedimento_destino'];
    }

    if (isset($_GET['id_procedimento_retorno'])) {
        $strParametros .= '&id_procedimento_retorno=' . $_GET['id_procedimento_retorno'];
    }


    PaginaSEI::getInstance()->salvarCamposPost(array('selTipoProcedimento','selTipoPrioridade'));

    $objProcedimentoDTO = new ProcedimentoDTO();

    $arrComandos = array();

    $objProcedimentoRN = new ProcedimentoRN();

    switch ($_GET['acao']) {
        case 'procedimento_upload_anexo':
            if (isset($_FILES['filArquivo'])) {
                PaginaSEI::getInstance()->processarUpload('filArquivo', DIR_SEI_TEMP, false);
            }
            die;


        case 'procedimento_gerar':
        case 'procedimento_gerar_relacionado':

            if ($_GET['acao'] == 'procedimento_gerar') {
                $strTitulo = 'Iniciar Processo';
            } else {
                $strTitulo = 'Iniciar Processo Relacionado';
            }

            $arrComandos[] = '<button type="button" onclick="confirmarDados()" accesskey="S" name="btnSalvar" id="btnSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="V" name="btnCancelar" value="Voltar" onclick="location.href=\'' . SessaoSEI::getInstance(
                )->assinarLink(
                    'controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno(
                    ) . '&acao_origem=' . $_GET['acao'] . $strParametros . PaginaSEI::getInstance()->montarAncora(
                        $_GET['id_tipo_procedimento']
                    )
                ) . '\';" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>';

            $objProtocoloDTO = new ProtocoloDTO();

            $objProcedimentoDTO->setDblIdProcedimento(null);

            if (isset($_GET['id_tipo_procedimento'])) {
                $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
                $objTipoProcedimentoDTO->retNumIdTipoProcedimento();
                $objTipoProcedimentoDTO->retStrNome();
                $objTipoProcedimentoDTO->setNumIdTipoProcedimento($_GET['id_tipo_procedimento']);

                $objTipoProcedimentoRN = new TipoProcedimentoRN();
                $objTipoProcedimentoDTO = $objTipoProcedimentoRN->consultarRN0267($objTipoProcedimentoDTO);

                if ($objTipoProcedimentoDTO == null) {
                    throw new InfraException('Tipo de processo não encontrado.');
                }

                $objProcedimentoDTO->setNumIdTipoProcedimento($objTipoProcedimentoDTO->getNumIdTipoProcedimento());
                $objProcedimentoDTO->setStrNomeTipoProcedimento($objTipoProcedimentoDTO->getStrNome());
                $objProtocoloDTO->setNumIdTipoProcedimentoProcedimento(
                    $objTipoProcedimentoDTO->getNumIdTipoProcedimento()
                );
                $objProtocoloDTO->setStrProtocoloFormatado(null);
                $objProtocoloDTO->setDtaGeracao(InfraData::getStrDataAtual());
                $objProtocoloDTO->setStrStaNivelAcessoLocal(null);
                //$objProtocoloDTO->setStrStaGrauSigilo(null);
                //$objProtocoloDTO->setNumIdHipoteseLegal(null);
            } else {
                if ($_POST['rdoProtocolo'] == 'M') {
                    $objProtocoloDTO->setStrProtocoloFormatado($_POST['txtProtocoloInformar']);
                    $objProtocoloDTO->setDtaGeracao($_POST['txtDtaGeracaoInformar']);
                } else {
                    $objProtocoloDTO->setStrProtocoloFormatado(null);
                    $objProtocoloDTO->setDtaGeracao(null);
                }

                $objProcedimentoDTO->setNumIdTipoProcedimento($_POST['hdnIdTipoProcedimento']);
                $objProtocoloDTO->setNumIdTipoProcedimentoProcedimento($_POST['hdnIdTipoProcedimento']);
                $objProcedimentoDTO->setStrNomeTipoProcedimento($_POST['hdnNomeTipoProcedimento']);
                $objProtocoloDTO->setStrStaNivelAcessoLocal($_POST['rdoNivelAcesso']);
                $objProtocoloDTO->setNumIdHipoteseLegal($_POST['selHipoteseLegal']);
                $objProtocoloDTO->setStrStaGrauSigilo($_POST['selGrauSigilo']);
            }

            $strStaNivelAcessoGlobal = null;

            $objProtocoloDTO->setStrDescricao($_POST['txtDescricao']);
            $objProcedimentoDTO->setNumIdTipoPrioridade($_POST['selTipoPrioridade']);
            $objProcedimentoDTO->setStrSinGerarPendencia('S');

            $objProtocoloDTO->setDblIdProtocolo(null);

            //ASSUNTOS
            $arrAssuntos = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnAssuntos']);

            $arrObjAssuntosDTO = array();
            for ($x = 0; $x < count($arrAssuntos); $x++) {
                $objRelProtocoloAssuntoDTO = new RelProtocoloAssuntoDTO();
                $objRelProtocoloAssuntoDTO->setNumIdAssunto($arrAssuntos[$x]);
                $objRelProtocoloAssuntoDTO->setNumSequencia($x);
                $arrObjAssuntosDTO[$x] = $objRelProtocoloAssuntoDTO;
            }
            $objProtocoloDTO->setArrObjRelProtocoloAssuntoDTO($arrObjAssuntosDTO);

            //OBSERVACOES
            $objObservacaoDTO = new ObservacaoDTO();
            $objObservacaoDTO->setStrDescricao($_POST['txaObservacoes']);
            $objProtocoloDTO->setArrObjObservacaoDTO(array($objObservacaoDTO));

            $arrObjParticipantesDTO = array();
            if ($_POST['hdnSinIndividual'] == 'N') {
                //INTERESSADOS
                $arrParticipantes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnInteressadosProcedimento']);

                for ($x = 0; $x < count($arrParticipantes); $x++) {
                    $objParticipanteDTO = new ParticipanteDTO();
                    $objParticipanteDTO->setNumIdContato($arrParticipantes[$x]);
                    $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);
                    $objParticipanteDTO->setNumSequencia($x);
                    $arrObjParticipantesDTO[$x] = $objParticipanteDTO;
                }
            } else {
                if ($_POST['hdnIdInteressadoUsuario'] != '') {
                    $objParticipante = new ParticipanteDTO();
                    $objParticipante->setNumIdContato($_POST['hdnIdInteressadoUsuario']);
                    $objParticipante->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);
                    $objParticipante->setNumSequencia(0);
                    $arrObjParticipantesDTO[] = $objParticipante;
                }
            }
            $objProtocoloDTO->setArrObjParticipanteDTO($arrObjParticipantesDTO);

            $objProcedimentoDTO->setObjProtocoloDTO($objProtocoloDTO);

            if ($_GET['acao'] == 'procedimento_gerar_relacionado') {
                $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
                $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($_GET['id_procedimento_destino']);
                $objRelProtocoloProtocoloDTO->setStrMotivo(null);
                $objProcedimentoDTO->setArrObjRelProtocoloProtocoloDTO(array($objRelProtocoloProtocoloDTO));
            }

            if ($_POST['hdnFlagProcedimentoCadastro'] == '2') {
                try {
                    $objProcedimentoDTO = $objProcedimentoRN->gerarRN0156($objProcedimentoDTO);

                    //PaginaSEI::getInstance()->setStrMensagem('Processo '.$objProcedimentoDTO->getStrProtocoloProcedimentoFormatado().' gerado com sucesso.');

                    $strAcao = 'procedimento_trabalhar';
                    if (PaginaSEI::getInstance()->isBolArvore()) {
                        $strAcao = 'arvore_visualizar';
                    }

                    header(
                        'Location: ' . SessaoSEI::getInstance()->assinarLink(
                            'controlador.php?acao=' . $strAcao . '&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . PaginaSEI::getInstance(
                            )->getAcaoRetorno() . '&id_procedimento=' . $objProcedimentoDTO->getDblIdProcedimento(
                            ) . $strParametros . '&atualizar_arvore=1' . PaginaSEI::getInstance()->montarAncora(
                                $objProcedimentoDTO->getDblIdProcedimento()
                            )
                        )
                    );
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'procedimento_alterar':

            $strTitulo = 'Alterar Processo';

            $arrComandos[] = '<button type="button" onclick="confirmarDados()" accesskey="S" name="btnSalvar" id="btnSalvar" value="Salvar" class="infraButton" style="width:10em;"><span class="infraTeclaAtalho">S</span>alvar</button>';

            $objProtocoloDTO = new ProtocoloDTO();

            $strObservacao = '';
            if (!isset($_POST['hdnIdProcedimento'])) {
                $objProcedimentoDTO->setDblIdProcedimento($_GET['id_procedimento']);
                $objProcedimentoDTO->retTodos(true);

                $objProcedimentoDTO = $objProcedimentoRN->consultarRN0201($objProcedimentoDTO);
                if ($objProcedimentoDTO == null) {
                    throw new InfraException("Registro não encontrado.");
                }
                $objProtocoloDTO->setStrProtocoloFormatado($objProcedimentoDTO->getStrProtocoloProcedimentoFormatado());
                $objProtocoloDTO->setDtaGeracao($objProcedimentoDTO->getDtaGeracaoProtocolo());
                $objProtocoloDTO->setStrDescricao($objProcedimentoDTO->getStrDescricaoProtocolo());
                $objProtocoloDTO->setStrStaNivelAcessoLocal($objProcedimentoDTO->getStrStaNivelAcessoLocalProtocolo());
                $objProtocoloDTO->setNumIdHipoteseLegal($objProcedimentoDTO->getNumIdHipoteseLegalProtocolo());
                $objProtocoloDTO->setStrStaGrauSigilo($objProcedimentoDTO->getStrStaGrauSigiloProtocolo());

                //observação buscar
                $objObservacaoDTO = new ObservacaoDTO();
                $objObservacaoDTO->retStrDescricao();
                $objObservacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
                $objObservacaoDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());

                $objObservacaoRN = new ObservacaoRN();
                $objObservacaoDTO = $objObservacaoRN->consultarRN0221($objObservacaoDTO);

                if ($objObservacaoDTO != null) {
                    $strObservacao = $objObservacaoDTO->getStrDescricao();
                }

                $strStaNivelAcessoGlobal = $objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo();
            } else {
                $objProcedimentoDTO->setDblIdProcedimento($_POST['hdnIdProcedimento']);

                if (isset($_POST['txtProtocoloAlterar'])) {
                    $objProtocoloDTO->setStrProtocoloFormatado($_POST['txtProtocoloAlterar']);
                } else {
                    $objProtocoloDTO->setStrProtocoloFormatado($_POST['hdnProtocoloFormatado']);
                }

                if (isset($_POST['txtDtaGeracaoAlterar'])) {
                    $objProtocoloDTO->setDtaGeracao($_POST['txtDtaGeracaoAlterar']);
                } else {
                    $objProtocoloDTO->setDtaGeracao($_POST['hdnDtaGeracao']);
                }

                $objProtocoloDTO->setStrDescricao($_POST['txtDescricao']);
                $objProcedimentoDTO->setNumIdTipoPrioridade($_POST['selTipoPrioridade']);
                $objProtocoloDTO->setStrStaNivelAcessoLocal($_POST['rdoNivelAcesso']);
                $objProtocoloDTO->setNumIdHipoteseLegal($_POST['selHipoteseLegal']);
                $objProtocoloDTO->setStrStaGrauSigilo($_POST['selGrauSigilo']);
                $objProcedimentoDTO->setNumIdTipoProcedimento($_POST['hdnIdTipoProcedimento']);
                $objProtocoloDTO->setNumIdTipoProcedimentoProcedimento($_POST['hdnIdTipoProcedimento']);
                $objProcedimentoDTO->setStrNomeTipoProcedimento($_POST['hdnNomeTipoProcedimento']);
                $strObservacao = $_POST['txaObservacoes'];
                $strStaNivelAcessoGlobal = $_POST['hdnStaNivelAcessoGlobal'];
            }


            $objProtocoloDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
            $objProcedimentoDTO->setStrSinGerarPendencia('N');

            //ASSUNTOS
            $arrAssuntos = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnAssuntos']);

            $arrObjAssuntoDTO = array();
            for ($x = 0; $x < count($arrAssuntos); $x++) {
                $objRelProtocoloAssuntoDTO = new RelProtocoloAssuntoDTO();
                $objRelProtocoloAssuntoDTO->setNumIdAssunto($arrAssuntos[$x]);
                $objRelProtocoloAssuntoDTO->setNumSequencia($x);
                $arrObjAssuntoDTO[$x] = $objRelProtocoloAssuntoDTO;
            }
            $objProtocoloDTO->setArrObjRelProtocoloAssuntoDTO($arrObjAssuntoDTO);

            //OBSERVACOES
            $objObservacaoDTO = new ObservacaoDTO();
            $objObservacaoDTO->setStrDescricao($strObservacao);
            $objProtocoloDTO->setArrObjObservacaoDTO(array($objObservacaoDTO));

            $arrObjParticipantesDTO = array();
            //INTERESSADOS
            if ($_POST['hdnSinIndividual'] == 'N') {
                $arrParticipantes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnInteressadosProcedimento']);

                for ($x = 0; $x < count($arrParticipantes); $x++) {
                    $objParticipanteDTO = new ParticipanteDTO();
                    $objParticipanteDTO->setNumIdContato($arrParticipantes[$x]);
                    $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);
                    $objParticipanteDTO->setNumSequencia($x);
                    $arrObjParticipantesDTO[$x] = $objParticipanteDTO;
                }
            } else {
                if ($_POST['hdnIdInteressadoUsuario'] != '') {
                    $objParticipante = new ParticipanteDTO();
                    $objParticipante->setNumIdContato($_POST['hdnIdInteressadoUsuario']);
                    $objParticipante->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);
                    $objParticipante->setNumSequencia(0);
                    $arrObjParticipantesDTO[] = $objParticipante;
                }
            }

            $objProtocoloDTO->setArrObjParticipanteDTO($arrObjParticipantesDTO);

            $objProcedimentoDTO->setObjProtocoloDTO($objProtocoloDTO);

            //$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros)).PaginaSEI::getInstance()->montarAncora($objProcedimentoDTO->getDblIdProcedimento()).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';


            if ($_POST['hdnFlagProcedimentoCadastro'] == '2') {
                try {
                    $objProcedimentoRN->alterarRN0202($objProcedimentoDTO);

                    //PaginaSEI::getInstance()->setStrMensagem('Processo '.$objProcedimentoDTO->getStrProtocoloProcedimentoFormatado().' alterado com sucesso.');

                    if (isset($_GET['id_procedimento_retorno'])) {
                        $strParametros = str_replace(
                            '&id_procedimento=' . $objProcedimentoDTO->getDblIdProcedimento(),
                            '&id_procedimento=' . $_GET['id_procedimento_retorno'] . '&id_procedimento_anexado=' . $objProcedimentoDTO->getDblIdProcedimento(
                            ),
                            $strParametros
                        );
                    }

                    header(
                        'Location: ' . SessaoSEI::getInstance()->assinarLink(
                            'controlador.php?acao=arvore_visualizar&acao_origem=' . $_GET['acao'] . $strParametros . '&atualizar_arvore=1' . PaginaSEI::getInstance(
                            )->montarAncora($objProcedimentoDTO->getDblIdProcedimento())
                        )
                    );
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'procedimento_consultar':
            $strTitulo = "Consultar Processo";

            $strAncora = $_GET['id_procedimento'];
            $strParametros = '&id_procedimento=' . $_GET['id_procedimento'];


            $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance(
                )->assinarLink(
                    'controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno(
                    ) . '&acao_origem=' . $_GET['acao'] . $strParametros . PaginaSEI::getInstance()->montarAncora(
                        $strAncora
                    )
                ) . '\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

            $objProcedimentoDTO->retTodos(true);
            $objProcedimentoDTO->setDblIdProcedimento($_GET['id_procedimento']);

            $objProcedimentoDTO = $objProcedimentoRN->consultarRN0201($objProcedimentoDTO);
            if ($objProcedimentoDTO == null) {
                throw new InfraException("Registro não encontrado.");
            }

            $objProtocoloDTO = new ProtocoloDTO();
            $objProtocoloDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
            $objProtocoloDTO->setStrProtocoloFormatado($objProcedimentoDTO->getStrProtocoloProcedimentoFormatado());
            $objProtocoloDTO->setDtaGeracao($objProcedimentoDTO->getDtaGeracaoProtocolo());
            $objProtocoloDTO->setStrDescricao($objProcedimentoDTO->getStrDescricaoProtocolo());
            $objProtocoloDTO->setStrStaNivelAcessoLocal($objProcedimentoDTO->getStrStaNivelAcessoLocalProtocolo());
            $objProtocoloDTO->setNumIdHipoteseLegal($objProcedimentoDTO->getNumIdHipoteseLegalProtocolo());
            $objProtocoloDTO->setStrStaGrauSigilo($objProcedimentoDTO->getStrStaGrauSigiloProtocolo());
            $objProcedimentoDTO->setStrSinGerarPendencia('N');


            //observação buscar
            $objObservacaoDTO = new ObservacaoDTO();
            $objObservacaoDTO->retStrDescricao();
            $objObservacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objObservacaoDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());

            $objObservacaoRN = new ObservacaoRN();
            $objObservacaoDTO = $objObservacaoRN->consultarRN0221($objObservacaoDTO);

            if ($objObservacaoDTO == null) {
                $objObservacaoDTO = new ObservacaoDTO();
                $objObservacaoDTO->setStrDescricao('');
            }

            $strStaNivelAcessoGlobal = $objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo();

            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    //ASSUNTOS
    $strAssuntosNegados = 'var arrAssuntosNegados = Array();' . "\n";
    $numAssuntos = 0;
    if (!isset($_POST['hdnFlagProcedimentoCadastro'])) {
        if ($_GET['acao'] == 'procedimento_gerar' || $_GET['acao'] == 'procedimento_gerar_relacionado') {
            $strItensSelRelProtocoloAssunto = TipoProcedimentoINT::montarSelectSugestaoAssuntosRI0567(
                $objProcedimentoDTO->getNumIdTipoProcedimento()
            );
        } else {
            $strItensSelRelProtocoloAssunto = RelProtocoloAssuntoINT::conjuntoPorCodigoDescricaoRI0510(
                $objProcedimentoDTO->getDblIdProcedimento()
            );
            if ($_GET['acao'] == 'procedimento_alterar') {
                $objRelProtocoloAssuntoDTO = new RelProtocoloAssuntoDTO();
                $objRelProtocoloAssuntoDTO->setDistinct(true);
                $objRelProtocoloAssuntoDTO->retNumIdAssunto();
                $objRelProtocoloAssuntoDTO->retStrSiglaUnidade();
                $objRelProtocoloAssuntoDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
                $objRelProtocoloAssuntoDTO->setNumIdUnidade(
                    SessaoSEI::getInstance()->getNumIdUnidadeAtual(),
                    InfraDTO::$OPER_DIFERENTE
                );
                $objRelProtocoloAssuntoRN = new RelProtocoloAssuntoRN();
                $arrObjRelProtocoloAssuntoDTO = $objRelProtocoloAssuntoRN->listarRN0188($objRelProtocoloAssuntoDTO);

                foreach ($arrObjRelProtocoloAssuntoDTO as $objRelProtocoloAssuntoDTO) {
                    $strAssuntosNegados .= 'arrAssuntosNegados[' . $numAssuntos++ . '] = {id_assunto:\'' . $objRelProtocoloAssuntoDTO->getNumIdAssunto(
                        ) . '\',sigla_unidade:\'' . $objRelProtocoloAssuntoDTO->getStrSiglaUnidade() . '\'};' . "\n";;
                }
            }
        }
    } else {
        if ($_POST['hdnFlagProcedimentoCadastro'] == '1') {
            $_POST['hdnAssuntos'] = '';
            $strItensSelRelProtocoloAssunto = AssuntoINT::montarSelectTrocaTipoProcedimento(
                $objProcedimentoDTO->getNumIdTipoProcedimento(),
                $arrAssuntos
            );
        }
    }


    //INTERESSADOS
    $strItensSelParticipante = ParticipanteINT::conjuntoPorParticipacaoRI0513(
        $objProcedimentoDTO->getDblIdProcedimento(),
        array(ParticipanteRN::$TP_INTERESSADO)
    );
    $strInteressadosNegados = 'var arrInteressadosNegados = Array();' . "\n";
    $numInteressados = 0;
    if ($_GET['acao'] == 'procedimento_alterar') {
        $objParticipanteDTO = new ParticipanteDTO();
        $objParticipanteDTO->retNumIdContato();
        $objParticipanteDTO->retStrSiglaUnidade();
        $objParticipanteDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
        $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);
        $objParticipanteDTO->setNumIdUnidade(
            SessaoSEI::getInstance()->getNumIdUnidadeAtual(),
            InfraDTO::$OPER_DIFERENTE
        );
        $objParticipanteRN = new ParticipanteRN();
        $arrObjParticipanteDTO = $objParticipanteRN->listarRN0189($objParticipanteDTO);

        foreach ($arrObjParticipanteDTO as $objParticipanteDTO) {
            $strInteressadosNegados .= 'arrInteressadosNegados[' . $numInteressados++ . '] = {id_contato: \'' . $objParticipanteDTO->getNumIdContato(
                ) . '\', sigla_unidade: \'' . $objParticipanteDTO->getStrSiglaUnidade() . '\'};' . "\n";
        }
        //$strInteressadosNegados = implode(',',InfraArray::converterArrInfraDTO($arrObjParticipanteDTO,'IdContato'));
    }

    $bolNumeroProcessoInformar = false;
    $bolNumeroProcessoAlterar = false;
    $bolNumeroProcessoExibir = false;
    $strExibirProtocoloParaDigitacao = 'visibility:hidden;';
    $bolMarcarProtocoloAutomatico = false;
    $bolMarcarProtocoloManual = false;
    if ($_GET['acao'] == 'procedimento_gerar' || $_GET['acao'] == 'procedimento_gerar_relacionado') {
        if (($bolNumeroProcessoInformar = $objProcedimentoRN->verificarLiberacaoNumeroProcesso($objProcedimentoDTO))) {
            if (!isset($_POST['rdoProtocolo'])) {
                $bolMarcarProtocoloAutomatico = true;
            } else {
                if ($_POST['rdoProtocolo'] == 'A') {
                    $bolMarcarProtocoloAutomatico = true;
                } else {
                    if ($_POST['rdoProtocolo'] == 'M') {
                        $bolMarcarProtocoloManual = true;
                        $strExibirProtocoloParaDigitacao = '';
                    }
                }
            }
        }
    } else {
        if ($_GET['acao'] == 'procedimento_alterar') {
            $bolNumeroProcessoAlterar = $objProcedimentoRN->verificarLiberacaoNumeroProcesso($objProcedimentoDTO);
        } else {
            $bolNumeroProcessoExibir = true;
        }
    }


    $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
    $objTipoProcedimentoDTO->setBolExclusaoLogica(false);
    $objTipoProcedimentoDTO->retStrSinIndividual();
    $objTipoProcedimentoDTO->setNumIdTipoProcedimento($objProcedimentoDTO->getNumIdTipoProcedimento());

    $objTipoProcedimentoRN = new TipoProcedimentoRN();
    $objTipoProcedimentoDTO = $objTipoProcedimentoRN->consultarRN0267($objTipoProcedimentoDTO);

    if ($objTipoProcedimentoDTO == null) {
        throw new InfraException('Tipo do processo não encontrado.');
    }

    $strSinIndividual = $objTipoProcedimentoDTO->getStrSinIndividual();


    //busca somente ao entrar na tela
    $strIdInteressadoUsuario = '';
    $strNomeInteressadoUsuario = '';

    if ($strSinIndividual == 'S') {
        if (!isset($_POST['hdnIdProcedimento'])) {
            $objParticipanteDTO = new ParticipanteDTO();
            $objParticipanteDTO->retNumIdContato();
            $objParticipanteDTO->retStrNomeContato();
            $objParticipanteDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
            $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);

            $objParticipanteRN = new ParticipanteRN();
            $arrObjParticipanteDTO = $objParticipanteRN->listarRN0189($objParticipanteDTO);

            if (count($arrObjParticipanteDTO) > 1) {
                //throw new InfraException('Processo individual com mais de um interessado cadastrado.');
                $strSinIndividual = 'N';
            } else {
                if (count($arrObjParticipanteDTO) == 1) {
                    $strIdInteressadoUsuario = $arrObjParticipanteDTO[0]->getNumIdContato();
                    $strNomeInteressadoUsuario = $arrObjParticipanteDTO[0]->getStrNomeContato();
                }
            }
        } else {
            $strIdInteressadoUsuario = $_POST['hdnIdInteressadoUsuario'];
            $strNomeInteressadoUsuario = $_POST['txtInteressadoUsuario'];
        }
    }


    //OBSERVACOES
    $strTabObservacoes = ObservacaoINT::tabelaObservacoesOutrasUnidades($objProcedimentoDTO->getDblIdProcedimento());

    //Links para uso com AJAX
    $strLinkAjaxAssuntoRI1223 = SessaoSEI::getInstance()->assinarLink(
        'controlador_ajax.php?acao_ajax=assunto_auto_completar_RI1223'
    );
    $strLinkAjaxInteressados = SessaoSEI::getInstance()->assinarLink(
        'controlador_ajax.php?acao_ajax=contato_auto_completar_contexto_RI1225'
    );
    $strLinkAjaxInteressadoUsuario = SessaoSEI::getInstance()->assinarLink(
        'controlador_ajax.php?acao_ajax=usuario_auto_completar_contato'
    );
    $strLinkAjaxCadastroAutomatico = SessaoSEI::getInstance()->assinarLink(
        'controlador_ajax.php?acao_ajax=contato_cadastro_contexto_temporario'
    );
    $strLinkAssuntosSelecao = SessaoSEI::getInstance()->assinarLink(
        'controlador.php?acao=assunto_selecionar&tipo_selecao=2&id_object=objLupaAssuntos'
    );
    $strLinkInteressados = SessaoSEI::getInstance()->assinarLink(
        'controlador.php?acao=contato_selecionar&tipo_selecao=2&id_object=objLupaInteressados'
    );
    $strLinkAlterarContato = SessaoSEI::getInstance()->assinarLink(
        'controlador.php?acao=contato_alterar&acao_origem=' . $_GET['acao'] . '&arvore=' . $_GET['arvore']
    );
    $strLinkConsultarContato = SessaoSEI::getInstance()->assinarLink(
        'controlador.php?acao=contato_consultar&acao_origem=' . $_GET['acao'] . '&arvore=' . $_GET['arvore']
    );
    $strLinkConsultarAssunto = SessaoSEI::getInstance()->assinarLink(
        'controlador.php?acao=assunto_consultar&acao_origem=' . $_GET['acao']
    );


    $strDisplayTipoProcedimentoTitulo = 'display:block;';
    $strDisplayTipoProcedimento = 'display:none;';

    $strDisplayTipoProcedimentoTitulo = 'display:none;';
    $strDisplayTipoProcedimento = 'display:block;';
    $strItensSelTipoProcedimento = TipoProcedimentoINT::montarSelectNomeLiberados(
        'null',
        '&nbsp;',
        $objProcedimentoDTO->getNumIdTipoProcedimento(),
        true
    );
    $strItensSelTipoPrioridade = TipoPrioridadeINT::montarSelectIdTipoPrioridade(
        'null',
        '&nbsp;',
        $objProcedimentoDTO->getNumIdTipoPrioridade(),
        true
    );

    $strDisplayDivInteressados = 'display:block;';
    $strDisplayDivInteressadoUsuario = 'display:none;';
    if ($strSinIndividual == 'S') {
        $strDisplayDivInteressados = 'display:none;';
        $strDisplayDivInteressadoUsuario = 'display:block;';
    }


    ProtocoloINT::montarNivelAcesso(array($objProcedimentoDTO->getNumIdTipoProcedimento()),
        $objProtocoloDTO,
        ($_GET['acao'] == 'procedimento_consultar'),
        $strCssNivelAcesso,
        $strHtmlNivelAcesso,
        $strJsGlobalNivelAcesso,
        $strJsInicializarNivelAcesso,
        $strJsValidacoesNivelAcesso);

    $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
    $arrMascaraProtocolo = explode('|', $objInfraParametro->getValor('SEI_MASCARA_NUMERO_PROCESSO_INFORMADO'));
    $strMascaraProtocolo = trim($arrMascaraProtocolo[0]);
} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>

    #divTipoProcedimentoTitulo {<?= $strDisplayTipoProcedimentoTitulo ?>}
    #divTipoProcedimento {<?= $strDisplayTipoProcedimento ?>}
    #lblTipoProcedimento {position:absolute;left:0%;top:0%;}
    #selTipoProcedimento {position:absolute;left:0%;top:38%;width:85%;}

    #lblTipoPrioridade {position:absolute;left:0%;top:0%;}
    #selTipoPrioridade {position:absolute;left:0%;top:38%;width:85%;}

<?
if ($bolNumeroProcessoInformar) { ?>
    #divProtocoloInformar {}
    #fldProtocoloInformar {position:absolute;left:0%;top:0%;height:80%;width:85%;}
    #divOptProtocoloAutomatico {position:absolute;left:5%;top:30%;width:15%;}
    #divOptProtocoloManual {position:absolute;left:5%;top:60%;width:15%;}

    #lblProtocoloInformar {position:absolute;left:25%;top:37%;width:34%;<?= $strExibirProtocoloParaDigitacao ?>}
    #txtProtocoloInformar {position:absolute;left:25%;top:60%;width:34%;<?= $strExibirProtocoloParaDigitacao ?>}

    #lblDtaGeracaoInformar {position:absolute;left:65%;top:37%;width:25%;<?= $strExibirProtocoloParaDigitacao ?>}
    #txtDtaGeracaoInformar {position:absolute;left:65%;top:60%;width:12%;<?= $strExibirProtocoloParaDigitacao ?>}
    #imgCalDtaGeracaoInformar {position:absolute;left:78%;top:60%;<?= $strExibirProtocoloParaDigitacao ?>}
    <?
} ?>

<?
if ($bolNumeroProcessoAlterar) { ?>
    #divProtocoloAlterar {}
    #lblProtocoloAlterar {position:absolute;left:0%;top:0%;}
    #txtProtocoloAlterar {position:absolute;left:0%;top:38%;width:30%;}
    #lblDtaGeracaoAlterar {position:absolute;left:33%;top:0%;}
    #txtDtaGeracaoAlterar {position:absolute;left:33%;top:40%;width:12%;}
    #imgCalDtaGeracaoAlterar {position:absolute;left:46%;top:40%;}
    <?
} ?>

<?
if ($bolNumeroProcessoExibir) { ?>
    #divProtocoloExibir {}
    #lblProtocoloExibir {position:absolute;left:0%;top:0%;}
    #txtProtocoloExibir {position:absolute;left:0%;top:38%;width:30%;}
    #lblDtaGeracaoExibir {position:absolute;left:33%;top:0%;}
    #txtDtaGeracaoExibir {position:absolute;left:33%;top:40%;width:16%;}
    <?
} ?>

    #lblDescricao {position:absolute;left:0%;top:0%;}
    #txtDescricao {position:absolute;left:0%;top:38%;width:85%;}

    #lblAssuntos {position:absolute;left:0%;top:0%;}
    #txtAssunto {position:absolute;left:0%;top:18%;width:50%;}
    #selAssuntos {position:absolute;left:0%;top:43%;width:85%;height:50%;}
    #divOpcoesAssuntos {position:absolute;left:86%;top:43%;}

    #divInteressados {<?= $strDisplayDivInteressados; ?>}
    #lblInteressadosProcedimento {position:absolute;left:0%;top:0%;width:90%;}
    #txtInteressadoProcedimento {position:absolute;left:0%;top:18%;width:50%;}
    #selInteressadosProcedimento {position:absolute;left:0%;top:43%;width:85%;height:50%;}
    #divOpcoesInteressados {position:absolute;left:86%;top:43%;}

    #divInteressadoUsuario {<?= $strDisplayDivInteressadoUsuario; ?>}
    #lblInteressadoUsuario {position:absolute;left:0%;top:0%;}
    #txtInteressadoUsuario {position:absolute;left:0%;top:40%;width:85%;}

    #lblObservacoes {position:absolute;left:0%;top:0%;width:50%;}
    #txaObservacoes {position:absolute;left:0%;top:27%;width:85%;}

<?= $strCssNivelAcesso ?>
<?
if (PaginaSEI::getInstance()->isBolAjustarTopFieldset()) {
    ?>
    #divOptProtocoloAutomatico {top:15%;}
    #divOptProtocoloManual {top:50%}

    #lblProtocoloInformar {top:23%;}
    #txtProtocoloInformar {top:50%;}

    #lblDtaGeracaoInformar {top:23%;}
    #txtDtaGeracaoInformar {top:50%;}
    #imgCalDtaGeracaoInformar {top:50%;}
    <?
}
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
    //<script>
    var objAutoCompletarAssuntoRI1223 = null;
    var objLupaAssuntos = null;
    var objAutoCompletarInteressadoRI1225 = null;
    var objLupaInteressados = null;
    var objAutoCompletarInteressadoUsuario = null;
    var objContatoCadastroAutomatico = null;

    <?=$strJsGlobalNivelAcesso?>

    <?=$strAssuntosNegados?>

    <?=$strInteressadosNegados?>

    function inicializar(){

        if ('<?=$_GET['acao']?>'=='procedimento_gerar' || '<?=$_GET['acao']?>'=='procedimento_gerar_relacionado'){
            document.getElementById('txtDescricao').focus();
        }else  if ('<?=$_GET['acao']?>'=='procedimento_alterar'){
            document.getElementById('txtDescricao').focus();
        }

        /* *********************************************************************************************** */

        objAutoCompletarInteressadoUsuario = new infraAjaxAutoCompletar('hdnIdInteressadoUsuario','txtInteressadoUsuario','<?=$strLinkAjaxInteressadoUsuario?>');
        //objAutoCompletarInteressadoUsuario.maiusculas = true;
        //objAutoCompletarInteressadoUsuario.mostrarAviso = true;
        //objAutoCompletarInteressadoUsuario.tempoAviso = 1000;
        //objAutoCompletarInteressadoUsuario.tamanhoMinimo = 3;
        objAutoCompletarInteressadoUsuario.limparCampo = false;
        //objAutoCompletarInteressadoUsuario.bolExecucaoAutomatica = false;

        objAutoCompletarInteressadoUsuario.prepararExecucao = function(){
            return 'palavras_pesquisa='+document.getElementById('txtInteressadoUsuario').value;
        };

        objAutoCompletarInteressadoUsuario.processarResultado = function(id,descricao,complemento){
            if (id!=''){
                document.getElementById('hdnIdInteressadoUsuario').value = id;
                document.getElementById('txtInteressadoUsuario').value = descricao;
            }
        }
        objAutoCompletarInteressadoUsuario.selecionar('<?=$strIdInteressadoUsuario?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeInteressadoUsuario,false);?>');

        /* *********************************************************************************************** */

        if ('<?=$_GET['acao']?>'=='procedimento_consultar'){
            infraDesabilitarCamposDiv(document.getElementById('divTipoProcedimento'));
            infraDesabilitarCamposDiv(document.getElementById('divDescricao'));
            infraDesabilitarCamposDiv(document.getElementById('divAssuntos'));

            document.getElementById('selAssuntos').ondblclick = function(e){
                if (this.selectedIndex!=-1) {
                    seiCadastroAssunto(this.options[this.selectedIndex].value, 'selAssuntos', 'frmProcedimentoCadastro', '<?=$strLinkConsultarAssunto?>');
                }
            };

            infraDesabilitarCamposDiv(document.getElementById('divInteressados'));
            document.getElementById('selInteressadosProcedimento').ondblclick = function(e){
                if (this.selectedIndex!=-1) {
                    seiCadastroContato(this.options[this.selectedIndex].value, 'selInteressadosProcedimento', 'frmProcedimentoCadastro','<?=$strLinkConsultarContato?>');
                }
            };



            infraDesabilitarCamposDiv(document.getElementById('divInteressadoUsuario'));
            infraDesabilitarCamposDiv(document.getElementById('divObservacoes'));
            if (document.getElementById('divObservacoesOutras')!=null) {
                infraDesabilitarCamposDiv(document.getElementById('divObservacoesOutras'));
            }
            infraDesabilitarCamposDiv(document.getElementById('divNivelAcesso'));
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

        <?if ($_GET['acao']=='procedimento_alterar'){?>
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
            seiCadastroAssunto(valor, 'selAssuntos','frmProcedimentoCadastro','<?=$strLinkConsultarAssunto?>');
        }

        document.getElementById('selAssuntos').ondblclick = function(e){
            objLupaAssuntos.alterar();
        };


        objAutoCompletarInteressadoRI1225 = new infraAjaxAutoCompletar('hdnIdInteressadoProcedimento','txtInteressadoProcedimento','<?=$strLinkAjaxInteressados?>');
        //objAutoCompletarInteressadoRI1225.maiusculas = true;
        //objAutoCompletarInteressadoRI1225.mostrarAviso = true;
        //objAutoCompletarInteressadoRI1225.tempoAviso = 1000;
        //objAutoCompletarInteressadoRI1225.tamanhoMinimo = 3;
        objAutoCompletarInteressadoRI1225.limparCampo = false;
        //objAutoCompletarInteressadoRI1225.bolExecucaoAutomatica = false;

        objAutoCompletarInteressadoRI1225.prepararExecucao = function(){
            return 'palavras_pesquisa='+encodeURIComponent(document.getElementById('txtInteressadoProcedimento').value);
        };

        objAutoCompletarInteressadoRI1225.processarResultado = function(id,descricao,complemento){
            if (id!=''){
                objLupaInteressados.adicionar(id,descricao,document.getElementById('txtInteressadoProcedimento'));
            }
        };

        infraAdicionarEvento(document.getElementById('txtInteressadoProcedimento'),'keyup',tratarEnterInteressado);

        objLupaInteressados = new infraLupaSelect('selInteressadosProcedimento','hdnInteressadosProcedimento','<?=$strLinkInteressados?>');

        objLupaInteressados.processarAlteracao = function (pos, texto, valor){
            seiCadastroContato(valor, 'selInteressadosProcedimento', 'frmProcedimentoCadastro','<?=$strLinkAlterarContato?>');
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

        document.getElementById('selInteressadosProcedimento').ondblclick = function(e){
            objLupaInteressados.alterar();
        };

        objContatoCadastroAutomatico = new infraAjaxComplementar(null,'<?=$strLinkAjaxCadastroAutomatico?>');
        //objContatoCadastroAutomatico.mostrarAviso = false;
        //objContatoCadastroAutomatico.tempoAviso = 3000;
        //objContatoCadastroAutomatico.limparCampo = false;

        objContatoCadastroAutomatico.prepararExecucao = function(){
            return 'nome='+encodeURIComponent(document.getElementById('txtInteressadoProcedimento').value);
        };

        objContatoCadastroAutomatico.processarResultado = function(arr){
            if (arr!=null){
                objAutoCompletarInteressadoRI1225.processarResultado(arr['IdContato'], document.getElementById('txtInteressadoProcedimento').value, null);
                //alert('Interessado cadastrado com sucesso.');
            }
        };

        <?=$strJsInicializarNivelAcesso?>

        infraEfeitoTabelas();
    }

    function tratarEnterInteressado(ev){

        var key = infraGetCodigoTecla(ev);

        if (key == 13 && document.getElementById('hdnIdInteressadoProcedimento').value==''){
            if (confirm('Nome inexistente. Deseja incluir?')){
                objContatoCadastroAutomatico.executar();
            }
        }
    }

    function confirmarDados(){
        if (OnSubmitForm()){
            var arrBotoesSalvar = document.getElementsByName('btnSalvar');
            for(var i=0; i < arrBotoesSalvar.length; i++){
                arrBotoesSalvar[i].disabled = true;
            }
            document.getElementById('hdnFlagProcedimentoCadastro').value = '2';
            document.getElementById('frmProcedimentoCadastro').submit();
        }
    }

    function OnSubmitForm() {
        return validarCadastroRI0152();
    }

    function validarCadastroRI0152() {

        <?if ($bolNumeroProcessoInformar){?>
        if (document.getElementById('optProtocoloManual').checked){
            if (infraTrim(document.getElementById('txtProtocoloInformar').value)==''){
                alert('Informe o número do protocolo.');
                document.getElementById('txtProtocoloInformar').focus();
                return false;
            }

            if (infraTrim(document.getElementById('txtDtaGeracaoInformar').value)==''){
                alert('Informe a Data de Autuação do protocolo.');
                document.getElementById('txtDtaGeracaoInformar').focus();
                return false;
            }
        }
        <?}?>

        <?if ($bolNumeroProcessoAlterar){?>
        if (infraTrim(document.getElementById('txtProtocoloAlterar').value)==''){
            alert('Informe o número do protocolo.');
            document.getElementById('txtProtocoloAlterar').focus();
            return false;
        }

        if (infraTrim(document.getElementById('txtDtaGeracaoAlterar').value)==''){
            alert('Informe a Data de Autuação do protocolo.');
            document.getElementById('txtDtaGeracaoAlterar').focus();
            return false;
        }
        <?}?>

        if (document.getElementById('hdnIdTipoProcedimento').value=='null') {
            alert('Informe o Tipo do Processo.');
            return false;
        }

        if (document.getElementById('hdnAssuntos').value=='') {
            alert('Informe os Assuntos.');
            document.getElementById('txtAssunto').focus();
            return false;
        }

        if (document.getElementById('hdnSinIndividual').value=='S' && infraTrim(document.getElementById('hdnIdInteressadoUsuario').value)==''){
            alert('Informe o Interessado.');
            document.getElementById('txtInteressadoUsuario').focus();
            return false;
        }

        <?=$strJsValidacoesNivelAcesso?>

        return true;
    }

    function trocarTipoProcedimento(){

        document.getElementById('hdnIdTipoProcedimento').value = document.getElementById('selTipoProcedimento').value;

        if (document.getElementById('hdnIdTipoProcedimento').value!='null') {
            document.getElementById('frmProcedimentoCadastro').submit();
        }
    }

    function alterarProtocolo(){
        if (document.getElementById('optProtocoloAutomatico').checked){
            document.getElementById('lblProtocoloInformar').style.visibility = 'hidden';
            document.getElementById('txtProtocoloInformar').style.visibility = 'hidden';
            document.getElementById('txtProtocoloInformar').value = '';

            document.getElementById('lblDtaGeracaoInformar').style.visibility = 'hidden';
            document.getElementById('txtDtaGeracaoInformar').style.visibility = 'hidden';
            document.getElementById('txtDtaGeracaoInformar').value = '';
            document.getElementById('imgCalDtaGeracaoInformar').style.visibility = 'hidden';

        }else if (document.getElementById('optProtocoloManual').checked){
            document.getElementById('lblProtocoloInformar').style.visibility = 'visible';
            document.getElementById('txtProtocoloInformar').style.visibility = 'visible';

            document.getElementById('lblDtaGeracaoInformar').style.visibility = 'visible';
            document.getElementById('txtDtaGeracaoInformar').style.visibility = 'visible';
            document.getElementById('imgCalDtaGeracaoInformar').style.visibility = 'visible';

            document.getElementById('txtProtocoloInformar').focus();
        }
    }
    //</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmProcedimentoCadastro" method="post" onsubmit="return OnSubmitForm();"
          action="<?= SessaoSEI::getInstance()->assinarLink(
              'controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] . $strParametros
          ) ?>" style="display:inline;">
        <?
        //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        //PaginaSEI::getInstance()->montarAreaValidacao();
        ?>
        <?
        if ($bolNumeroProcessoInformar) { ?>
            <div id="divProtocoloInformar" class="infraAreaDados" style="height:11em;">

                <fieldset id="fldProtocoloInformar" class="infraFieldset">
                    <legend class="infraLegend">Protocolo</legend>

                    <div id="divOptProtocoloAutomatico" class="infraDivRadio">
                        <input type="radio" name="rdoProtocolo" id="optProtocoloAutomatico" onclick="alterarProtocolo()"
                               value="A" <?= ($bolMarcarProtocoloAutomatico ? 'checked="checked"' : '') ?>
                               class="infraRadio"/>
                        <span id="spnProtocoloAutomatico"><label id="lblProtocoloAutomatico"
                                                                 for="optProtocoloAutomatico" class="infraLabelRadio"
                                                                 tabindex="<?= PaginaSEI::getInstance(
                                                                 )->getProxTabDados() ?>">Automático</label><label>&nbsp;</label></span>
                    </div>

                    <div id="divOptProtocoloManual" class="infraDivRadio">
                        <input type="radio" name="rdoProtocolo" id="optProtocoloManual" onclick="alterarProtocolo()"
                               value="M" <?= ($bolMarcarProtocoloManual ? 'checked="checked"' : '') ?>
                               class="infraRadio"/>
                        <span id="spnProtocoloManual"><label id="lblProtocoloManual" for="optProtocoloManual"
                                                             class="infraLabelRadio"
                                                             tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(
                                                             ) ?>">Informado</label></span>
                    </div>

                    <label id="lblProtocoloInformar" for="txtProtocoloInformar" accesskey=""
                           class="infraLabelObrigatorio">Número:</label>
                    <input type="text" id="txtProtocoloInformar"
                           name="txtProtocoloInformar" <?= (InfraString::isBolVazia(
                        $strMascaraProtocolo
                    ) ? '' : 'onkeypress="return infraMascara(this,event,\'' . $strMascaraProtocolo . '\');"') ?>
                           class="infraText"
                           value="<?= PaginaSEI::tratarHTML($objProtocoloDTO->getStrProtocoloFormatado()); ?>"
                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados(
                           ) ?>" <?= ($_POST['rdoProtocolo'] == 'M' ? 'style="visibility:visible"' : '') ?> />

                    <label id="lblDtaGeracaoInformar" for="txtDtaGeracaoInformar" class="infraLabelObrigatorio">Data de
                        Autuação:</label>
                    <input type="text" id="txtDtaGeracaoInformar" name="txtDtaGeracaoInformar"
                           onkeypress="return infraMascaraData(this, event)" class="infraText"
                           value="<?= PaginaSEI::tratarHTML($_POST['txtDtaGeracaoInformar']) ?>"
                           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                    <img src="<?= PaginaSEI::getInstance()->getIconeCalendario() ?>" id="imgCalDtaGeracaoInformar"
                         title="Selecionar Data" alt="Selecionar Data" class="infraImg"
                         onclick="infraCalendario('txtDtaGeracaoInformar',this);"
                         tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

                </fieldset>
            </div>
            <?
        } ?>

        <?
        if ($bolNumeroProcessoAlterar) { ?>
            <div id="divProtocoloAlterar" class="infraAreaDados" style="height:5em;">
                <label id="lblProtocoloAlterar" for="txtProtocoloAlterar" accesskey="" class="infraLabelObrigatorio">Protocolo:</label>
                <input type="text" id="txtProtocoloAlterar" name="txtProtocoloAlterar" <?= (InfraString::isBolVazia(
                    $strMascaraProtocolo
                ) ? '' : 'onkeypress="return infraMascara(this,event,\'' . $strMascaraProtocolo . '\');"') ?>
                       class="infraText"
                       value="<?= PaginaSEI::tratarHTML($objProtocoloDTO->getStrProtocoloFormatado()); ?>"
                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

                <label id="lblDtaGeracaoAlterar" for="txtDtaGeracaoAlterar" accesskey="" class="infraLabelObrigatorio">Data
                    de Autuação:</label>
                <input type="text" id="txtDtaGeracaoAlterar" name="txtDtaGeracaoAlterar"
                       onkeypress="return infraMascaraData(this, event)" class="infraText"
                       value="<?= PaginaSEI::tratarHTML($objProtocoloDTO->getDtaGeracao()); ?>"
                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                <img src="<?= PaginaSEI::getInstance()->getIconeCalendario() ?>" id="imgCalDtaGeracaoAlterar"
                     title="Selecionar Data" alt="Selecionar Data" class="infraImg"
                     onclick="infraCalendario('txtDtaGeracaoAlterar',this);"
                     tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            </div>
            <?
        } ?>

        <?
        if ($bolNumeroProcessoExibir) { ?>
            <div id="divProtocoloExibir" class="infraAreaDados" style="height:5em;">
                <label id="lblProtocoloExibir" for="txtProtocoloExibir" accesskey="" class="infraLabelObrigatorio">Protocolo:</label>
                <input type="text" id="txtProtocoloExibir" name="txtProtocoloExibir" class="infraText infraReadOnly"
                       readonly="readonly"
                       value="<?= PaginaSEI::tratarHTML($objProtocoloDTO->getStrProtocoloFormatado()); ?>"
                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

                <label id="lblDtaGeracaoExibir" for="txtDtaGeracaoExibir" accesskey="" class="infraLabelObrigatorio">Data
                    de Autuação:</label>
                <input type="text" id="txtDtaGeracaoExibir" name="txtDtaGeracaoExibir" class="infraText infraReadOnly"
                       readonly="readonly" value="<?= PaginaSEI::tratarHTML($objProtocoloDTO->getDtaGeracao()); ?>"
                       tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>

            </div>

            <div id="divTipoProcedimentoTitulo" class="tituloProcessoDocumento">
                <label id="lblTipoProcedimentoTitulo"><?= PaginaSEI::tratarHTML(
                        $objProcedimentoDTO->getStrNomeTipoProcedimento()
                    ) ?></label>
            </div>
            <?
        } ?>

        <div id="divTipoProcedimento" class="infraAreaDados" style="height:5em;">
            <label id="lblTipoProcedimento" for="selTipoProcedimento" accesskey="" class="infraLabelObrigatorio">Tipo do
                Processo:</label>
            <select id="selTipoProcedimento" name="selTipoProcedimento" onchange="trocarTipoProcedimento();"
                    class="infraSelect" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $strItensSelTipoProcedimento ?>
            </select>
        </div>

        <div id="divDescricao" class="infraAreaDados" style="height:5em;">
            <label id="lblDescricao" for="txtDescricao" accesskey="" class="infraLabelOpcional">Especificação:</label>
            <input type="text" id="txtDescricao" name="txtDescricao"
                   onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" class="infraText"
                   value="<?= PaginaSEI::tratarHTML($objProtocoloDTO->getStrDescricao()) ?>"
                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
        </div>

        <div id="divTipoPrioridade" class="infraAreaDados" style="height:5em;">
            <label id="lblTipoPrioridade" for="selTipoPrioridade" accesskey="" class="infraLabelOpcional">Prioridade:</label>
            <select id="selTipoPrioridade" name="selTipoPrioridade"
                    class="infraSelect" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $strItensSelTipoPrioridade ?>
            </select>
        </div>

        <div id="divAssuntos" class="infraAreaDados" style="height:10em;">
            <label id="lblAssuntos" for="txtAssunto" accesskey="u" class="infraLabelObrigatorio">Classificação por
                Ass<span class="infraTeclaAtalho">u</span>ntos:</label>
            <input type="text" id="txtAssunto" name="txtAssunto" class="infraText"
                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            <input type="hidden" id="hdnIdAssunto" name="hdnIdAssunto" class="infraText" value=""/>
            <select id="selAssuntos" name="selAssuntos" class="infraSelect" multiple="multiple"
                    tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $strItensSelRelProtocoloAssunto ?>
            </select>
            <div id="divOpcoesAssuntos">
                <img id="imgPesquisarAssuntos" onclick="objLupaAssuntos.selecionar(700,500);"
                     src="<?= PaginaSEI::getInstance()->getIconePesquisar() ?>" alt="Pesquisa de Assuntos"
                     title="Pesquisa de Assuntos" class="infraImg"
                     tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                <img id="imgRemoverAssuntos" onclick="objLupaAssuntos.remover();"
                     src="<?= PaginaSEI::getInstance()->getIconeRemover() ?>" alt="Remover Assuntos Selecionados"
                     title="Remover Assuntos Selecionados" class="infraImg"
                     tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                <br/>
                <img id="imgAssuntosAcima" onclick="objLupaAssuntos.moverAcima();"
                     src="<?= PaginaSEI::getInstance()->getIconeMoverAcima() ?>" alt="Mover Acima Assunto Selecionado"
                     title="Mover Acima Assunto Selecionado" class="infraImg"
                     tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                <img id="imgAssuntosAbaixo" onclick="objLupaAssuntos.moverAbaixo();"
                     src="<?= PaginaSEI::getInstance()->getIconeMoverAbaixo() ?>" alt="Mover Abaixo Assunto Selecionado"
                     title="Mover Abaixo Assunto Selecionado" class="infraImg"
                     tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            </div>
        </div>

        <div id="divInteressados" class="infraAreaDados" style="height:10em;">
            <label id="lblInteressadosProcedimento" for="txtInteressadoProcedimento" accesskey="I"
                   class="infraLabelOpcional"><span class="infraTeclaAtalho">I</span>nteressados:</label>
            <input type="text" id="txtInteressadoProcedimento" name="txtInteressadoProcedimento" class="infraText"
                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            <input type="hidden" id="hdnIdInteressadoProcedimento" name="hdnIdInteressadoProcedimento" class="infraText"
                   value=""/>
            <select id="selInteressadosProcedimento" name="selInteressadosProcedimento" class="infraSelect"
                    multiple="multiple" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
                <?= $strItensSelParticipante ?>
            </select>
            <div id="divOpcoesInteressados">
                <img id="imgSelecionarGrupo" onclick="objLupaInteressados.selecionar(700,500);"
                     src="<?= PaginaSEI::getInstance()->getIconePesquisar() ?>"
                     title="Selecionar Contatos para Interessados" alt="Selecionar Contatos para Interessados"
                     class="infraImg" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                <img id="imgAlterarInteressado" onclick="objLupaInteressados.alterar();"
                     src="<?= PaginaSEI::getInstance()->getIconeAlterar() ?>"
                     alt="Consultar/Alterar Dados do Interessado Selecionado"
                     title="Consultar/Alterar Dados do Interessado Selecionado" class="infraImg"
                     tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                <img id="imgRemoverInteressados" onclick="objLupaInteressados.remover();"
                     src="<?= PaginaSEI::getInstance()->getIconeRemover() ?>" alt="Remover Interessados Selecionados"
                     title="Remover Interessados Selecionados" class="infraImg"
                     tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                <br/>
                <img id="imgInteressadosAcima" onclick="objLupaInteressados.moverAcima();"
                     src="<?= PaginaSEI::getInstance()->getIconeMoverAcima() ?>"
                     alt="Mover Acima Interessado Selecionado" title="Mover Acima Interessado Selecionado"
                     class="infraImg" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
                <img id="imgInteressadosAbaixo" onclick="objLupaInteressados.moverAbaixo();"
                     src="<?= PaginaSEI::getInstance()->getIconeMoverAbaixo() ?>"
                     alt="Mover Abaixo Interessado Selecionado" title="Mover Abaixo Interessado Selecionado"
                     class="infraImg" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            </div>
        </div>

        <div id="divInteressadoUsuario" class="infraAreaDados" style="height:5em;">
            <label id="lblInteressadoUsuario" for="txtInteressadoUsuario" accesskey="I"
                   class="infraLabelObrigatorio"><span class="infraTeclaAtalho">I</span>nteressado:</label>
            <input type="text" id="txtInteressadoUsuario" name="txtInteressadoUsuario" class="infraText"
                   tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
            <input type="hidden" id="hdnIdInteressadoUsuario" name="hdnIdInteressadoUsuario" class="infraText"
                   value=""/>
        </div>

        <div id="divObservacoes" class="infraAreaDados" style="height:7em;">
            <label id="lblObservacoes" for="txaObservacoes" accesskey="O" class="infraLabelOpcional"><span
                    class="infraTeclaAtalho">O</span>bservações desta unidade:</label>
            <textarea id="txaObservacoes" name="txaObservacoes" class="infraTextarea" rows="2"
                      onkeypress="return infraLimitarTexto(this,event,1000);"
                      tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"><?= PaginaSEI::tratarHTML(
                    $objObservacaoDTO->getStrDescricao()
                ) ?></textarea>
        </div>

        <?
        if ($strTabObservacoes != '') { ?>
            <div id="divObservacoesOutras" class="infraAreaTabela" style="padding-bottom: 2em;">
                <?= $strTabObservacoes ?>
            </div>
            <?
        } ?>

        <?= $strHtmlNivelAcesso ?>

        <input type="hidden" id="hdnFlagProcedimentoCadastro" name="hdnFlagProcedimentoCadastro" value="1"/>
        <input type="hidden" id="hdnIdTipoProcedimento" name="hdnIdTipoProcedimento"
               value="<?= $objProcedimentoDTO->getNumIdTipoProcedimento() ?>"/>
        <input type="hidden" id="hdnNomeTipoProcedimento" name="hdnNomeTipoProcedimento"
               value="<?= $objProcedimentoDTO->getNumIdTipoPrioridade() ?>"/>
        <input type="hidden" id="hdnAssuntos" name="hdnAssuntos" value="<?= $_POST['hdnAssuntos'] ?>"/>
        <input type="hidden" id="hdnInteressadosProcedimento" name="hdnInteressadosProcedimento"
               value="<?= PaginaSEI::tratarHTML($_POST['hdnInteressadosProcedimento']) ?>"/>
        <input type="hidden" id="hdnIdProcedimento" name="hdnIdProcedimento"
               value="<?= $objProcedimentoDTO->getDblIdProcedimento(); ?>"/>
        <input type="hidden" id="hdnProtocoloFormatado" name="hdnProtocoloFormatado"
               value="<?= $objProtocoloDTO->getStrProtocoloFormatado(); ?>"/>
        <input type="hidden" id="hdnStaNivelAcessoGlobal" name="hdnStaNivelAcessoGlobal"
               value="<?= $strStaNivelAcessoGlobal ?>"/>
        <input type="hidden" id="hdnSinIndividual" name="hdnSinIndividual" value="<?= $strSinIndividual ?>"/>
        <input type="hidden" id="hdnIdHipoteseLegalSugestao" name="hdnIdHipoteseLegalSugestao" value=""/>
        <input type="hidden" id="hdnDtaGeracao" name="hdnDtaGeracao" value="<?= $objProtocoloDTO->getDtaGeracao() ?>"/>

        <input type="hidden" id="hdnContatoObject" name="hdnContatoObject" value=""/>
        <input type="hidden" id="hdnContatoIdentificador" name="hdnContatoIdentificador" value=""/>

        <input type="hidden" id="hdnAssuntoIdentificador" name="hdnAssuntoIdentificador" value=""/>

    </form>
    <br/>
<?
PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>