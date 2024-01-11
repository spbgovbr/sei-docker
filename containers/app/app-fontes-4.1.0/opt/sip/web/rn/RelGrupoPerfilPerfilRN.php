<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 15/07/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../Sip.php';

class RelGrupoPerfilPerfilRN extends InfraRN {

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSip::getInstance();
  }

  private function validarNumIdGrupoPerfil(
    RelGrupoPerfilPerfilDTO $objRelGrupoPerfilPerfilDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objRelGrupoPerfilPerfilDTO->getNumIdGrupoPerfil())) {
      $objInfraException->adicionarValidacao('Grupo de Perfil não informado.');
    }
  }

  private function validarNumIdSistema(
    RelGrupoPerfilPerfilDTO $objRelGrupoPerfilPerfilDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objRelGrupoPerfilPerfilDTO->getNumIdSistema())) {
      $objInfraException->adicionarValidacao('Sistema não informado.');
    }
  }

  private function validarNumIdPerfil(
    RelGrupoPerfilPerfilDTO $objRelGrupoPerfilPerfilDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objRelGrupoPerfilPerfilDTO->getNumIdPerfil())) {
      $objInfraException->adicionarValidacao('Perfil não informado.');
    }
  }

  protected function cadastrarControlado(RelGrupoPerfilPerfilDTO $objRelGrupoPerfilPerfilDTO) {
    try {
      SessaoSip::getInstance()->validarAuditarPermissao('rel_grupo_perfil_perfil_cadastrar', __METHOD__, $objRelGrupoPerfilPerfilDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdGrupoPerfil($objRelGrupoPerfilPerfilDTO, $objInfraException);
      $this->validarNumIdSistema($objRelGrupoPerfilPerfilDTO, $objInfraException);
      $this->validarNumIdPerfil($objRelGrupoPerfilPerfilDTO, $objInfraException);

      $objGrupoPerfilDTO = new GrupoPerfilDTO();
      $objGrupoPerfilDTO->setBolExclusaoLogica(false);
      $objGrupoPerfilDTO->retNumIdSistema();
      $objGrupoPerfilDTO->retStrNome();
      $objGrupoPerfilDTO->retStrSiglaSistema();
      $objGrupoPerfilDTO->setNumIdGrupoPerfil($objRelGrupoPerfilPerfilDTO->getNumIdGrupoPerfil());

      $objGrupoPerfilRN = new GrupoPerfilRN();
      $objGrupoPerfilDTO = $objGrupoPerfilRN->consultar($objGrupoPerfilDTO);

      if ($objGrupoPerfilDTO == null) {
        throw new InfraException('Grupo de Perfil não encontrado.');
      }

      if ($objGrupoPerfilDTO->getNumIdSistema() != $objRelGrupoPerfilPerfilDTO->getNumIdSistema()) {
        throw new InfraException('Grupo de Perfil "' . $objGrupoPerfilDTO->getStrNome() . '" está associado com o sistema ' . $objGrupoPerfilDTO->getStrSiglaSistema() . '.');
      }


      $objInfraException->lancarValidacoes();

      $objRelGrupoPerfilPerfilBD = new RelGrupoPerfilPerfilBD($this->getObjInfraIBanco());
      $ret = $objRelGrupoPerfilPerfilBD->cadastrar($objRelGrupoPerfilPerfilDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro cadastrando Grupo do Perfil.', $e);
    }
  }

  protected function alterarControlado(RelGrupoPerfilPerfilDTO $objRelGrupoPerfilPerfilDTO) {
    try {
      SessaoSip::getInstance()->validarAuditarPermissao('rel_grupo_perfil_perfil_alterar', __METHOD__, $objRelGrupoPerfilPerfilDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelGrupoPerfilPerfilDTO->isSetNumIdGrupoPerfil()) {
        $this->validarNumIdGrupoPerfil($objRelGrupoPerfilPerfilDTO, $objInfraException);
      }
      if ($objRelGrupoPerfilPerfilDTO->isSetNumIdSistema()) {
        $this->validarNumIdSistema($objRelGrupoPerfilPerfilDTO, $objInfraException);
      }
      if ($objRelGrupoPerfilPerfilDTO->isSetNumIdPerfil()) {
        $this->validarNumIdPerfil($objRelGrupoPerfilPerfilDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelGrupoPerfilPerfilBD = new RelGrupoPerfilPerfilBD($this->getObjInfraIBanco());
      $objRelGrupoPerfilPerfilBD->alterar($objRelGrupoPerfilPerfilDTO);
    } catch (Exception $e) {
      throw new InfraException('Erro alterando Grupo do Perfil.', $e);
    }
  }

  protected function excluirControlado($arrObjRelGrupoPerfilPerfilDTO) {
    try {
      SessaoSip::getInstance()->validarAuditarPermissao('rel_grupo_perfil_perfil_excluir', __METHOD__, $arrObjRelGrupoPerfilPerfilDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelGrupoPerfilPerfilBD = new RelGrupoPerfilPerfilBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjRelGrupoPerfilPerfilDTO); $i++) {
        $objRelGrupoPerfilPerfilBD->excluir($arrObjRelGrupoPerfilPerfilDTO[$i]);
      }
    } catch (Exception $e) {
      throw new InfraException('Erro excluindo Grupo do Perfil.', $e);
    }
  }

  protected function consultarConectado(RelGrupoPerfilPerfilDTO $objRelGrupoPerfilPerfilDTO) {
    try {
      SessaoSip::getInstance()->validarAuditarPermissao('rel_grupo_perfil_perfil_consultar', __METHOD__, $objRelGrupoPerfilPerfilDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelGrupoPerfilPerfilBD = new RelGrupoPerfilPerfilBD($this->getObjInfraIBanco());
      $ret = $objRelGrupoPerfilPerfilBD->consultar($objRelGrupoPerfilPerfilDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando Grupo do Perfil.', $e);
    }
  }

  protected function listarConectado(RelGrupoPerfilPerfilDTO $objRelGrupoPerfilPerfilDTO) {
    try {
      SessaoSip::getInstance()->validarAuditarPermissao('rel_grupo_perfil_perfil_listar', __METHOD__, $objRelGrupoPerfilPerfilDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelGrupoPerfilPerfilBD = new RelGrupoPerfilPerfilBD($this->getObjInfraIBanco());
      $ret = $objRelGrupoPerfilPerfilBD->listar($objRelGrupoPerfilPerfilDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando Grupos do Perfil.', $e);
    }
  }

  protected function contarConectado(RelGrupoPerfilPerfilDTO $objRelGrupoPerfilPerfilDTO) {
    try {
      SessaoSip::getInstance()->validarAuditarPermissao('rel_grupo_perfil_perfil_listar', __METHOD__, $objRelGrupoPerfilPerfilDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelGrupoPerfilPerfilBD = new RelGrupoPerfilPerfilBD($this->getObjInfraIBanco());
      $ret = $objRelGrupoPerfilPerfilBD->contar($objRelGrupoPerfilPerfilDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando Grupos do Perfil.', $e);
    }
  }
  /*
    protected function desativarControlado($arrObjRelGrupoPerfilPerfilDTO){
      try {

        SessaoSip::getInstance()->validarAuditarPermissao('rel_grupo_perfil_perfil_desativar', __METHOD__, $arrObjRelGrupoPerfilPerfilDTO);

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objRelGrupoPerfilPerfilBD = new RelGrupoPerfilPerfilBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjRelGrupoPerfilPerfilDTO);$i++){
          $objRelGrupoPerfilPerfilBD->desativar($arrObjRelGrupoPerfilPerfilDTO[$i]);
        }

      }catch(Exception $e){
        throw new InfraException('Erro desativando Grupo do Perfil.',$e);
      }
    }

    protected function reativarControlado($arrObjRelGrupoPerfilPerfilDTO){
      try {

        SessaoSip::getInstance()->validarAuditarPermissao('rel_grupo_perfil_perfil_reativar', __METHOD__, $arrObjRelGrupoPerfilPerfilDTO);

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objRelGrupoPerfilPerfilBD = new RelGrupoPerfilPerfilBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjRelGrupoPerfilPerfilDTO);$i++){
          $objRelGrupoPerfilPerfilBD->reativar($arrObjRelGrupoPerfilPerfilDTO[$i]);
        }

      }catch(Exception $e){
        throw new InfraException('Erro reativando Grupo do Perfil.',$e);
      }
    }

    protected function bloquearControlado(RelGrupoPerfilPerfilDTO $objRelGrupoPerfilPerfilDTO){
      try {

        SessaoSip::getInstance()->validarAuditarPermissao('rel_grupo_perfil_perfil_consultar', __METHOD__, $objRelGrupoPerfilPerfilDTO);

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objRelGrupoPerfilPerfilBD = new RelGrupoPerfilPerfilBD($this->getObjInfraIBanco());
        $ret = $objRelGrupoPerfilPerfilBD->bloquear($objRelGrupoPerfilPerfilDTO);

        return $ret;
      }catch(Exception $e){
        throw new InfraException('Erro bloqueando Grupo do Perfil.',$e);
      }
    }

   */
}
