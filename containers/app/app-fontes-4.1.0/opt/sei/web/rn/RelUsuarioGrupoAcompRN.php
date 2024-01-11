<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 12/09/2017 - criado por mga
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelUsuarioGrupoAcompRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdUsuario(RelUsuarioGrupoAcompDTO $objRelUsuarioGrupoAcompDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelUsuarioGrupoAcompDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao('Usuário não informado.');
    }
  }

  private function validarNumIdGrupoAcompanhamento(RelUsuarioGrupoAcompDTO $objRelUsuarioGrupoAcompDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelUsuarioGrupoAcompDTO->getNumIdGrupoAcompanhamento())){
      $objInfraException->adicionarValidacao('Grupo de Acompanhamento não informado.');
    }
  }

  protected function configurarControlado($arrObjRelUsuarioGrupoAcompDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_grupo_acomp_configurar',__METHOD__,$arrObjRelUsuarioGrupoAcompDTO);

      //Regras de Negocio

      $objRelUsuarioGrupoAcompDTO = new RelUsuarioGrupoAcompDTO();
      $objRelUsuarioGrupoAcompDTO->retNumIdGrupoAcompanhamento();
      $objRelUsuarioGrupoAcompDTO->retNumIdUsuario();
      $objRelUsuarioGrupoAcompDTO->setNumIdUnidadeGrupoAcompanhamento(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objRelUsuarioGrupoAcompDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());

      $this->excluir($this->listar($objRelUsuarioGrupoAcompDTO));

      foreach($arrObjRelUsuarioGrupoAcompDTO as $objRelUsuarioGrupoAcompDTO){
        $objRelUsuarioGrupoAcompDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $this->cadastrar($objRelUsuarioGrupoAcompDTO);
      }

    }catch(Exception $e){
      throw new InfraException('Erro configurando visualização de grupos de acompanhamento especial.',$e);
    }
  }
  
  protected function cadastrarControlado(RelUsuarioGrupoAcompDTO $objRelUsuarioGrupoAcompDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_grupo_acomp_cadastrar',__METHOD__,$objRelUsuarioGrupoAcompDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdUsuario($objRelUsuarioGrupoAcompDTO, $objInfraException);
      $this->validarNumIdGrupoAcompanhamento($objRelUsuarioGrupoAcompDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelUsuarioGrupoAcompBD = new RelUsuarioGrupoAcompBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioGrupoAcompBD->cadastrar($objRelUsuarioGrupoAcompDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Grupo de Acompanhamento Especial Selecionado.',$e);
    }
  }

  protected function alterarControlado(RelUsuarioGrupoAcompDTO $objRelUsuarioGrupoAcompDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_grupo_acomp_alterar',__METHOD__,$objRelUsuarioGrupoAcompDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelUsuarioGrupoAcompDTO->isSetNumIdUsuario()){
        $this->validarNumIdUsuario($objRelUsuarioGrupoAcompDTO, $objInfraException);
      }
      if ($objRelUsuarioGrupoAcompDTO->isSetNumIdGrupoAcompanhamento()){
        $this->validarNumIdGrupoAcompanhamento($objRelUsuarioGrupoAcompDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelUsuarioGrupoAcompBD = new RelUsuarioGrupoAcompBD($this->getObjInfraIBanco());
      $objRelUsuarioGrupoAcompBD->alterar($objRelUsuarioGrupoAcompDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Grupo de Acompanhamento Especial Selecionado.',$e);
    }
  }

  protected function excluirControlado($arrObjRelUsuarioGrupoAcompDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_grupo_acomp_excluir',__METHOD__,$arrObjRelUsuarioGrupoAcompDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioGrupoAcompBD = new RelUsuarioGrupoAcompBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelUsuarioGrupoAcompDTO);$i++){
        $objRelUsuarioGrupoAcompBD->excluir($arrObjRelUsuarioGrupoAcompDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Grupo de Acompanhamento Especial Selecionado.',$e);
    }
  }

  protected function consultarConectado(RelUsuarioGrupoAcompDTO $objRelUsuarioGrupoAcompDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_grupo_acomp_consultar',__METHOD__,$objRelUsuarioGrupoAcompDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioGrupoAcompBD = new RelUsuarioGrupoAcompBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioGrupoAcompBD->consultar($objRelUsuarioGrupoAcompDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Grupo de Acompanhamento Especial Selecionado.',$e);
    }
  }

  protected function listarConectado(RelUsuarioGrupoAcompDTO $objRelUsuarioGrupoAcompDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_grupo_acomp_listar',__METHOD__,$objRelUsuarioGrupoAcompDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioGrupoAcompBD = new RelUsuarioGrupoAcompBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioGrupoAcompBD->listar($objRelUsuarioGrupoAcompDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Grupos de Acompanhamentos Especiais Selecionados.',$e);
    }
  }

  protected function contarConectado(RelUsuarioGrupoAcompDTO $objRelUsuarioGrupoAcompDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_grupo_acomp_listar',__METHOD__,$objRelUsuarioGrupoAcompDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioGrupoAcompBD = new RelUsuarioGrupoAcompBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioGrupoAcompBD->contar($objRelUsuarioGrupoAcompDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Grupos de Acompanhamentos Especiais Selecionados.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjRelUsuarioGrupoAcompDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_grupo_acomp_desativar',__METHOD__,$arrObjRelUsuarioGrupoAcompDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioGrupoAcompBD = new RelUsuarioGrupoAcompBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelUsuarioGrupoAcompDTO);$i++){
        $objRelUsuarioGrupoAcompBD->desativar($arrObjRelUsuarioGrupoAcompDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Grupo de Acompanhamento Especial Selecionado.',$e);
    }
  }

  protected function reativarControlado($arrObjRelUsuarioGrupoAcompDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_grupo_acomp_reativar',__METHOD__,$arrObjRelUsuarioGrupoAcompDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioGrupoAcompBD = new RelUsuarioGrupoAcompBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelUsuarioGrupoAcompDTO);$i++){
        $objRelUsuarioGrupoAcompBD->reativar($arrObjRelUsuarioGrupoAcompDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Grupo de Acompanhamento Especial Selecionado.',$e);
    }
  }

  protected function bloquearControlado(RelUsuarioGrupoAcompDTO $objRelUsuarioGrupoAcompDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_grupo_acomp_consultar',__METHOD__,$objRelUsuarioGrupoAcompDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioGrupoAcompBD = new RelUsuarioGrupoAcompBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioGrupoAcompBD->bloquear($objRelUsuarioGrupoAcompDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Grupo de Acompanhamento Especial Selecionado.',$e);
    }
  }

 */
}
?>