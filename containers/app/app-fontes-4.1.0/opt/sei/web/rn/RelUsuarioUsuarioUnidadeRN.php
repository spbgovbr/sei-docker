<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 12/09/2017 - criado por mga
*
* Versão do Gerador de Código: 1.40.1
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelUsuarioUsuarioUnidadeRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdUsuario(RelUsuarioUsuarioUnidadeDTO $objRelUsuarioUsuarioUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelUsuarioUsuarioUnidadeDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao('Usuário não informado.');
    }
  }

  private function validarNumIdUsuarioAtribuicao(RelUsuarioUsuarioUnidadeDTO $objRelUsuarioUsuarioUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelUsuarioUsuarioUnidadeDTO->getNumIdUsuarioAtribuicao())){
      $objInfraException->adicionarValidacao('Usuário de Atribuição não informado.');
    }
  }

  private function validarNumIdUnidade(RelUsuarioUsuarioUnidadeDTO $objRelUsuarioUsuarioUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelUsuarioUsuarioUnidadeDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  protected function configurarControlado($arrObjRelUsuarioUsuarioUnidadeDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_usuario_unidade_configurar',__METHOD__,$arrObjRelUsuarioUsuarioUnidadeDTO);

      //Regras de Negocio

      $objRelUsuarioUsuarioUnidadeDTO = new RelUsuarioUsuarioUnidadeDTO();
      $objRelUsuarioUsuarioUnidadeDTO->retNumIdUsuarioAtribuicao();
      $objRelUsuarioUsuarioUnidadeDTO->retNumIdUsuario();
      $objRelUsuarioUsuarioUnidadeDTO->retNumIdUnidade();
      $objRelUsuarioUsuarioUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objRelUsuarioUsuarioUnidadeDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());

      $this->excluir($this->listar($objRelUsuarioUsuarioUnidadeDTO));

      foreach($arrObjRelUsuarioUsuarioUnidadeDTO as $objRelUsuarioUsuarioUnidadeDTO){
        $objRelUsuarioUsuarioUnidadeDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $objRelUsuarioUsuarioUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $this->cadastrar($objRelUsuarioUsuarioUnidadeDTO);
      }

    }catch(Exception $e){
      throw new InfraException('Erro configurando visualização de atribuições de processos.',$e);
    }
  }
  
  protected function cadastrarControlado(RelUsuarioUsuarioUnidadeDTO $objRelUsuarioUsuarioUnidadeDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_usuario_unidade_cadastrar',__METHOD__,$objRelUsuarioUsuarioUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdUsuario($objRelUsuarioUsuarioUnidadeDTO, $objInfraException);
      $this->validarNumIdUsuarioAtribuicao($objRelUsuarioUsuarioUnidadeDTO, $objInfraException);
      $this->validarNumIdUnidade($objRelUsuarioUsuarioUnidadeDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelUsuarioUsuarioUnidadeBD = new RelUsuarioUsuarioUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioUsuarioUnidadeBD->cadastrar($objRelUsuarioUsuarioUnidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Usuário Selecionado.',$e);
    }
  }

  protected function alterarControlado(RelUsuarioUsuarioUnidadeDTO $objRelUsuarioUsuarioUnidadeDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_usuario_unidade_alterar',__METHOD__,$objRelUsuarioUsuarioUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelUsuarioUsuarioUnidadeDTO->isSetNumIdUsuario()){
        $this->validarNumIdUsuario($objRelUsuarioUsuarioUnidadeDTO, $objInfraException);
      }
      if ($objRelUsuarioUsuarioUnidadeDTO->isSetNumIdUsuarioAtribuicao()){
        $this->validarNumIdUsuarioAtribuicao($objRelUsuarioUsuarioUnidadeDTO, $objInfraException);
      }
      if ($objRelUsuarioUsuarioUnidadeDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objRelUsuarioUsuarioUnidadeDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelUsuarioUsuarioUnidadeBD = new RelUsuarioUsuarioUnidadeBD($this->getObjInfraIBanco());
      $objRelUsuarioUsuarioUnidadeBD->alterar($objRelUsuarioUsuarioUnidadeDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Usuário Selecionado.',$e);
    }
  }

  protected function excluirControlado($arrObjRelUsuarioUsuarioUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_usuario_unidade_excluir',__METHOD__,$arrObjRelUsuarioUsuarioUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioUsuarioUnidadeBD = new RelUsuarioUsuarioUnidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelUsuarioUsuarioUnidadeDTO);$i++){
        $objRelUsuarioUsuarioUnidadeBD->excluir($arrObjRelUsuarioUsuarioUnidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Usuário Selecionado.',$e);
    }
  }

  protected function consultarConectado(RelUsuarioUsuarioUnidadeDTO $objRelUsuarioUsuarioUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_usuario_unidade_consultar',__METHOD__,$objRelUsuarioUsuarioUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioUsuarioUnidadeBD = new RelUsuarioUsuarioUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioUsuarioUnidadeBD->consultar($objRelUsuarioUsuarioUnidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Usuário Selecionado.',$e);
    }
  }

  protected function listarConectado(RelUsuarioUsuarioUnidadeDTO $objRelUsuarioUsuarioUnidadeDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_usuario_unidade_listar',__METHOD__,$objRelUsuarioUsuarioUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioUsuarioUnidadeBD = new RelUsuarioUsuarioUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioUsuarioUnidadeBD->listar($objRelUsuarioUsuarioUnidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Usuários Selecionados.',$e);
    }
  }

  protected function contarConectado(RelUsuarioUsuarioUnidadeDTO $objRelUsuarioUsuarioUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_usuario_unidade_listar',__METHOD__,$objRelUsuarioUsuarioUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioUsuarioUnidadeBD = new RelUsuarioUsuarioUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioUsuarioUnidadeBD->contar($objRelUsuarioUsuarioUnidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Usuários Selecionados.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjRelUsuarioUsuarioUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_usuario_unidade_desativar',__METHOD__,$arrObjRelUsuarioUsuarioUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioUsuarioUnidadeBD = new RelUsuarioUsuarioUnidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelUsuarioUsuarioUnidadeDTO);$i++){
        $objRelUsuarioUsuarioUnidadeBD->desativar($arrObjRelUsuarioUsuarioUnidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Usuário Selecionado.',$e);
    }
  }

  protected function reativarControlado($arrObjRelUsuarioUsuarioUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_usuario_unidade_reativar',__METHOD__,$arrObjRelUsuarioUsuarioUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioUsuarioUnidadeBD = new RelUsuarioUsuarioUnidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelUsuarioUsuarioUnidadeDTO);$i++){
        $objRelUsuarioUsuarioUnidadeBD->reativar($arrObjRelUsuarioUsuarioUnidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Usuário Selecionado.',$e);
    }
  }

  protected function bloquearControlado(RelUsuarioUsuarioUnidadeDTO $objRelUsuarioUsuarioUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_usuario_unidade_consultar',__METHOD__,$objRelUsuarioUsuarioUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioUsuarioUnidadeBD = new RelUsuarioUsuarioUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioUsuarioUnidadeBD->bloquear($objRelUsuarioUsuarioUnidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Usuário Selecionado.',$e);
    }
  }

 */
}
?>