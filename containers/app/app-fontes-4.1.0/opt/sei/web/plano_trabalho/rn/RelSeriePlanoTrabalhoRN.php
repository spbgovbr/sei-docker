<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 02/01/2023 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.44
 **/

require_once dirname(__FILE__) . '/../../SEI.php';

class RelSeriePlanoTrabalhoRN extends InfraRN {
  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSEI::getInstance();
  }

  private function validarNumIdSerie(RelSeriePlanoTrabalhoDTO $objRelSeriePlanoTrabalhoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objRelSeriePlanoTrabalhoDTO->getNumIdSerie())) {
      $objInfraException->adicionarValidacao('Tipo de Documento não informado');
    }
  }

  private function validarNumIdPlanoTrabalho(RelSeriePlanoTrabalhoDTO $objRelSeriePlanoTrabalhoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objRelSeriePlanoTrabalhoDTO->getNumIdPlanoTrabalho())) {
      $objInfraException->adicionarValidacao('Plano de Trabalho não informado');
    }
  }

  /**
   * @param RelSeriePlanoTrabalhoDTO $objRelSeriePlanoTrabalhoDTO
   * @return RelSeriePlanoTrabalhoDTO
   * @throws InfraException
   */
  protected function cadastrarControlado(RelSeriePlanoTrabalhoDTO $objRelSeriePlanoTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_serie_plano_trabalho_cadastrar', __METHOD__, $objRelSeriePlanoTrabalhoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdSerie($objRelSeriePlanoTrabalhoDTO, $objInfraException);
      $this->validarNumIdPlanoTrabalho($objRelSeriePlanoTrabalhoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelSeriePlanoTrabalhoBD = new RelSeriePlanoTrabalhoBD($this->getObjInfraIBanco());
      return $objRelSeriePlanoTrabalhoBD->cadastrar($objRelSeriePlanoTrabalhoDTO);
    } catch (Exception $e) {
      throw new InfraException('Erro cadastrando Tipo de Documento bloqueado no Plano de Trabalho.', $e);
    }
  }

  /**
   * @param RelSeriePlanoTrabalhoDTO $objRelSeriePlanoTrabalhoDTO
   * @return RelSeriePlanoTrabalhoDTO
   * @throws InfraException
   */
  protected function alterarControlado(RelSeriePlanoTrabalhoDTO $objRelSeriePlanoTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_serie_plano_trabalho_alterar', __METHOD__, $objRelSeriePlanoTrabalhoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelSeriePlanoTrabalhoDTO->isSetNumIdSerie()) {
        $this->validarNumIdSerie($objRelSeriePlanoTrabalhoDTO, $objInfraException);
      }

      if ($objRelSeriePlanoTrabalhoDTO->isSetNumIdPlanoTrabalho()) {
        $this->validarNumIdPlanoTrabalho($objRelSeriePlanoTrabalhoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelSeriePlanoTrabalhoBD = new RelSeriePlanoTrabalhoBD($this->getObjInfraIBanco());
      $ret = $objRelSeriePlanoTrabalhoBD->alterar($objRelSeriePlanoTrabalhoDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro alterando Tipo de Documento bloqueado no Plano de Trabalho.', $e);
    }
  }

  /**
   * @param RelSeriePlanoTrabalhoDTO[] $arrObjRelSeriePlanoTrabalhoDTO
   * @return void
   * @throws InfraException
   */
  protected function excluirControlado($arrObjRelSeriePlanoTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_serie_plano_trabalho_excluir', __METHOD__, $arrObjRelSeriePlanoTrabalhoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSeriePlanoTrabalhoBD = new RelSeriePlanoTrabalhoBD($this->getObjInfraIBanco());
      foreach ($arrObjRelSeriePlanoTrabalhoDTO as $objRelSeriePlanoTrabalhoDTO) {
        $objRelSeriePlanoTrabalhoBD->excluir($objRelSeriePlanoTrabalhoDTO);
      }
    } catch (Exception $e) {
      throw new InfraException('Erro excluindo Tipo de Documento bloqueado no Plano de Trabalho.', $e);
    }
  }

  /**
   * @param RelSeriePlanoTrabalhoDTO $objRelSeriePlanoTrabalhoDTO
   * @return RelSeriePlanoTrabalhoDTO|null
   * @throws InfraException
   */
  protected function consultarConectado(RelSeriePlanoTrabalhoDTO $objRelSeriePlanoTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_serie_plano_trabalho_consultar', __METHOD__, $objRelSeriePlanoTrabalhoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSeriePlanoTrabalhoBD = new RelSeriePlanoTrabalhoBD($this->getObjInfraIBanco());

      return $objRelSeriePlanoTrabalhoBD->consultar($objRelSeriePlanoTrabalhoDTO);
    } catch (Exception $e) {
      throw new InfraException('Erro consultando Tipo de Documento bloqueado no Plano de Trabalho.', $e);
    }
  }

  /**
   * @param RelSeriePlanoTrabalhoDTO $objRelSeriePlanoTrabalhoDTO
   * @return RelSeriePlanoTrabalhoDTO[]
   * @throws InfraException
   */
  protected function listarConectado(RelSeriePlanoTrabalhoDTO $objRelSeriePlanoTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_serie_plano_trabalho_listar', __METHOD__, $objRelSeriePlanoTrabalhoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSeriePlanoTrabalhoBD = new RelSeriePlanoTrabalhoBD($this->getObjInfraIBanco());

      return $objRelSeriePlanoTrabalhoBD->listar($objRelSeriePlanoTrabalhoDTO);
    } catch (Exception $e) {
      throw new InfraException('Erro listando Tipos de Documento bloqueados no Plano de Trabalho.', $e);
    }
  }

  /**
   * @param RelSeriePlanoTrabalhoDTO $objRelSeriePlanoTrabalhoDTO
   * @return int
   * @throws InfraException
   */
  protected function contarConectado(RelSeriePlanoTrabalhoDTO $objRelSeriePlanoTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_serie_plano_trabalho_listar', __METHOD__, $objRelSeriePlanoTrabalhoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelSeriePlanoTrabalhoBD = new RelSeriePlanoTrabalhoBD($this->getObjInfraIBanco());
      return $objRelSeriePlanoTrabalhoBD->contar($objRelSeriePlanoTrabalhoDTO);
    } catch (Exception $e) {
      throw new InfraException('Erro contando Tipos de Documento bloqueados no Plano de Trabalho.', $e);
    }
  }
  /**
   * @param RelSeriePlanoTrabalhoDTO[] $arrObjRelSeriePlanoTrabalhoDTO
   * @return void
   * @throws InfraException
   */
  /*
  protected function desativarControlado($arrObjRelSeriePlanoTrabalhoDTO)
  {
      try {
          SessaoSEI::getInstance()->validarAuditarPermissao('rel_serie_plano_trabalho_desativar', __METHOD__, $arrObjRelSeriePlanoTrabalhoDTO);

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objRelSeriePlanoTrabalhoBD = new RelSeriePlanoTrabalhoBD($this->getObjInfraIBanco());
          foreach ($arrObjRelSeriePlanoTrabalhoDTO as $objRelSeriePlanoTrabalhoDTO) {
              $objRelSeriePlanoTrabalhoBD->desativar($objRelSeriePlanoTrabalhoDTO);
          }

      } catch (Exception $e) {
          throw new InfraException('Erro desativando Tipo de Documento bloqueado no Plano de Trabalho.', $e);
      }
  }
  */

  /**
   * @param RelSeriePlanoTrabalhoDTO[] $arrObjRelSeriePlanoTrabalhoDTO
   * @return void
   * @throws InfraException
   */
  /*
  protected function reativarControlado($arrObjRelSeriePlanoTrabalhoDTO)
  {
      try {
          SessaoSEI::getInstance()->validarAuditarPermissao('rel_serie_plano_trabalho_reativar', __METHOD__, $arrObjRelSeriePlanoTrabalhoDTO);

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objRelSeriePlanoTrabalhoBD = new RelSeriePlanoTrabalhoBD($this->getObjInfraIBanco());
          foreach ($arrObjRelSeriePlanoTrabalhoDTO as $objRelSeriePlanoTrabalhoDTO) {
              $objRelSeriePlanoTrabalhoBD->reativar($objRelSeriePlanoTrabalhoDTO);
          }

      } catch (Exception $e) {
          throw new InfraException('Erro reativando Tipo de Documento bloqueado no Plano de Trabalho.', $e);
      }
  }
  */

  /**
   * @param RelSeriePlanoTrabalhoDTO $objRelSeriePlanoTrabalhoDTO
   * @return RelSeriePlanoTrabalhoDTO|null
   * @throws InfraException
   */
  /*
  protected function bloquearConectado(RelSeriePlanoTrabalhoDTO $objRelSeriePlanoTrabalhoDTO)
  {
      try {
          SessaoSEI::getInstance()->validarAuditarPermissao('rel_serie_plano_trabalho_consultar', __METHOD__, $objRelSeriePlanoTrabalhoDTO);

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objRelSeriePlanoTrabalhoBD = new RelSeriePlanoTrabalhoBD($this->getObjInfraIBanco());

          return $objRelSeriePlanoTrabalhoBD->bloquear($objRelSeriePlanoTrabalhoDTO);

      } catch (Exception $e) {
          throw new InfraException('Erro bloqueando Tipo de Documento bloqueado no Plano de Trabalho.', $e);
      }
  }
  */
}
