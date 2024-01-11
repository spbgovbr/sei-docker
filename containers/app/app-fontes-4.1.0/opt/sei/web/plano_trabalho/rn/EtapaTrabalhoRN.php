<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 22/09/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../../SEI.php';

class EtapaTrabalhoRN extends InfraRN {

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSEI::getInstance();
  }

  private function validarNumIdPlanoTrabalho(EtapaTrabalhoDTO $objEtapaTrabalhoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objEtapaTrabalhoDTO->getNumIdPlanoTrabalho())) {
      $objInfraException->adicionarValidacao('Plano de Trabalho não informado.');
    }
  }

  private function validarStrNome(EtapaTrabalhoDTO $objEtapaTrabalhoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objEtapaTrabalhoDTO->getStrNome())) {
      $objInfraException->adicionarValidacao('Nome não informado.');
    } else {
      $objEtapaTrabalhoDTO->setStrNome(trim($objEtapaTrabalhoDTO->getStrNome()));

      if (strlen($objEtapaTrabalhoDTO->getStrNome()) > 100) {
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 100 caracteres.');
      }

      $dto = new EtapaTrabalhoDTO();
      $dto->setBolExclusaoLogica(false);
      $dto->retNumIdEtapaTrabalho();
      $dto->setStrNome($objEtapaTrabalhoDTO->getStrNome());
      $dto->setNumIdPlanoTrabalho($objEtapaTrabalhoDTO->getNumIdPlanoTrabalho());
      $dto->setNumIdEtapaTrabalho($objEtapaTrabalhoDTO->getNumIdEtapaTrabalho(), InfraDTO::$OPER_DIFERENTE);
      if ($this->consultar($dto) != null) {
        $objInfraException->adicionarValidacao('Existe outra Etapa de Trabalho com o mesmo Nome.');
      }
    }
  }

  private function validarStrDescricao(EtapaTrabalhoDTO $objEtapaTrabalhoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objEtapaTrabalhoDTO->getStrDescricao())) {
      $objEtapaTrabalhoDTO->setStrDescricao(null);
    } else {
      $objEtapaTrabalhoDTO->setStrDescricao(trim($objEtapaTrabalhoDTO->getStrDescricao()));

      if (strlen($objEtapaTrabalhoDTO->getStrDescricao()) > 4000) {
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 4000 caracteres.');
      }
    }
  }

  private function validarNumOrdem(EtapaTrabalhoDTO $objEtapaTrabalhoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objEtapaTrabalhoDTO->getNumOrdem())) {
      $objInfraException->adicionarValidacao('Ordem não informada.');
    }
  }

  private function validarStrSinAtivo(EtapaTrabalhoDTO $objEtapaTrabalhoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objEtapaTrabalhoDTO->getStrSinAtivo())) {
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    } else {
      if (!InfraUtil::isBolSinalizadorValido($objEtapaTrabalhoDTO->getStrSinAtivo())) {
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(EtapaTrabalhoDTO $objEtapaTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('etapa_trabalho_cadastrar', __METHOD__, $objEtapaTrabalhoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdPlanoTrabalho($objEtapaTrabalhoDTO, $objInfraException);
      $this->validarStrNome($objEtapaTrabalhoDTO, $objInfraException);
      $this->validarStrDescricao($objEtapaTrabalhoDTO, $objInfraException);
      $this->validarNumOrdem($objEtapaTrabalhoDTO, $objInfraException);
      $this->validarStrSinAtivo($objEtapaTrabalhoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objEtapaTrabalhoBD = new EtapaTrabalhoBD($this->getObjInfraIBanco());
      $ret = $objEtapaTrabalhoBD->cadastrar($objEtapaTrabalhoDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro cadastrando Etapa de Trabalho.', $e);
    }
  }

  protected function alterarControlado(EtapaTrabalhoDTO $objEtapaTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('etapa_trabalho_alterar', __METHOD__, $objEtapaTrabalhoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objEtapaTrabalhoDTO->isSetNumIdPlanoTrabalho()) {
        $this->validarNumIdPlanoTrabalho($objEtapaTrabalhoDTO, $objInfraException);
      }
      if ($objEtapaTrabalhoDTO->isSetStrNome()) {
        $this->validarStrNome($objEtapaTrabalhoDTO, $objInfraException);
      }
      if ($objEtapaTrabalhoDTO->isSetStrDescricao()) {
        $this->validarStrDescricao($objEtapaTrabalhoDTO, $objInfraException);
      }
      if ($objEtapaTrabalhoDTO->isSetNumOrdem()) {
        $this->validarNumOrdem($objEtapaTrabalhoDTO, $objInfraException);
      }
      if ($objEtapaTrabalhoDTO->isSetStrSinAtivo()) {
        $this->validarStrSinAtivo($objEtapaTrabalhoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objEtapaTrabalhoBD = new EtapaTrabalhoBD($this->getObjInfraIBanco());
      $objEtapaTrabalhoBD->alterar($objEtapaTrabalhoDTO);
    } catch (Exception $e) {
      throw new InfraException('Erro alterando Etapa de Trabalho.', $e);
    }
  }

  protected function excluirControlado($arrObjEtapaTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('etapa_trabalho_excluir', __METHOD__, $arrObjEtapaTrabalhoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objItemEtapaRN = new ItemEtapaRN();

      $objEtapaTrabalhoBD = new EtapaTrabalhoBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjEtapaTrabalhoDTO); $i++) {
        $objItemEtapaDTO = new ItemEtapaDTO();
        $objItemEtapaDTO->retNumIdItemEtapa();
        $objItemEtapaDTO->setNumIdEtapaTrabalho($arrObjEtapaTrabalhoDTO[$i]->getNumIdEtapaTrabalho());
        $objItemEtapaRN->excluir($objItemEtapaRN->listar($objItemEtapaDTO));

        $objEtapaTrabalhoBD->excluir($arrObjEtapaTrabalhoDTO[$i]);
      }
    } catch (Exception $e) {
      throw new InfraException('Erro excluindo Etapa de Trabalho.', $e);
    }
  }

  protected function consultarConectado(EtapaTrabalhoDTO $objEtapaTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('etapa_trabalho_consultar', __METHOD__, $objEtapaTrabalhoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEtapaTrabalhoBD = new EtapaTrabalhoBD($this->getObjInfraIBanco());
      $ret = $objEtapaTrabalhoBD->consultar($objEtapaTrabalhoDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando Etapa de Trabalho.', $e);
    }
  }

  protected function listarConectado(EtapaTrabalhoDTO $objEtapaTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('etapa_trabalho_listar', __METHOD__, $objEtapaTrabalhoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEtapaTrabalhoBD = new EtapaTrabalhoBD($this->getObjInfraIBanco());
      $ret = $objEtapaTrabalhoBD->listar($objEtapaTrabalhoDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando Etapas de Trabalho.', $e);
    }
  }

  protected function contarConectado(EtapaTrabalhoDTO $objEtapaTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('etapa_trabalho_listar', __METHOD__, $objEtapaTrabalhoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEtapaTrabalhoBD = new EtapaTrabalhoBD($this->getObjInfraIBanco());
      $ret = $objEtapaTrabalhoBD->contar($objEtapaTrabalhoDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando Etapas de Trabalho.', $e);
    }
  }

  protected function desativarControlado($arrObjEtapaTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('etapa_trabalho_desativar', __METHOD__, $arrObjEtapaTrabalhoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEtapaTrabalhoBD = new EtapaTrabalhoBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjEtapaTrabalhoDTO); $i++) {
        $objEtapaTrabalhoBD->desativar($arrObjEtapaTrabalhoDTO[$i]);
      }
    } catch (Exception $e) {
      throw new InfraException('Erro desativando Etapa de Trabalho.', $e);
    }
  }

  protected function reativarControlado($arrObjEtapaTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('etapa_trabalho_reativar', __METHOD__, $arrObjEtapaTrabalhoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEtapaTrabalhoBD = new EtapaTrabalhoBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjEtapaTrabalhoDTO); $i++) {
        $objEtapaTrabalhoBD->reativar($arrObjEtapaTrabalhoDTO[$i]);
      }
    } catch (Exception $e) {
      throw new InfraException('Erro reativando Etapa de Trabalho.', $e);
    }
  }

  protected function bloquearControlado(EtapaTrabalhoDTO $objEtapaTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('etapa_trabalho_consultar', __METHOD__, $objEtapaTrabalhoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEtapaTrabalhoBD = new EtapaTrabalhoBD($this->getObjInfraIBanco());
      $ret = $objEtapaTrabalhoBD->bloquear($objEtapaTrabalhoDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro bloqueando Etapa de Trabalho.', $e);
    }
  }
}
