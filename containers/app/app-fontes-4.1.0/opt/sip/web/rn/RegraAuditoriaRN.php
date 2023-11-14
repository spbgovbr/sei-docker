<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 25/10/2011 - criado por mga
 *
 * Versão do Gerador de Código: 1.32.1
 *
 * Versão no CVS: $Id$
 */

require_once dirname(__FILE__) . '/../Sip.php';

class RegraAuditoriaRN extends InfraRN {

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSip::getInstance();
  }

  private function validarStrDescricao(RegraAuditoriaDTO $objRegraAuditoriaDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objRegraAuditoriaDTO->getStrDescricao())) {
      $objInfraException->adicionarValidacao('Descrição não informada.');
    } else {
      $objRegraAuditoriaDTO->setStrDescricao(trim($objRegraAuditoriaDTO->getStrDescricao()));

      $dto = null;

      if ($objRegraAuditoriaDTO->getNumIdRegraAuditoria() != null && !$objRegraAuditoriaDTO->isSetNumIdSistema()) {
        $dto->retNumIdSistema();
        $dto->setNumIdRegraAuditoria($objRegraAuditoriaDTO->getNumIdRegraAuditoria());
        $dto = $this->consultar($dto);
        $dto->setNumIdRegraAuditoria($objRegraAuditoriaDTO->getNumIdRegraAuditoria(), InfraDTO::$OPER_DIFERENTE);
      } else {
        $dto = new RegraAuditoriaDTO();
        $dto->setNumIdSistema($objRegraAuditoriaDTO->getNumIdSistema());
        $dto->setNumIdRegraAuditoria($objRegraAuditoriaDTO->getNumIdRegraAuditoria(), InfraDTO::$OPER_DIFERENTE);
      }

      $dto->setStrDescricao($objRegraAuditoriaDTO->getStrDescricao());

      if ($this->contar($dto)) {
        $objInfraException->lancarValidacao('Existe outra Regra de Auditoria para este sistema com a mesma descrição.');
      }

      if (strlen($objRegraAuditoriaDTO->getStrDescricao()) > 250) {
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarNumIdSistema(RegraAuditoriaDTO $objRegraAuditoriaDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objRegraAuditoriaDTO->getNumIdSistema())) {
      $objInfraException->adicionarValidacao('Sistema não informado.');
    }
  }

  private function validarStrSinAtivo(RegraAuditoriaDTO $objRegraAuditoriaDTO, InfraException $objInfraException) {
    if ($objRegraAuditoriaDTO->getStrSinAtivo() === null || ($objRegraAuditoriaDTO->getStrSinAtivo() !== 'S' && $objRegraAuditoriaDTO->getStrSinAtivo() !== 'N')) {
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
    }
  }

  protected function cadastrarControlado(RegraAuditoriaDTO $objRegraAuditoriaDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('regra_auditoria_cadastrar', __METHOD__, $objRegraAuditoriaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrDescricao($objRegraAuditoriaDTO, $objInfraException);
      $this->validarNumIdSistema($objRegraAuditoriaDTO, $objInfraException);
      $this->validarStrSinAtivo($objRegraAuditoriaDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRegraAuditoriaBD = new RegraAuditoriaBD($this->getObjInfraIBanco());
      $ret = $objRegraAuditoriaBD->cadastrar($objRegraAuditoriaDTO);

      $objRelRegraAuditoriaRecursoRN = new RelRegraAuditoriaRecursoRN();
      $arrObjRelRegraAuditoriaRecursoDTO = $objRegraAuditoriaDTO->getArrObjRelRegraAuditoriaRecursoDTO();
      foreach ($arrObjRelRegraAuditoriaRecursoDTO as $objRelRegraAuditoriaRecursoDTO) {
        $objRelRegraAuditoriaRecursoDTO->setNumIdSistema($objRegraAuditoriaDTO->getNumIdSistema());
        $objRelRegraAuditoriaRecursoDTO->setNumIdRegraAuditoria($ret->getNumIdRegraAuditoria());
        $objRelRegraAuditoriaRecursoRN->cadastrar($objRelRegraAuditoriaRecursoDTO);
      }


      $objReplicacaoRegraAuditoriaDTO = new ReplicacaoRegraAuditoriaDTO();
      $objReplicacaoRegraAuditoriaDTO->setStrStaOperacao('C');
      $objReplicacaoRegraAuditoriaDTO->setNumIdRegraAuditoria($ret->getNumIdRegraAuditoria());

      $objSistemaRN = new SistemaRN();
      $objSistemaRN->replicarRegraAuditoria($objReplicacaoRegraAuditoriaDTO);

      //RegraAuditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro cadastrando Regra de Auditoria.', $e);
    }
  }

  protected function alterarControlado(RegraAuditoriaDTO $objRegraAuditoriaDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('regra_auditoria_alterar', __METHOD__, $objRegraAuditoriaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRegraAuditoriaDTO->isSetStrDescricao()) {
        $this->validarStrDescricao($objRegraAuditoriaDTO, $objInfraException);
      }
      if ($objRegraAuditoriaDTO->isSetNumIdSistema()) {
        $this->validarNumIdSistema($objRegraAuditoriaDTO, $objInfraException);
      }
      if ($objRegraAuditoriaDTO->isSetStrSinAtivo()) {
        $this->validarStrSinAtivo($objRegraAuditoriaDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();


      if ($objRegraAuditoriaDTO->isSetArrObjRelRegraAuditoriaRecursoDTO()) {
        $objRelRegraAuditoriaRecursoRN = new RelRegraAuditoriaRecursoRN();

        $objRelRegraAuditoriaRecursoDTO = new RelRegraAuditoriaRecursoDTO();
        $objRelRegraAuditoriaRecursoDTO->retTodos();
        $objRelRegraAuditoriaRecursoDTO->setNumIdRegraAuditoria($objRegraAuditoriaDTO->getNumIdRegraAuditoria());

        $objRelRegraAuditoriaRecursoRN->excluir($objRelRegraAuditoriaRecursoRN->listar($objRelRegraAuditoriaRecursoDTO));

        $arrObjRelRegraAuditoriaRecursoDTO = $objRegraAuditoriaDTO->getArrObjRelRegraAuditoriaRecursoDTO();
        foreach ($arrObjRelRegraAuditoriaRecursoDTO as $objRelRegraAuditoriaRecursoDTO) {
          $objRelRegraAuditoriaRecursoDTO->setNumIdSistema($objRegraAuditoriaDTO->getNumIdSistema());
          $objRelRegraAuditoriaRecursoDTO->setNumIdRegraAuditoria($objRegraAuditoriaDTO->getNumIdRegraAuditoria());
          $objRelRegraAuditoriaRecursoRN->cadastrar($objRelRegraAuditoriaRecursoDTO);
        }
      }

      $objRegraAuditoriaBD = new RegraAuditoriaBD($this->getObjInfraIBanco());
      $objRegraAuditoriaBD->alterar($objRegraAuditoriaDTO);

      $objReplicacaoRegraAuditoriaDTO = new ReplicacaoRegraAuditoriaDTO();
      $objReplicacaoRegraAuditoriaDTO->setStrStaOperacao('A');
      $objReplicacaoRegraAuditoriaDTO->setNumIdRegraAuditoria($objRegraAuditoriaDTO->getNumIdRegraAuditoria());

      $objSistemaRN = new SistemaRN();
      $objSistemaRN->replicarRegraAuditoria($objReplicacaoRegraAuditoriaDTO);
      //RegraAuditoria

    } catch (Exception $e) {
      throw new InfraException('Erro alterando Regra de Auditoria.', $e);
    }
  }

  protected function excluirControlado($arrObjRegraAuditoriaDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('regra_auditoria_excluir', __METHOD__, $arrObjRegraAuditoriaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelRegraAuditoriaRecursoRN = new RelRegraAuditoriaRecursoRN();
      for ($i = 0; $i < count($arrObjRegraAuditoriaDTO); $i++) {
        $objRelRegraAuditoriaRecursoDTO = new RelRegraAuditoriaRecursoDTO();
        $objRelRegraAuditoriaRecursoDTO->retTodos();
        $objRelRegraAuditoriaRecursoDTO->setNumIdRegraAuditoria($arrObjRegraAuditoriaDTO[$i]->getNumIdRegraAuditoria());
        $objRelRegraAuditoriaRecursoRN->excluir($objRelRegraAuditoriaRecursoRN->listar($objRelRegraAuditoriaRecursoDTO));
      }

      $objSistemaRN = new SistemaRN();
      for ($i = 0; $i < count($arrObjRegraAuditoriaDTO); $i++) {
        $objReplicacaoRegraAuditoriaDTO = new ReplicacaoRegraAuditoriaDTO();
        $objReplicacaoRegraAuditoriaDTO->setStrStaOperacao('E');
        $objReplicacaoRegraAuditoriaDTO->setNumIdRegraAuditoria($arrObjRegraAuditoriaDTO[$i]->getNumIdRegraAuditoria());

        $objSistemaRN->replicarRegraAuditoria($objReplicacaoRegraAuditoriaDTO);
      }

      $objRegraAuditoriaBD = new RegraAuditoriaBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjRegraAuditoriaDTO); $i++) {
        $objRegraAuditoriaBD->excluir($arrObjRegraAuditoriaDTO[$i]);
      }
      //RegraAuditoria

    } catch (Exception $e) {
      throw new InfraException('Erro excluindo Regra de Auditoria.', $e);
    }
  }

  protected function consultarConectado(RegraAuditoriaDTO $objRegraAuditoriaDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('regra_auditoria_consultar', __METHOD__, $objRegraAuditoriaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRegraAuditoriaBD = new RegraAuditoriaBD($this->getObjInfraIBanco());
      $ret = $objRegraAuditoriaBD->consultar($objRegraAuditoriaDTO);

      //RegraAuditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando Regra de Auditoria.', $e);
    }
  }

  protected function listarConectado(RegraAuditoriaDTO $objRegraAuditoriaDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('regra_auditoria_listar', __METHOD__, $objRegraAuditoriaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRegraAuditoriaBD = new RegraAuditoriaBD($this->getObjInfraIBanco());
      $ret = $objRegraAuditoriaBD->listar($objRegraAuditoriaDTO);

      //RegraAuditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando Regras de Auditoria.', $e);
    }
  }

  protected function contarConectado(RegraAuditoriaDTO $objRegraAuditoriaDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('regra_auditoria_listar', __METHOD__, $objRegraAuditoriaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRegraAuditoriaBD = new RegraAuditoriaBD($this->getObjInfraIBanco());
      $ret = $objRegraAuditoriaBD->contar($objRegraAuditoriaDTO);

      //RegraAuditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando Regras de Auditoria.', $e);
    }
  }

  protected function desativarControlado($arrObjRegraAuditoriaDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('regra_auditoria_desativar', __METHOD__, $arrObjRegraAuditoriaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRegraAuditoriaBD = new RegraAuditoriaBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjRegraAuditoriaDTO); $i++) {
        $objRegraAuditoriaBD->desativar($arrObjRegraAuditoriaDTO[$i]);
      }

      $objSistemaRN = new SistemaRN();
      for ($i = 0; $i < count($arrObjRegraAuditoriaDTO); $i++) {
        $objReplicacaoRegraAuditoriaDTO = new ReplicacaoRegraAuditoriaDTO();
        $objReplicacaoRegraAuditoriaDTO->setStrStaOperacao('D');
        $objReplicacaoRegraAuditoriaDTO->setNumIdRegraAuditoria($arrObjRegraAuditoriaDTO[$i]->getNumIdRegraAuditoria());

        $objSistemaRN->replicarRegraAuditoria($objReplicacaoRegraAuditoriaDTO);
      }
      //RegraAuditoria

    } catch (Exception $e) {
      throw new InfraException('Erro desativando Regra de Auditoria.', $e);
    }
  }

  protected function reativarControlado($arrObjRegraAuditoriaDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('regra_auditoria_reativar', __METHOD__, $arrObjRegraAuditoriaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRegraAuditoriaBD = new RegraAuditoriaBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjRegraAuditoriaDTO); $i++) {
        $objRegraAuditoriaBD->reativar($arrObjRegraAuditoriaDTO[$i]);
      }

      $objSistemaRN = new SistemaRN();
      for ($i = 0; $i < count($arrObjRegraAuditoriaDTO); $i++) {
        $objReplicacaoRegraAuditoriaDTO = new ReplicacaoRegraAuditoriaDTO();
        $objReplicacaoRegraAuditoriaDTO->setStrStaOperacao('R');
        $objReplicacaoRegraAuditoriaDTO->setNumIdRegraAuditoria($arrObjRegraAuditoriaDTO[$i]->getNumIdRegraAuditoria());

        $objSistemaRN->replicarRegraAuditoria($objReplicacaoRegraAuditoriaDTO);
      }
      //RegraAuditoria

    } catch (Exception $e) {
      throw new InfraException('Erro reativando Regra de Auditoria.', $e);
    }
  }

  /*
  protected function bloquearControlado(RegraAuditoriaDTO $objRegraAuditoriaDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('regra_auditoria_consultar',__METHOD__,$objRegraAuditoriaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRegraAuditoriaBD = new RegraAuditoriaBD($this->getObjInfraIBanco());
      $ret = $objRegraAuditoriaBD->bloquear($objRegraAuditoriaDTO);

      //RegraAuditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Regra de Auditoria.',$e);
    }
  }

 */
}

?>