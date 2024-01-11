<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/09/2017 - criado por mga
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelUsuarioMarcadorRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdMarcador(RelUsuarioMarcadorDTO $objRelUsuarioMarcadorDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelUsuarioMarcadorDTO->getNumIdMarcador())){
      $objInfraException->adicionarValidacao('Marcador não informado.');
    }
  }

  private function validarNumIdUsuario(RelUsuarioMarcadorDTO $objRelUsuarioMarcadorDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelUsuarioMarcadorDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao('Usuário não informado.');
    }
  }

  protected function configurarControlado($arrObjRelUsuarioMarcadorDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_marcador_configurar',__METHOD__,$arrObjRelUsuarioMarcadorDTO);

      //Regras de Negocio

      $objRelUsuarioMarcadorDTO = new RelUsuarioMarcadorDTO();
      $objRelUsuarioMarcadorDTO->retNumIdMarcador();
      $objRelUsuarioMarcadorDTO->retNumIdUsuario();
      $objRelUsuarioMarcadorDTO->setNumIdUnidadeMarcador(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objRelUsuarioMarcadorDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());

      $this->excluir($this->listar($objRelUsuarioMarcadorDTO));

      foreach($arrObjRelUsuarioMarcadorDTO as $objRelUsuarioMarcadorDTO){
        $objRelUsuarioMarcadorDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $this->cadastrar($objRelUsuarioMarcadorDTO);
      }

    }catch(Exception $e){
      throw new InfraException('Erro configurando visualização de marcadores.',$e);
    }
  }

  protected function cadastrarControlado(RelUsuarioMarcadorDTO $objRelUsuarioMarcadorDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_marcador_cadastrar',__METHOD__,$objRelUsuarioMarcadorDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMarcador($objRelUsuarioMarcadorDTO, $objInfraException);
      $this->validarNumIdUsuario($objRelUsuarioMarcadorDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelUsuarioMarcadorBD = new RelUsuarioMarcadorBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioMarcadorBD->cadastrar($objRelUsuarioMarcadorDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Marcador Selecionado.',$e);
    }
  }

  protected function alterarControlado(RelUsuarioMarcadorDTO $objRelUsuarioMarcadorDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_marcador_alterar',__METHOD__,$objRelUsuarioMarcadorDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelUsuarioMarcadorDTO->isSetNumIdMarcador()){
        $this->validarNumIdMarcador($objRelUsuarioMarcadorDTO, $objInfraException);
      }
      if ($objRelUsuarioMarcadorDTO->isSetNumIdUsuario()){
        $this->validarNumIdUsuario($objRelUsuarioMarcadorDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelUsuarioMarcadorBD = new RelUsuarioMarcadorBD($this->getObjInfraIBanco());
      $objRelUsuarioMarcadorBD->alterar($objRelUsuarioMarcadorDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Marcador Selecionado.',$e);
    }
  }

  protected function excluirControlado($arrObjRelUsuarioMarcadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_marcador_excluir',__METHOD__,$arrObjRelUsuarioMarcadorDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioMarcadorBD = new RelUsuarioMarcadorBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelUsuarioMarcadorDTO);$i++){
        $objRelUsuarioMarcadorBD->excluir($arrObjRelUsuarioMarcadorDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Marcador Selecionado.',$e);
    }
  }

  protected function consultarConectado(RelUsuarioMarcadorDTO $objRelUsuarioMarcadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_marcador_consultar',__METHOD__,$objRelUsuarioMarcadorDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioMarcadorBD = new RelUsuarioMarcadorBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioMarcadorBD->consultar($objRelUsuarioMarcadorDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Marcador Selecionado.',$e);
    }
  }

  protected function listarConectado(RelUsuarioMarcadorDTO $objRelUsuarioMarcadorDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_marcador_listar',__METHOD__,$objRelUsuarioMarcadorDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioMarcadorBD = new RelUsuarioMarcadorBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioMarcadorBD->listar($objRelUsuarioMarcadorDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Marcadores Selecionados.',$e);
    }
  }

  protected function contarConectado(RelUsuarioMarcadorDTO $objRelUsuarioMarcadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_marcador_listar',__METHOD__,$objRelUsuarioMarcadorDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioMarcadorBD = new RelUsuarioMarcadorBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioMarcadorBD->contar($objRelUsuarioMarcadorDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Marcadores Selecionados.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjRelUsuarioMarcadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_marcador_desativar',__METHOD__,$arrObjRelUsuarioMarcadorDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioMarcadorBD = new RelUsuarioMarcadorBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelUsuarioMarcadorDTO);$i++){
        $objRelUsuarioMarcadorBD->desativar($arrObjRelUsuarioMarcadorDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Marcador Selecionado.',$e);
    }
  }

  protected function reativarControlado($arrObjRelUsuarioMarcadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_marcador_reativar',__METHOD__,$arrObjRelUsuarioMarcadorDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioMarcadorBD = new RelUsuarioMarcadorBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelUsuarioMarcadorDTO);$i++){
        $objRelUsuarioMarcadorBD->reativar($arrObjRelUsuarioMarcadorDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Marcador Selecionado.',$e);
    }
  }

  protected function bloquearControlado(RelUsuarioMarcadorDTO $objRelUsuarioMarcadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_marcador_consultar',__METHOD__,$objRelUsuarioMarcadorDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioMarcadorBD = new RelUsuarioMarcadorBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioMarcadorBD->bloquear($objRelUsuarioMarcadorDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Marcador Selecionado.',$e);
    }
  }

 */
}
?>