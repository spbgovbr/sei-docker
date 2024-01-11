<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class RelHierarquiaUnidadeRN extends InfraRN {

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSip::getInstance();
  }

  protected function cadastrarControlado(RelHierarquiaUnidadeDTO $objRelHierarquiaUnidadeDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('rel_hierarquia_unidade_cadastrar', __METHOD__, $objRelHierarquiaUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdUnidade($objRelHierarquiaUnidadeDTO, $objInfraException);
      $this->validarNumIdHierarquia($objRelHierarquiaUnidadeDTO, $objInfraException);
      $this->validarDtaDataInicio($objRelHierarquiaUnidadeDTO, $objInfraException);
      $this->validarDtaDataFim($objRelHierarquiaUnidadeDTO, $objInfraException);
      $this->validarPeriodoDatas($objRelHierarquiaUnidadeDTO, $objInfraException);
      $this->validarStrSinAtivo($objRelHierarquiaUnidadeDTO, $objInfraException);

      if ($objRelHierarquiaUnidadeDTO->getNumIdUnidadePai() == $objRelHierarquiaUnidadeDTO->getNumIdUnidade()) {
        $objInfraException->lancarValidacao('Unidade superior não pode ser a própria unidade.');
      }

      $objInfraException->lancarValidacoes();

      $dto = new RelHierarquiaUnidadeDTO();
      $dto->setBolExclusaoLogica(false);
      $dto->retStrSiglaUnidade();
      $dto->retStrSiglaOrgaoUnidade();
      $dto->retStrNomeHierarquia();
      $dto->retStrSinAtivo();
      $dto->setNumIdHierarquia($objRelHierarquiaUnidadeDTO->getNumIdHierarquia());
      $dto->setNumIdUnidade($objRelHierarquiaUnidadeDTO->getNumIdUnidade());

      $dto = $this->consultar($dto);

      if ($dto != null) {
        $objInfraException->lancarValidacao('Unidade ' . $dto->getStrSiglaUnidade() . '/' . $dto->getStrSiglaOrgaoUnidade() . ' já consta' . ($dto->getStrSinAtivo() == 'N' ? ' desativada ' : ' ') . 'na hierarquia ' . $dto->getStrNomeHierarquia() . '.');
      }


      $objRelHierarquiaUnidadeBD = new RelHierarquiaUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelHierarquiaUnidadeBD->cadastrar($objRelHierarquiaUnidadeDTO);


      $objReplicacaoUnidadeDTO = new ReplicacaoUnidadeDTO();
      $objReplicacaoUnidadeDTO->setStrStaOperacao('C');
      $objReplicacaoUnidadeDTO->setNumIdHierarquia($objRelHierarquiaUnidadeDTO->getNumIdHierarquia());
      $objReplicacaoUnidadeDTO->setNumIdUnidade($objRelHierarquiaUnidadeDTO->getNumIdUnidade());

      $objSistemaRN = new SistemaRN();
      $objSistemaRN->replicarUnidade($objReplicacaoUnidadeDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro cadastrando unidade na hierarquia.', $e);
    }
  }

  protected function alterarControlado(RelHierarquiaUnidadeDTO $objRelHierarquiaUnidadeDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('rel_hierarquia_unidade_alterar', __METHOD__, $objRelHierarquiaUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdUnidade($objRelHierarquiaUnidadeDTO, $objInfraException);
      $this->validarNumIdHierarquia($objRelHierarquiaUnidadeDTO, $objInfraException);
      $this->validarDtaDataInicio($objRelHierarquiaUnidadeDTO, $objInfraException);
      $this->validarDtaDataFim($objRelHierarquiaUnidadeDTO, $objInfraException);
      $this->validarPeriodoDatas($objRelHierarquiaUnidadeDTO, $objInfraException);
      $this->validarStrSinAtivo($objRelHierarquiaUnidadeDTO, $objInfraException);
      $this->validarUnidadePai($objRelHierarquiaUnidadeDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelHierarquiaUnidadeBD = new RelHierarquiaUnidadeBD($this->getObjInfraIBanco());
      $objRelHierarquiaUnidadeBD->alterar($objRelHierarquiaUnidadeDTO);


      //replicar nos sistemas
      $objReplicacaoUnidadeDTO = new ReplicacaoUnidadeDTO();
      $objReplicacaoUnidadeDTO->setStrStaOperacao('A');
      $objReplicacaoUnidadeDTO->setNumIdHierarquia($objRelHierarquiaUnidadeDTO->getNumIdHierarquia());
      $objReplicacaoUnidadeDTO->setNumIdUnidade($objRelHierarquiaUnidadeDTO->getNumIdUnidade());

      $objSistemaRN = new SistemaRN();
      $objSistemaRN->replicarUnidade($objReplicacaoUnidadeDTO);
      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro alterando unidade na hierarquia.', $e);
    }
  }

  private function validarUnidadePai($parObjRelHierarquiaUnidadeDTO, $objInfraException) {
    if ($parObjRelHierarquiaUnidadeDTO->getNumIdUnidadePai() != null) {
      if ($parObjRelHierarquiaUnidadeDTO->getNumIdUnidadePai() == $parObjRelHierarquiaUnidadeDTO->getNumIdUnidade()) {
        $objInfraException->lancarValidacao('Unidade superior não pode ser a própria unidade.');
      }

      $objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
      $objRelHierarquiaUnidadeDTO->retArrUnidadesSuperiores();
      $objRelHierarquiaUnidadeDTO->setNumIdHierarquia($parObjRelHierarquiaUnidadeDTO->getNumIdHierarquia());

      $arrHierarquia = $this->listarHierarquia($objRelHierarquiaUnidadeDTO);

      foreach ($arrHierarquia as $objRelHierarquiaUnidadeDTONovoPai) {
        //encontra o novo pai na hierarquia
        if ($objRelHierarquiaUnidadeDTONovoPai->getNumIdUnidade() == $parObjRelHierarquiaUnidadeDTO->getNumIdUnidadePai()) {
          $arrPais = $objRelHierarquiaUnidadeDTONovoPai->getArrUnidadesSuperiores();

          foreach ($arrPais as $objRelHierarquiaUnidadeDTOPaiNovoPai) {
            //se um dos pais do novo pai é igual a unidade atual (referência circular)
            if ($objRelHierarquiaUnidadeDTOPaiNovoPai->getNumIdUnidade() == $parObjRelHierarquiaUnidadeDTO->getNumIdUnidade()) {
              //busca o pai imediatamente superior da unidade atual
              $dto = new RelHierarquiaUnidadeDTO();
              $dto->retNumIdHierarquiaPai();
              $dto->retNumIdUnidadePai();
              $dto->setNumIdHierarquia($parObjRelHierarquiaUnidadeDTO->getNumIdHierarquia());
              $dto->setNumIdUnidade($parObjRelHierarquiaUnidadeDTO->getNumIdUnidade());

              $dto1 = $this->consultar($dto);

              $dto = new RelHierarquiaUnidadeDTO();
              $dto->setNumIdHierarquiaPai($dto1->getNumIdHierarquiaPai());
              $dto->setNumIdUnidadePai($dto1->getNumIdUnidadePai());
              $dto->setNumIdHierarquia($objRelHierarquiaUnidadeDTONovoPai->getNumIdHierarquia());
              $dto->setNumIdUnidade($objRelHierarquiaUnidadeDTONovoPai->getNumIdUnidade());

              $objRelHierarquiaUnidadeBD = new RelHierarquiaUnidadeBD($this->getObjInfraIBanco());
              $objRelHierarquiaUnidadeBD->alterar($dto);

              break;
            }
          }
          break;
        }
      }
    }
  }

  protected function excluirControlado($arrObjRelHierarquiaUnidadeDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('rel_hierarquia_unidade_excluir', __METHOD__, $arrObjRelHierarquiaUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if (count($arrObjRelHierarquiaUnidadeDTO)) {
        $arrIdUnidades = InfraArray::converterArrInfraDTO($arrObjRelHierarquiaUnidadeDTO, 'IdUnidade');
        $arrIdHierarquia = array_unique(InfraArray::converterArrInfraDTO($arrObjRelHierarquiaUnidadeDTO, 'IdHierarquia'));

        if (count($arrIdHierarquia) > 1) {
          throw new InfraException('Não é possível excluir múltiplas unidades com hierarquias diferentes.');
        }

        $objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
        $objRelHierarquiaUnidadeDTO->setBolExclusaoLogica(false);
        $objRelHierarquiaUnidadeDTO->retArrUnidadesInferiores();
        $objRelHierarquiaUnidadeDTO->setNumIdHierarquia($arrIdHierarquia[0]);
        $objRelHierarquiaUnidadeDTO->setNumIdUnidade($arrIdUnidades);
        $arrHierarquia = InfraArray::indexarArrInfraDTO($this->listarHierarquia($objRelHierarquiaUnidadeDTO), 'IdUnidade');


        //carrega os sisteamas associados com as hierarquias
        $objSistemaDTO = new SistemaDTO();
        $objSistemaDTO->retNumIdSistema();
        $objSistemaDTO->retStrSigla();
        $objSistemaDTO->retNumIdHierarquia();
        $objSistemaDTO->setNumIdHierarquia($arrIdHierarquia[0]);

        $objSistemaRN = new SistemaRN();
        $arrObjSistemaDTO = $objSistemaRN->listar($objSistemaDTO);

        $objPermissaoRN = new PermissaoRN();
        for ($i = 0; $i < count($arrObjRelHierarquiaUnidadeDTO); $i++) {
          $arrUnidadesInferiores = $arrHierarquia[$arrObjRelHierarquiaUnidadeDTO[$i]->getNumIdUnidade()]->getArrUnidadesInferiores();

          if (count($arrUnidadesInferiores) > 0) {
            $objInfraException->adicionarValidacao('Unidade ' . $arrHierarquia[$arrObjRelHierarquiaUnidadeDTO[$i]->getNumIdUnidade()]->getStrSiglaUnidade() . ' possui subunidades.');
          }

          foreach ($arrObjSistemaDTO as $objSistemaDTO) {
            $objPermissaoDTO = new PermissaoDTO();
            $objPermissaoDTO->setNumIdSistema($objSistemaDTO->getNumIdSistema());
            $objPermissaoDTO->setNumIdUnidade($arrObjRelHierarquiaUnidadeDTO[$i]->getNumIdUnidade());

            if ($objPermissaoRN->contar($objPermissaoDTO)) {
              $objInfraException->adicionarValidacao('Sistema ' . $objSistemaDTO->getStrSigla() . ' possui permissões na unidade ' . $arrHierarquia[$arrObjRelHierarquiaUnidadeDTO[$i]->getNumIdUnidade()]->getStrSiglaUnidade() . '.');
            }
          }
        }

        $objInfraException->lancarValidacoes();

        //replicação
        for ($i = 0; $i < count($arrObjRelHierarquiaUnidadeDTO); $i++) {
          $objReplicacaoUnidadeDTO = new ReplicacaoUnidadeDTO();
          $objReplicacaoUnidadeDTO->setStrStaOperacao('E');
          $objReplicacaoUnidadeDTO->setNumIdHierarquia($arrObjRelHierarquiaUnidadeDTO[$i]->getNumIdHierarquia());
          $objReplicacaoUnidadeDTO->setNumIdUnidade($arrObjRelHierarquiaUnidadeDTO[$i]->getNumIdUnidade());

          $objSistemaRN = new SistemaRN();
          $objSistemaRN->replicarUnidade($objReplicacaoUnidadeDTO);
        }

        $objRelHierarquiaUnidadeBD = new RelHierarquiaUnidadeBD($this->getObjInfraIBanco());
        for ($i = 0; $i < count($arrObjRelHierarquiaUnidadeDTO); $i++) {
          $objRelHierarquiaUnidadeBD->excluir($arrObjRelHierarquiaUnidadeDTO[$i]);
        }
      }
      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro excluindo unidades na hierarquia.', $e);
    }
  }

  protected function desativarControlado($arrObjRelHierarquiaUnidadeDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('rel_hierarquia_unidade_desativar', __METHOD__, $arrObjRelHierarquiaUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if (count($arrObjRelHierarquiaUnidadeDTO)) {
        $arrIdUnidades = InfraArray::converterArrInfraDTO($arrObjRelHierarquiaUnidadeDTO, 'IdUnidade');
        $arrIdHierarquia = array_unique(InfraArray::converterArrInfraDTO($arrObjRelHierarquiaUnidadeDTO, 'IdHierarquia'));

        if (count($arrIdHierarquia) > 1) {
          throw new InfraException('Não é possível desativar múltiplas unidades com hierarquias diferentes.');
        }

        $objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
        $objRelHierarquiaUnidadeDTO->setBolExclusaoLogica(false);
        $objRelHierarquiaUnidadeDTO->retArrUnidadesInferiores();
        $objRelHierarquiaUnidadeDTO->setNumIdHierarquia($arrIdHierarquia[0]);
        $objRelHierarquiaUnidadeDTO->setNumIdUnidade($arrIdUnidades);
        $arrHierarquia = InfraArray::indexarArrInfraDTO($this->listarHierarquia($objRelHierarquiaUnidadeDTO), 'IdUnidade');

        //carrega os sistemas associados com as hierarquias
        $objSistemaDTO = new SistemaDTO();
        $objSistemaDTO->retNumIdSistema();
        $objSistemaDTO->retStrSigla();
        $objSistemaDTO->retNumIdHierarquia();
        $objSistemaDTO->setNumIdHierarquia($arrIdHierarquia[0]);

        $objSistemaRN = new SistemaRN();
        $arrObjSistemaDTO = $objSistemaRN->listar($objSistemaDTO);

        $objPermissaoRN = new PermissaoRN();

        for ($i = 0; $i < count($arrObjRelHierarquiaUnidadeDTO); $i++) {
          $arrUnidadesInferiores = $arrHierarquia[$arrObjRelHierarquiaUnidadeDTO[$i]->getNumIdUnidade()]->getArrUnidadesInferiores();

          $arrUnidadesAtivas = array();
          foreach ($arrUnidadesInferiores as $unidadeInferior) {
            if ($arrHierarquia[$unidadeInferior->getNumIdUnidade()]->getStrSinAtivo() == 'S') {
              $arrUnidadesAtivas[] = $unidadeInferior->getStrSiglaUnidade();
            }
          }

          if (count($arrUnidadesAtivas) > 0) {
            $objInfraException->adicionarValidacao('Unidade ' . $arrHierarquia[$arrObjRelHierarquiaUnidadeDTO[$i]->getNumIdUnidade()]->getStrSiglaUnidade() . ' possui subunidades ativas: ' . implode(', ', $arrUnidadesAtivas));
          }

          foreach ($arrObjSistemaDTO as $objSistemaDTO) {
            $objPermissaoDTO = new PermissaoDTO();
            $objPermissaoDTO->setNumIdSistema($objSistemaDTO->getNumIdSistema());
            $objPermissaoDTO->setNumIdUnidade($arrObjRelHierarquiaUnidadeDTO[$i]->getNumIdUnidade());

            if ($objPermissaoRN->contar($objPermissaoDTO)) {
              $objInfraException->adicionarValidacao('Sistema ' . $objSistemaDTO->getStrSigla() . ' possui permissões na unidade ' . $arrHierarquia[$arrObjRelHierarquiaUnidadeDTO[$i]->getNumIdUnidade()]->getStrSiglaUnidade() . '.');
            }
          }
        }

        $objInfraException->lancarValidacoes();

        //replicação
        for ($i = 0; $i < count($arrObjRelHierarquiaUnidadeDTO); $i++) {
          $objReplicacaoUnidadeDTO = new ReplicacaoUnidadeDTO();
          $objReplicacaoUnidadeDTO->setStrStaOperacao('D');
          $objReplicacaoUnidadeDTO->setNumIdHierarquia($arrObjRelHierarquiaUnidadeDTO[$i]->getNumIdHierarquia());
          $objReplicacaoUnidadeDTO->setNumIdUnidade($arrObjRelHierarquiaUnidadeDTO[$i]->getNumIdUnidade());

          $objSistemaRN = new SistemaRN();
          $objSistemaRN->replicarUnidade($objReplicacaoUnidadeDTO);
        }


        $objRelHierarquiaUnidadeBD = new RelHierarquiaUnidadeBD($this->getObjInfraIBanco());
        for ($i = 0; $i < count($arrObjRelHierarquiaUnidadeDTO); $i++) {
          $objRelHierarquiaUnidadeBD->desativar($arrObjRelHierarquiaUnidadeDTO[$i]);
        }
      }
      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro desativando unidades na hierarquia.', $e);
    }
  }

  protected function reativarControlado($arrObjRelHierarquiaUnidadeDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('rel_hierarquia_unidade_reativar', __METHOD__, $arrObjRelHierarquiaUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if (count($arrObjRelHierarquiaUnidadeDTO)) {
        $arrIdUnidades = InfraArray::converterArrInfraDTO($arrObjRelHierarquiaUnidadeDTO, 'IdUnidade');
        $arrIdHierarquia = array_unique(InfraArray::converterArrInfraDTO($arrObjRelHierarquiaUnidadeDTO, 'IdHierarquia'));

        if (count($arrIdHierarquia) > 1) {
          throw new InfraException('Não é possível reativar múltiplas unidades com hierarquias diferentes.');
        }

        $objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
        $objRelHierarquiaUnidadeDTO->setBolExclusaoLogica(false);
        $objRelHierarquiaUnidadeDTO->retArrUnidadesSuperiores();
        $objRelHierarquiaUnidadeDTO->setNumIdHierarquia($arrIdHierarquia[0]);
        $objRelHierarquiaUnidadeDTO->setNumIdUnidade($arrIdUnidades);
        $arrHierarquia = InfraArray::indexarArrInfraDTO($this->listarHierarquia($objRelHierarquiaUnidadeDTO), 'IdUnidade');

        for ($i = 0; $i < count($arrObjRelHierarquiaUnidadeDTO); $i++) {
          $arrUnidadesSuperiores = $arrHierarquia[$arrObjRelHierarquiaUnidadeDTO[$i]->getNumIdUnidade()]->getArrUnidadesSuperiores();

          $arrUnidadesInativas = array();

          foreach ($arrUnidadesSuperiores as $unidadeSuperior) {
            if ($arrHierarquia[$unidadeSuperior->getNumIdUnidade()]->getStrSinAtivo() == 'N') {
              $arrUnidadesInativas[] = $unidadeSuperior->getStrSiglaUnidade();
            }
          }

          if (count($arrUnidadesInativas) > 0) {
            $objInfraException->adicionarValidacao('Unidade ' . $arrHierarquia[$arrObjRelHierarquiaUnidadeDTO[$i]->getNumIdUnidade()]->getStrSiglaUnidade() . ' possui unidades superiores inativas: ' . implode(', ', $arrUnidadesInativas));
          }
        }

        $objInfraException->lancarValidacoes();

        $objRelHierarquiaUnidadeBD = new RelHierarquiaUnidadeBD($this->getObjInfraIBanco());
        for ($i = 0; $i < count($arrObjRelHierarquiaUnidadeDTO); $i++) {
          $objRelHierarquiaUnidadeBD->reativar($arrObjRelHierarquiaUnidadeDTO[$i]);
        }

        //replicação
        for ($i = 0; $i < count($arrObjRelHierarquiaUnidadeDTO); $i++) {
          $objReplicacaoUnidadeDTO = new ReplicacaoUnidadeDTO();
          $objReplicacaoUnidadeDTO->setStrStaOperacao('R');
          $objReplicacaoUnidadeDTO->setNumIdHierarquia($arrObjRelHierarquiaUnidadeDTO[$i]->getNumIdHierarquia());
          $objReplicacaoUnidadeDTO->setNumIdUnidade($arrObjRelHierarquiaUnidadeDTO[$i]->getNumIdUnidade());

          $objSistemaRN = new SistemaRN();
          $objSistemaRN->replicarUnidade($objReplicacaoUnidadeDTO);
        }
      }
      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro reativando unidades na hierarquia.', $e);
    }
  }

  protected function consultarConectado(RelHierarquiaUnidadeDTO $objRelHierarquiaUnidadeDTO) {
    try {
      //Valida Permissao
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('rel_hierarquia_unidade_consultar',__METHOD__,$objRelHierarquiaUnidadeDTO);
      /////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelHierarquiaUnidadeBD = new RelHierarquiaUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelHierarquiaUnidadeBD->consultar($objRelHierarquiaUnidadeDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando unidade na hierarquia.', $e);
    }
  }

  protected function listarConectado(RelHierarquiaUnidadeDTO $objRelHierarquiaUnidadeDTO) {
    try {
      //Valida Permissao
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('rel_hierarquia_unidade_listar',__METHOD__,$objRelHierarquiaUnidadeDTO);
      /////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelHierarquiaUnidadeBD = new RelHierarquiaUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelHierarquiaUnidadeBD->listar($objRelHierarquiaUnidadeDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando unidades na hierarquia.', $e);
    }
  }

  protected function contarConectado(RelHierarquiaUnidadeDTO $objRelHierarquiaUnidadeDTO) {
    try {
      //Valida Permissao
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('rel_hierarquia_unidade_listar',__METHOD__,$objRelHierarquiaUnidadeDTO);
      /////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelHierarquiaUnidadeBD = new RelHierarquiaUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelHierarquiaUnidadeBD->contar($objRelHierarquiaUnidadeDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando unidades na hierarquia.', $e);
    }
  }

  protected function listarHierarquiaConectado(RelHierarquiaUnidadeDTO $parObjRelHierarquiaUnidadeDTO) {
    try {
      //Valida Permissao
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('rel_hierarquia_unidade_listar',__METHOD__,$parObjRelHierarquiaUnidadeDTO);
      /////////////////////////////////////////////////////////////////

      //$numSeg = InfraUtil::verificarTempoProcessamento();

      $bolRetRamificacao = $parObjRelHierarquiaUnidadeDTO->isRetStrRamificacao();
      $bolRetSuperiores = $parObjRelHierarquiaUnidadeDTO->isRetArrUnidadesSuperiores();
      $bolRetInferiores = $parObjRelHierarquiaUnidadeDTO->isRetArrUnidadesInferiores();

      $objRelHierarquiaUnidadeBD = new RelHierarquiaUnidadeBD($this->getObjInfraIBanco());

      $arrUnidadesRamificacao = array();

      if ($parObjRelHierarquiaUnidadeDTO->isSetNumIdUnidade() && (is_array($parObjRelHierarquiaUnidadeDTO->getNumIdUnidade()) || !InfraString::isBolVazia($parObjRelHierarquiaUnidadeDTO->getNumIdUnidade()))) {
        $numIdHierarquia = $parObjRelHierarquiaUnidadeDTO->getNumIdHierarquia();

        if (!is_array($parObjRelHierarquiaUnidadeDTO->getNumIdUnidade())) {
          $arrIdUnidadesFiltro = array($parObjRelHierarquiaUnidadeDTO->getNumIdUnidade());
        } else {
          $arrIdUnidadesFiltro = $parObjRelHierarquiaUnidadeDTO->getNumIdUnidade();
        }

        foreach ($arrIdUnidadesFiltro as $numIdUnidadeFiltro) {
          $numIdUnidade = $numIdUnidadeFiltro;

          while ($numIdUnidade != null) {
            $arrUnidadesRamificacao[] = $numIdUnidade;

            $objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
            $objRelHierarquiaUnidadeDTO->setBolExclusaoLogica(false);
            $objRelHierarquiaUnidadeDTO->retNumIdUnidadePai();
            $objRelHierarquiaUnidadeDTO->setNumIdUnidade($numIdUnidade);
            $objRelHierarquiaUnidadeDTO->setNumIdHierarquia($numIdHierarquia);
            $objRelHierarquiaUnidadeDTO = $objRelHierarquiaUnidadeBD->consultar($objRelHierarquiaUnidadeDTO);

            if ($objRelHierarquiaUnidadeDTO == null) {
              break;
            }

            $numIdUnidade = $objRelHierarquiaUnidadeDTO->getNumIdUnidadePai();
          }

          $arrIdUnidadeFilhas = array($numIdUnidadeFiltro);

          do {
            $objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
            $objRelHierarquiaUnidadeDTO->setBolExclusaoLogica(false);
            $objRelHierarquiaUnidadeDTO->retNumIdUnidade();
            $objRelHierarquiaUnidadeDTO->setNumIdUnidadePai($arrIdUnidadeFilhas, InfraDTO::$OPER_IN);
            $objRelHierarquiaUnidadeDTO->setNumIdHierarquiaPai($numIdHierarquia);
            $arrIdUnidadeFilhas = InfraArray::converterArrInfraDTO($objRelHierarquiaUnidadeBD->listar($objRelHierarquiaUnidadeDTO), 'IdUnidade');

            $arrUnidadesRamificacao = array_unique(array_merge($arrUnidadesRamificacao, $arrIdUnidadeFilhas));
          } while (count($arrIdUnidadeFilhas));
        }
      }


      //Lista todas as unidades para poder montar as superiores e inferiores
      $parObjRelHierarquiaUnidadeDTO->retNumIdHierarquia();
      $parObjRelHierarquiaUnidadeDTO->retNumIdUnidade();
      $parObjRelHierarquiaUnidadeDTO->retStrIdOrigemUnidade();
      $parObjRelHierarquiaUnidadeDTO->retNumIdOrgaoUnidade();
      $parObjRelHierarquiaUnidadeDTO->retStrSiglaOrgaoUnidade();
      $parObjRelHierarquiaUnidadeDTO->retStrDescricaoOrgaoUnidade();
      $parObjRelHierarquiaUnidadeDTO->retNumIdHierarquiaPai();
      $parObjRelHierarquiaUnidadeDTO->retNumIdUnidadePai();
      $parObjRelHierarquiaUnidadeDTO->retStrSiglaUnidade();
      $parObjRelHierarquiaUnidadeDTO->retStrDescricaoUnidade();
      $parObjRelHierarquiaUnidadeDTO->retStrSinAtivo();

      if (count($arrUnidadesRamificacao)) {
        $parObjRelHierarquiaUnidadeDTO->setNumIdUnidade($arrUnidadesRamificacao, InfraDTO::$OPER_IN);
      } else {
        $parObjRelHierarquiaUnidadeDTO->unSetNumIdUnidade();
      }

      $parObjRelHierarquiaUnidadeDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);

      $numPaginaAtual = $parObjRelHierarquiaUnidadeDTO->getNumPaginaAtual();
      $numMaxRegistrosRetorno = $parObjRelHierarquiaUnidadeDTO->getNumMaxRegistrosRetorno();

      if ($numPaginaAtual !== null && $numMaxRegistrosRetorno !== null) {
        $parObjRelHierarquiaUnidadeDTO->setNumPaginaAtual(null);
        $parObjRelHierarquiaUnidadeDTO->setNumMaxRegistrosRetorno(null);
      }

      $arrObjRelHierarquiaUnidadeDTO = InfraArray::indexarArrInfraDTO($objRelHierarquiaUnidadeBD->listar($parObjRelHierarquiaUnidadeDTO), 'IdUnidade');

      $arrUnidadesInferiores = array();

      foreach ($arrObjRelHierarquiaUnidadeDTO as $objRelHierarquiaUnidadeDTO) {
        if ($bolRetInferiores) {
          $objRelHierarquiaUnidadeDTO->setArrUnidadesInferiores(array());
        }

        $numIdUnidadePai = $objRelHierarquiaUnidadeDTO->getNumIdUnidadePai();

        $arrUnidadesProcessadas = array($numIdUnidadePai => true);

        $arrUnidadesSuperiores = array();

        $strRamificacao = '';

        //Enquanto tiver pai armazena unidades superiores
        while ($numIdUnidadePai != null) {
          if ($bolRetInferiores) {
            $arrUnidadesInferiores[$numIdUnidadePai][] = $objRelHierarquiaUnidadeDTO;
          }

          $objPai = $arrObjRelHierarquiaUnidadeDTO[$numIdUnidadePai];

          if ($objPai == null) {
            throw new InfraException('Unidade superior [' . $numIdUnidadePai . '] não encontrada na hierarquia.');
          }

          if ($bolRetSuperiores) {
            $arrUnidadesSuperiores[] = $objPai;
          }

          if ($bolRetRamificacao) {
            $strRamificacao = $objPai->getStrSiglaUnidade() . ' / ' . $strRamificacao;
          }

          $numIdUnidadePai = $objPai->getNumIdUnidadePai();

          if ($numIdUnidadePai != null) {
            if (isset($arrUnidadesProcessadas[$numIdUnidadePai])) {
              throw new InfraException('Referência circular na hierarquia envolvendo a unidade ' . $objPai->getNumIdUnidadePai() . '.');
            }

            $arrUnidadesProcessadas[$numIdUnidadePai] = true;
          }
        }

        if ($bolRetRamificacao) {
          $objRelHierarquiaUnidadeDTO->setStrRamificacao($strRamificacao . $objRelHierarquiaUnidadeDTO->getStrSiglaUnidade());
        }

        if ($bolRetSuperiores) {
          if (count($arrUnidadesSuperiores)) {
            $objRelHierarquiaUnidadeDTO->setArrUnidadesSuperiores(array_reverse($arrUnidadesSuperiores));
          } else {
            $objRelHierarquiaUnidadeDTO->setArrUnidadesSuperiores(array());
          }
        }
      }

      if ($bolRetInferiores) {
        foreach ($arrUnidadesInferiores as $numIdUnidade => $arrSubUnidades) {
          $arrObjRelHierarquiaUnidadeDTO[$numIdUnidade]->setArrUnidadesInferiores($arrSubUnidades);
        }
      }

      unset($arrUnidadesInferiores);

      $arrObjRelHierarquiaUnidadeDTO = array_values($arrObjRelHierarquiaUnidadeDTO);

      if ($parObjRelHierarquiaUnidadeDTO->isSetStrRamificacao() && !InfraString::isBolVazia($parObjRelHierarquiaUnidadeDTO->getStrRamificacao())) {
        $strRamificacao = $parObjRelHierarquiaUnidadeDTO->getStrRamificacao();
        $strRamificacao = str_replace('/', ' / ', $strRamificacao);
        $strRamificacao = str_replace('  ', ' ', $strRamificacao);
        $strRamificacao = InfraString::transformarCaixaAlta($strRamificacao);

        $arrTemp = array();
        foreach ($arrObjRelHierarquiaUnidadeDTO as $objRelHierarquiaUnidadeDTO) {
          if (strpos(InfraString::transformarCaixaAlta($objRelHierarquiaUnidadeDTO->getStrRamificacao()), $strRamificacao) !== false) {
            $arrTemp[] = $objRelHierarquiaUnidadeDTO;
          }
        }
        $arrObjRelHierarquiaUnidadeDTO = $arrTemp;
      }

      if ($numPaginaAtual !== null && $numMaxRegistrosRetorno !== null) {
        if ($parObjRelHierarquiaUnidadeDTO->isOrdStrRamificacao()) {
          InfraArray::ordenarArrInfraDTO($arrObjRelHierarquiaUnidadeDTO, 'Ramificacao', $parObjRelHierarquiaUnidadeDTO->getOrdStrRamificacao());
        }

        $numTotalRegistros = count($arrObjRelHierarquiaUnidadeDTO);
        $arrObjRelHierarquiaUnidadeDTO = array_slice($arrObjRelHierarquiaUnidadeDTO, $numPaginaAtual * $numMaxRegistrosRetorno, $numMaxRegistrosRetorno);
        $parObjRelHierarquiaUnidadeDTO->setNumTotalRegistros($numTotalRegistros);
        $parObjRelHierarquiaUnidadeDTO->setNumRegistrosPaginaAtual(count($arrObjRelHierarquiaUnidadeDTO));
      }

      //InfraDebug::getInstance()->gravar('#'.InfraUtil::verificarTempoProcessamento($numSeg).' s');
      //InfraDebug::getInstance()->gravar('#'.InfraUtil::formatarTamanhoBytes(memory_get_usage()));

      return $arrObjRelHierarquiaUnidadeDTO;
    } catch (Exception $e) {
      throw new InfraException('Erro listando hierarquia.', $e);
    }
  }


  private function validarNumIdUnidade(
    RelHierarquiaUnidadeDTO $objRelHierarquiaUnidadeDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objRelHierarquiaUnidadeDTO->getNumIdUnidade())) {
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  private function validarNumIdHierarquia(
    RelHierarquiaUnidadeDTO $objRelHierarquiaUnidadeDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objRelHierarquiaUnidadeDTO->getNumIdHierarquia())) {
      $objInfraException->adicionarValidacao('Hierarquia não informada.');
    }
  }

  private function validarDtaDataInicio(
    RelHierarquiaUnidadeDTO $objRelHierarquiaUnidadeDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objRelHierarquiaUnidadeDTO->getDtaDataInicio())) {
      $objInfraException->adicionarValidacao('Data Inicial não informada.');
    }

    if (!InfraData::validarData($objRelHierarquiaUnidadeDTO->getDtaDataInicio())) {
      $objInfraException->adicionarValidacao('Data Inicial inválida.');
    }

    //if (InfraData::compararDatas(InfraData::getStrDataAtual(),$objRelHierarquiaUnidadeDTO->getDtaDataInicio())<0){
    //	$objInfraException->adicionarValidacao('Data Inicial não pode estar no passado.');
    //}
  }

  private function validarDtaDataFim(
    RelHierarquiaUnidadeDTO $objRelHierarquiaUnidadeDTO, InfraException $objInfraException) {
    if (!InfraData::validarData($objRelHierarquiaUnidadeDTO->getDtaDataFim())) {
      $objInfraException->adicionarValidacao('Data Final inválida.');
    }

    if (InfraData::compararDatas(InfraData::getStrDataAtual(), $objRelHierarquiaUnidadeDTO->getDtaDataFim()) < 0) {
      $objInfraException->adicionarValidacao('Data Final não pode estar no passado.');
    }
  }

  private function validarPeriodoDatas(
    RelHierarquiaUnidadeDTO $objRelHierarquiaUnidadeDTO, InfraException $objInfraException) {
    if (InfraData::compararDatas($objRelHierarquiaUnidadeDTO->getDtaDataInicio(), $objRelHierarquiaUnidadeDTO->getDtaDataFim()) < 0) {
      $objInfraException->adicionarValidacao('Data Final deve ser igual ou superior a Data Inicial.');
    }
  }

  private function validarStrSinAtivo(
    RelHierarquiaUnidadeDTO $objRelHierarquiaUnidadeDTO, InfraException $objInfraException) {
    if ($objRelHierarquiaUnidadeDTO->getStrSinAtivo() === null || ($objRelHierarquiaUnidadeDTO->getStrSinAtivo() !== 'S' && $objRelHierarquiaUnidadeDTO->getStrSinAtivo() !== 'N')) {
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
    }
  }

  protected function listarUnidadesNovasConectado(RelHierarquiaUnidadeDTO $objRelHierarquiaUnidadeDTO) {
    try {
      //Valida Permissao
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('rel_hierarquia_unidade_listar',__METHOD__,$objRelHierarquiaUnidadeDTO);
      /////////////////////////////////////////////////////////////////

      $objRelHierarquiaUnidadeBD = new RelHierarquiaUnidadeBD($this->getObjInfraIBanco());
      return $objRelHierarquiaUnidadeBD->listarUnidadesNovas($objRelHierarquiaUnidadeDTO);
    } catch (Exception $e) {
      throw new InfraException('Erro listando unidades novas para hierarquia.', $e);
    }
  }
}

?>