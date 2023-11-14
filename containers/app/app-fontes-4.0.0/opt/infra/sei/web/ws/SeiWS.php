<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 27/11/2006 - criado por mga
 * 05/06/2018 - cjy - adicionados campos de numero de passaporte e pais do passaporte
 *
 */

require_once dirname(__FILE__).'/../SEI.php';

LimiteSEI::getInstance()->configurarNivel3();

class SeiWS extends InfraWS {
	
	public function getObjInfraLog(){
		return LogSEI::getInstance();
	}

	public function __call($func, $params) {
		try{

			SessaoSEI::getInstance(false);

			if (!method_exists($this, $func.'Monitorado')) {
				throw new InfraException('Serviço ['.get_class($this).'.'.$func.'] não encontrado.');
			}

			BancoSEI::getInstance()->abrirConexao();

			$objUsuarioDTO = new UsuarioDTO();
			$objUsuarioDTO->retNumIdUsuario();
			$objUsuarioDTO->setStrSigla($params[0]);
			$objUsuarioDTO->setStrStaTipo(UsuarioRN::$TU_SISTEMA);

			$objUsuarioRN = new UsuarioRN();
			$objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);

			if ($objUsuarioDTO==null){
				throw new InfraException('Sistema ['.$params[0].'] não encontrado.');
			}

      $objServicoRN = new ServicoRN();

      $objServicoDTO = new ServicoDTO();
      $objServicoDTO->retNumIdServico();
      $objServicoDTO->retStrIdentificacao();
      $objServicoDTO->retStrSiglaUsuario();
      $objServicoDTO->retNumIdUsuario();
      $objServicoDTO->retStrServidor();
      $objServicoDTO->retStrSinLinkExterno();
      $objServicoDTO->retNumIdContatoUsuario();
      $objServicoDTO->retStrChaveAcesso();
      $objServicoDTO->retStrSinServidor();
      $objServicoDTO->retStrSinChaveAcesso();
      $objServicoDTO->setNumIdUsuario($objUsuarioDTO->getNumIdUsuario());

      if(strpos($params[1], ' ') === false && strlen($params[1]) == 72 && preg_match("/[0-9a-z]/", $params[1])){

        $objServicoDTO->setStrCrc(substr($params[1],0,8));
        $objServicoDTO = $objServicoRN->consultar($objServicoDTO);

        if ($objServicoDTO==null){
          throw new InfraException('Serviço do sistema ['.$params[0].'] não encontrado.' );
        }

        if ($objServicoDTO->getStrSinChaveAcesso() == 'N'){
          throw new InfraException('Serviço ['.$objServicoDTO->getStrIdentificacao().'] do sistema ['.$objServicoDTO->getStrSiglaUsuario().'] não possui autenticação por Chave de Acesso.');
        }

        $objInfraBcrypt = new InfraBcrypt();
        if (!$objInfraBcrypt->verificar(md5(substr($params[1],8)), $objServicoDTO->getStrChaveAcesso())) {
          throw new InfraException('Chave de Acesso inválida para o serviço ['.$objServicoDTO->getStrIdentificacao().'] do sistema ['.$objServicoDTO->getStrSiglaUsuario().'].');
        }

      }else{

        $objServicoDTO->setStrIdentificacao($params[1]);

        $objServicoDTO = $objServicoRN->consultar($objServicoDTO);

        if ($objServicoDTO==null){
          throw new InfraException('Serviço ['.$params[1].'] do sistema ['.$params[0].'] não encontrado.');
        }

        if ($objServicoDTO->getStrSinServidor() == 'N'){
          throw new InfraException('Serviço ['.$params[1].'] do sistema ['.$params[0].'] não possui autenticação por Endereço.');
        }

        $this->validarAcessoAutorizado(explode(',',str_replace(' ','',$objServicoDTO->getStrServidor())));

      }

			$numIdUnidade = null;
			if (!in_array($func, array('listarUnidades', 'confirmarDisponibilizacaoPublicacao', 'registrarOuvidoria', 'listarTiposProcedimentoOuvidoria'))){
				$numIdUnidade = $params[2];
			}

			if ($numIdUnidade!=null){

				$objUnidadeDTO = new UnidadeDTO();
				$objUnidadeDTO->setBolExclusaoLogica(false);
				$objUnidadeDTO->retStrSigla();
				$objUnidadeDTO->retStrSinAtivo();
				$objUnidadeDTO->setNumIdUnidade($numIdUnidade);

				$objUnidadeRN = new UnidadeRN();
				$objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

				if ($objUnidadeDTO == null){
					throw new InfraException('Unidade ['.$numIdUnidade.'] não encontrada.');
				}

				if ($objUnidadeDTO->getStrSinAtivo()=='N'){
					throw new InfraException('Unidade '.$objUnidadeDTO->getStrSigla().' está desativada.');
				}
			}

			$objServicoDTO->setNumIdUnidade($numIdUnidade);
			

			if ($numIdUnidade==null){
				SessaoSEI::getInstance()->simularLogin(null, SessaoSEI::$UNIDADE_TESTE, $objServicoDTO->getNumIdUsuario(), null);
			}else{
				SessaoSEI::getInstance()->simularLogin(null, null, $objServicoDTO->getNumIdUsuario(), $numIdUnidade);
			}

			SessaoSEI::getInstance()->setObjServicoDTO($objServicoDTO);

			$numSeg = InfraUtil::verificarTempoProcessamento();

			$debugWebServices = (int)ConfiguracaoSEI::getInstance()->getValor('SEI','DebugWebServices',false,0);

			if ($debugWebServices) {
				InfraDebug::getInstance()->setBolLigado(true);
				InfraDebug::getInstance()->setBolDebugInfra(($debugWebServices==2));
				InfraDebug::getInstance()->limpar();

				InfraDebug::getInstance()->gravar("Serviço: ".$func."\nParâmetros: ".$this->debugParametros($params));

				if ($debugWebServices==1) {
					LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(),InfraLog::$DEBUG);
				}
			}

			$ret = call_user_func_array(array($this, $func.'Monitorado'), $params);

			if ($debugWebServices==2) {
				LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(),InfraLog::$DEBUG);
			}

			try {

				$numSeg = InfraUtil::verificarTempoProcessamento($numSeg);

				$objMonitoramentoServicoDTO = new MonitoramentoServicoDTO();
				$objMonitoramentoServicoDTO->setNumIdServico($objServicoDTO->getNumIdServico());
				$objMonitoramentoServicoDTO->setStrOperacao($func);
				$objMonitoramentoServicoDTO->setDblTempoExecucao($numSeg*1000);
				$objMonitoramentoServicoDTO->setStrIpAcesso(InfraUtil::getStrIpUsuario());
				$objMonitoramentoServicoDTO->setDthAcesso(InfraData::getStrDataHoraAtual());
				$objMonitoramentoServicoDTO->setStrServidor(substr($_SERVER['SERVER_NAME'].' ('.$_SERVER['SERVER_ADDR'].')',0,250));
				$objMonitoramentoServicoDTO->setStrUserAgent(substr($_SERVER['HTTP_USER_AGENT'], 0, 250));

				$objMonitoramentoServicoRN = new MonitoramentoServicoRN();
				$objMonitoramentoServicoRN->cadastrar($objMonitoramentoServicoDTO);

			}catch(Throwable $e){
				try{
					LogSEI::getInstance()->gravar('Erro monitorando acesso do serviço.'."\n".InfraException::inspecionar($e));
				}catch (Throwable $e){}
			}

			BancoSEI::getInstance()->fecharConexao();

			return $ret;

		}catch(Throwable $e){

			try{
				BancoSEI::getInstance()->fecharConexao();
			}catch(Throwable $e2){}

			$this->processarExcecao($e);
		}
	}

	protected function gerarProcedimentoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $Procedimento, $Documentos, $ProcedimentosRelacionados, $UnidadesEnvio, $SinManterAbertoUnidade, $SinEnviarEmailNotificacao, $DataRetornoProgramado, $DiasRetornoProgramado, $SinDiasUteisRetornoProgramado, $IdMarcador, $TextoMarcador, $DataControlePrazo, $DiasControlePrazo, $SinDiasUteisControlePrazo ){
		try{

			if ($Documentos!=null){
				$objInfraParametro = new InfraParametro(BancoSEI::getInstance());
				$numMaxDocs = $objInfraParametro->getValor('SEI_WS_NUM_MAX_DOCS');
				if (count($Documentos) > $numMaxDocs){
				  throw new InfraException('O número máximo de documentos para inclusão ao gerar um processo por web services é '.$numMaxDocs.'.');
				}
			}

			$objEntradaGerarProcedimentoAPI = new EntradaGerarProcedimentoAPI();

			$objEntradaGerarProcedimentoAPI->setProcedimento($this->montarProcedimentoAPI($Procedimento));

			$arrObjDocumentoAPI = array();
			if ($Documentos!=null){
				foreach($Documentos as $Documento){
					
					if ($Documento->IdProcedimento!=null){
						throw new InfraException('Documento não pode referenciar outro procedimento.');
					}

					$arrObjDocumentoAPI[] = $this->montarDocumentoAPI($Documento);
				}
			}
			$objEntradaGerarProcedimentoAPI->setDocumentos($arrObjDocumentoAPI);

			$objEntradaGerarProcedimentoAPI->setProcedimentosRelacionados($ProcedimentosRelacionados);
			$objEntradaGerarProcedimentoAPI->setUnidadesEnvio($UnidadesEnvio);
      $objEntradaGerarProcedimentoAPI->setSinManterAbertoUnidade($SinManterAbertoUnidade);
      $objEntradaGerarProcedimentoAPI->setSinEnviarEmailNotificacao($SinEnviarEmailNotificacao);
		  $objEntradaGerarProcedimentoAPI->setDataRetornoProgramado($DataRetornoProgramado);
		  $objEntradaGerarProcedimentoAPI->setDiasRetornoProgramado($DiasRetornoProgramado);
			$objEntradaGerarProcedimentoAPI->setSinDiasUteisRetornoProgramado($SinDiasUteisRetornoProgramado);
			$objEntradaGerarProcedimentoAPI->setIdMarcador($IdMarcador);
			$objEntradaGerarProcedimentoAPI->setTextoMarcador($TextoMarcador);
			$objEntradaGerarProcedimentoAPI->setDataControlePrazo($DataControlePrazo);
			$objEntradaGerarProcedimentoAPI->setDiasControlePrazo($DiasControlePrazo);
			$objEntradaGerarProcedimentoAPI->setSinDiasUteisControlePrazo($SinDiasUteisControlePrazo);

      $objSeiRN = new SeiRN();
      return $objSeiRN->gerarProcedimento($objEntradaGerarProcedimentoAPI);

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de geração de procedimento.', $e);
		}
	}

	protected function incluirDocumentoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $Documento){
		try{

			$objDocumentoAPI = $this->montarDocumentoAPI($Documento);

      $objSeiRN = new SeiRN();
      return $objSeiRN->incluirDocumento($objDocumentoAPI);

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de inclusão de documento.',$e);
		}
	}

	protected function consultarProcedimentoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $ProtocoloProcedimento, $SinRetornarAssuntos,$SinRetornarInteressados,$SinRetornarObservacoes,$SinRetornarAndamentoGeracao,$SinRetornarAndamentoConclusao,$SinRetornarUltimoAndamento,$SinRetornarUnidadesProcedimentoAberto,$SinRetornarProcedimentosRelacionados, $SinRetornarProcedimentosAnexados){
		try{

			$objEntradaConsultarProcedimentoAPI = new EntradaConsultarProcedimentoAPI();
			//$objEntradaConsultarProcedimentoAPI->setIdProcedimento();
      $objEntradaConsultarProcedimentoAPI->setProtocoloProcedimento($ProtocoloProcedimento);
      $objEntradaConsultarProcedimentoAPI->setSinRetornarAssuntos($SinRetornarAssuntos);
      $objEntradaConsultarProcedimentoAPI->setSinRetornarInteressados($SinRetornarInteressados);
      $objEntradaConsultarProcedimentoAPI->setSinRetornarObservacoes($SinRetornarObservacoes);
      $objEntradaConsultarProcedimentoAPI->setSinRetornarAndamentoGeracao($SinRetornarAndamentoGeracao);
      $objEntradaConsultarProcedimentoAPI->setSinRetornarAndamentoConclusao($SinRetornarAndamentoConclusao);
      $objEntradaConsultarProcedimentoAPI->setSinRetornarUltimoAndamento($SinRetornarUltimoAndamento);
      $objEntradaConsultarProcedimentoAPI->setSinRetornarUnidadesProcedimentoAberto($SinRetornarUnidadesProcedimentoAberto);
      $objEntradaConsultarProcedimentoAPI->setSinRetornarProcedimentosRelacionados($SinRetornarProcedimentosRelacionados);
      $objEntradaConsultarProcedimentoAPI->setSinRetornarProcedimentosAnexados($SinRetornarProcedimentosAnexados);

      $objSeiRN = new SeiRN();
      return $objSeiRN->consultarProcedimento($objEntradaConsultarProcedimentoAPI);

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de consulta de procedimento.',$e);
		}
	}

  protected function consultarProcedimentoIndividualMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $IdOrgaoProcedimento, $IdTipoProcedimento, $IdOrgaoUsuario, $SiglaUsuario){
    try{

      $objEntradaConsultarProcedimentoIndividualAPI = new EntradaConsultarProcedimentoIndividualAPI();
      $objEntradaConsultarProcedimentoIndividualAPI->setIdOrgaoProcedimento($IdOrgaoProcedimento);
      $objEntradaConsultarProcedimentoIndividualAPI->setIdTipoProcedimento($IdTipoProcedimento);
      $objEntradaConsultarProcedimentoIndividualAPI->setIdOrgaoUsuario($IdOrgaoUsuario);
      $objEntradaConsultarProcedimentoIndividualAPI->setSiglaUsuario($SiglaUsuario);

      $objSeiRN = new SeiRN();
      return $objSeiRN->consultarProcedimentoIndividual($objEntradaConsultarProcedimentoIndividualAPI);

    }catch(Throwable $e){
      throw new InfraException('Erro no serviço de consulta de procedimento individual.',$e);
    }
  }

	protected function consultarDocumentoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $ProtocoloDocumento, $SinRetornarAndamentoGeracao,$SinRetornarAssinaturas,$SinRetornarPublicacao,$SinRetornarCampos){
		try{

			$objEntradaConsultarDocumentoAPI = new EntradaConsultarDocumentoAPI();
			//$objEntradaConsultarDocumentoAPI->setIdDocumento();
			$objEntradaConsultarDocumentoAPI->setProtocoloDocumento($ProtocoloDocumento);
      $objEntradaConsultarDocumentoAPI->setSinRetornarAndamentoGeracao($SinRetornarAndamentoGeracao);
      $objEntradaConsultarDocumentoAPI->setSinRetornarAssinaturas($SinRetornarAssinaturas);
      $objEntradaConsultarDocumentoAPI->setSinRetornarPublicacao($SinRetornarPublicacao);
      $objEntradaConsultarDocumentoAPI->setSinRetornarCampos($SinRetornarCampos);

      $objSeiRN = new SeiRN();
      return $objSeiRN->consultarDocumento($objEntradaConsultarDocumentoAPI);

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de consulta de documento.',$e);
		}
	}

	protected function enviarProcessoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $ProtocoloProcedimento, $UnidadesDestino, $SinManterAbertoUnidade, $SinRemoverAnotacao, $SinEnviarEmailNotificacao, $DataRetornoProgramado, $DiasRetornoProgramado, $SinDiasUteisRetornoProgramado, $SinReabrir){
		try{

			$objEntradaEnviarProcessoAPI = new EntradaEnviarProcessoAPI();
			//$objEntradaEnviarProcessoAPI->setIdProcedimento();
			$objEntradaEnviarProcessoAPI->setProtocoloProcedimento($ProtocoloProcedimento);
			$objEntradaEnviarProcessoAPI->setUnidadesDestino($UnidadesDestino);
			$objEntradaEnviarProcessoAPI->setSinManterAbertoUnidade($SinManterAbertoUnidade);
			$objEntradaEnviarProcessoAPI->setSinRemoverAnotacao($SinRemoverAnotacao);
			$objEntradaEnviarProcessoAPI->setSinEnviarEmailNotificacao($SinEnviarEmailNotificacao);
		  $objEntradaEnviarProcessoAPI->setDataRetornoProgramado($DataRetornoProgramado);
		  $objEntradaEnviarProcessoAPI->setDiasRetornoProgramado($DiasRetornoProgramado);
			$objEntradaEnviarProcessoAPI->setSinDiasUteisRetornoProgramado($SinDiasUteisRetornoProgramado);
		  $objEntradaEnviarProcessoAPI->setSinReabrir($SinReabrir);

			$objSeiRN = new SeiRN();
			$objSeiRN->enviarProcesso($objEntradaEnviarProcessoAPI);

			return true;

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de envio de processo.',$e);
		}
	}
	
	protected function lancarAndamentoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $ProtocoloProcedimento, $IdTarefa, $IdTarefaModulo, $Atributos){
		try{

			$objEntradaLancarAndamentoAPI = new EntradaLancarAndamentoAPI();
			//$objEntradaLancarAndamentoAPI->setIdProcedimento();
			$objEntradaLancarAndamentoAPI->setProtocoloProcedimento($ProtocoloProcedimento);
			$objEntradaLancarAndamentoAPI->setIdTarefa($IdTarefa);
			$objEntradaLancarAndamentoAPI->setIdTarefaModulo($IdTarefaModulo);

			$arrObjAtributoAndamentoAPI = array();
			if ($Atributos!=null){
				foreach($Atributos as $Atributo){

					$objAtributoAndamentoAPI = new AtributoAndamentoAPI();
					$objAtributoAndamentoAPI->setNome($Atributo->Nome);
					$objAtributoAndamentoAPI->setValor($Atributo->Valor);
					$objAtributoAndamentoAPI->setIdOrigem($Atributo->IdOrigem);

					$arrObjAtributoAndamentoAPI[] = $objAtributoAndamentoAPI;
				}
			}
			$objEntradaLancarAndamentoAPI->setAtributos($arrObjAtributoAndamentoAPI);

			$objSeiRN = new SeiRN();
			return $objSeiRN->lancarAndamento($objEntradaLancarAndamentoAPI);

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de lançamento de andamento.',$e);
		}
	}

	protected function listarAndamentosMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $ProtocoloProcedimento, $SinRetornarAtributos, $Andamentos, $Tarefas, $TarefasModulos){
		try{

			$objEntradaListarAndamentosAPI = new EntradaListarAndamentosAPI();
			//$objEntradaListarAndamentosAPI->setIdProcedimento();
			$objEntradaListarAndamentosAPI->setProtocoloProcedimento($ProtocoloProcedimento);
			$objEntradaListarAndamentosAPI->setSinRetornarAtributos($SinRetornarAtributos);
			$objEntradaListarAndamentosAPI->setAndamentos($Andamentos);
			$objEntradaListarAndamentosAPI->setTarefas($Tarefas);
			$objEntradaListarAndamentosAPI->setTarefasModulos($TarefasModulos);

			$objSeiRN = new SeiRN();
			return $objSeiRN->listarAndamentos($objEntradaListarAndamentosAPI);

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de listagem de andamentos.',$e);
		}
	}
	
	protected function cancelarDocumentoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $ProtocoloDocumento, $Motivo){
    try{

      $objEntradaCancelarDocumentoAPI = new EntradaCancelarDocumentoAPI();
			//$objEntradaCancelarDocumentoAPI->setIdDocumento();
      $objEntradaCancelarDocumentoAPI->setProtocoloDocumento($ProtocoloDocumento);
      $objEntradaCancelarDocumentoAPI->setMotivo($Motivo);

      $objSeiRN = new SeiRN();
      $objSeiRN->cancelarDocumento($objEntradaCancelarDocumentoAPI);

      return true;

    }catch(Throwable $e){
			throw new InfraException('Erro no serviço de cancelamento de documento.',$e);
    }
  }

  protected function bloquearDocumentoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $ProtocoloDocumento){
    try{

      $objEntradaBloquearDocumentoAPI = new EntradaBloquearDocumentoAPI();
      //$objEntradaBloquearDocumentoAPI->setIdDocumento();
      $objEntradaBloquearDocumentoAPI->setProtocoloDocumento($ProtocoloDocumento);

      $objSeiRN = new SeiRN();
      $objSeiRN->bloquearDocumento($objEntradaBloquearDocumentoAPI);

      return true;

    }catch(Throwable $e){
      throw new InfraException('Erro no serviço de bloqueio de documento.',$e);
    }
  }

	protected function gerarBlocoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $Tipo, $Descricao, $UnidadesDisponibilizacao, $Documentos, $SinDisponibilizar){
		try{

			$objEntradaGerarBlocoAPI = new EntradaGerarBlocoAPI();
			$objEntradaGerarBlocoAPI->setTipo($Tipo);
			$objEntradaGerarBlocoAPI->setDescricao($Descricao);
			$objEntradaGerarBlocoAPI->setUnidadesDisponibilizacao($UnidadesDisponibilizacao);
			$objEntradaGerarBlocoAPI->setDocumentos($Documentos);
      //$objEntradaGerarBlocoAPI->setIdDocumentos(array());
			$objEntradaGerarBlocoAPI->setSinDisponibilizar($SinDisponibilizar);
			
      $objSeiRN = new SeiRN();
      return $objSeiRN->gerarBloco($objEntradaGerarBlocoAPI);

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de geração de bloco.',$e);
		}
	}

	protected function consultarBlocoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $IdBloco, $SinRetornarProtocolos){
    try{

      $objEntradaConsultarBlocoAPI = new EntradaConsultarBlocoAPI();
      $objEntradaConsultarBlocoAPI->setIdBloco($IdBloco);
      $objEntradaConsultarBlocoAPI->setSinRetornarProtocolos($SinRetornarProtocolos);

      $objSeiRN = new SeiRN();
      return $objSeiRN->consultarBloco($objEntradaConsultarBlocoAPI);

    }catch(Throwable $e){
			throw new InfraException('Erro no serviço de consulta de bloco.',$e);
    }
  }

	protected function excluirBlocoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $IdBloco){
	  try{

	    $objEntradaExcluirBlocoAPI = new EntradaExcluirBlocoAPI();
	    $objEntradaExcluirBlocoAPI->setIdBloco($IdBloco);
	    	
	    $objSeiRN = new SeiRN();
	    $objSeiRN->excluirBloco($objEntradaExcluirBlocoAPI);

	    return true;
	
	  }catch(Throwable $e){
			throw new InfraException('Erro no serviço de exclusão de bloco.',$e);
	  }
	}

  protected function excluirProcessoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $ProtocoloProcedimento){
    try{

      $objEntradaExcluirProcessoAPI = new EntradaExcluirProcessoAPI();
      //$objEntradaExcluirProcessoAPI->setIdProcedimento();
      $objEntradaExcluirProcessoAPI->setProtocoloProcedimento($ProtocoloProcedimento);

      $objSeiRN = new SeiRN();
      $objSeiRN->excluirProcesso($objEntradaExcluirProcessoAPI);

      return true;

    }catch(Throwable $e){
      throw new InfraException('Erro no serviço de exclusão de processo.',$e);
    }
  }

  protected function excluirDocumentoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $ProtocoloDocumento){
    try{

      $objEntradaExcluirDocumentoAPI = new EntradaExcluirDocumentoAPI();
      //$objEntradaExcluirDocumentoAPI->setIdDocumento();
      $objEntradaExcluirDocumentoAPI->setProtocoloDocumento($ProtocoloDocumento);

      $objSeiRN = new SeiRN();
      $objSeiRN->excluirDocumento($objEntradaExcluirDocumentoAPI);

      return true;

    }catch(Throwable $e){
      throw new InfraException('Erro no serviço de exclusão de documento.',$e);
    }
  }

  protected function disponibilizarBlocoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $IdBloco){
		try{
			
			$objEntradaDisponibilizarBlocoAPI = new EntradaDisponibilizarBlocoAPI();
			$objEntradaDisponibilizarBlocoAPI->setIdBloco($IdBloco);
			
      $objSeiRN = new SeiRN();
      $objSeiRN->disponibilizarBloco($objEntradaDisponibilizarBlocoAPI);

			return true;

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de disponibilização de bloco.',$e);
		}
	}

	protected function cancelarDisponibilizacaoBlocoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $IdBloco){
	  try{
	    	
	    $objEntradaCancelarDisponibilizacaoBlocoAPI = new EntradaCancelarDisponibilizacaoBlocoAPI();
	    $objEntradaCancelarDisponibilizacaoBlocoAPI->setIdBloco($IdBloco);
	    	
	    $objSeiRN = new SeiRN();
	    $objSeiRN->cancelarDisponibilizacaoBloco($objEntradaCancelarDisponibilizacaoBlocoAPI);

	    return true;
	
	  }catch(Throwable $e){
			throw new InfraException('Erro no serviço de cancelamento de disponibilização de bloco.',$e);
	  }
	}

	protected function incluirDocumentoBlocoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $IdBloco, $ProtocoloDocumento, $Anotacao){
		try{

			$objEntradaIncluirDocumentoBlocoAPI = new EntradaIncluirDocumentoBlocoAPI();
			$objEntradaIncluirDocumentoBlocoAPI->setIdBloco($IdBloco);
			//$objEntradaIncluirDocumentoBlocoAPI->setIdDocumento();
			$objEntradaIncluirDocumentoBlocoAPI->setProtocoloDocumento($ProtocoloDocumento);
			$objEntradaIncluirDocumentoBlocoAPI->setAnotacao($Anotacao);
			
      $objSeiRN = new SeiRN();
      $objSeiRN->incluirDocumentoBloco($objEntradaIncluirDocumentoBlocoAPI);
        
			return true;

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de inclusão de documento em bloco.',$e);
		}
	}

	protected function retirarDocumentoBlocoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $IdBloco, $ProtocoloDocumento){
	  try{

	    $objEntradaRetirarDocumentoBlocoAPI = new EntradaRetirarDocumentoBlocoAPI();
	    $objEntradaRetirarDocumentoBlocoAPI->setIdBloco($IdBloco);
			//$objEntradaRetirarDocumentoBlocoAPI->setIdDocumento();
	    $objEntradaRetirarDocumentoBlocoAPI->setProtocoloDocumento($ProtocoloDocumento);
	    	
	    $objSeiRN = new SeiRN();
	    $objSeiRN->retirarDocumentoBloco($objEntradaRetirarDocumentoBlocoAPI);

	    return true;
	
	  }catch(Throwable $e){
			throw new InfraException('Erro no serviço de retirada de documento de bloco.',$e);
	  }
	}

	protected function incluirProcessoBlocoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $IdBloco, $ProtocoloProcedimento, $Anotacao){
	  try{

	    $objEntradaIncluirProcessoBlocoAPI = new EntradaIncluirProcessoBlocoAPI();
	    $objEntradaIncluirProcessoBlocoAPI->setIdBloco($IdBloco);
			//$objEntradaIncluirProcessoBlocoAPI->setIdProcedimento();
	    $objEntradaIncluirProcessoBlocoAPI->setProtocoloProcedimento($ProtocoloProcedimento);
			$objEntradaIncluirProcessoBlocoAPI->setAnotacao($Anotacao);
	    	
	    $objSeiRN = new SeiRN();
	    $objSeiRN->incluirProcessoBloco($objEntradaIncluirProcessoBlocoAPI);
	
	    return true;
	
	  }catch(Throwable $e){
			throw new InfraException('Erro no serviço de inclusão de processo em bloco.',$e);
	  }
	}

	protected function retirarProcessoBlocoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $IdBloco, $ProtocoloProcedimento){
	  try{

	    $objEntradaRetirarProcessoBlocoAPI = new EntradaRetirarProcessoBlocoAPI();
	    $objEntradaRetirarProcessoBlocoAPI->setIdBloco($IdBloco);
			//$objEntradaRetirarProcessoBlocoAPI->setIdProcedimento();
	    $objEntradaRetirarProcessoBlocoAPI->setProtocoloProcedimento($ProtocoloProcedimento);
	
	    $objSeiRN = new SeiRN();
	    $objSeiRN->retirarProcessoBloco($objEntradaRetirarProcessoBlocoAPI);
	
	    return true;
	
	  }catch(Throwable $e){
			throw new InfraException('Erro no serviço de retirada de processo de bloco.',$e);
	  }
	}

	protected function reabrirProcessoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $ProtocoloProcedimento){
	  try{

	    $objEntradaReabrirProcessoAPI = new EntradaReabrirProcessoAPI();
			//$objEntradaReabrirProcessoAPI->setIdProcedimento();
	    $objEntradaReabrirProcessoAPI->setProtocoloProcedimento($ProtocoloProcedimento);

	    $objSeiRN = new SeiRN();
	    $objSeiRN->reabrirProcesso($objEntradaReabrirProcessoAPI);

	    return true;
	
	  }catch(Throwable $e){
			throw new InfraException('Erro no serviço de reabertura de processo.',$e);
	  }
	}

	protected function concluirProcessoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $ProtocoloProcedimento){
	  try{

	    $objEntradaConcluirProcessoAPI = new EntradaConcluirProcessoAPI();
			//$objEntradaConcluirProcessoAPI->setIdProcedimento();
			$objEntradaConcluirProcessoAPI->setProtocoloProcedimento($ProtocoloProcedimento);

	    $objSeiRN = new SeiRN();
	    $objSeiRN->concluirProcesso($objEntradaConcluirProcessoAPI);

	    return true;
	
	  }catch(Throwable $e){
			throw new InfraException('Erro no serviço de conclusão de processo.',$e);
	  }
	}

	protected function atribuirProcessoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $ProtocoloProcedimento, $IdUsuario, $SinReabrir){

		try{

			$objEntradaAtribuirProcessoAPI = new EntradaAtribuirProcessoAPI();
			//$objEntradaAtribuirProcessoAPI->setIdProcedimento();
			$objEntradaAtribuirProcessoAPI->setProtocoloProcedimento($ProtocoloProcedimento);
			$objEntradaAtribuirProcessoAPI->setIdUsuario($IdUsuario);
			$objEntradaAtribuirProcessoAPI->setSinReabrir($SinReabrir);

			$objSeiRN = new SeiRN();
			$objSeiRN->atribuirProcesso($objEntradaAtribuirProcessoAPI);

			return true;

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de atribuição de processo.',$e);
		}
	}

	protected function bloquearProcessoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $ProtocoloProcedimento){
		try{

			$objEntradaBloquearProcessoAPI = new EntradaBloquearProcessoAPI();
			//$objEntradaBloquearProcessoAPI->setIdProcedimento();
			$objEntradaBloquearProcessoAPI->setProtocoloProcedimento($ProtocoloProcedimento);

			$objSeiRN = new SeiRN();
			$objSeiRN->bloquearProcesso($objEntradaBloquearProcessoAPI);

			return true;

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de bloqueio de processo.',$e);
		}
	}

	protected function desbloquearProcessoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $ProtocoloProcedimento){
		try{

			$objEntradaDesbloquearProcessoAPI = new EntradaDesbloquearProcessoAPI();
			//$objEntradaDesbloquearProcessoAPI->setIdProcedimento();
			$objEntradaDesbloquearProcessoAPI->setProtocoloProcedimento($ProtocoloProcedimento);

			$objSeiRN = new SeiRN();
			$objSeiRN->desbloquearProcesso($objEntradaDesbloquearProcessoAPI);

			return true;

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de desbloqueio de processo.',$e);
		}
	}

	protected function relacionarProcessoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $ProtocoloProcedimento1, $ProtocoloProcedimento2){
		try{

			$objEntradaRelacionarProcessoAPI = new EntradaRelacionarProcessoAPI();
			//$objEntradaRelacionarProcessoAPI->setIdProcedimento1();
			$objEntradaRelacionarProcessoAPI->setProtocoloProcedimento1($ProtocoloProcedimento1);
			//$objEntradaRelacionarProcessoAPI->setIdProcedimento2();
			$objEntradaRelacionarProcessoAPI->setProtocoloProcedimento2($ProtocoloProcedimento2);

			$objSeiRN = new SeiRN();
			$objSeiRN->relacionarProcesso($objEntradaRelacionarProcessoAPI);

			return true;

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de relacionamento de processo.',$e);
		}
	}

	protected function removerRelacionamentoProcessoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $ProtocoloProcedimento1, $ProtocoloProcedimento2){
		try{

			$objEntradaRemoverRelacionamentoProcessoAPI = new EntradaRemoverRelacionamentoProcessoAPI();
			//$objEntradaRemoverRelacionamentoProcessoAPI->setIdProcedimento1();
			$objEntradaRemoverRelacionamentoProcessoAPI->setProtocoloProcedimento1($ProtocoloProcedimento1);
			//$objEntradaRemoverRelacionamentoProcessoAPI->setIdProcedimento2();
			$objEntradaRemoverRelacionamentoProcessoAPI->setProtocoloProcedimento2($ProtocoloProcedimento2);

			$objSeiRN = new SeiRN();
			$objSeiRN->removerRelacionamentoProcesso($objEntradaRemoverRelacionamentoProcessoAPI);

			return true;

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de remoção de relacionamento de processo.',$e);
		}
	}

	protected function sobrestarProcessoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $ProtocoloProcedimento, $ProtocoloProcedimentoVinculado, $Motivo){
		try{

			$objEntradaSobrestarProcessoAPI = new EntradaSobrestarProcessoAPI();
			//$objEntradaSobrestarProcessoAPI->setIdProcedimento();
			$objEntradaSobrestarProcessoAPI->setProtocoloProcedimento($ProtocoloProcedimento);
			//$objEntradaSobrestarProcessoAPI->setIdProcedimentoVinculado();
			$objEntradaSobrestarProcessoAPI->setProtocoloProcedimentoVinculado($ProtocoloProcedimentoVinculado);
			$objEntradaSobrestarProcessoAPI->setMotivo($Motivo);

			$objSeiRN = new SeiRN();
			$objSeiRN->sobrestarProcesso($objEntradaSobrestarProcessoAPI);

			return true;

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de sobrestamento de processo.',$e);
		}
	}

	protected function removerSobrestamentoProcessoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $ProtocoloProcedimento){
		try{

			$objEntradaRemoverSobrestamentoProcessoAPI = new EntradaRemoverSobrestamentoProcessoAPI();
			//$objEntradaRemoverSobrestamentoProcessoAPI->setIdProcedimento();
			$objEntradaRemoverSobrestamentoProcessoAPI->setProtocoloProcedimento($ProtocoloProcedimento);

			$objSeiRN = new SeiRN();
			$objSeiRN->removerSobrestamentoProcesso($objEntradaRemoverSobrestamentoProcessoAPI);

			return true;

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de remoção de sobrestamento de processo.',$e);
		}
	}

	protected function anexarProcessoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $ProtocoloProcedimentoPrincipal, $ProtocoloProcedimentoAnexado){
		try{

			$objEntradaAnexarProcessoAPI = new EntradaAnexarProcessoAPI();
			//$objEntradaAnexarProcessoAPI->setIdProcedimentoPrincipal();
			$objEntradaAnexarProcessoAPI->setProtocoloProcedimentoPrincipal($ProtocoloProcedimentoPrincipal);
			//$objEntradaAnexarProcessoAPI->setIdProcedimentoAnexado();
			$objEntradaAnexarProcessoAPI->setProtocoloProcedimentoAnexado($ProtocoloProcedimentoAnexado);

			$objSeiRN = new SeiRN();
			$objSeiRN->anexarProcesso($objEntradaAnexarProcessoAPI);

			return true;

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de anexação de processo.',$e);
		}
	}

	protected function desanexarProcessoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $ProtocoloProcedimentoPrincipal, $ProtocoloProcedimentoAnexado, $Motivo){
		try{
			
			$objEntradaDesanexarProcessoAPI = new EntradaDesanexarProcessoAPI();
			//$objEntradaDesanexarProcessoAPI->setIdProcedimentoPrincipal();
			$objEntradaDesanexarProcessoAPI->setProtocoloProcedimentoPrincipal($ProtocoloProcedimentoPrincipal);
			//$objEntradaDesanexarProcessoAPI->setIdProcedimentoAnexado();
			$objEntradaDesanexarProcessoAPI->setProtocoloProcedimentoAnexado($ProtocoloProcedimentoAnexado);
			$objEntradaDesanexarProcessoAPI->setMotivo($Motivo);

			$objSeiRN = new SeiRN();
			$objSeiRN->desanexarProcesso($objEntradaDesanexarProcessoAPI);

			return true;

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de desanexação de processo.',$e);
		}
	}

	protected function listarMarcadoresUnidadeMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade){

		try{

			$objSeiRN = new SeiRN();
			return $objSeiRN->listarMarcadoresUnidade();

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de listagem de marcadores da unidade.',$e);
		}
	}

	protected function definirMarcadorMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $Definicoes){

		try{

			$arrObjDefinicaoMarcadorAPI = array();

			if ($Definicoes!=null){
				foreach($Definicoes as $DefinicaoMarcador){
					$objDefinicaoMarcadorAPI = new DefinicaoMarcadorAPI();
					//$objDefinicaoMarcadorAPI->setIdProcedimento();
					$objDefinicaoMarcadorAPI->setProtocoloProcedimento($DefinicaoMarcador->ProtocoloProcedimento);
					$objDefinicaoMarcadorAPI->setIdMarcador($DefinicaoMarcador->IdMarcador);
					$objDefinicaoMarcadorAPI->setTexto($DefinicaoMarcador->Texto);
					$arrObjDefinicaoMarcadorAPI[] = $objDefinicaoMarcadorAPI;
				}
			}

			$objSeiRN = new SeiRN();
			$objSeiRN->definirMarcador($arrObjDefinicaoMarcadorAPI);

			return true;

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de definição de marcador.',$e);
		}
	}

	protected function definirControlePrazoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $Definicoes){
		try{


      $arrObjEntradaDefinirControlePrazoAPI = array();

      if ($Definicoes!=null){
        foreach($Definicoes as $DefinicaoControlePrazo){

          $objEntradaDefinirControlePrazoAPI = new EntradaDefinirControlePrazoAPI();
          $objEntradaDefinirControlePrazoAPI->setProtocoloProcedimento($DefinicaoControlePrazo->ProtocoloProcedimento);
          //$objEntradaDefinirControlePrazoAPI->setIdProcedimento();
          $objEntradaDefinirControlePrazoAPI->setDataPrazo($DefinicaoControlePrazo->DataPrazo);
          $objEntradaDefinirControlePrazoAPI->setDias($DefinicaoControlePrazo->Dias);
          $objEntradaDefinirControlePrazoAPI->setSinDiasUteis($DefinicaoControlePrazo->SinDiasUteis);

          $arrObjEntradaDefinirControlePrazoAPI[] = $objEntradaDefinirControlePrazoAPI;
        }
      }

			$objSeiRN = new SeiRN();
			$objSeiRN->definirControlePrazo($arrObjEntradaDefinirControlePrazoAPI);

			return true;

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de definição de controle de prazos.',$e);
		}
	}

  protected function concluirControlePrazoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $ProtocolosProcedimento){
    try{

      $arrObjEntradaConcluirControlePrazo = array();

      if ($ProtocolosProcedimento!=null){
        foreach($ProtocolosProcedimento as $Procedimento){

          $objEntradaConcluirControlePrazoAPI = new EntradaConcluirControlePrazoAPI();
          //$objEntradaConcluirControlePrazoAPI->setIdProcedimento();
          $objEntradaConcluirControlePrazoAPI->setProtocoloProcedimento($Procedimento);

          $arrObjEntradaConcluirControlePrazo[] = $objEntradaConcluirControlePrazoAPI;
        }
      }


      $objSeiRN = new SeiRN();
      $objSeiRN->concluirControlePrazo($arrObjEntradaConcluirControlePrazo);

      return true;

    }catch(Throwable $e){
      throw new InfraException('Erro no serviço de conclusão de controle de prazos.',$e);
    }
  }

	protected function removerControlePrazoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $ProtocolosProcedimento){
		try{

      $arrObjEntradaRemoverControlePrazoAPI = array();

      if ($ProtocolosProcedimento!=null){
        foreach($ProtocolosProcedimento as $Procedimento){

          $objEntradaRemoverControlePrazoAPI = new EntradaRemoverControlePrazoAPI();
          //$objEntradaRemoverControlePrazoAPI->setIdProcedimento();
          $objEntradaRemoverControlePrazoAPI->setProtocoloProcedimento($Procedimento);

          $arrObjEntradaRemoverControlePrazoAPI[] = $objEntradaRemoverControlePrazoAPI;
        }
      }


			$objSeiRN = new SeiRN();
			$objSeiRN->removerControlePrazo($arrObjEntradaRemoverControlePrazoAPI);

			return true;

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de definição de controle de prazos.',$e);
		}
	}

	protected function listarAndamentosMarcadoresMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $ProtocoloProcedimento, $Marcadores){
		try{

			$objEntradaListarAndamentosMarcadoresAPI = new EntradaListarAndamentosMarcadoresAPI();
			//$objEntradaListarAndamentosMarcadoresAPI->setIdProcedimento();
			$objEntradaListarAndamentosMarcadoresAPI->setProtocoloProcedimento($ProtocoloProcedimento);
			$objEntradaListarAndamentosMarcadoresAPI->setMarcadores($Marcadores);

			$objSeiRN = new SeiRN();
			return $objSeiRN->listarAndamentosMarcadores($objEntradaListarAndamentosMarcadoresAPI);

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de listagem de andamentos de marcadores.',$e);
		}
	}
	
	protected function listarExtensoesPermitidasMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $IdArquivoExtensao){
	
		try{
			
			$objEntradaListarExtensoesPermitidasAPI = new EntradaListarExtensoesPermitidasAPI();
  		$objEntradaListarExtensoesPermitidasAPI->setIdArquivoExtensao($IdArquivoExtensao);

			$objSeiRN = new SeiRN();
			return $objSeiRN->listarExtensoesPermitidas($objEntradaListarExtensoesPermitidasAPI);

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de listagem de extensões permitidas.',$e);
		}
	}

	protected function listarHipotesesLegaisMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $NivelAcesso){

		try{

			$objEntradaListarHipotesesLegaisAPI = new EntradaListarHipotesesLegaisAPI();
			$objEntradaListarHipotesesLegaisAPI->setNivelAcesso($NivelAcesso);

			$objSeiRN = new SeiRN();
			return $objSeiRN->listarHipotesesLegais($objEntradaListarHipotesesLegaisAPI);

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de listagem de hipóteses legais.',$e);
		}
	}

	protected function listarTiposConferenciaMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade){

		try{

			$objSeiRN = new SeiRN();
			return $objSeiRN->listarTiposConferencia();

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de listagem de tipos de conferência.',$e);
		}
	}
	
	protected function listarUsuariosMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $IdUsuario){

    try{

      $objEntradaListarUsuariosAPI = new EntradaListarUsuariosAPI();
      $objEntradaListarUsuariosAPI->setIdUsuario($IdUsuario);

      $objSeiRN = new SeiRN();
      return $objSeiRN->listarUsuarios($objEntradaListarUsuariosAPI);

    }catch(Throwable $e){
			throw new InfraException('Erro no serviço de listagem de usuários.',$e);
    }
  }

	protected function listarPaisesMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade){

		try{

			$objSeiRN = new SeiRN();
			return $objSeiRN->listarPaises();

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de listagem de países.',$e);
		}
	}

	protected function listarEstadosMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $IdPais){

		try{

			$objEntradaListarEstadosAPI = new EntradaListarEstadosAPI();
			$objEntradaListarEstadosAPI->setIdPais($IdPais);

			$objSeiRN = new SeiRN();
			return $objSeiRN->listarEstados($objEntradaListarEstadosAPI);

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de listagem de estados.',$e);
		}
	}

	protected function listarCidadesMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $IdPais, $IdEstado){

		try{

			$objEntradaListarCidadesAPI = new EntradaListarCidadesAPI();
			$objEntradaListarCidadesAPI->setIdPais($IdPais);
			$objEntradaListarCidadesAPI->setIdEstado($IdEstado);

			$objSeiRN = new SeiRN();
			return $objSeiRN->listarCidades($objEntradaListarCidadesAPI);

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de listagem de cidades.',$e);
		}
	}

  protected function listarTiposProcedimentoOuvidoriaMonitorado($SiglaSistema, $IdentificacaoServico){
    try{

      $objSeiRN = new SeiRN();
      return $objSeiRN->listarTiposProcedimentoOuvidoria();

    }catch(Throwable $e){
      throw new InfraException('Erro no serviço de listagem de tipos de processo da ouvidoria.',$e);
    }
  }

  protected function listarCargosMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $IdCargo){

    try{

      $objEntradaListarCargosAPI = new EntradaListarCargosAPI();
      $objEntradaListarCargosAPI->setIdCargo($IdCargo);

      $objSeiRN = new SeiRN();
      return $objSeiRN->listarCargos($objEntradaListarCargosAPI);

    }catch(Throwable $e){
      throw new InfraException('Erro no serviço de listagem de cargos.',$e);
    }
  }

	protected function listarContatosMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $IdTipoContato, $PaginaRegistros, $PaginaAtual, $Sigla, $Nome, $Cpf, $Cnpj, $Matricula, $IdContatos){

		try{

			$objEntradaListarContatosAPI = new EntradaListarContatosAPI();
			$objEntradaListarContatosAPI->setIdTipoContato($IdTipoContato);
			$objEntradaListarContatosAPI->setPaginaRegistros($PaginaRegistros);
			$objEntradaListarContatosAPI->setPaginaAtual($PaginaAtual);
			$objEntradaListarContatosAPI->setSigla($Sigla);
			$objEntradaListarContatosAPI->setNome($Nome);
			$objEntradaListarContatosAPI->setCpf($Cpf);
			$objEntradaListarContatosAPI->setCnpj($Cnpj);
			$objEntradaListarContatosAPI->setMatricula($Matricula);
      $objEntradaListarContatosAPI->setIdContatos($IdContatos);

			$objSeiRN = new SeiRN();
			return $objSeiRN->listarContatos($objEntradaListarContatosAPI);

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de listagem de contatos.',$e);
		}
	}

	protected function atualizarContatosMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $Contatos){

		try{

			$arrObjContatoAPI = array();
			foreach($Contatos as $Contato) {

				$objContatoAPI = new ContatoAPI();
			  $objContatoAPI->setStaOperacao($Contato->StaOperacao);
				$objContatoAPI->setIdContato($Contato->IdContato);
				$objContatoAPI->setIdTipoContato($Contato->IdTipoContato);
				$objContatoAPI->setSigla($Contato->Sigla);
				$objContatoAPI->setNome($Contato->Nome);
        $objContatoAPI->setNomeSocial($Contato->NomeSocial);
				$objContatoAPI->setStaNatureza($Contato->StaNatureza);
				$objContatoAPI->setIdContatoAssociado($Contato->IdContatoAssociado);
				$objContatoAPI->setSinEnderecoAssociado($Contato->SinEnderecoAssociado);
				$objContatoAPI->setEndereco($Contato->Endereco);
				$objContatoAPI->setComplemento($Contato->Complemento);
				$objContatoAPI->setBairro($Contato->Bairro);
				$objContatoAPI->setIdCidade($Contato->IdCidade);
				$objContatoAPI->setIdEstado($Contato->IdEstado);
				$objContatoAPI->setIdPais($Contato->IdPais);
				$objContatoAPI->setCep($Contato->Cep);
				$objContatoAPI->setStaGenero($Contato->StaGenero);
				$objContatoAPI->setIdCargo($Contato->IdCargo);
				$objContatoAPI->setCpf($Contato->Cpf);
				$objContatoAPI->setCnpj($Contato->Cnpj);
				$objContatoAPI->setRg($Contato->Rg);
				$objContatoAPI->setOrgaoExpedidor($Contato->OrgaoExpedidor);
				$objContatoAPI->setMatricula($Contato->Matricula);
				$objContatoAPI->setMatriculaOab($Contato->MatriculaOab);
				$objContatoAPI->setTelefoneComercial($Contato->TelefoneComercial);
				$objContatoAPI->setTelefoneResidencial($Contato->TelefoneResidencial);
				$objContatoAPI->setTelefoneCelular($Contato->TelefoneCelular);
				$objContatoAPI->setDataNascimento($Contato->DataNascimento);
				$objContatoAPI->setEmail($Contato->Email);
				$objContatoAPI->setSitioInternet($Contato->SitioInternet);
				$objContatoAPI->setObservacao($Contato->Observacao);
				$objContatoAPI->setSinAtivo($Contato->SinAtivo);
        $objContatoAPI->setIdPaisPassaporte($Contato->IdPaisPassaporte);
        $objContatoAPI->setNumeroPassaporte($Contato->NumeroPassaporte);
        $objContatoAPI->setConjuge($Contato->Conjuge);
        $objContatoAPI->setFuncao($Contato->Funcao);
        $objContatoAPI->setIdTitulo($Contato->IdTitulo);
        $objContatoAPI->setExpressaoTitulo($Contato->ExpressaoTitulo);
        $objContatoAPI->setAbreviaturaTitulo($Contato->AbreviaturaTitulo);
        $objContatoAPI->setIdCategoria($Contato->IdCategoria);
        $objContatoAPI->setNomeCategoria($Contato->NomeCategoria);

				$arrObjContatoAPI[] = $objContatoAPI;
      }

			$objSeiRN = new SeiRN();
			return $objSeiRN->atualizarContatos($arrObjContatoAPI);

		}catch(Throwable $e){
			throw new InfraException('Erro no serviço de atualização de contatos.',$e);
		}
	}

  protected function adicionarArquivoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $Nome, $Tamanho, $Hash, $Conteudo){

    try{

      $objEntradaAdicionarArquivoAPI = new EntradaAdicionarArquivoAPI();
			$objEntradaAdicionarArquivoAPI->setNome($Nome);
			$objEntradaAdicionarArquivoAPI->setTamanho($Tamanho);
			$objEntradaAdicionarArquivoAPI->setHash($Hash);
			$objEntradaAdicionarArquivoAPI->setConteudo($Conteudo);

      $objSeiRN = new SeiRN();
      return $objSeiRN->adicionarArquivo($objEntradaAdicionarArquivoAPI);

    }catch(Throwable $e){
			throw new InfraException('Erro no serviço de inclusão de arquivo.',$e);
    }
  }

  protected function adicionarConteudoArquivoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $IdArquivo, $Conteudo){

    try{

			$objEntradaAdicionarConteudoArquivoAPI = new EntradaAdicionarConteudoArquivoAPI();
			$objEntradaAdicionarConteudoArquivoAPI->setIdArquivo($IdArquivo);
			$objEntradaAdicionarConteudoArquivoAPI->setConteudo($Conteudo);

			$objSeiRN = new SeiRN();
      return $objSeiRN->adicionarConteudoArquivo($objEntradaAdicionarConteudoArquivoAPI);

    }catch(Throwable $e){
			throw new InfraException('Erro no serviço de inclusão de conteúdo em arquivo.',$e);
    }
  }

  protected function registrarOuvidoriaMonitorado($SiglaSistema, $IdentificacaoServico, $IdOrgao, $Nome, $NomeSocial, $Email, $Cpf, $Rg, $OrgaoExpedidor, $Telefone, $Estado, $Cidade, $IdTipoProcedimento, $Processos, $SinRetorno, $Mensagem, $AtributosAdicionais){

    try{

      $objEntradaRegistrarOuvidoriaAPI = new EntradaRegistrarOuvidoriaAPI();
      $objEntradaRegistrarOuvidoriaAPI->setIdOrgao($IdOrgao);
      $objEntradaRegistrarOuvidoriaAPI->setNome($Nome);
      $objEntradaRegistrarOuvidoriaAPI->setNomeSocial($NomeSocial);
      $objEntradaRegistrarOuvidoriaAPI->setEmail($Email);
      $objEntradaRegistrarOuvidoriaAPI->setCpf($Cpf);
      $objEntradaRegistrarOuvidoriaAPI->setRg($Rg);
      $objEntradaRegistrarOuvidoriaAPI->setOrgaoExpedidor($OrgaoExpedidor);
      $objEntradaRegistrarOuvidoriaAPI->setTelefone($Telefone);
      $objEntradaRegistrarOuvidoriaAPI->setEstado($Estado);
      $objEntradaRegistrarOuvidoriaAPI->setCidade($Cidade);
      $objEntradaRegistrarOuvidoriaAPI->setIdTipoProcedimento($IdTipoProcedimento);
      $objEntradaRegistrarOuvidoriaAPI->setProcessos($Processos);
      $objEntradaRegistrarOuvidoriaAPI->setSinRetorno($SinRetorno);
      $objEntradaRegistrarOuvidoriaAPI->setMensagem($Mensagem);

      $arrObjAtributoOuvidoriaAPI = array();
      if (is_array($AtributosAdicionais)){
        foreach($AtributosAdicionais as $atributo){
          $objAtributoOuvidoriaAPI = new AtributoOuvidoriaAPI();
          $objAtributoOuvidoriaAPI->setId($atributo->Id);
          $objAtributoOuvidoriaAPI->setNome($atributo->Nome);
          $objAtributoOuvidoriaAPI->setTitulo($atributo->Titulo);
          $objAtributoOuvidoriaAPI->setValor($atributo->Valor);
          $arrObjAtributoOuvidoriaAPI[] = $objAtributoOuvidoriaAPI;
        }
      }
      $objEntradaRegistrarOuvidoriaAPI->setAtributosAdicionais($arrObjAtributoOuvidoriaAPI);

      $objSeiRN = new SeiRN();
      return $objSeiRN->registrarOuvidoria($objEntradaRegistrarOuvidoriaAPI);

    }catch(Throwable $e){
      throw new InfraException('Erro no serviço de registro da ouvidoria.',$e);
    }
  }

  protected function listarFeriadosMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $IdOrgao, $DataInicial, $DataFinal){
    try{

      $objEntradaListarFeriadosAPI = new EntradaListarFeriadosAPI();
      $objEntradaListarFeriadosAPI->setIdOrgao($IdOrgao);
      $objEntradaListarFeriadosAPI->setDataInicial($DataInicial);
      $objEntradaListarFeriadosAPI->setDataFinal($DataFinal);

      $objSeiRN = new SeiRN();
      return $objSeiRN->listarFeriados($objEntradaListarFeriadosAPI);

    }catch(Throwable $e){
      throw new InfraException('Erro no serviço de listagem de feriados.',$e);
    }
  }

  protected function consultarPublicacaoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $IdPublicacao, $IdDocumento, $ProtocoloDocumento, $SinRetornarAndamento, $SinRetornarAssinaturas){

    try{

      $objEntradaConsultarPublicacaoAPI = new EntradaConsultarPublicacaoAPI();
      $objEntradaConsultarPublicacaoAPI->setIdPublicacao($IdPublicacao);
      $objEntradaConsultarPublicacaoAPI->setIdDocumento($IdDocumento);
      $objEntradaConsultarPublicacaoAPI->setProtocoloDocumento($ProtocoloDocumento);
      $objEntradaConsultarPublicacaoAPI->setSinRetornarAndamento($SinRetornarAndamento);
      $objEntradaConsultarPublicacaoAPI->setSinRetornarAssinaturas($SinRetornarAssinaturas);

      $objSeiRN = new SeiRN();
      return $objSeiRN->consultarPublicacao($objEntradaConsultarPublicacaoAPI);

    }catch(Throwable $e){
      throw new InfraException('Erro no serviço de consulta de publicação.',$e);
    }
  }

  protected function agendarPublicacaoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $IdDocumento, $ProtocoloDocumento, $StaMotivo, $IdVeiculoPublicacao, $DataDisponibilizacao, $Resumo, $ImprensaNacional){

    try{

      $objEntradaAgendarPublicacaoAPI = new EntradaAgendarPublicacaoAPI();
      $objEntradaAgendarPublicacaoAPI->setIdDocumento($IdDocumento);
      $objEntradaAgendarPublicacaoAPI->setProtocoloDocumento($ProtocoloDocumento);
      $objEntradaAgendarPublicacaoAPI->setStaMotivo($StaMotivo);
      $objEntradaAgendarPublicacaoAPI->setIdVeiculoPublicacao($IdVeiculoPublicacao);
      $objEntradaAgendarPublicacaoAPI->setDataDisponibilizacao($DataDisponibilizacao);
      $objEntradaAgendarPublicacaoAPI->setResumo($Resumo);

      if ($ImprensaNacional!=null){

        $objPublicacaoImprensaNacionalAPI = new PublicacaoImprensaNacionalAPI();

        if (isset($ImprensaNacional->IdVeiculo)){
          $objPublicacaoImprensaNacionalAPI->setIdVeiculo($ImprensaNacional->IdVeiculo);
        }

        if (isset($ImprensaNacional->IdSecao)){
          $objPublicacaoImprensaNacionalAPI->setIdSecao($ImprensaNacional->IdSecao);
        }

        if (isset($ImprensaNacional->Pagina)){
          $objPublicacaoImprensaNacionalAPI->setPagina($ImprensaNacional->Pagina);
        }

        if (isset($ImprensaNacional->Data)){
          $objPublicacaoImprensaNacionalAPI->setData($ImprensaNacional->Data);
        }

        $objEntradaAgendarPublicacaoAPI->setImprensaNacional($objPublicacaoImprensaNacionalAPI);
      }else{
        $objEntradaAgendarPublicacaoAPI->setImprensaNacional(null);
      }

      $objSeiRN = new SeiRN();
      $objPublicacaoAPI = $objSeiRN->agendarPublicacao($objEntradaAgendarPublicacaoAPI);

      return $objPublicacaoAPI->getIdPublicacao();

    }catch(Throwable $e){
      throw new InfraException('Erro no serviço de agendamento de publicação.',$e);
    }
  }

  protected function alterarPublicacaoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $IdPublicacao, $IdDocumento, $ProtocoloDocumento, $StaMotivo, $IdVeiculoPublicacao, $DataDisponibilizacao, $Resumo, $ImprensaNacional){

    try{

      $objEntradaAlterarPublicacaoAPI = new EntradaAlterarPublicacaoAPI();
      $objEntradaAlterarPublicacaoAPI->setIdPublicacao($IdPublicacao);
      $objEntradaAlterarPublicacaoAPI->setIdDocumento($IdDocumento);
      $objEntradaAlterarPublicacaoAPI->setProtocoloDocumento($ProtocoloDocumento);
      $objEntradaAlterarPublicacaoAPI->setStaMotivo($StaMotivo);
      $objEntradaAlterarPublicacaoAPI->setIdVeiculoPublicacao($IdVeiculoPublicacao);
      $objEntradaAlterarPublicacaoAPI->setDataDisponibilizacao($DataDisponibilizacao);
      $objEntradaAlterarPublicacaoAPI->setResumo($Resumo);

      if ($ImprensaNacional!=null){

        $objPublicacaoImprensaNacionalAPI = new PublicacaoImprensaNacionalAPI();

        if (isset($ImprensaNacional->IdVeiculo)){
          $objPublicacaoImprensaNacionalAPI->setIdVeiculo($ImprensaNacional->IdVeiculo);
        }

        if (isset($ImprensaNacional->IdSecao)){
          $objPublicacaoImprensaNacionalAPI->setIdSecao($ImprensaNacional->IdSecao);
        }

        if (isset($ImprensaNacional->Pagina)){
          $objPublicacaoImprensaNacionalAPI->setPagina($ImprensaNacional->Pagina);
        }

        if (isset($ImprensaNacional->Data)){
          $objPublicacaoImprensaNacionalAPI->setData($ImprensaNacional->Data);
        }

        $objEntradaAlterarPublicacaoAPI->setImprensaNacional($objPublicacaoImprensaNacionalAPI);
      }else{
        $objEntradaAlterarPublicacaoAPI->setImprensaNacional(null);
      }

      $objSeiRN = new SeiRN();
      $objSeiRN->alterarPublicacao($objEntradaAlterarPublicacaoAPI);

    }catch(Throwable $e){
      throw new InfraException('Erro no serviço de alteração de publicação.',$e);
    }
  }

  protected function cancelarAgendamentoPublicacaoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $IdPublicacao, $IdDocumento, $ProtocoloDocumento){

    try{

      $objEntradaCancelarAgendamentoPublicacaoAPI = new EntradaCancelarAgendamentoPublicacaoAPI();
      $objEntradaCancelarAgendamentoPublicacaoAPI->setIdPublicacao($IdPublicacao);
      $objEntradaCancelarAgendamentoPublicacaoAPI->setIdDocumento($IdDocumento);
      $objEntradaCancelarAgendamentoPublicacaoAPI->setProtocoloDocumento($ProtocoloDocumento);

      $objSeiRN = new SeiRN();
      $objSeiRN->cancelarAgendamentoPublicacao($objEntradaCancelarAgendamentoPublicacaoAPI);

    }catch(Throwable $e){
      throw new InfraException('Erro no serviço de cancelamento de agendamento de publicação.',$e);
    }
  }

  protected function confirmarDisponibilizacaoPublicacaoMonitorado($SiglaSistema, $IdentificacaoServico, $IdVeiculoPublicacao, $DataDisponibilizacao, $DataPublicacao, $Numero, $IdDocumentos ){
    try {

      $objEntradaConfirmarDisponibilizacaoPublicacaoAPI = new EntradaConfirmarDisponibilizacaoPublicacaoAPI();
      $objEntradaConfirmarDisponibilizacaoPublicacaoAPI->setIdVeiculoPublicacao($IdVeiculoPublicacao);
      $objEntradaConfirmarDisponibilizacaoPublicacaoAPI->setDataDisponibilizacao($DataDisponibilizacao);
      $objEntradaConfirmarDisponibilizacaoPublicacaoAPI->setDataPublicacao($DataPublicacao);
      $objEntradaConfirmarDisponibilizacaoPublicacaoAPI->setNumero($Numero);

      if ($IdDocumentos != null && !is_array($IdDocumentos)){
        $IdDocumentos = array($IdDocumentos);
      }
      $objEntradaConfirmarDisponibilizacaoPublicacaoAPI->setIdDocumentos($IdDocumentos);

      $objSeiRN = new SeiRN();
      $objSeiRN->confirmarDisponibilizacaoPublicacao($objEntradaConfirmarDisponibilizacaoPublicacaoAPI);

      return true;

    }catch(Throwable $e){
      throw new InfraException('Erro no serviço de confirmação de disponibilização de publicação.',$e);
    }
  }

  protected function enviarEmailMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $ProtocoloProcedimento, $De, $Para, $CCO, $Assunto, $Mensagem, $IdDocumentos){
    try{

      $objEntradaEnviarEmailAPI = new EntradaEnviarEmailAPI();
      //$objEntradaEnviarEmailAPI->setIdProcedimento();
      $objEntradaEnviarEmailAPI->setProtocoloProcedimento($ProtocoloProcedimento);
      $objEntradaEnviarEmailAPI->setDe($De);
      $objEntradaEnviarEmailAPI->setPara($Para);
      $objEntradaEnviarEmailAPI->setCCO($CCO);
      $objEntradaEnviarEmailAPI->setAssunto($Assunto);
      $objEntradaEnviarEmailAPI->setMensagem($Mensagem);
      $objEntradaEnviarEmailAPI->setIdDocumentos($IdDocumentos);

      $objSeiRN = new SeiRN();
      return $objSeiRN->enviarEmail($objEntradaEnviarEmailAPI);

    }catch(Exception $e){
      throw new InfraException('Erro no serviço de envio de e-mail.',$e);
    }
  }

  protected function listarUnidadesMonitorado($SiglaSistema, $IdentificacaoServico, $IdTipoProcedimento, $IdSerie){

    try{

      $objOperacaoServicoRN = new OperacaoServicoRN();

      $objOperacaoServicoDTO = new OperacaoServicoDTO();
      $objOperacaoServicoDTO->setDistinct(true);
      $objOperacaoServicoDTO->retNumIdUnidade();
      $objOperacaoServicoDTO->setNumIdServico(SessaoSEI::getInstance()->getObjServicoDTO()->getNumIdServico());

      if (!InfraString::isBolVazia($IdTipoProcedimento)){
        $objOperacaoServicoDTO->adicionarCriterio(array('IdTipoProcedimento','IdTipoProcedimento'),
            array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),
            array($IdTipoProcedimento,null),
            InfraDTO::$OPER_LOGICO_OR);
      }

      if (!InfraString::isBolVazia($IdSerie)){
        $objOperacaoServicoDTO->adicionarCriterio(array('IdSerie','IdSerie'),
            array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),
            array($IdSerie,null),
            InfraDTO::$OPER_LOGICO_OR);
      }

      $arrObjOperacaoServicoDTO = $objOperacaoServicoRN->listar($objOperacaoServicoDTO);

      $ret = array();

      if (count($arrObjOperacaoServicoDTO)){

        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->retNumIdUnidade();
        $objUnidadeDTO->retStrSigla();
        $objUnidadeDTO->retStrDescricao();
        $objUnidadeDTO->retStrSinProtocolo();
        $objUnidadeDTO->retStrSinArquivamento();
        $objUnidadeDTO->retStrSinOuvidoria();

        $bolFlagTodas = false;
        $arrIdUnidade = array();
        foreach($arrObjOperacaoServicoDTO as $objOperacaoServicoDTO){
          if ($objOperacaoServicoDTO->getNumIdUnidade()==null){
            $bolFlagTodas = true;
            break;
          }else{
            $arrIdUnidade[] = $objOperacaoServicoDTO->getNumIdUnidade();
          }
        }

        if (!$bolFlagTodas){
          $objUnidadeDTO->setNumIdUnidade($arrIdUnidade,InfraDTO::$OPER_IN);
        }

        $objUnidadeDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objUnidadeRN = new UnidadeRN();
        $arrObjUnidadeDTO = $objUnidadeRN->listarRN0127($objUnidadeDTO);

        foreach ($arrObjUnidadeDTO as $objUnidadeDTO) {
          $objUnidadeAPI = new UnidadeAPI();
          $objUnidadeAPI->setIdUnidade($objUnidadeDTO->getNumIdUnidade());
          $objUnidadeAPI->setSigla($objUnidadeDTO->getStrSigla());
          $objUnidadeAPI->setDescricao($objUnidadeDTO->getStrDescricao());
          $objUnidadeAPI->setSinProtocolo($objUnidadeDTO->getStrSinProtocolo());
          $objUnidadeAPI->setSinArquivamento($objUnidadeDTO->getStrSinArquivamento());
          $objUnidadeAPI->setSinOuvidoria($objUnidadeDTO->getStrSinOuvidoria());
          $ret[] = $objUnidadeAPI;
        }
      }

      return $ret;

    }catch(Throwable $e){
      throw new InfraException('Erro no serviço de listagem de unidades.',$e);
    }
  }

  protected function listarTiposProcedimentoMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $IdSerie){

    try{

      $objOperacaoServicoRN = new OperacaoServicoRN();

      $objOperacaoServicoDTO = new OperacaoServicoDTO();
      $objOperacaoServicoDTO->setDistinct(true);
      $objOperacaoServicoDTO->retNumIdTipoProcedimento();
      $objOperacaoServicoDTO->setNumIdServico(SessaoSEI::getInstance()->getObjServicoDTO()->getNumIdServico());

      if (!InfraString::isBolVazia($IdUnidade)){
        $objOperacaoServicoDTO->adicionarCriterio(array('IdUnidade','IdUnidade'),
            array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),
            array($IdUnidade,null),
            InfraDTO::$OPER_LOGICO_OR);
      }

      if (!InfraString::isBolVazia($IdSerie)){
        $objOperacaoServicoDTO->adicionarCriterio(array('IdSerie','IdSerie'),
            array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),
            array($IdSerie,null),
            InfraDTO::$OPER_LOGICO_OR);
      }

      $arrObjOperacaoServicoDTO = $objOperacaoServicoRN->listar($objOperacaoServicoDTO);

      $ret = array();

      if (count($arrObjOperacaoServicoDTO)){

        $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
        $objTipoProcedimentoDTO->retNumIdTipoProcedimento();
        $objTipoProcedimentoDTO->retStrNome();
        $objTipoProcedimentoDTO->retStrDescricao();
        $objTipoProcedimentoDTO->retStrStaNivelAcessoSugestao();
        $objTipoProcedimentoDTO->retNumIdHipoteseLegalSugestao();
        $objTipoProcedimentoDTO->retStrStaGrauSigiloSugestao();

        $objTipoProcedimentoRN = new TipoProcedimentoRN();

        if (InfraString::isBolVazia($IdUnidade)) {
          $arrTiposNaoLiberados = array();
        }else{
          $arrTiposNaoLiberados = InfraArray::converterArrInfraDTO($objTipoProcedimentoRN->listarNaoLiberadosNaUnidade(),'IdTipoProcedimento');
        }

        $bolFlagTodos = false;
        $arrIdTipoProcedimento = array();
        foreach($arrObjOperacaoServicoDTO as $objOperacaoServicoDTO){
          if ($objOperacaoServicoDTO->getNumIdTipoProcedimento()==null){
            $bolFlagTodos = true;
            break;
          }else{
            if (!in_array($objOperacaoServicoDTO->getNumIdTipoProcedimento(), $arrTiposNaoLiberados)) {
              $arrIdTipoProcedimento[] = $objOperacaoServicoDTO->getNumIdTipoProcedimento();
            }
          }
        }

        if (!$bolFlagTodos){
          if (count($arrIdTipoProcedimento)) {
            $objTipoProcedimentoDTO->setNumIdTipoProcedimento($arrIdTipoProcedimento, InfraDTO::$OPER_IN);
          }
        }else{
          if (count($arrTiposNaoLiberados)){
            $objTipoProcedimentoDTO->setNumIdTipoProcedimento($arrTiposNaoLiberados,InfraDTO::$OPER_NOT_IN);
          }
        }

        $objTipoProcedimentoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);


        $arrObjTipoProcedimentoDTO = $objTipoProcedimentoRN->listarRN0244($objTipoProcedimentoDTO);

        foreach ($arrObjTipoProcedimentoDTO as $objTipoProcedimentoDTO) {
          $objTipoProcedimentoAPI = new TipoProcedimentoAPI();
          $objTipoProcedimentoAPI->setIdTipoProcedimento($objTipoProcedimentoDTO->getNumIdTipoProcedimento());
          $objTipoProcedimentoAPI->setNome($objTipoProcedimentoDTO->getStrNome());
          $ret[] = $objTipoProcedimentoAPI;
        }
      }

      return $ret;

    }catch(Throwable $e){
      throw new InfraException('Erro no serviço de listagem de tipos de processo.',$e);
    }
  }

  protected function listarSeriesMonitorado($SiglaSistema, $IdentificacaoServico, $IdUnidade, $IdTipoProcedimento){

    try{

      $objOperacaoServicoRN = new OperacaoServicoRN();

      $objOperacaoServicoDTO = new OperacaoServicoDTO();
      $objOperacaoServicoDTO->setDistinct(true);
      $objOperacaoServicoDTO->retNumIdSerie();
      $objOperacaoServicoDTO->setNumIdServico(SessaoSEI::getInstance()->getObjServicoDTO()->getNumIdServico());

      if (!InfraString::isBolVazia($IdUnidade)){
        $objOperacaoServicoDTO->adicionarCriterio(array('IdUnidade','IdUnidade'),
            array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),
            array($IdUnidade,null),
            InfraDTO::$OPER_LOGICO_OR);
      }

      if (!InfraString::isBolVazia($IdTipoProcedimento)){
        $objOperacaoServicoDTO->adicionarCriterio(array('IdTipoProcedimento','IdTipoProcedimento'),
            array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),
            array($IdTipoProcedimento,null),
            InfraDTO::$OPER_LOGICO_OR);
      }

      $arrObjOperacaoServicoDTO = $objOperacaoServicoRN->listar($objOperacaoServicoDTO);

      $ret = array();

      if (count($arrObjOperacaoServicoDTO)){

        $objSerieDTO = new SerieDTO();
        $objSerieDTO->retNumIdSerie();
        $objSerieDTO->retNumIdTipoFormulario();
        $objSerieDTO->retStrNome();
        $objSerieDTO->retStrDescricao();
        $objSerieDTO->retStrStaAplicabilidade();

        $objSerieRN = new SerieRN();

        if (InfraString::isBolVazia($IdUnidade)) {
          $arrTiposNaoLiberados = array();
        }else{
          $arrTiposNaoLiberados = InfraArray::converterArrInfraDTO($objSerieRN->listarNaoLiberadosNaUnidade(),'IdSerie');
        }

        $bolFlagTodas = false;
        $arrIdSerie = array();
        foreach($arrObjOperacaoServicoDTO as $objOperacaoServicoDTO){
          if ($objOperacaoServicoDTO->getNumIdSerie()==null){
            $bolFlagTodas = true;
            break;
          }else{
            if (!in_array($objOperacaoServicoDTO->getNumIdSerie(), $arrTiposNaoLiberados)) {
              $arrIdSerie[] = $objOperacaoServicoDTO->getNumIdSerie();
            }
          }
        }

        if (!$bolFlagTodas){
          if (count($arrIdSerie)) {
            $objSerieDTO->adicionarCriterio(array('IdSerie','StaAplicabilidade'),
                                            array(InfraDTO::$OPER_IN, InfraDTO::$OPER_DIFERENTE),
                                            array($arrIdSerie, 'I'),
                                            InfraDTO::$OPER_LOGICO_OR);
          }
        }else {
          if (count($arrTiposNaoLiberados)) {
            $objSerieDTO->adicionarCriterio(array('IdSerie','StaAplicabilidade'),
                                            array(InfraDTO::$OPER_NOT_IN, InfraDTO::$OPER_DIFERENTE),
                                            array($arrTiposNaoLiberados, 'I'),
                                            InfraDTO::$OPER_LOGICO_OR);
          }
        }

        $objSerieDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

        $arrObjSerieDTO = $objSerieRN->listarRN0646($objSerieDTO);

        foreach ($arrObjSerieDTO as $objSerieDTO) {
          $objSerieAPI = new SerieAPI();
          $objSerieAPI->setIdSerie($objSerieDTO->getNumIdSerie());
          $objSerieAPI->setNome($objSerieDTO->getStrNome());
          $objSerieAPI->setAplicabilidade($objSerieDTO->getStrStaAplicabilidade());
          $ret[] = $objSerieAPI;
        }
      }

      return $ret;

    }catch(Throwable $e){
      throw new InfraException('Erro no serviço de listagem de tipos de documento.',$e);
    }
  }

  private function montarProcedimentoAPI($Procedimento){

		$objProcedimentoAPI = new ProcedimentoAPI();
		$objProcedimentoAPI->setIdTipoProcedimento($Procedimento->IdTipoProcedimento);
		$objProcedimentoAPI->setNumeroProtocolo($Procedimento->NumeroProtocolo);
		$objProcedimentoAPI->setDataAutuacao($Procedimento->DataAutuacao);
		$objProcedimentoAPI->setEspecificacao($Procedimento->Especificacao);

		$arrObjAssuntoAPI = array();
		if (is_array($Procedimento->Assuntos)) {

			$numAssuntos = count($Procedimento->Assuntos);

			for($i=0;$i<$numAssuntos;$i++){

				if (is_array($Procedimento->Assuntos[$i])){
					$Procedimento->Assuntos[$i] = (object)$Procedimento->Assuntos[$i];
				}

				$objAssuntoAPI = new AssuntoAPI();
				$objAssuntoAPI->setCodigoEstruturado($Procedimento->Assuntos[$i]->CodigoEstruturado);
				$arrObjAssuntoAPI[] = $objAssuntoAPI;
			}
		}
		$objProcedimentoAPI->setAssuntos($arrObjAssuntoAPI);

		$arrObjInteressadoAPI = array();
		if (is_array($Procedimento->Interessados)){

			$numInteressados = count($Procedimento->Interessados);

			for($i=0;$i<$numInteressados;$i++){

				if (is_array($Procedimento->Interessados[$i])){
					$Procedimento->Interessados[$i] = (object)$Procedimento->Interessados[$i];
				}

				$objInteressadoAPI = new InteressadoAPI();
				$objInteressadoAPI->setSigla($Procedimento->Interessados[$i]->Sigla);
				$objInteressadoAPI->setNome($Procedimento->Interessados[$i]->Nome);
				$arrObjInteressadoAPI[] = $objInteressadoAPI;
			}
		}
		$objProcedimentoAPI->setInteressados($arrObjInteressadoAPI);

		$objProcedimentoAPI->setObservacao($Procedimento->Observacao);
		$objProcedimentoAPI->setNivelAcesso($Procedimento->NivelAcesso);
		$objProcedimentoAPI->setIdHipoteseLegal($Procedimento->IdHipoteseLegal);

		return $objProcedimentoAPI;
	}

	private function montarDocumentoAPI($Documento){

		if (is_array($Documento)){
			$Documento = (object)$Documento;
		}

		$objDocumentoAPI = new DocumentoAPI();
		$objDocumentoAPI->setTipo($Documento->Tipo);
		$objDocumentoAPI->setIdProcedimento($Documento->IdProcedimento);
		$objDocumentoAPI->setProtocoloProcedimento($Documento->ProtocoloProcedimento);
		$objDocumentoAPI->setIdSerie($Documento->IdSerie);
		$objDocumentoAPI->setNumero($Documento->Numero);
		$objDocumentoAPI->setNomeArvore($Documento->NomeArvore);
		$objDocumentoAPI->setData($Documento->Data);
		$objDocumentoAPI->setDescricao($Documento->Descricao);
		$objDocumentoAPI->setIdTipoConferencia($Documento->IdTipoConferencia);
    $objDocumentoAPI->setSinArquivamento($Documento->SinArquivamento);

		$objRemetenteAPI = null;
		if ($Documento->Remetente!=null){
			if (isset($Documento->Remetente->Sigla) || isset($Documento->Remetente->Nome)){
				$objRemetenteAPI = new RemetenteAPI();

				if (isset($Documento->Remetente->Sigla)) {
					$objRemetenteAPI->setSigla($Documento->Remetente->Sigla);
				}else{
					$objRemetenteAPI->setSigla(null);
				}

				if (isset($Documento->Remetente->Nome)) {
					$objRemetenteAPI->setNome($Documento->Remetente->Nome);
				}else{
					$objRemetenteAPI->setNome(null);
				}
			}
		}
		$objDocumentoAPI->setRemetente($objRemetenteAPI);

		$arrObjInteressadoAPI = array();
		if (is_array($Documento->Interessados)){

			$numInteressados = count($Documento->Interessados);

			for($i=0;$i<$numInteressados;$i++){

				if (is_array($Documento->Interessados[$i])){
					$Documento->Interessados[$i] = (object)$Documento->Interessados[$i];
				}

				$objInteressadoAPI = new InteressadoAPI();
				$objInteressadoAPI->setSigla($Documento->Interessados[$i]->Sigla);
				$objInteressadoAPI->setNome($Documento->Interessados[$i]->Nome);
				$arrObjInteressadoAPI[] = $objInteressadoAPI;
			}
		}
		$objDocumentoAPI->setInteressados($arrObjInteressadoAPI);

		$arrObjDestinatarioAPI = array();
		if (is_array($Documento->Destinatarios)){

			$numDestinatarios = count($Documento->Destinatarios);

			for($i=0;$i<$numDestinatarios;$i++){

				if (is_array($Documento->Destinatarios[$i])){
					$Documento->Destinatarios[$i] = (object)$Documento->Destinatarios[$i];
				}

				$objDestinatarioAPI = new DestinatarioAPI();
				$objDestinatarioAPI->setSigla($Documento->Destinatarios[$i]->Sigla);
				$objDestinatarioAPI->setNome($Documento->Destinatarios[$i]->Nome);
				$arrObjDestinatarioAPI[] = $objDestinatarioAPI;
			}
		}
		$objDocumentoAPI->setDestinatarios($arrObjDestinatarioAPI);


		$objDocumentoAPI->setObservacao($Documento->Observacao);
		$objDocumentoAPI->setNomeArquivo($Documento->NomeArquivo);
		$objDocumentoAPI->setNivelAcesso($Documento->NivelAcesso);
		$objDocumentoAPI->setIdHipoteseLegal($Documento->IdHipoteseLegal);
		$objDocumentoAPI->setConteudo($Documento->Conteudo);
		$objDocumentoAPI->setConteudoMTOM($Documento->ConteudoMTOM);
		$objDocumentoAPI->setSinBloqueado($Documento->SinBloqueado);
		$objDocumentoAPI->setIdArquivo($Documento->IdArquivo);

		$arrObjCampoAPI = array();
		if (is_array($Documento->Campos)){

			$numCampos = count($Documento->Campos);

			for($i=0;$i<$numCampos;$i++){
				if (is_array($Documento->Campos[$i])){
					$Documento->Campos[$i] = (object)$Documento->Campos[$i];
				}
				$objCampoAPI = new CampoAPI();
				$objCampoAPI->setNome($Documento->Campos[$i]->Nome);
				$objCampoAPI->setValor($Documento->Campos[$i]->Valor);
				$arrObjCampoAPI[] = $objCampoAPI;
			}
		}
		$objDocumentoAPI->setCampos($arrObjCampoAPI);

		return $objDocumentoAPI;
	}

	private function debugParametros($var){
		$ret = '';
		if (is_array($var)) {
			$arr = $var;
			if (isset($arr['Conteudo']) && $arr['Conteudo'] != null) {
				$arr['Conteudo'] = strlen($arr['Conteudo']) . ' bytes';
			}
			if (isset($arr['ConteudoMTOM']) && $arr['ConteudoMTOM'] != null) {
				$arr['ConteudoMTOM'] = strlen($arr['ConteudoMTOM']) . ' bytes';
			}
			$numItens = count($arr);
			for ($i = 0; $i < $numItens; $i++) {
				$arr[$i] = $this->debugParametros($arr[$i]);
			}
			$ret = print_r($arr, true);
		}elseif (is_object($var)) {
			$obj = clone($var);
			if (isset($obj->Conteudo) && $obj->Conteudo != null) {
				$obj->Conteudo = strlen($obj->Conteudo) . ' bytes';
			}
			if (isset($obj->ConteudoMTOM) && $obj->ConteudoMTOM != null) {
				$obj->ConteudoMTOM = strlen($obj->ConteudoMTOM) . ' bytes';
			}
			$ret = print_r($obj, true);
		}else{
			$ret = $var;
		}
		return $ret;
	}
}

$servidorSoap = new BeSimple\SoapServer\SoapServer( "sei.wsdl", array ('encoding'=>'ISO-8859-1',
		                                                                   'soap_version' => SOAP_1_1,
		                                                                   'attachment_type'=>BeSimple\SoapCommon\Helper::ATTACHMENTS_TYPE_MTOM));
$servidorSoap->setClass ( "SeiWS" );

//Só processa se acessado via POST
if ($_SERVER['REQUEST_METHOD']=='POST') {
  $servidorSoap->handle($HTTP_RAW_POST_DATA);
}
