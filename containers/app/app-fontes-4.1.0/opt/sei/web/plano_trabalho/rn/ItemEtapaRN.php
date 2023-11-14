<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 23/09/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../../SEI.php';

class ItemEtapaRN extends InfraRN {

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSEI::getInstance();
  }

  private function validarNumIdEtapaTrabalho(ItemEtapaDTO $objItemEtapaDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objItemEtapaDTO->getNumIdEtapaTrabalho())) {
      $objInfraException->adicionarValidacao('Etapa de Trabalho não informada.');
    }
  }

  private function validarStrNome(ItemEtapaDTO $objItemEtapaDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objItemEtapaDTO->getStrNome())) {
      $objInfraException->adicionarValidacao('Nome não informado.');
    } else {
      $objItemEtapaDTO->setStrNome(trim($objItemEtapaDTO->getStrNome()));

      if (strlen($objItemEtapaDTO->getStrNome()) > 100) {
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 100 caracteres.');
      }

      $dto = new ItemEtapaDTO();
      $dto->setBolExclusaoLogica(false);
      $dto->retNumIdItemEtapa();
      $dto->setStrNome($objItemEtapaDTO->getStrNome());
      $dto->setNumIdEtapaTrabalho($objItemEtapaDTO->getNumIdEtapaTrabalho());
      $dto->setNumIdItemEtapa($objItemEtapaDTO->getNumIdItemEtapa(), InfraDTO::$OPER_DIFERENTE);
      if ($this->consultar($dto) != null) {
        $objInfraException->adicionarValidacao('Existe outro Item na Etapa com o mesmo Nome.');
      }
    }
  }

  private function validarStrDescricao(ItemEtapaDTO $objItemEtapaDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objItemEtapaDTO->getStrDescricao())) {
      $objItemEtapaDTO->setStrDescricao(null);
    } else {
      $objItemEtapaDTO->setStrDescricao(trim($objItemEtapaDTO->getStrDescricao()));

      if (strlen($objItemEtapaDTO->getStrDescricao()) > 4000) {
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 4000 caracteres.');
      }
    }
  }


  private function validarNumOrdem(ItemEtapaDTO $objItemEtapaDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objItemEtapaDTO->getNumOrdem())) {
      $objInfraException->adicionarValidacao('Ordem não informada.');
    }
  }

  private function validarStrSinAtivo(ItemEtapaDTO $objItemEtapaDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objItemEtapaDTO->getStrSinAtivo())) {
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    } else {
      if (!InfraUtil::isBolSinalizadorValido($objItemEtapaDTO->getStrSinAtivo())) {
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(ItemEtapaDTO $objItemEtapaDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('item_etapa_cadastrar', __METHOD__, $objItemEtapaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdEtapaTrabalho($objItemEtapaDTO, $objInfraException);
      $this->validarStrNome($objItemEtapaDTO, $objInfraException);
      $this->validarStrDescricao($objItemEtapaDTO, $objInfraException);
      $this->validarNumOrdem($objItemEtapaDTO, $objInfraException);
      $this->validarStrSinAtivo($objItemEtapaDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objItemEtapaBD = new ItemEtapaBD($this->getObjInfraIBanco());
      $ret = $objItemEtapaBD->cadastrar($objItemEtapaDTO);

      if ($objItemEtapaDTO->isSetArrObjRelItemEtapaUnidadeDTO()) {
        $arrObjRelItemEtapaUnidadeDTO = $objItemEtapaDTO->getArrObjRelItemEtapaUnidadeDTO();
        $objRelItemEtapaUnidadeRN = new RelItemEtapaUnidadeRN();
        foreach ($arrObjRelItemEtapaUnidadeDTO as $objRelItemEtapaUnidadeDTO) {
          $objRelItemEtapaUnidadeDTO->setNumIdItemEtapa($ret->getNumIdItemEtapa());
          $objRelItemEtapaUnidadeRN->cadastrar($objRelItemEtapaUnidadeDTO);
        }
      }

      if ($objItemEtapaDTO->isSetArrObjRelItemEtapaSerieDTO()) {
        $arrObjRelItemEtapaSerieDTO = $objItemEtapaDTO->getArrObjRelItemEtapaSerieDTO();
        $objRelItemEtapaSerieRN = new RelItemEtapaSerieRN();
        foreach ($arrObjRelItemEtapaSerieDTO as $objRelItemEtapaSerieDTO) {
          $objRelItemEtapaSerieDTO->setNumIdItemEtapa($ret->getNumIdItemEtapa());
          $objRelItemEtapaSerieRN->cadastrar($objRelItemEtapaSerieDTO);
        }
      }

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro cadastrando Item da Etapa.', $e);
    }
  }

  protected function alterarControlado(ItemEtapaDTO $objItemEtapaDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('item_etapa_alterar', __METHOD__, $objItemEtapaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objItemEtapaDTO->isSetNumIdEtapaTrabalho()) {
        $this->validarNumIdEtapaTrabalho($objItemEtapaDTO, $objInfraException);
      }
      if ($objItemEtapaDTO->isSetStrNome()) {
        $this->validarStrNome($objItemEtapaDTO, $objInfraException);
      }
      if ($objItemEtapaDTO->isSetStrDescricao()) {
        $this->validarStrDescricao($objItemEtapaDTO, $objInfraException);
      }
      if ($objItemEtapaDTO->isSetNumOrdem()) {
        $this->validarNumOrdem($objItemEtapaDTO, $objInfraException);
      }
      if ($objItemEtapaDTO->isSetStrSinAtivo()) {
        $this->validarStrSinAtivo($objItemEtapaDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objItemEtapaBD = new ItemEtapaBD($this->getObjInfraIBanco());
      $objItemEtapaBD->alterar($objItemEtapaDTO);

      if ($objItemEtapaDTO->isSetArrObjRelItemEtapaUnidadeDTO()) {
        $objRelItemEtapaUnidadeDTO = new RelItemEtapaUnidadeDTO();
        $objRelItemEtapaUnidadeDTO->retNumIdItemEtapa();
        $objRelItemEtapaUnidadeDTO->retNumIdUnidade();
        $objRelItemEtapaUnidadeDTO->setNumIdItemEtapa($objItemEtapaDTO->getNumIdItemEtapa());

        $objRelItemEtapaUnidadeRN = new RelItemEtapaUnidadeRN();
        $objRelItemEtapaUnidadeRN->excluir($objRelItemEtapaUnidadeRN->listar($objRelItemEtapaUnidadeDTO));

        $arrObjRelItemEtapaUnidadeDTO = $objItemEtapaDTO->getArrObjRelItemEtapaUnidadeDTO();

        foreach ($arrObjRelItemEtapaUnidadeDTO as $objRelItemEtapaUnidadeDTO) {
          $objRelItemEtapaUnidadeDTO->setNumIdItemEtapa($objItemEtapaDTO->getNumIdItemEtapa());
          $objRelItemEtapaUnidadeRN->cadastrar($objRelItemEtapaUnidadeDTO);
        }
      }

      if ($objItemEtapaDTO->isSetArrObjRelItemEtapaSerieDTO()) {
        $objRelItemEtapaSerieDTO = new RelItemEtapaSerieDTO();
        $objRelItemEtapaSerieDTO->retNumIdItemEtapa();
        $objRelItemEtapaSerieDTO->retNumIdSerie();
        $objRelItemEtapaSerieDTO->setNumIdItemEtapa($objItemEtapaDTO->getNumIdItemEtapa());

        $objRelItemEtapaSerieRN = new RelItemEtapaSerieRN();
        $objRelItemEtapaSerieRN->excluir($objRelItemEtapaSerieRN->listar($objRelItemEtapaSerieDTO));

        $arrObjRelItemEtapaSerieDTO = $objItemEtapaDTO->getArrObjRelItemEtapaSerieDTO();

        foreach ($arrObjRelItemEtapaSerieDTO as $objRelItemEtapaSerieDTO) {
          $objRelItemEtapaSerieDTO->setNumIdItemEtapa($objItemEtapaDTO->getNumIdItemEtapa());
          $objRelItemEtapaSerieRN->cadastrar($objRelItemEtapaSerieDTO);
        }
      }
    } catch (Exception $e) {
      throw new InfraException('Erro alterando Item da Etapa.', $e);
    }
  }

  protected function excluirControlado($arrObjItemEtapaDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('item_etapa_excluir', __METHOD__, $arrObjItemEtapaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if (count($arrObjItemEtapaDTO)) {
        $objItemEtapaDTO = new ItemEtapaDTO();
        $objItemEtapaDTO->setBolExclusaoLogica(false);
        $objItemEtapaDTO->retNumIdItemEtapa();
        $objItemEtapaDTO->retStrNome();
        $objItemEtapaDTO->setNumIdItemEtapa(InfraArray::converterArrInfraDTO($arrObjItemEtapaDTO, 'IdItemEtapa'), InfraDTO::$OPER_IN);
        $arrObjItemEtapaDTOBanco = InfraArray::indexarArrInfraDTO($this->listar($objItemEtapaDTO), 'IdItemEtapa');

        $objAtributoAndamPlanoTrabRN = new AtributoAndamPlanoTrabRN();
        $objRelItemEtapaDocumentoRN = new RelItemEtapaDocumentoRN();

        $arrAndamentos = array();
        $arrDocumentos = array();

        foreach ($arrObjItemEtapaDTO as $objItemEtapaDTO) {
          $objAtributoAndamPlanoTrabDTO = new AtributoAndamPlanoTrabDTO();
          $objAtributoAndamPlanoTrabDTO->setNumMaxRegistrosRetorno(1);
          $objAtributoAndamPlanoTrabDTO->retNumIdAtributoAndamPlanoTrab();
          $objAtributoAndamPlanoTrabDTO->setStrChave('ITEM_ETAPA');
          $objAtributoAndamPlanoTrabDTO->setStrIdOrigem($objItemEtapaDTO->getNumIdItemEtapa());

          if (($objAtributoAndamPlanoTrabDTO = $objAtributoAndamPlanoTrabRN->consultar($objAtributoAndamPlanoTrabDTO)) != null) {
            $arrAndamentos[] = $arrObjItemEtapaDTOBanco[$objItemEtapaDTO->getNumIdItemEtapa()]->getStrNome();
          }

          $objRelItemEtapaDocumentoDTO = new RelItemEtapaDocumentoDTO();
          $objRelItemEtapaDocumentoDTO->setNumMaxRegistrosRetorno(1);
          $objRelItemEtapaDocumentoDTO->retDblIdDocumento();
          $objRelItemEtapaDocumentoDTO->setNumIdItemEtapa($objItemEtapaDTO->getNumIdItemEtapa());
          if (($objRelItemEtapaDocumentoDTO = $objRelItemEtapaDocumentoRN->consultar($objRelItemEtapaDocumentoDTO)) != null) {
            $arrDocumentos[] = $arrObjItemEtapaDTOBanco[$objItemEtapaDTO->getNumIdItemEtapa()]->getStrNome();
          }
        }

        if (count($arrAndamentos)) {
          $objInfraException->adicionarValidacao('Existem andamentos associados com os Itens: ' . InfraString::formatarArray($arrAndamentos) . '.');
        }

        if (count($arrDocumentos)) {
          $objInfraException->adicionarValidacao('Existem documentos associados com os Itens: ' . InfraString::formatarArray($arrDocumentos) . '.');
        }

        $objInfraException->lancarValidacoes();

        $objRelItemEtapaUnidadeRN = new RelItemEtapaUnidadeRN();
        $objRelItemEtapaSerieRN = new RelItemEtapaSerieRN();

        $objItemEtapaBD = new ItemEtapaBD($this->getObjInfraIBanco());
        for ($i = 0; $i < count($arrObjItemEtapaDTO); $i++) {
          $objRelItemEtapaUnidadeDTO = new RelItemEtapaUnidadeDTO();
          $objRelItemEtapaUnidadeDTO->retNumIdItemEtapa();
          $objRelItemEtapaUnidadeDTO->retNumIdUnidade();
          $objRelItemEtapaUnidadeDTO->setNumIdItemEtapa($arrObjItemEtapaDTO[$i]->getNumIdItemEtapa());
          $objRelItemEtapaUnidadeRN->excluir($objRelItemEtapaUnidadeRN->listar($objRelItemEtapaUnidadeDTO));

          $objRelItemEtapaSerieDTO = new RelItemEtapaSerieDTO();
          $objRelItemEtapaSerieDTO->retNumIdItemEtapa();
          $objRelItemEtapaSerieDTO->retNumIdSerie();
          $objRelItemEtapaSerieDTO->setNumIdItemEtapa($arrObjItemEtapaDTO[$i]->getNumIdItemEtapa());
          $objRelItemEtapaSerieRN->excluir($objRelItemEtapaSerieRN->listar($objRelItemEtapaSerieDTO));

          $objItemEtapaBD->excluir($arrObjItemEtapaDTO[$i]);
        }
      }
    } catch (Exception $e) {
      throw new InfraException('Erro excluindo Item da Etapa.', $e);
    }
  }

  protected function consultarConectado(ItemEtapaDTO $objItemEtapaDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('item_etapa_consultar', __METHOD__, $objItemEtapaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objItemEtapaBD = new ItemEtapaBD($this->getObjInfraIBanco());
      $ret = $objItemEtapaBD->consultar($objItemEtapaDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando Item da Etapa.', $e);
    }
  }

  protected function listarConectado(ItemEtapaDTO $objItemEtapaDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('item_etapa_listar', __METHOD__, $objItemEtapaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objItemEtapaBD = new ItemEtapaBD($this->getObjInfraIBanco());
      $ret = $objItemEtapaBD->listar($objItemEtapaDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando Itens da Etapa.', $e);
    }
  }

  protected function contarConectado(ItemEtapaDTO $objItemEtapaDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('item_etapa_listar', __METHOD__, $objItemEtapaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objItemEtapaBD = new ItemEtapaBD($this->getObjInfraIBanco());
      $ret = $objItemEtapaBD->contar($objItemEtapaDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando Itens da Etapa.', $e);
    }
  }

  protected function desativarControlado($arrObjItemEtapaDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('item_etapa_desativar', __METHOD__, $arrObjItemEtapaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objItemEtapaBD = new ItemEtapaBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjItemEtapaDTO); $i++) {
        $objItemEtapaBD->desativar($arrObjItemEtapaDTO[$i]);
      }
    } catch (Exception $e) {
      throw new InfraException('Erro desativando Item da Etapa.', $e);
    }
  }

  protected function reativarControlado($arrObjItemEtapaDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('item_etapa_reativar', __METHOD__, $arrObjItemEtapaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objItemEtapaBD = new ItemEtapaBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjItemEtapaDTO); $i++) {
        $objItemEtapaBD->reativar($arrObjItemEtapaDTO[$i]);
      }
    } catch (Exception $e) {
      throw new InfraException('Erro reativando Item da Etapa.', $e);
    }
  }

  protected function bloquearControlado(ItemEtapaDTO $objItemEtapaDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('item_etapa_consultar', __METHOD__, $objItemEtapaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objItemEtapaBD = new ItemEtapaBD($this->getObjInfraIBanco());
      $ret = $objItemEtapaBD->bloquear($objItemEtapaDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro bloqueando Item da Etapa.', $e);
    }
  }


}
