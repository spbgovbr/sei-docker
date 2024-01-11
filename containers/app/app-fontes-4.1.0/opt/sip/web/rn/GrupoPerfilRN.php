<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 15/07/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../Sip.php';

class GrupoPerfilRN extends InfraRN {

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSip::getInstance();
  }

  private function validarNumIdSistema(GrupoPerfilDTO $objGrupoPerfilDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objGrupoPerfilDTO->getNumIdSistema())) {
      $objInfraException->adicionarValidacao('Sistema não informado.');
    }
  }

  private function validarStrNome(GrupoPerfilDTO $objGrupoPerfilDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objGrupoPerfilDTO->getStrNome())) {
      $objInfraException->adicionarValidacao('Nome não informado.');
    }

    $objGrupoPerfilDTO->setStrNome(trim($objGrupoPerfilDTO->getStrNome()));

    if (strlen($objGrupoPerfilDTO->getStrNome()) > 100) {
      $objInfraException->adicionarValidacao('Nome possui tamanho superior a 100 caracteres.');
    }

    $dto = new GrupoPerfilDTO();
    $dto->setBolExclusaoLogica(false);
    $dto->retStrSinAtivo();
    $dto->setNumIdSistema($objGrupoPerfilDTO->getNumIdSistema());
    if ($objGrupoPerfilDTO->isSetNumIdGrupoPerfil() && $objGrupoPerfilDTO->getNumIdGrupoPerfil() != null) {
      $dto->setNumIdGrupoPerfil($objGrupoPerfilDTO->getNumIdGrupoPerfil(), InfraDTO::$OPER_DIFERENTE);
    }
    $dto->setStrNome($objGrupoPerfilDTO->getStrNome());
    $dto = $this->consultar($dto);

    if ($dto != null) {
      if ($dto->getStrSinAtivo() == 'S') {
        $objInfraException->adicionarValidacao('Já existe um grupo de perfil com este nome.');
      } else {
        $objInfraException->adicionarValidacao('Existe um grupo de perfil inativo com este nome.');
      }
    }
  }

  private function validarStrSinAtivo(GrupoPerfilDTO $objGrupoPerfilDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objGrupoPerfilDTO->getStrSinAtivo())) {
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    } else {
      if (!InfraUtil::isBolSinalizadorValido($objGrupoPerfilDTO->getStrSinAtivo())) {
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(GrupoPerfilDTO $objGrupoPerfilDTO) {
    try {
      SessaoSip::getInstance()->validarAuditarPermissao('grupo_perfil_cadastrar', __METHOD__, $objGrupoPerfilDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdSistema($objGrupoPerfilDTO, $objInfraException);
      $this->validarStrNome($objGrupoPerfilDTO, $objInfraException);
      $this->validarStrSinAtivo($objGrupoPerfilDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objGrupoPerfilBD = new GrupoPerfilBD($this->getObjInfraIBanco());
      $ret = $objGrupoPerfilBD->cadastrar($objGrupoPerfilDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro cadastrando Grupo de Perfil.', $e);
    }
  }

  protected function alterarControlado(GrupoPerfilDTO $objGrupoPerfilDTO) {
    try {
      SessaoSip::getInstance()->validarAuditarPermissao('grupo_perfil_alterar', __METHOD__, $objGrupoPerfilDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objGrupoPerfilDTO->isSetNumIdSistema()) {
        $this->validarNumIdSistema($objGrupoPerfilDTO, $objInfraException);
      }
      if ($objGrupoPerfilDTO->isSetStrNome()) {
        $this->validarStrNome($objGrupoPerfilDTO, $objInfraException);
      }
      if ($objGrupoPerfilDTO->isSetStrSinAtivo()) {
        $this->validarStrSinAtivo($objGrupoPerfilDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objGrupoPerfilBD = new GrupoPerfilBD($this->getObjInfraIBanco());
      $objGrupoPerfilBD->alterar($objGrupoPerfilDTO);
    } catch (Exception $e) {
      throw new InfraException('Erro alterando Grupo de Perfil.', $e);
    }
  }

  protected function excluirControlado($arrObjGrupoPerfilDTO) {
    try {
      SessaoSip::getInstance()->validarAuditarPermissao('grupo_perfil_excluir', __METHOD__, $arrObjGrupoPerfilDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoPerfilBD = new GrupoPerfilBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjGrupoPerfilDTO); $i++) {
        $objRelGrupoPerfilPerfilDTO = new RelGrupoPerfilPerfilDTO();
        $objRelGrupoPerfilPerfilDTO->retTodos();
        $objRelGrupoPerfilPerfilDTO->setNumIdGrupoPerfil($arrObjGrupoPerfilDTO[$i]->getNumIdGrupoPerfil());
        $objRelGrupoPerfilPerfilRN = new RelGrupoPerfilPerfilRN();
        $objRelGrupoPerfilPerfilRN->excluir($objRelGrupoPerfilPerfilRN->listar($objRelGrupoPerfilPerfilDTO));

        $objGrupoPerfilBD->excluir($arrObjGrupoPerfilDTO[$i]);
      }
    } catch (Exception $e) {
      throw new InfraException('Erro excluindo Grupo de Perfil.', $e);
    }
  }

  protected function consultarConectado(GrupoPerfilDTO $objGrupoPerfilDTO) {
    try {
      SessaoSip::getInstance()->validarAuditarPermissao('grupo_perfil_consultar', __METHOD__, $objGrupoPerfilDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoPerfilBD = new GrupoPerfilBD($this->getObjInfraIBanco());
      $ret = $objGrupoPerfilBD->consultar($objGrupoPerfilDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando Grupo de Perfil.', $e);
    }
  }

  protected function listarConectado(GrupoPerfilDTO $objGrupoPerfilDTO) {
    try {
      SessaoSip::getInstance()->validarAuditarPermissao('grupo_perfil_listar', __METHOD__, $objGrupoPerfilDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoPerfilBD = new GrupoPerfilBD($this->getObjInfraIBanco());
      $ret = $objGrupoPerfilBD->listar($objGrupoPerfilDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando Grupos de Perfis.', $e);
    }
  }

  protected function contarConectado(GrupoPerfilDTO $objGrupoPerfilDTO) {
    try {
      SessaoSip::getInstance()->validarAuditarPermissao('grupo_perfil_listar', __METHOD__, $objGrupoPerfilDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoPerfilBD = new GrupoPerfilBD($this->getObjInfraIBanco());
      $ret = $objGrupoPerfilBD->contar($objGrupoPerfilDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando Grupos de Perfis.', $e);
    }
  }

  protected function desativarControlado($arrObjGrupoPerfilDTO) {
    try {
      SessaoSip::getInstance()->validarAuditarPermissao('grupo_perfil_desativar', __METHOD__, $arrObjGrupoPerfilDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoPerfilBD = new GrupoPerfilBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjGrupoPerfilDTO); $i++) {
        $objGrupoPerfilBD->desativar($arrObjGrupoPerfilDTO[$i]);
      }
    } catch (Exception $e) {
      throw new InfraException('Erro desativando Grupo de Perfil.', $e);
    }
  }

  protected function reativarControlado($arrObjGrupoPerfilDTO) {
    try {
      SessaoSip::getInstance()->validarAuditarPermissao('grupo_perfil_reativar', __METHOD__, $arrObjGrupoPerfilDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoPerfilBD = new GrupoPerfilBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjGrupoPerfilDTO); $i++) {
        $objGrupoPerfilBD->reativar($arrObjGrupoPerfilDTO[$i]);
      }
    } catch (Exception $e) {
      throw new InfraException('Erro reativando Grupo de Perfil.', $e);
    }
  }

  protected function bloquearControlado(GrupoPerfilDTO $objGrupoPerfilDTO) {
    try {
      SessaoSip::getInstance()->validarAuditarPermissao('grupo_perfil_consultar', __METHOD__, $objGrupoPerfilDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoPerfilBD = new GrupoPerfilBD($this->getObjInfraIBanco());
      $ret = $objGrupoPerfilBD->bloquear($objGrupoPerfilDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro bloqueando Grupo de Perfil.', $e);
    }
  }


}
