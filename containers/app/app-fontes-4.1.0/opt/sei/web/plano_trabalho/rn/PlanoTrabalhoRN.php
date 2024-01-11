<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 22/09/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../../SEI.php';

class PlanoTrabalhoRN extends InfraRN {

  public static $TH_TOTAL = 'T';
  public static $TH_PLANO_TRABALHO = 'P';
  public static $TH_ANDAMENTO_ITEM_ETAPA = 'I';
  public static $TH_PLANO_DETALHAR = 'D';

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSEI::getInstance();
  }

  private function validarStrNome(PlanoTrabalhoDTO $objPlanoTrabalhoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objPlanoTrabalhoDTO->getStrNome())) {
      $objInfraException->adicionarValidacao('Nome não informado.');
    } else {
      $objPlanoTrabalhoDTO->setStrNome(trim($objPlanoTrabalhoDTO->getStrNome()));

      if (strlen($objPlanoTrabalhoDTO->getStrNome()) > 100) {
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 100 caracteres.');
      }

      $dto = new PlanoTrabalhoDTO();
      $dto->retStrSinAtivo();
      $dto->setNumIdPlanoTrabalho($objPlanoTrabalhoDTO->getNumIdPlanoTrabalho(), InfraDTO::$OPER_DIFERENTE);
      $dto->setStrNome($objPlanoTrabalhoDTO->getStrNome(), InfraDTO::$OPER_IGUAL);
      $dto->setBolExclusaoLogica(false);

      $dto = $this->consultar($dto);
      if ($dto != null) {
        if ($dto->getStrSinAtivo() == 'S') {
          $objInfraException->adicionarValidacao('Existe outra ocorrência de Plano de Trabalho que utiliza o mesmo Nome.');
        } else {
          $objInfraException->adicionarValidacao('Existe ocorrência inativa de Plano de Trabalho que utiliza o mesmo Nome.');
        }
      }
    }
  }

  private function validarStrDescricao(PlanoTrabalhoDTO $objPlanoTrabalhoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objPlanoTrabalhoDTO->getStrDescricao())) {
      $objPlanoTrabalhoDTO->setStrDescricao(null);
    } else {
      $objPlanoTrabalhoDTO->setStrDescricao(trim($objPlanoTrabalhoDTO->getStrDescricao()));

      if (strlen($objPlanoTrabalhoDTO->getStrDescricao()) > 4000) {
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 4000 caracteres.');
      }
    }
  }

  private function validarStrSinAtivo(PlanoTrabalhoDTO $objPlanoTrabalhoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objPlanoTrabalhoDTO->getStrSinAtivo())) {
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    } else {
      if (!InfraUtil::isBolSinalizadorValido($objPlanoTrabalhoDTO->getStrSinAtivo())) {
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(PlanoTrabalhoDTO $objPlanoTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('plano_trabalho_cadastrar', __METHOD__, $objPlanoTrabalhoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objPlanoTrabalhoDTO, $objInfraException);
      $this->validarStrDescricao($objPlanoTrabalhoDTO, $objInfraException);
      $this->validarStrSinAtivo($objPlanoTrabalhoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objPlanoTrabalhoBD = new PlanoTrabalhoBD($this->getObjInfraIBanco());
      $ret = $objPlanoTrabalhoBD->cadastrar($objPlanoTrabalhoDTO);

      if ($objPlanoTrabalhoDTO->isSetArrObjTipoProcedimentoDTO()) {
        $arrObjTipoProcedimentoDTO = $objPlanoTrabalhoDTO->getArrObjTipoProcedimentoDTO();
        $objTipoProcedimentoRN = new TipoProcedimentoRN();
        foreach ($arrObjTipoProcedimentoDTO as $objTipoProcedimentoDTO) {
          $objTipoProcedimentoDTO->setNumIdPlanoTrabalho($ret->getNumIdPlanoTrabalho());
          $objTipoProcedimentoRN->alterarRN0266($objTipoProcedimentoDTO);
        }
      }

      if ($objPlanoTrabalhoDTO->isSetArrObjRelSeriePlanoTrabalhoDTO()) {
        $arrObjRelSeriePlanoTrabalhoDTO = $objPlanoTrabalhoDTO->getArrObjRelSeriePlanoTrabalhoDTO();
        $objRelSeriePlanoTrabalhoRN = new RelSeriePlanoTrabalhoRN();
        foreach ($arrObjRelSeriePlanoTrabalhoDTO as $objRelSeriePlanoTrabalhoDTO) {
          $objRelSeriePlanoTrabalhoDTO->setNumIdPlanoTrabalho($ret->getNumIdPlanoTrabalho());
          $objRelSeriePlanoTrabalhoRN->cadastrar($objRelSeriePlanoTrabalhoDTO);
        }
      }

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro cadastrando Plano de Trabalho.', $e);
    }
  }

  protected function alterarControlado(PlanoTrabalhoDTO $objPlanoTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('plano_trabalho_alterar', __METHOD__, $objPlanoTrabalhoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objPlanoTrabalhoDTO->isSetStrNome()) {
        $this->validarStrNome($objPlanoTrabalhoDTO, $objInfraException);
      }
      if ($objPlanoTrabalhoDTO->isSetStrDescricao()) {
        $this->validarStrDescricao($objPlanoTrabalhoDTO, $objInfraException);
      }
      if ($objPlanoTrabalhoDTO->isSetStrSinAtivo()) {
        $this->validarStrSinAtivo($objPlanoTrabalhoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objPlanoTrabalhoBD = new PlanoTrabalhoBD($this->getObjInfraIBanco());
      $objPlanoTrabalhoBD->alterar($objPlanoTrabalhoDTO);

      if ($objPlanoTrabalhoDTO->isSetArrObjTipoProcedimentoDTO()) {

        $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
        $objTipoProcedimentoDTO->setBolExclusaoLogica(false);
        $objTipoProcedimentoDTO->retNumIdTipoProcedimento();
        $objTipoProcedimentoDTO->setNumIdPlanoTrabalho($objPlanoTrabalhoDTO->getNumIdPlanoTrabalho());

        $objTipoProcedimentoRN = new TipoProcedimentoRN();
        $arrObjTipoProcedimentoDTOAntigos = $objTipoProcedimentoRN->listarRN0244($objTipoProcedimentoDTO);

        foreach($arrObjTipoProcedimentoDTOAntigos as $objTipoProcedimentoDTO){
          $objTipoProcedimentoDTO->setNumIdPlanoTrabalho(null);
          $objTipoProcedimentoRN->alterarRN0266($objTipoProcedimentoDTO);
        }

        $arrObjTipoProcedimentoDTONovos = $objPlanoTrabalhoDTO->getArrObjTipoProcedimentoDTO();

        foreach ($arrObjTipoProcedimentoDTONovos as $objTipoProcedimentoDTO) {
          $objTipoProcedimentoDTO->setNumIdPlanoTrabalho($objPlanoTrabalhoDTO->getNumIdPlanoTrabalho());
          $objTipoProcedimentoRN->alterarRN0266($objTipoProcedimentoDTO);
        }
      }

      if ($objPlanoTrabalhoDTO->isSetArrObjRelSeriePlanoTrabalhoDTO()) {
        $objRelSeriePlanoTrabalhoDTO = new RelSeriePlanoTrabalhoDTO();
        $objRelSeriePlanoTrabalhoDTO->retNumIdPlanoTrabalho();
        $objRelSeriePlanoTrabalhoDTO->retNumIdSerie();
        $objRelSeriePlanoTrabalhoDTO->setNumIdPlanoTrabalho($objPlanoTrabalhoDTO->getNumIdPlanoTrabalho());

        $objRelSeriePlanoTrabalhoRN = new RelSeriePlanoTrabalhoRN();
        $objRelSeriePlanoTrabalhoRN->excluir($objRelSeriePlanoTrabalhoRN->listar($objRelSeriePlanoTrabalhoDTO));

        $arrObjRelSeriePlanoTrabalhoDTO = $objPlanoTrabalhoDTO->getArrObjRelSeriePlanoTrabalhoDTO();

        foreach ($arrObjRelSeriePlanoTrabalhoDTO as $objRelSeriePlanoTrabalhoDTO) {
          $objRelSeriePlanoTrabalhoDTO->setNumIdPlanoTrabalho($objPlanoTrabalhoDTO->getNumIdPlanoTrabalho());
          $objRelSeriePlanoTrabalhoRN->cadastrar($objRelSeriePlanoTrabalhoDTO);
        }
      }
    } catch (Exception $e) {
      throw new InfraException('Erro alterando Plano de Trabalho.', $e);
    }
  }

  protected function excluirControlado($arrObjPlanoTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('plano_trabalho_excluir', __METHOD__, $arrObjPlanoTrabalhoDTO);

      //Regras de Negocio

      $objInfraException = new InfraException();

      if (count($arrObjPlanoTrabalhoDTO)) {
        $objAndamentoPlanoTrabalhoRN = new AndamentoPlanoTrabalhoRN();
        $objRelItemEtapaDocumentoRN = new RelItemEtapaDocumentoRN();

        $arrAndamentos = array();
        $arrDocumentos = array();

        foreach ($arrObjPlanoTrabalhoDTO as $objPlanoTrabalhoDTO) {
          $objAndamentoPlanoTrabalhoDTO = new AndamentoPlanoTrabalhoDTO();
          $objAndamentoPlanoTrabalhoDTO->setNumMaxRegistrosRetorno(1);
          $objAndamentoPlanoTrabalhoDTO->retStrNomePlanoTrabalho();
          $objAndamentoPlanoTrabalhoDTO->setNumIdPlanoTrabalho($objPlanoTrabalhoDTO->getNumIdPlanoTrabalho());
          if (($objAndamentoPlanoTrabalhoDTO = $objAndamentoPlanoTrabalhoRN->consultar($objAndamentoPlanoTrabalhoDTO)) != null) {
            $arrAndamentos[] = $objAndamentoPlanoTrabalhoDTO->getStrNomePlanoTrabalho();
          }

          $objRelItemEtapaDocumentoDTO = new RelItemEtapaDocumentoDTO();
          $objRelItemEtapaDocumentoDTO->setNumMaxRegistrosRetorno(1);
          $objRelItemEtapaDocumentoDTO->retStrNomePlanoTrabalho();
          $objRelItemEtapaDocumentoDTO->setNumIdPlanoTrabalhoEtapaTrabalho($objPlanoTrabalhoDTO->getNumIdPlanoTrabalho());
          if (($objRelItemEtapaDocumentoDTO = $objRelItemEtapaDocumentoRN->consultar($objRelItemEtapaDocumentoDTO)) != null) {
            $arrDocumentos[] = $objRelItemEtapaDocumentoDTO->getStrNomePlanoTrabalho();
          }
        }

        if (count($arrAndamentos)) {
          $objInfraException->adicionarValidacao('Existem andamentos de itens associados com os Planos de Trabalho: ' . InfraString::formatarArray($arrAndamentos) . '.');
        }

        if (count($arrDocumentos)) {
          $objInfraException->adicionarValidacao('Existem documentos de itens associados com os Planos de Trabalho: ' . InfraString::formatarArray($arrDocumentos) . '.');
        }

        $objInfraException->lancarValidacoes();

        $objTipoProcedimentoRN = new TipoProcedimentoRN();
        $objRelSeriePlanoTrabalhoRN = new RelSeriePlanoTrabalhoRN();
        $objProcedimentoRN = new ProcedimentoRN();
        $objEtapaTrabalhoRN = new EtapaTrabalhoRN();

        $objPlanoTrabalhoBD = new PlanoTrabalhoBD($this->getObjInfraIBanco());
        for ($i = 0; $i < count($arrObjPlanoTrabalhoDTO); $i++) {

          $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
          $objTipoProcedimentoDTO->setBolExclusaoLogica(false);
          $objTipoProcedimentoDTO->retNumIdTipoProcedimento();
          $objTipoProcedimentoDTO->setNumIdPlanoTrabalho($arrObjPlanoTrabalhoDTO[$i]->getNumIdPlanoTrabalho());
          $arrObjTipoProcedimentoDTO = $objTipoProcedimentoRN->listarRN0244($objTipoProcedimentoDTO);
          foreach($arrObjTipoProcedimentoDTO as $objTipoProcedimentoDTO){
            $objTipoProcedimentoDTO->setNumIdPlanoTrabalho(null);
            $objTipoProcedimentoRN->alterarRN0266($objTipoProcedimentoDTO);
          }

          $objRelSeriePlanoTrabalhoDTO = new RelSeriePlanoTrabalhoDTO();
          $objRelSeriePlanoTrabalhoDTO->retNumIdSerie();
          $objRelSeriePlanoTrabalhoDTO->retNumIdPlanoTrabalho();
          $objRelSeriePlanoTrabalhoDTO->setNumIdPlanoTrabalho($arrObjPlanoTrabalhoDTO[$i]->getNumIdPlanoTrabalho());
          $objRelSeriePlanoTrabalhoRN->excluir($objRelSeriePlanoTrabalhoRN->listar($objRelSeriePlanoTrabalhoDTO));

          $objProcedimentoDTO = new ProcedimentoDTO();
          $objProcedimentoDTO->retDblIdProcedimento();
          $objProcedimentoDTO->setNumIdPlanoTrabalho($arrObjPlanoTrabalhoDTO[$i]->getNumIdPlanoTrabalho());
          $arrObjProcedimentoDTO = $objProcedimentoRN->listarRN0278($objProcedimentoDTO);
          foreach($arrObjProcedimentoDTO as $objProcedimentoDTO){
            $objProcedimentoDTO->setNumIdPlanoTrabalho(null);
            $objProcedimentoRN->alterarRN0202($objProcedimentoDTO);
          }

          $objEtapaTrabalhoDTO = new EtapaTrabalhoDTO();
          $objEtapaTrabalhoDTO->retNumIdEtapaTrabalho();
          $objEtapaTrabalhoDTO->setNumIdPlanoTrabalho($arrObjPlanoTrabalhoDTO[$i]->getNumIdPlanoTrabalho());
          $objEtapaTrabalhoRN->excluir($objEtapaTrabalhoRN->listar($objEtapaTrabalhoDTO));

          $objPlanoTrabalhoBD->excluir($arrObjPlanoTrabalhoDTO[$i]);
        }
      }
    } catch (Exception $e) {
      throw new InfraException('Erro excluindo Plano de Trabalho.', $e);
    }
  }

  protected function consultarConectado(PlanoTrabalhoDTO $objPlanoTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('plano_trabalho_consultar', __METHOD__, $objPlanoTrabalhoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPlanoTrabalhoBD = new PlanoTrabalhoBD($this->getObjInfraIBanco());
      $ret = $objPlanoTrabalhoBD->consultar($objPlanoTrabalhoDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando Plano de Trabalho.', $e);
    }
  }

  protected function listarConectado(PlanoTrabalhoDTO $objPlanoTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('plano_trabalho_listar', __METHOD__, $objPlanoTrabalhoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPlanoTrabalhoBD = new PlanoTrabalhoBD($this->getObjInfraIBanco());
      $ret = $objPlanoTrabalhoBD->listar($objPlanoTrabalhoDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando Planos de Trabalho.', $e);
    }
  }

  protected function contarConectado(PlanoTrabalhoDTO $objPlanoTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('plano_trabalho_listar', __METHOD__, $objPlanoTrabalhoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPlanoTrabalhoBD = new PlanoTrabalhoBD($this->getObjInfraIBanco());
      $ret = $objPlanoTrabalhoBD->contar($objPlanoTrabalhoDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando Planos de Trabalho.', $e);
    }
  }

  protected function desativarControlado($arrObjPlanoTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('plano_trabalho_desativar', __METHOD__, $arrObjPlanoTrabalhoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPlanoTrabalhoBD = new PlanoTrabalhoBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjPlanoTrabalhoDTO); $i++) {
        $objPlanoTrabalhoBD->desativar($arrObjPlanoTrabalhoDTO[$i]);
      }
    } catch (Exception $e) {
      throw new InfraException('Erro desativando Plano de Trabalho.', $e);
    }
  }

  protected function reativarControlado($arrObjPlanoTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('plano_trabalho_reativar', __METHOD__, $arrObjPlanoTrabalhoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPlanoTrabalhoBD = new PlanoTrabalhoBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjPlanoTrabalhoDTO); $i++) {
        $objPlanoTrabalhoBD->reativar($arrObjPlanoTrabalhoDTO[$i]);
      }
    } catch (Exception $e) {
      throw new InfraException('Erro reativando Plano de Trabalho.', $e);
    }
  }

  protected function bloquearControlado(PlanoTrabalhoDTO $objPlanoTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('plano_trabalho_consultar', __METHOD__, $objPlanoTrabalhoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPlanoTrabalhoBD = new PlanoTrabalhoBD($this->getObjInfraIBanco());
      $ret = $objPlanoTrabalhoBD->bloquear($objPlanoTrabalhoDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro bloqueando Plano de Trabalho.', $e);
    }
  }

  protected function clonarControlado(PlanoTrabalhoDTO $parObjPlanoTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('plano_trabalho_clonar', __METHOD__, $parObjPlanoTrabalhoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if (InfraString::isBolVazia($parObjPlanoTrabalhoDTO->getNumIdPlanoTrabalho())) {
        $objInfraException->adicionarValidacao('Plano de Trabalho de Origem não informado.');
      }

      if (InfraString::isBolVazia($parObjPlanoTrabalhoDTO->getStrNome())) {
        $objInfraException->adicionarValidacao('Nome de Destino não informado.');
      }

      $objInfraException->lancarValidacoes();

      $objPlanoTrabalhoDTO = new PlanoTrabalhoDTO();
      $objPlanoTrabalhoDTO->setBolExclusaoLogica(false);
      $objPlanoTrabalhoDTO->retTodos();
      $objPlanoTrabalhoDTO->setNumIdPlanoTrabalho($parObjPlanoTrabalhoDTO->getNumIdPlanoTrabalho());
      $objPlanoTrabalhoDTO = $this->consultar($objPlanoTrabalhoDTO);

      if ($objPlanoTrabalhoDTO == null) {
        throw new InfraException('Plano de Trabalho não encontrado.');
      }

      $objPlanoTrabalhoDTO->setNumIdPlanoTrabalho(null);
      $objPlanoTrabalhoDTO->setStrNome($parObjPlanoTrabalhoDTO->getStrNome());
      $objPlanoTrabalhoDTODestino = $this->cadastrar($objPlanoTrabalhoDTO);

      $objEtapaTrabalhoDTO = new EtapaTrabalhoDTO();
      $objEtapaTrabalhoDTO->retTodos();
      $objEtapaTrabalhoDTO->setNumIdPlanoTrabalho($parObjPlanoTrabalhoDTO->getNumIdPlanoTrabalho());

      $objEtapaTrabalhoRN = new EtapaTrabalhoRN();
      $arrObjEtapaTrabalhoDTO = $objEtapaTrabalhoRN->listar($objEtapaTrabalhoDTO);

      $objItemEtapaRN = new ItemEtapaRN();
      $objRelItemEtapaUnidadeRN = new RelItemEtapaUnidadeRN();
      $objRelItemEtapaSerieRN = new RelItemEtapaSerieRN();

      foreach ($arrObjEtapaTrabalhoDTO as $objEtapaTrabalhoDTO) {
        $objItemEtapaDTO = new ItemEtapaDTO();
        $objItemEtapaDTO->retTodos();
        $objItemEtapaDTO->setNumIdEtapaTrabalho($objEtapaTrabalhoDTO->getNumIdEtapaTrabalho());
        $arrObjItemEtapaTrabalhoDTO = $objItemEtapaRN->listar($objItemEtapaDTO);

        $objEtapaTrabalhoDTO->setNumIdEtapaTrabalho(null);
        $objEtapaTrabalhoDTO->setNumIdPlanoTrabalho($objPlanoTrabalhoDTODestino->getNumIdPlanoTrabalho());
        $objEtapaTrabalhoDTODestino = $objEtapaTrabalhoRN->cadastrar($objEtapaTrabalhoDTO);

        foreach ($arrObjItemEtapaTrabalhoDTO as $objItemEtapaDTO) {
          $objRelItemEtapaUnidadeDTO = new RelItemEtapaUnidadeDTO();
          $objRelItemEtapaUnidadeDTO->retNumIdUnidade();
          $objRelItemEtapaUnidadeDTO->setNumIdItemEtapa($objItemEtapaDTO->getNumIdItemEtapa());
          $objItemEtapaDTO->setArrObjRelItemEtapaUnidadeDTO($objRelItemEtapaUnidadeRN->listar($objRelItemEtapaUnidadeDTO));

          $objRelItemEtapaSerieDTO = new RelItemEtapaSerieDTO();
          $objRelItemEtapaSerieDTO->retNumIdSerie();
          $objRelItemEtapaSerieDTO->setNumIdItemEtapa($objItemEtapaDTO->getNumIdItemEtapa());
          $objItemEtapaDTO->setArrObjRelItemEtapaSerieDTO($objRelItemEtapaSerieRN->listar($objRelItemEtapaSerieDTO));

          $objItemEtapaDTO->setNumIdItemEtapa(null);
          $objItemEtapaDTO->setNumIdEtapaTrabalho($objEtapaTrabalhoDTODestino->getNumIdEtapaTrabalho());
          $objItemEtapaRN->cadastrar($objItemEtapaDTO);
        }
      }

      return $objPlanoTrabalhoDTO;
    } catch (Exception $e) {
      throw new InfraException('Erro clonando Plano de Trabalho.', $e);
    }
  }

  protected function detalharConectado(PlanoTrabalhoDTO $parObjPlanoTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('plano_trabalho_detalhar', __METHOD__, $parObjPlanoTrabalhoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->retNumIdPlanoTrabalho();
      $objProcedimentoDTO->setDblIdProcedimento($parObjPlanoTrabalhoDTO->getDblIdProcedimento());

      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoDTO = $objProcedimentoRN->consultarRN0201($objProcedimentoDTO);

      if ($objProcedimentoDTO != null && $objProcedimentoDTO->getNumIdPlanoTrabalho()!=null) {
        $objPlanoTrabalhoDTO = new PlanoTrabalhoDTO();
        $objPlanoTrabalhoDTO->setBolExclusaoLogica(false);
        $objPlanoTrabalhoDTO->retNumIdPlanoTrabalho();
        $objPlanoTrabalhoDTO->retStrNome();
        //$objPlanoTrabalhoDTO->retStrDescricao();
        $objPlanoTrabalhoDTO->setNumIdPlanoTrabalho($objProcedimentoDTO->getNumIdPlanoTrabalho());

        $objPlanoTrabalhoDTO = $this->consultar($objPlanoTrabalhoDTO);

        $objEtapaTrabalhoDTO = new EtapaTrabalhoDTO();
        $objEtapaTrabalhoDTO->setBolExclusaoLogica(false);
        $objEtapaTrabalhoDTO->retNumIdEtapaTrabalho();
        $objEtapaTrabalhoDTO->retNumIdPlanoTrabalho();
        $objEtapaTrabalhoDTO->retStrNome();
        $objEtapaTrabalhoDTO->retNumOrdem();
        $objEtapaTrabalhoDTO->retStrSinAtivo();
        $objEtapaTrabalhoDTO->setNumIdPlanoTrabalho($objPlanoTrabalhoDTO->getNumIdPlanoTrabalho());
        $objEtapaTrabalhoDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objEtapaTrabalhoRN = new EtapaTrabalhoRN();
        $arrObjEtapaTrabalhoDTO = $objEtapaTrabalhoRN->listar($objEtapaTrabalhoDTO);

        if (count($arrObjEtapaTrabalhoDTO)) {
          $arrIdEtapas = InfraArray::converterArrInfraDTO($arrObjEtapaTrabalhoDTO, 'IdEtapaTrabalho');

          $objItemEtapaDTO = new ItemEtapaDTO();
          $objItemEtapaDTO->setBolExclusaoLogica(false);
          $objItemEtapaDTO->retNumIdItemEtapa();
          $objItemEtapaDTO->retNumIdEtapaTrabalho();
          $objItemEtapaDTO->retStrNome();
          $objItemEtapaDTO->retNumOrdem();
          $objItemEtapaDTO->retStrSinAtivo();
          $objItemEtapaDTO->setNumIdEtapaTrabalho($arrIdEtapas, InfraDTO::$OPER_IN);
          $objItemEtapaDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);

          $objItemEtapaRN = new ItemEtapaRN();
          $arrObjItemEtapaDTO = $objItemEtapaRN->listar($objItemEtapaDTO);

          $arrObjRelItemEtapaUnidadeDTO = array();
          $arrObjRelItemEtapaSerieDTO = array();
          $arrObjRelItemEtapaDocumentoDTO = array();
          $arrObjAndamentoPlanoTrabalhoDTO = array();

          if (count($arrObjItemEtapaDTO)) {
            $arrIdItemEtapa = InfraArray::converterArrInfraDTO($arrObjItemEtapaDTO, 'IdItemEtapa');

            $objRelItemEtapaUnidadeDTO = new RelItemEtapaUnidadeDTO();
            $objRelItemEtapaUnidadeDTO->retNumIdItemEtapa();
            $objRelItemEtapaUnidadeDTO->retNumIdUnidade();
            $objRelItemEtapaUnidadeDTO->setNumIdItemEtapa($arrIdItemEtapa, InfraDTO::$OPER_IN);
            $objRelItemEtapaUnidadeDTO->setOrdNumIdItemEtapa(InfraDTO::$TIPO_ORDENACAO_ASC);

            $objRelItemEtapaUnidadeRN = new RelItemEtapaUnidadeRN();
            $arrObjRelItemEtapaUnidadeDTO = InfraArray::indexarArrInfraDTO($objRelItemEtapaUnidadeRN->listar($objRelItemEtapaUnidadeDTO), 'IdItemEtapa', true);

            $objRelItemEtapaSerieDTO = new RelItemEtapaSerieDTO();
            $objRelItemEtapaSerieDTO->retNumIdItemEtapa();
            $objRelItemEtapaSerieDTO->retNumIdSerie();
            $objRelItemEtapaSerieDTO->setNumIdItemEtapa($arrIdItemEtapa, InfraDTO::$OPER_IN);
            $objRelItemEtapaSerieDTO->setOrdNumIdItemEtapa(InfraDTO::$TIPO_ORDENACAO_ASC);

            $objRelItemEtapaSerieRN = new RelItemEtapaSerieRN();
            $arrObjRelItemEtapaSerieDTO = $objRelItemEtapaSerieRN->listar($objRelItemEtapaSerieDTO);

            //$arrIdSeriesPlanoTrabalho = array_unique(InfraArray::converterArrInfraDTO($objRelItemEtapaSerieDTO,'IdSerie'));

            $arrObjRelItemEtapaSerieDTO = InfraArray::indexarArrInfraDTO($arrObjRelItemEtapaSerieDTO, 'IdItemEtapa', true);

            $objRelItemEtapaDocumentoDTO = new RelItemEtapaDocumentoDTO();
            $objRelItemEtapaDocumentoDTO->retNumIdItemEtapa();
            $objRelItemEtapaDocumentoDTO->retDblIdDocumento();
            $objRelItemEtapaDocumentoDTO->setNumIdItemEtapa($arrIdItemEtapa, InfraDTO::$OPER_IN);
            $objRelItemEtapaDocumentoDTO->setDblIdProcedimentoDocumento($parObjPlanoTrabalhoDTO->getDblIdProcedimento());
            $objRelItemEtapaDocumentoDTO->setOrdDblIdDocumento(InfraDTO::$TIPO_ORDENACAO_ASC);

            $objRelItemEtapaDocumentoRN = new RelItemEtapaDocumentoRN();
            $arrObjRelItemEtapaDocumentoDTO = $objRelItemEtapaDocumentoRN->listar($objRelItemEtapaDocumentoDTO);

            $arrIdDocumentosItens = InfraArray::converterArrInfraDTO($arrObjRelItemEtapaDocumentoDTO, 'IdDocumento');

            if (count($arrIdDocumentosItens)) {
              $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
              $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_DOCUMENTOS);
              $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_TODOS);
              $objPesquisaProtocoloDTO->setDblIdProtocolo($arrIdDocumentosItens, InfraDTO::$OPER_IN);

              $objProtocoloRN = new ProtocoloRN();
              $arrObjProtocoloDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO), 'IdProtocolo');

              foreach ($arrObjRelItemEtapaDocumentoDTO as $objRelItemEtapaDocumentoDTO) {
                if (isset($arrObjProtocoloDTO[$objRelItemEtapaDocumentoDTO->getDblIdDocumento()]) && in_array($objRelItemEtapaDocumentoDTO->getNumIdItemEtapa(), $arrIdItemEtapa)) {
                  $objRelItemEtapaDocumentoDTO->setObjProtocoloDTO($arrObjProtocoloDTO[$objRelItemEtapaDocumentoDTO->getDblIdDocumento()]);
                } else {
                  $objRelItemEtapaDocumentoDTO->setObjProtocoloDTO(null);
                }
              }
            }

            $arrObjRelItemEtapaDocumentoDTO = InfraArray::indexarArrInfraDTO($arrObjRelItemEtapaDocumentoDTO, 'IdItemEtapa', true);


            $objHistoricoPlanoTrabalhoDTO = new HistoricoPlanoTrabalhoDTO();
            $objHistoricoPlanoTrabalhoDTO->setNumIdPlanoTrabalho($objProcedimentoDTO->getNumIdPlanoTrabalho());
            $objHistoricoPlanoTrabalhoDTO->setDblIdProcedimento($parObjPlanoTrabalhoDTO->getDblIdProcedimento());
            $objHistoricoPlanoTrabalhoDTO->setStrStaHistorico(PlanoTrabalhoRN::$TH_PLANO_DETALHAR);
            $objHistoricoPlanoTrabalhoDTO->setNumIdItemEtapa($arrIdItemEtapa);
            $objPlanoTrabalhoDTOHistorico = $this->consultarHistorico($objHistoricoPlanoTrabalhoDTO);

            $arrObjAndamentoPlanoTrabalhoDTO = $objPlanoTrabalhoDTOHistorico->getArrObjAndamentoPlanoTrabalhoDTO();
          }

          $arrObjAndamentoPlanoTrabalhoDTO = InfraArray::indexarArrInfraDTO($arrObjAndamentoPlanoTrabalhoDTO, 'IdItemEtapa', true);

          $arrObjItemEtapaDTO = InfraArray::indexarArrInfraDTO($arrObjItemEtapaDTO, 'IdEtapaTrabalho', true);


          foreach ($arrObjEtapaTrabalhoDTO as $objEtapaTrabalhoDTO) {
            if (!isset($arrObjItemEtapaDTO[$objEtapaTrabalhoDTO->getNumIdEtapaTrabalho()])) {
              $objEtapaTrabalhoDTO->setArrObjItemEtapaDTO(array());
            } else {
              $arr = $arrObjItemEtapaDTO[$objEtapaTrabalhoDTO->getNumIdEtapaTrabalho()];

              /** @var ItemEtapaDTO $objItemEtapaDTO */
              foreach ($arr as $objItemEtapaDTO) {
                $objItemEtapaDTO->setStrSinUnidadeAcesso('N');

                if (!isset($arrObjRelItemEtapaUnidadeDTO[$objItemEtapaDTO->getNumIdItemEtapa()])) {
                  $objItemEtapaDTO->setArrObjRelItemEtapaUnidadeDTO(array());
                  $objItemEtapaDTO->setStrSinUnidadeAcesso('S');
                } else {
                  $objItemEtapaDTO->setArrObjRelItemEtapaUnidadeDTO($arrObjRelItemEtapaUnidadeDTO[$objItemEtapaDTO->getNumIdItemEtapa()]);

                  if (in_array(SessaoSEI::getInstance()->getNumIdUnidadeAtual(), InfraArray::converterArrInfraDTO($objItemEtapaDTO->getArrObjRelItemEtapaUnidadeDTO(), 'IdUnidade'))) {
                    $objItemEtapaDTO->setStrSinUnidadeAcesso('S');
                  }
                }

                if (!isset($arrObjRelItemEtapaSerieDTO[$objItemEtapaDTO->getNumIdItemEtapa()])) {
                  $objItemEtapaDTO->setArrObjRelItemEtapaSerieDTO(array());
                } else {
                  $objItemEtapaDTO->setArrObjRelItemEtapaSerieDTO($arrObjRelItemEtapaSerieDTO[$objItemEtapaDTO->getNumIdItemEtapa()]);
                }

                if (!isset($arrObjRelItemEtapaDocumentoDTO[$objItemEtapaDTO->getNumIdItemEtapa()])) {
                  $objItemEtapaDTO->setArrObjRelItemEtapaDocumentoDTO(array());
                } else {
                  $objItemEtapaDTO->setArrObjRelItemEtapaDocumentoDTO($arrObjRelItemEtapaDocumentoDTO[$objItemEtapaDTO->getNumIdItemEtapa()]);
                }

                if (!isset($arrObjAndamentoPlanoTrabalhoDTO[$objItemEtapaDTO->getNumIdItemEtapa()])) {
                  $objItemEtapaDTO->setArrObjAndamentoPlanoTrabalhoDTO(array());
                } else {
                  $objItemEtapaDTO->setArrObjAndamentoPlanoTrabalhoDTO($arrObjAndamentoPlanoTrabalhoDTO[$objItemEtapaDTO->getNumIdItemEtapa()]);
                }
              }

              $objEtapaTrabalhoDTO->setArrObjItemEtapaDTO($arrObjItemEtapaDTO[$objEtapaTrabalhoDTO->getNumIdEtapaTrabalho()]);
            }
          }
        }

        $numEtapas = count($arrObjEtapaTrabalhoDTO);

        for ($i = 0; $i < $numEtapas; $i++) {
          $arrObjItemEtapaDTO = $arrObjEtapaTrabalhoDTO[$i]->getArrObjItemEtapaDTO();

          $numItens = count($arrObjItemEtapaDTO);

          for ($j = 0; $j < $numItens; $j++) {
            if (($arrObjEtapaTrabalhoDTO[$i]->getStrSinAtivo() == 'N' || $arrObjItemEtapaDTO[$j]->getStrSinAtivo() == 'N') && count($arrObjItemEtapaDTO[$j]->getArrObjRelItemEtapaDocumentoDTO()) == 0 && count($arrObjItemEtapaDTO[$j]->getArrObjAndamentoPlanoTrabalhoDTO()) == 0) {
              unset($arrObjItemEtapaDTO[$j]);
            }
          }

          $arrObjEtapaTrabalhoDTO[$i]->setArrObjItemEtapaDTO(array_values($arrObjItemEtapaDTO));


          if ($arrObjEtapaTrabalhoDTO[$i]->getStrSinAtivo() == 'N' && count($arrObjEtapaTrabalhoDTO[$i]->getArrObjItemEtapaDTO()) == 0) {
            unset($arrObjEtapaTrabalhoDTO[$i]);
          }
        }


        $objPlanoTrabalhoDTO->setArrObjEtapaTrabalhoDTO(array_values($arrObjEtapaTrabalhoDTO));

        return $objPlanoTrabalhoDTO;
      }

      return null;
    } catch (Exception $e) {
      throw new InfraException('Erro detalhando Plano de Trabalho.', $e);
    }
  }

  protected function consultarHistoricoConectado(HistoricoPlanoTrabalhoDTO $objHistoricoPlanoTrabalhoDTO) {
    try {
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('plano_trabalho_consultar_historico', __METHOD__, $objHistoricoPlanoTrabalhoDTO);


      //Regras de Negocio

      $objPlanoTrabalhoDTO = new PlanoTrabalhoDTO();
      $objPlanoTrabalhoDTO->setBolExclusaoLogica(false);
      $objPlanoTrabalhoDTO->retNumIdPlanoTrabalho();
      $objPlanoTrabalhoDTO->retStrNome();
      $objPlanoTrabalhoDTO->setNumIdPlanoTrabalho($objHistoricoPlanoTrabalhoDTO->getNumIdPlanoTrabalho());

      $objPlanoTrabalhoRN = new PlanoTrabalhoRN();
      $objPlanoTrabalhoDTO = $objPlanoTrabalhoRN->consultar($objPlanoTrabalhoDTO);

      if ($objPlanoTrabalhoDTO == null) {
        throw new InfraException('Plano de Trabalho não encontrado.');
      }

      $objAndamentoPlanoTrabalhoDTO = new AndamentoPlanoTrabalhoDTO();
      $objAndamentoPlanoTrabalhoDTO->retNumIdAndamentoPlanoTrabalho();
      $objAndamentoPlanoTrabalhoDTO->retNumIdPlanoTrabalho();
      $objAndamentoPlanoTrabalhoDTO->retDthExecucao();
      $objAndamentoPlanoTrabalhoDTO->retNumIdUnidadeOrigem();
      $objAndamentoPlanoTrabalhoDTO->retStrSiglaUnidadeOrigem();
      $objAndamentoPlanoTrabalhoDTO->retStrDescricaoUnidadeOrigem();
      $objAndamentoPlanoTrabalhoDTO->retNumIdUsuarioOrigem();
      $objAndamentoPlanoTrabalhoDTO->retStrSiglaUsuarioOrigem();
      $objAndamentoPlanoTrabalhoDTO->retStrNomeUsuarioOrigem();
      $objAndamentoPlanoTrabalhoDTO->retNumIdTarefaPlanoTrabalho();
      $objAndamentoPlanoTrabalhoDTO->retStrNomeTarefaPlanoTrabalho();
      $objAndamentoPlanoTrabalhoDTO->retStrStaSituacao();

      $objAndamentoPlanoTrabalhoDTO->setNumIdPlanoTrabalho($objHistoricoPlanoTrabalhoDTO->getNumIdPlanoTrabalho());
      $objAndamentoPlanoTrabalhoDTO->setDblIdProcedimento($objHistoricoPlanoTrabalhoDTO->getDblIdProcedimento());

      if ($objHistoricoPlanoTrabalhoDTO->getStrStaHistorico() == PlanoTrabalhoRN::$TH_PLANO_TRABALHO) {
        //$objAndamentoPlanoTrabalhoDTO->setNumIdTarefaPlanoTrabalho(TarefaPlanoTrabalhoRN::$TP_ANDAMENTO_ATIVIDADE_ENTREGA, InfraDTO::$OPER_DIFERENTE);

      } else {
        if ($objHistoricoPlanoTrabalhoDTO->getStrStaHistorico() == PlanoTrabalhoRN::$TH_PLANO_DETALHAR) {
          $objAndamentoPlanoTrabalhoDTO->setStrStaSituacao(null, InfraDTO::$OPER_DIFERENTE);
        }

        if ($objHistoricoPlanoTrabalhoDTO->getStrStaHistorico() == PlanoTrabalhoRN::$TH_PLANO_DETALHAR || $objHistoricoPlanoTrabalhoDTO->getStrStaHistorico() == PlanoTrabalhoRN::$TH_ANDAMENTO_ITEM_ETAPA) {
          $objAtributoAndamPlanoTrabDTO = new AtributoAndamPlanoTrabDTO();
          $objAtributoAndamPlanoTrabDTO->retNumIdAndamentoPlanoTrabalho();
          $objAtributoAndamPlanoTrabDTO->retStrIdOrigem();
          $objAtributoAndamPlanoTrabDTO->setNumIdPlanoTrabalhoAndamentoPlanoTrabalho($objHistoricoPlanoTrabalhoDTO->getNumIdPlanoTrabalho());
          //$objAtributoAndamPlanoTrabDTO->setNumIdTarefaPlanoTrabalhoAndamentoPlanoTrabalho(TarefaPlanoTrabalhoRN::$TPT_ATUALIZACAO_ITEM_ETAPA);
          $objAtributoAndamPlanoTrabDTO->setStrChave('ITEM_ETAPA');
          $objAtributoAndamPlanoTrabDTO->setDblIdProcedimentoAndamentoPlanoTrabalho($objHistoricoPlanoTrabalhoDTO->getDblIdProcedimento());

          if (is_array($objHistoricoPlanoTrabalhoDTO->getNumIdItemEtapa())) {
            $objAtributoAndamPlanoTrabDTO->setStrIdOrigem($objHistoricoPlanoTrabalhoDTO->getNumIdItemEtapa(), InfraDTO::$OPER_IN);
          } else {
            $objAtributoAndamPlanoTrabDTO->setStrIdOrigem($objHistoricoPlanoTrabalhoDTO->getNumIdItemEtapa());
          }

          $objAtributoAndamPlanoTrabRN = new AtributoAndamPlanoTrabRN();
          $arrObjAtributoAndamPlanoTrabDTO = $objAtributoAndamPlanoTrabRN->listar($objAtributoAndamPlanoTrabDTO);

          if (count($arrObjAtributoAndamPlanoTrabDTO)) {
            $objAndamentoPlanoTrabalhoDTO->setNumIdAndamentoPlanoTrabalho(InfraArray::converterArrInfraDTO($arrObjAtributoAndamPlanoTrabDTO, 'IdAndamentoPlanoTrabalho'), InfraDTO::$OPER_IN);
          } else {
            $objAndamentoPlanoTrabalhoDTO->setNumIdAndamentoPlanoTrabalho(null);
          }
        }
      }

      $objAndamentoPlanoTrabalhoDTO->setOrdDthExecucao(InfraDTO::$TIPO_ORDENACAO_DESC);

      //paginação
      $objAndamentoPlanoTrabalhoDTO->setNumMaxRegistrosRetorno($objHistoricoPlanoTrabalhoDTO->getNumMaxRegistrosRetorno());
      $objAndamentoPlanoTrabalhoDTO->setNumPaginaAtual($objHistoricoPlanoTrabalhoDTO->getNumPaginaAtual());

      $objAndamentoPlanoTrabalhoRN = new AndamentoPlanoTrabalhoRN();
      $arrObjAndamentoPlanoTrabalhoDTO = InfraArray::indexarArrInfraDTO($objAndamentoPlanoTrabalhoRN->listar($objAndamentoPlanoTrabalhoDTO), 'IdAndamentoPlanoTrabalho');

      //paginação
      $objHistoricoPlanoTrabalhoDTO->setNumTotalRegistros($objAndamentoPlanoTrabalhoDTO->getNumTotalRegistros());
      $objHistoricoPlanoTrabalhoDTO->setNumRegistrosPaginaAtual($objAndamentoPlanoTrabalhoDTO->getNumRegistrosPaginaAtual());

      if (count($arrObjAndamentoPlanoTrabalhoDTO)) {
        $objAndamentoPlanoTrabalhoRN = new AndamentoPlanoTrabalhoRN();
        $arrObjSituacaoAndamentoPlanoTrabalhoDTO = InfraArray::indexarArrInfraDTO($objAndamentoPlanoTrabalhoRN->listarValoresSituacao(), 'StaSituacao');

        foreach ($arrObjAndamentoPlanoTrabalhoDTO as $objAndamentoPlanoTrabalhoDTO) {
          if ($objAndamentoPlanoTrabalhoDTO->getStrStaSituacao() != null) {
            $objAndamentoPlanoTrabalhoDTO->setObjSituacaoAndamentoPlanoTrabalhoDTO($arrObjSituacaoAndamentoPlanoTrabalhoDTO[$objAndamentoPlanoTrabalhoDTO->getStrStaSituacao()]);
          } else {
            $objAndamentoPlanoTrabalhoDTO->setObjSituacaoAndamentoPlanoTrabalhoDTO(null);
          }
        }

        $objAtributoAndamPlanoTrabDTO = new AtributoAndamPlanoTrabDTO();
        $objAtributoAndamPlanoTrabDTO->retTodos(true);
        $objAtributoAndamPlanoTrabDTO->setNumIdAndamentoPlanoTrabalho(InfraArray::converterArrInfraDTO($arrObjAndamentoPlanoTrabalhoDTO, 'IdAndamentoPlanoTrabalho'), InfraDTO::$OPER_IN);

        $objAtributoAndamPlanoTrabRN = new AtributoAndamPlanoTrabRN();
        $arrObjAtributoAndamPlanoTrabDTO = $objAtributoAndamPlanoTrabRN->listar($objAtributoAndamPlanoTrabDTO);
        $arrObjAtributoAndamPlanoTrabDTOPorChave = InfraArray::indexarArrInfraDTO($arrObjAtributoAndamPlanoTrabDTO, 'Chave', true);

        $arrObjPlanoTrabalhoDTO = array();
        if (isset($arrObjAtributoAndamPlanoTrabDTOPorChave['PLANO_TRABALHO'])) {
          $objPlanoTrabalhoDTO = new PlanoTrabalhoDTO();
          $objPlanoTrabalhoDTO->setBolExclusaoLogica(false);
          $objPlanoTrabalhoDTO->retNumIdPlanoTrabalho();
          $objPlanoTrabalhoDTO->retStrNome();
          $objPlanoTrabalhoDTO->setNumIdPlanoTrabalho(array_unique(InfraArray::converterArrInfraDTO($arrObjAtributoAndamPlanoTrabDTOPorChave['PLANO_TRABALHO'], 'IdOrigem')), InfraDTO::$OPER_IN);

          $objPlanoTrabalhoRN = new PlanoTrabalhoRN();
          $arrObjPlanoTrabalhoDTO = InfraArray::indexarArrInfraDTO($objPlanoTrabalhoRN->listar($objPlanoTrabalhoDTO), 'IdPlanoTrabalho');
        }

        $arrObjItemEtapaDTO = array();
        if (isset($arrObjAtributoAndamPlanoTrabDTOPorChave['ITEM_ETAPA'])) {
          $objItemEtapaDTO = new ItemEtapaDTO();
          $objItemEtapaDTO->setBolExclusaoLogica(false);
          $objItemEtapaDTO->retNumIdItemEtapa();
          $objItemEtapaDTO->retStrNome();
          $objItemEtapaDTO->setNumIdItemEtapa(array_unique(InfraArray::converterArrInfraDTO($arrObjAtributoAndamPlanoTrabDTOPorChave['ITEM_ETAPA'], 'IdOrigem')), InfraDTO::$OPER_IN);

          $objItemEtapaRN = new ItemEtapaRN();
          $arrObjItemEtapaDTO = InfraArray::indexarArrInfraDTO($objItemEtapaRN->listar($objItemEtapaDTO), 'IdItemEtapa');
        }

        $arrObjEtapaTrabalhoDTO = array();
        if (isset($arrObjAtributoAndamPlanoTrabDTOPorChave['ETAPA_TRABALHO'])) {
          $objEtapaTrabalhoDTO = new EtapaTrabalhoDTO();
          $objEtapaTrabalhoDTO->setBolExclusaoLogica(false);
          $objEtapaTrabalhoDTO->retNumIdEtapaTrabalho();
          $objEtapaTrabalhoDTO->retStrNome();
          $objEtapaTrabalhoDTO->setNumIdEtapaTrabalho(array_unique(InfraArray::converterArrInfraDTO($arrObjAtributoAndamPlanoTrabDTOPorChave['ETAPA_TRABALHO'], 'IdOrigem')), InfraDTO::$OPER_IN);

          $objEtapaTrabalhoRN = new EtapaTrabalhoRN();
          $arrObjEtapaTrabalhoDTO = InfraArray::indexarArrInfraDTO($objEtapaTrabalhoRN->listar($objEtapaTrabalhoDTO), 'IdEtapaTrabalho');
        }

        $arrObjDocumentoDTO = array();
        if (isset($arrObjAtributoAndamPlanoTrabDTOPorChave['DOCUMENTO'])) {
          $dto = new DocumentoDTO();
          $dto->retDblIdDocumento();
          $dto->retStrProtocoloDocumentoFormatado();
          $dto->retStrNomeSerie();
          $dto->retStrNumero();
          $dto->retStrStaProtocoloProtocolo();
          $dto->setDblIdDocumento(array_unique(InfraArray::converterArrInfraDTO($arrObjAtributoAndamPlanoTrabDTOPorChave['DOCUMENTO'], 'IdOrigem')), InfraDTO::$OPER_IN);

          $objDocumentoRN = new DocumentoRN();
          $arrObjDocumentoDTO = InfraArray::indexarArrInfraDTO($objDocumentoRN->listarRN0008($dto), 'IdDocumento');
        }


        if (count($arrObjAtributoAndamPlanoTrabDTO) > 0) {
          $bolAcaoDocumentoVisualizar = SessaoSEI::getInstance()->verificarPermissao('documento_visualizar');

          foreach ($arrObjAtributoAndamPlanoTrabDTO as $objAtributoAndamPlanoTrabDTO) {
            $objAndamentoPlanoTrabalhoDTO = $arrObjAndamentoPlanoTrabalhoDTO[$objAtributoAndamPlanoTrabDTO->getNumIdAndamentoPlanoTrabalho()];

            $objAtributoAndamPlanoTrabDTO->setStrValor(PaginaSEI::tratarHTML($objAtributoAndamPlanoTrabDTO->getStrValor()));

            $strNomeTarefa = $objAndamentoPlanoTrabalhoDTO->getStrNomeTarefaPlanoTrabalho();

            switch ($objAndamentoPlanoTrabalhoDTO->getNumIdTarefaPlanoTrabalho()) {
              case TarefaPlanoTrabalhoRN::$TPT_ASSOCIACAO_PLANO_TRABALHO:
              case TarefaPlanoTrabalhoRN::$TPT_REMOCAO_ASSOCIACAO_PLANO_TRABALHO:

                switch ($objAtributoAndamPlanoTrabDTO->getStrChave()) {
                  case 'PLANO_TRABALHO':
                    $objPlanoTrabalhoDTO = $arrObjPlanoTrabalhoDTO[$objAtributoAndamPlanoTrabDTO->getStrIdOrigem()];
                    if ($objPlanoTrabalhoDTO != null) {
                      $strNomeTarefa = str_replace('@' . $objAtributoAndamPlanoTrabDTO->getStrChave() . '@', '"' . $objPlanoTrabalhoDTO->getStrNome() . '"', $strNomeTarefa);
                    }
                    break;
                }
                break;

              case TarefaPlanoTrabalhoRN::$TPT_ATUALIZACAO_ITEM_ETAPA:
              case TarefaPlanoTrabalhoRN::$TPT_ASSOCIACAO_DOCUMENTO_ITEM_ETAPA:
              case TarefaPlanoTrabalhoRN::$TPT_REMOCAO_ASSOCIACAO_DOCUMENTO_ITEM_ETAPA:

                switch ($objAtributoAndamPlanoTrabDTO->getStrChave()) {
                  case 'ITEM_ETAPA':
                    $objItemEtapaDTO = $arrObjItemEtapaDTO[$objAtributoAndamPlanoTrabDTO->getStrIdOrigem()];
                    if ($objItemEtapaDTO != null) {
                      $strNomeTarefa = str_replace('@' . $objAtributoAndamPlanoTrabDTO->getStrChave() . '@', '"' . $objItemEtapaDTO->getStrNome() . '"', $strNomeTarefa);
                    }
                    $arrObjAndamentoPlanoTrabalhoDTO[$objAtributoAndamPlanoTrabDTO->getNumIdAndamentoPlanoTrabalho()]->setNumIdItemEtapa($objAtributoAndamPlanoTrabDTO->getStrIdOrigem());
                    break;

                  case 'ETAPA_TRABALHO':
                    $objEtapaTrabalhoDTO = $arrObjEtapaTrabalhoDTO[$objAtributoAndamPlanoTrabDTO->getStrIdOrigem()];
                    if ($objEtapaTrabalhoDTO != null) {
                      $strNomeTarefa = str_replace('@' . $objAtributoAndamPlanoTrabDTO->getStrChave() . '@', '"' . $objEtapaTrabalhoDTO->getStrNome() . '"', $strNomeTarefa);
                    }
                    $arrObjAndamentoPlanoTrabalhoDTO[$objAtributoAndamPlanoTrabDTO->getNumIdAndamentoPlanoTrabalho()]->setNumIdEtapaTrabalho($objAtributoAndamPlanoTrabDTO->getStrIdOrigem());
                    break;

                  case 'DESCRICAO':
                    if ($objAtributoAndamPlanoTrabDTO->getStrValor() != null) {
                      $objAtributoAndamPlanoTrabDTO->setStrValor(".\n" . $objAtributoAndamPlanoTrabDTO->getStrValor());
                    }
                    $strNomeTarefa = str_replace('@' . $objAtributoAndamPlanoTrabDTO->getStrChave() . '@', $objAtributoAndamPlanoTrabDTO->getStrValor(), $strNomeTarefa);
                    //$arrObjAndamentoPlanoTrabalhoDTO[$objAtributoAndamPlanoTrabDTO->getNumIdAndamentoPlanoTrabalho()]->setStrDescricao($objAtributoAndamPlanoTrabDTO->getStrValor());
                    break;

                  case 'DOCUMENTO':

                    if (!isset($arrObjDocumentoDTO[$objAtributoAndamPlanoTrabDTO->getStrIdOrigem()])) {
                      $strSubstituicao = '<a href="javascript:void(0);" onclick="alert(\'Este documento foi excluído.\');" class="ancoraHistoricoProcesso">' . $objAtributoAndamPlanoTrabDTO->getStrValor() . '</a>';
                    } else {
                      $objDocumentoDTO = $arrObjDocumentoDTO[$objAtributoAndamPlanoTrabDTO->getStrIdOrigem()];
                      $strIdentificacao = PaginaSEI::tratarHTML(trim($objDocumentoDTO->getStrNomeSerie() . ' ' . $objDocumentoDTO->getStrNumero()));
                      if ($bolAcaoDocumentoVisualizar) {
                        $strSubstituicao = '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&id_documento=' . $objAtributoAndamPlanoTrabDTO->getStrIdOrigem()) . '" target="_blank" class="ancoraHistoricoProcesso">' . $objAtributoAndamPlanoTrabDTO->getStrValor() . '</a> (' . $strIdentificacao . ')';
                      } else {
                        $strSubstituicao = $objAtributoAndamPlanoTrabDTO->getStrValor() . ' (' . $strIdentificacao . ')';
                      }
                    }
                    $strNomeTarefa = str_replace('@' . $objAtributoAndamPlanoTrabDTO->getStrChave() . '@', $strSubstituicao, $strNomeTarefa);
                    break;
                }
                break;
            }

            $arrObjAndamentoPlanoTrabalhoDTO[$objAtributoAndamPlanoTrabDTO->getNumIdAndamentoPlanoTrabalho()]->setStrNomeTarefaPlanoTrabalho($strNomeTarefa);
          }
        }
      }

      $objPlanoTrabalhoDTO->setArrObjAndamentoPlanoTrabalhoDTO(array_values($arrObjAndamentoPlanoTrabalhoDTO));

      return $objPlanoTrabalhoDTO;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando histórico de plano de trabalho.', $e);
    }
  }

  protected function obterEtapasDocumentoConectado(DocumentoDTO $objDocumentoDTO) {
    try{
      $arr = null;

      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->retNumIdPlanoTrabalho();
      $objProcedimentoDTO->setDblIdProcedimento($objDocumentoDTO->getDblIdProcedimento());

      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoDTO = $objProcedimentoRN->consultarRN0201($objProcedimentoDTO);

      if ($objProcedimentoDTO != null && $objProcedimentoDTO->getNumIdPlanoTrabalho()!=null) {
        $objRelItemEtapaSerieDTO = new RelItemEtapaSerieDTO();
        $objRelItemEtapaSerieDTO->retNumIdItemEtapa();
        $objRelItemEtapaSerieDTO->retNumIdEtapaTrabalhoItemEtapa();
        $objRelItemEtapaSerieDTO->retNumIdPlanoTrabalhoEtapaTrabalho();
        $objRelItemEtapaSerieDTO->retNumIdSerie();
        $objRelItemEtapaSerieDTO->retStrNomeSerie();
        $objRelItemEtapaSerieDTO->setNumIdSerie($objDocumentoDTO->getNumIdSerie());
        $objRelItemEtapaSerieDTO->setNumIdPlanoTrabalhoEtapaTrabalho($objProcedimentoDTO->getNumIdPlanoTrabalho());

        $objRelItemEtapaSerieRN = new RelItemEtapaSerieRN();
        $arr = $objRelItemEtapaSerieRN->listar($objRelItemEtapaSerieDTO);
      }

      return $arr;

    } catch (Exception $e) {
      throw new InfraException('Erro obtendo etapas do Plano de Trabalho associadas com o documento.', $e);
    }
  }
}
