<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 14/10/2019 - criado por mga
 *
 * Versão do Gerador de Código: 1.42.0
 */

require_once dirname(__FILE__) . '/../Sip.php';

class CodigoBloqueioRN extends InfraRN {

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSip::getInstance();
  }

  private function validarStrIdCodigoAcesso(
    CodigoBloqueioDTO $objCodigoBloqueioDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objCodigoBloqueioDTO->getStrIdCodigoAcesso())) {
      $objInfraException->adicionarValidacao('Código de Acesso não informado.');
    }
  }

  private function validarStrChaveBloqueio(CodigoBloqueioDTO $objCodigoBloqueioDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objCodigoBloqueioDTO->getStrChaveBloqueio())) {
      $objInfraException->adicionarValidacao('Chave de Bloqueio não informada.');
    } else {
      $objCodigoBloqueioDTO->setStrChaveBloqueio(trim($objCodigoBloqueioDTO->getStrChaveBloqueio()));

      if (strlen($objCodigoBloqueioDTO->getStrChaveBloqueio()) > 60) {
        $objInfraException->adicionarValidacao('Chave de Bloqueio possui tamanho superior a 60 caracteres.');
      }
    }
  }

  private function validarDthEnvio(CodigoBloqueioDTO $objCodigoBloqueioDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objCodigoBloqueioDTO->getDthEnvio())) {
      $objInfraException->adicionarValidacao('Data/Hora de Envio não informada.');
    } else {
      if (!InfraData::validarDataHora($objCodigoBloqueioDTO->getDthEnvio())) {
        $objInfraException->adicionarValidacao('Data/Hora de Envio inválida.');
      }
    }
  }

  private function validarDthBloqueio(CodigoBloqueioDTO $objCodigoBloqueioDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objCodigoBloqueioDTO->getDthBloqueio())) {
      $objCodigoBloqueioDTO->setDthBloqueio(null);
    } else {
      if (!InfraData::validarDataHora($objCodigoBloqueioDTO->getDthBloqueio())) {
        $objInfraException->adicionarValidacao('Data/Hora de Bloqueio inválida.');
      }
    }
  }

  private function validarStrSinAtivo(CodigoBloqueioDTO $objCodigoBloqueioDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objCodigoBloqueioDTO->getStrSinAtivo())) {
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    } else {
      if (!InfraUtil::isBolSinalizadorValido($objCodigoBloqueioDTO->getStrSinAtivo())) {
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(CodigoBloqueioDTO $objCodigoBloqueioDTO) {
    try {
      //SessaoSip::getInstance()->validarAuditarPermissao('codigo_bloqueio_cadastrar', __METHOD__, $objCodigoBloqueioDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrIdCodigoAcesso($objCodigoBloqueioDTO, $objInfraException);
      $this->validarStrChaveBloqueio($objCodigoBloqueioDTO, $objInfraException);
      $this->validarDthEnvio($objCodigoBloqueioDTO, $objInfraException);
      $this->validarDthBloqueio($objCodigoBloqueioDTO, $objInfraException);
      $this->validarStrSinAtivo($objCodigoBloqueioDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objCodigoBloqueioBD = new CodigoBloqueioBD($this->getObjInfraIBanco());
      $ret = $objCodigoBloqueioBD->cadastrar($objCodigoBloqueioDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro cadastrando Código de Bloqueio.', $e);
    }
  }

  protected function alterarControlado(CodigoBloqueioDTO $objCodigoBloqueioDTO) {
    try {
      //SessaoSip::getInstance()->validarAuditarPermissao('codigo_bloqueio_alterar', __METHOD__, $objCodigoBloqueioDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objCodigoBloqueioDTO->isSetStrIdCodigoAcesso()) {
        $this->validarStrIdCodigoAcesso($objCodigoBloqueioDTO, $objInfraException);
      }
      if ($objCodigoBloqueioDTO->isSetStrChaveBloqueio()) {
        $this->validarStrChaveBloqueio($objCodigoBloqueioDTO, $objInfraException);
      }
      if ($objCodigoBloqueioDTO->isSetDthEnvio()) {
        $this->validarDthEnvio($objCodigoBloqueioDTO, $objInfraException);
      }
      if ($objCodigoBloqueioDTO->isSetDthBloqueio()) {
        $this->validarDthBloqueio($objCodigoBloqueioDTO, $objInfraException);
      }
      if ($objCodigoBloqueioDTO->isSetStrSinAtivo()) {
        $this->validarStrSinAtivo($objCodigoBloqueioDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objCodigoBloqueioBD = new CodigoBloqueioBD($this->getObjInfraIBanco());
      $objCodigoBloqueioBD->alterar($objCodigoBloqueioDTO);
    } catch (Exception $e) {
      throw new InfraException('Erro alterando Código de Bloqueio.', $e);
    }
  }

  protected function excluirControlado($arrObjCodigoBloqueioDTO) {
    try {
      //SessaoSip::getInstance()->validarAuditarPermissao('codigo_bloqueio_excluir', __METHOD__, $arrObjCodigoBloqueioDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCodigoBloqueioBD = new CodigoBloqueioBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjCodigoBloqueioDTO); $i++) {
        $objCodigoBloqueioBD->excluir($arrObjCodigoBloqueioDTO[$i]);
      }
    } catch (Exception $e) {
      throw new InfraException('Erro excluindo Código de Bloqueio.', $e);
    }
  }

  protected function consultarConectado(CodigoBloqueioDTO $objCodigoBloqueioDTO) {
    try {
      //SessaoSip::getInstance()->validarAuditarPermissao('codigo_bloqueio_consultar', __METHOD__, $objCodigoBloqueioDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCodigoBloqueioBD = new CodigoBloqueioBD($this->getObjInfraIBanco());
      $ret = $objCodigoBloqueioBD->consultar($objCodigoBloqueioDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando Código de Bloqueio.', $e);
    }
  }

  protected function listarConectado(CodigoBloqueioDTO $objCodigoBloqueioDTO) {
    try {
      //SessaoSip::getInstance()->validarAuditarPermissao('codigo_bloqueio_listar', __METHOD__, $objCodigoBloqueioDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCodigoBloqueioBD = new CodigoBloqueioBD($this->getObjInfraIBanco());
      $ret = $objCodigoBloqueioBD->listar($objCodigoBloqueioDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando Códigos de Bloqueio.', $e);
    }
  }

  protected function contarConectado(CodigoBloqueioDTO $objCodigoBloqueioDTO) {
    try {
      //SessaoSip::getInstance()->validarAuditarPermissao('codigo_bloqueio_listar', __METHOD__, $objCodigoBloqueioDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCodigoBloqueioBD = new CodigoBloqueioBD($this->getObjInfraIBanco());
      $ret = $objCodigoBloqueioBD->contar($objCodigoBloqueioDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando Códigos de Bloqueio.', $e);
    }
  }

  protected function desativarControlado($arrObjCodigoBloqueioDTO) {
    try {
      //SessaoSip::getInstance()->validarAuditarPermissao('codigo_bloqueio_desativar', __METHOD__, $arrObjCodigoBloqueioDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCodigoBloqueioBD = new CodigoBloqueioBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjCodigoBloqueioDTO); $i++) {
        $objCodigoBloqueioBD->desativar($arrObjCodigoBloqueioDTO[$i]);
      }
    } catch (Exception $e) {
      throw new InfraException('Erro desativando Código de Bloqueio.', $e);
    }
  }

  protected function reativarControlado($arrObjCodigoBloqueioDTO) {
    try {
      //SessaoSip::getInstance()->validarAuditarPermissao('codigo_bloqueio_reativar', __METHOD__, $arrObjCodigoBloqueioDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCodigoBloqueioBD = new CodigoBloqueioBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjCodigoBloqueioDTO); $i++) {
        $objCodigoBloqueioBD->reativar($arrObjCodigoBloqueioDTO[$i]);
      }
    } catch (Exception $e) {
      throw new InfraException('Erro reativando Código de Bloqueio.', $e);
    }
  }

  protected function bloquearControlado(CodigoBloqueioDTO $objCodigoBloqueioDTO) {
    try {
      //SessaoSip::getInstance()->validarAuditarPermissao('codigo_bloqueio_consultar', __METHOD__, $objCodigoBloqueioDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCodigoBloqueioBD = new CodigoBloqueioBD($this->getObjInfraIBanco());
      $ret = $objCodigoBloqueioBD->bloquear($objCodigoBloqueioDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro bloqueando Código de Bloqueio.', $e);
    }
  }
}
