<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 30/11/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class AdministradorSistemaRN extends InfraRN {

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSip::getInstance();
  }

  protected function cadastrarControlado(AdministradorSistemaDTO $objAdministradorSistemaDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('administrador_sistema_cadastrar', __METHOD__, $objAdministradorSistemaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdUsuario($objAdministradorSistemaDTO, $objInfraException);
      $this->validarNumIdSistema($objAdministradorSistemaDTO, $objInfraException);

      if ($this->contar($objAdministradorSistemaDTO)) {
        $objInfraException->lancarValidacao('Usuário já é administrador do sistema.');
      }

      $objInfraParametro = new InfraParametro(BancoSip::getInstance());


      $objPermissaoDTO = new PermissaoDTO();
      $objPermissaoDTO->setNumIdUsuario($objAdministradorSistemaDTO->getNumIdUsuario());
      $objPermissaoDTO->setNumIdPerfil($objInfraParametro->getValor('ID_PERFIL_SIP_ADMINISTRADOR_SISTEMA'));

      $objPermissaoRN = new PermissaoRN();
      $objPermissaoRN->adicionarPerfilReservado($objPermissaoDTO);

      if ($objAdministradorSistemaDTO->getNumIdSistema() == $objInfraParametro->getValor('ID_SISTEMA_SIP')) {
        $objPermissaoDTO->setNumIdPerfil($objInfraParametro->getValor('ID_PERFIL_SIP_ADMINISTRADOR_SIP'));
        $objPermissaoRN->adicionarPerfilReservado($objPermissaoDTO);
      }

      $objAdministradorSistemaBD = new AdministradorSistemaBD($this->getObjInfraIBanco());
      $ret = $objAdministradorSistemaBD->cadastrar($objAdministradorSistemaDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro cadastrando Administrador.', $e);
    }
  }

  protected function alterarControlado(AdministradorSistemaDTO $objAdministradorSistemaDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('administrador_sistema_alterar', __METHOD__, $objAdministradorSistemaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdUsuario($objAdministradorSistemaDTO, $objInfraException);
      $this->validarNumIdSistema($objAdministradorSistemaDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objAdministradorSistemaBD = new AdministradorSistemaBD($this->getObjInfraIBanco());
      $objAdministradorSistemaBD->alterar($objAdministradorSistemaDTO);
      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro alterando Administrador.', $e);
    }
  }

  protected function excluirControlado($arrObjAdministradorSistemaDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('administrador_sistema_excluir', __METHOD__, $arrObjAdministradorSistemaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraParametro = new InfraParametro(BancoSip::getInstance());
      $objPermissaoRN = new PermissaoRN();

      $objAdministradorSistemaBD = new AdministradorSistemaBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjAdministradorSistemaDTO); $i++) {
        $objAdministradorSistemaBD->excluir($arrObjAdministradorSistemaDTO[$i]);

        //Se o usuário não é mais administrador de nenhum sistema remove o perfil "Administrador de Sistema" do SIP das suas permissões
        $objAdministradorSistemaDTO = new AdministradorSistemaDTO();
        $objAdministradorSistemaDTO->setNumIdUsuario($arrObjAdministradorSistemaDTO[$i]->getNumIdUsuario());
        if ($this->contar($objAdministradorSistemaDTO) == 0) {
          $objPermissaoDTO = new PermissaoDTO();
          $objPermissaoDTO->setNumIdUsuario($arrObjAdministradorSistemaDTO[$i]->getNumIdUsuario());
          $objPermissaoDTO->setNumIdPerfil($objInfraParametro->getValor('ID_PERFIL_SIP_ADMINISTRADOR_SISTEMA'));
          $objPermissaoRN->removerPerfilReservado($objPermissaoDTO);
        }

        if ($arrObjAdministradorSistemaDTO[$i]->getNumIdSistema() == $objInfraParametro->getValor('ID_SISTEMA_SIP')) {
          $objPermissaoDTO = new PermissaoDTO();
          $objPermissaoDTO->setNumIdUsuario($arrObjAdministradorSistemaDTO[$i]->getNumIdUsuario());
          $objPermissaoDTO->setNumIdPerfil($objInfraParametro->getValor('ID_PERFIL_SIP_ADMINISTRADOR_SIP'));
          $objPermissaoRN->removerPerfilReservado($objPermissaoDTO);
        }
      }
      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro excluindo Administrador.', $e);
    }
  }

  protected function desativarControlado($arrObjAdministradorSistemaDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('administrador_sistema_desativar', __METHOD__, $arrObjAdministradorSistemaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAdministradorSistemaBD = new AdministradorSistemaBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjAdministradorSistemaDTO); $i++) {
        $objAdministradorSistemaBD->desativar($arrObjAdministradorSistemaDTO[$i]);
      }
      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro desativando Administrador.', $e);
    }
  }

  protected function consultarConectado(AdministradorSistemaDTO $objAdministradorSistemaDTO) {
    try {
      //Valida Permissao
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('administrador_sistema_consultar', __METHOD__, $objAdministradorSistemaDTO);
      /////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAdministradorSistemaBD = new AdministradorSistemaBD($this->getObjInfraIBanco());
      $ret = $objAdministradorSistemaBD->consultar($objAdministradorSistemaDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando Administrador.', $e);
    }
  }

  protected function listarConectado(AdministradorSistemaDTO $objAdministradorSistemaDTO) {
    try {
      //Valida Permissao
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('administrador_sistema_listar', __METHOD__, $objAdministradorSistemaDTO);
      /////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAdministradorSistemaBD = new AdministradorSistemaBD($this->getObjInfraIBanco());
      $ret = $objAdministradorSistemaBD->listar($objAdministradorSistemaDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando Administradores.', $e);
    }
  }

  protected function contarConectado(AdministradorSistemaDTO $objAdministradorSistemaDTO) {
    try {
      //////////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('administrador_sistema_contar', __METHOD__, $objAdministradorSistemaDTO);
      //////////////////////////////////////////////////////////////////////


      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAdministradorSistemaBD = new AdministradorSistemaBD($this->getObjInfraIBanco());
      $ret = $objAdministradorSistemaBD->contar($objAdministradorSistemaDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando Administradores de Sistemas.', $e);
    }
  }

  private function validarNumIdUsuario(
    AdministradorSistemaDTO $objAdministradorSistemaDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objAdministradorSistemaDTO->getNumIdUsuario())) {
      $objInfraException->adicionarValidacao('Usuário não informado.');
    }
  }

  private function validarNumIdSistema(
    AdministradorSistemaDTO $objAdministradorSistemaDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objAdministradorSistemaDTO->getNumIdSistema())) {
      $objInfraException->adicionarValidacao('Sistema não informado.');
    }
  }

}

?>