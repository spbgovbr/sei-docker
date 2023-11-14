<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 21/03/2007 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class RelPerfilItemMenuRN extends InfraRN {

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSip::getInstance();
  }

  protected function cadastrarControlado(RelPerfilItemMenuDTO $objRelPerfilItemMenuDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('rel_perfil_item_menu_cadastrar', __METHOD__, $objRelPerfilItemMenuDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdPerfil($objRelPerfilItemMenuDTO, $objInfraException);
      $this->validarNumIdSistema($objRelPerfilItemMenuDTO, $objInfraException);
      $this->validarNumIdMenu($objRelPerfilItemMenuDTO, $objInfraException);
      $this->validarNumIdItemMenu($objRelPerfilItemMenuDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelPerfilItemMenuBD = new RelPerfilItemMenuBD($this->getObjInfraIBanco());
      $ret = $objRelPerfilItemMenuBD->cadastrar($objRelPerfilItemMenuDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro cadastrando item de menu do perfil.', $e);
    }
  }

  protected function alterarControlado(RelPerfilItemMenuDTO $objRelPerfilItemMenuDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('rel_perfil_item_menu_alterar', __METHOD__, $objRelPerfilItemMenuDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdPerfil($objRelPerfilItemMenuDTO, $objInfraException);
      $this->validarNumIdSistema($objRelPerfilItemMenuDTO, $objInfraException);
      $this->validarNumIdMenu($objRelPerfilItemMenuDTO, $objInfraException);
      $this->validarNumIdItemMenu($objRelPerfilItemMenuDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelPerfilItemMenuBD = new RelPerfilItemMenuBD($this->getObjInfraIBanco());
      $objRelPerfilItemMenuBD->alterar($objRelPerfilItemMenuDTO);
      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro alterando item de menu do perfil.', $e);
    }
  }

  protected function excluirControlado($arrObjRelPerfilItemMenuDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('rel_perfil_item_menu_excluir', __METHOD__, $arrObjRelPerfilItemMenuDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelPerfilItemMenuBD = new RelPerfilItemMenuBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjRelPerfilItemMenuDTO); $i++) {
        $objRelPerfilItemMenuBD->excluir($arrObjRelPerfilItemMenuDTO[$i]);
      }
      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro excluindo itens de menu do perfil.', $e);
    }
  }

  protected function consultarConectado(RelPerfilItemMenuDTO $objRelPerfilItemMenuDTO) {
    try {
      //Valida Permissao
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('rel_perfil_item_menu_consultar',__METHOD__,$objRelPerfilItemMenuDTO);
      /////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelPerfilItemMenuBD = new RelPerfilItemMenuBD($this->getObjInfraIBanco());
      $ret = $objRelPerfilItemMenuBD->consultar($objRelPerfilItemMenuDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando item de menu do perfil.', $e);
    }
  }

  protected function listarConectado(RelPerfilItemMenuDTO $objRelPerfilItemMenuDTO) {
    try {
      //Valida Permissao
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('rel_perfil_item_menu_listar',__METHOD__,$objRelPerfilItemMenuDTO);
      /////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelPerfilItemMenuBD = new RelPerfilItemMenuBD($this->getObjInfraIBanco());
      $ret = $objRelPerfilItemMenuBD->listar($objRelPerfilItemMenuDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando itens de menu do perfil.', $e);
    }
  }

  protected function contarConectado(RelPerfilItemMenuDTO $objRelPerfilItemMenuDTO) {
    try {
      //Valida Permissao
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('rel_perfil_item_menu_listar',__METHOD__,$objRelPerfilItemMenuDTO);
      /////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelPerfilItemMenuBD = new RelPerfilItemMenuBD($this->getObjInfraIBanco());
      $ret = $objRelPerfilItemMenuBD->contar($objRelPerfilItemMenuDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando itens de menu do perfil.', $e);
    }
  }

  private function validarNumIdPerfil(
    RelPerfilItemMenuDTO $objRelPerfilItemMenuDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objRelPerfilItemMenuDTO->getNumIdPerfil())) {
      $objInfraException->adicionarValidacao('Perfil não informado.');
    }
  }

  private function validarNumIdSistema(
    RelPerfilItemMenuDTO $objRelPerfilItemMenuDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objRelPerfilItemMenuDTO->getNumIdSistema())) {
      $objInfraException->adicionarValidacao('Sistema não informado.');
    }
  }

  private function validarNumIdMenu(RelPerfilItemMenuDTO $objRelPerfilItemMenuDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objRelPerfilItemMenuDTO->getNumIdMenu())) {
      $objInfraException->adicionarValidacao('Menu não informado.');
    }
  }

  private function validarNumIdItemMenu(
    RelPerfilItemMenuDTO $objRelPerfilItemMenuDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objRelPerfilItemMenuDTO->getNumIdItemMenu())) {
      $objInfraException->adicionarValidacao('Item de Menu não informado.');
    }
  }

}

?>